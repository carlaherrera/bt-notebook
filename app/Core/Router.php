<?php
// app/Core/Router.php
// Classe responsável por registrar rotas e despachar requisições.

declare(strict_types=1);

namespace App\Core;

use App\Core\Security;
use App\Core\ErrorPage;
use App\Core\Logger;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];
    private array $middlewareRegistry = [];

    public function __construct()
    {
        // Middlewares padrão
        $this->middlewareRegistry = [
            'auth' => [\App\Middlewares\AuthMiddleware::class, 'handle'],
            'role' => [\App\Middlewares\RoleMiddleware::class, 'handle'],
        ];
    }

    /**
     * Tenta resolver rota com parâmetros dinâmicos {param}
     */
    private function matchRoute(string $uri, string $method): array|null
    {
        if (!isset($this->routes[$method])) {
            return null;
        }

        // Match exato primeiro
        if (isset($this->routes[$method][$uri])) {
            return [$this->routes[$method][$uri], []];
        }

        // Match com parâmetros
        foreach ($this->routes[$method] as $routePath => $routeData) {
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return [$routeData, $matches];
            }
        }

        return null;
    }

    /**
     * Registra uma rota GET
     */
    public function get(string $path, callable|array $action, array $middleware = []): void
    {
        $this->routes['GET'][$path] = [
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    /**
     * Registra uma rota POST
     */
    public function post(string $path, callable|array $action, array $middleware = []): void
    {
        $this->routes['POST'][$path] = [
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    /**
     * Registra middleware customizado por nome.
     */
    public function registerMiddleware(string $name, callable $handler): void
    {
        $this->middlewareRegistry[$name] = $handler;
    }

    /**
     * Realiza o despacho da rota atual:
     * - verifica se existe rota
     * - resolve controller@metodo
     * - chama método
     */
    public function dispatch(string $uri, string $method): void
    {
        $method = strtoupper($method);

        $matched = $this->matchRoute($uri, $method);

        // Caso não exista rota registrada para esse método ou URI
        if (!$matched) {
            ErrorPage::render(404, "Página não encontrada");
            return;
        }

        [$routeData, $params] = $matched;
        $action = $routeData['action'] ?? null;
        $middlewares = $routeData['middleware'] ?? [];

        // Rate limit global para POST (proteção básica contra abuso)
        if ($method === 'POST') {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $key = hash('sha256', 'post|' . $ip);
            $maxHits = 30;        // 30 requisições
            $decaySeconds = 60;   // por minuto

            if (Security::rateLimitExceeded($key, $maxHits, $decaySeconds)) {
                $wait = Security::rateLimitWaitSeconds($key, $maxHits, $decaySeconds);
                http_response_code(429);
                header('Retry-After: ' . max(1, $wait));
                echo htmlspecialchars("Muitas requisições. Tente novamente em " . max(1, ceil($wait)) . "s.", ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                Logger::error('Rate limit POST atingido', ['ip' => $ip, 'uri' => $uri, 'wait' => $wait]);
                return;
            }

            Security::addRateHit($key, $decaySeconds);
        }

        // Validação CSRF automática para POST
        if ($method === 'POST' && !Security::validateCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo htmlspecialchars("Falha de verificação CSRF.", ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            return;
        }

        // Executa middlewares
        if (!$this->runMiddleware($middlewares)) {
            return;
        }

        // Se a ação for uma função anônima (callback simples)
        if (is_callable($action)) {
            call_user_func_array($action, $params);
            return;
        }

        // Se a ação for array: ['Controller', 'metodo']
        if (is_array($action)) {
            [$controllerName, $methodName] = $action;

            // Monta o namespace completo do Controller
            $controllerClass = str_starts_with($controllerName, '\\') ? ltrim($controllerName, '\\') : "App\\Controllers\\$controllerName";

            // Se for um namespace já ancorado em App\, mantém
            if (str_starts_with($controllerName, 'App\\')) {
                $controllerClass = $controllerName;
            }

            // Instancia o Controller
            if (!class_exists($controllerClass)) {
                Logger::error("Controller não encontrado", ['controller' => $controllerClass, 'uri' => $uri]);
                ErrorPage::render(404, "Controller não encontrado");
                return;
            }

            try {
                $controller = new $controllerClass();

                // Executa o método no Controller
                if (method_exists($controller, $methodName)) {
                    Logger::debug("Executando controller", ['controller' => $controllerClass, 'method' => $methodName, 'uri' => $uri, 'params' => $params]);
                    $controller->{$methodName}(...$params);
                    return;
                }
            } catch (\Throwable $e) {
                Logger::error("Erro ao executar controller", [
                    'controller' => $controllerClass,
                    'method' => $methodName,
                    'uri' => $uri,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
                ErrorPage::render(500, "Erro ao processar requisição");
                return;
            }
        }

        // Nada funcionou = 404
        ErrorPage::render(404, "Página não encontrada");
    }

    /**
     * Executa middlewares registrados. Se algum retornar false, interrompe.
     */
    private function runMiddleware(array $middlewares): bool
    {
        foreach ($middlewares as $middleware) {
            $name = null;
            $param = null;
            $callable = null;

            if (is_callable($middleware)) {
                $callable = $middleware;
            } elseif (is_string($middleware)) {
                if (str_contains($middleware, ':')) {
                    [$name, $param] = explode(':', $middleware, 2);
                } else {
                    $name = $middleware;
                }

                // Se middleware for classe com método handle (MiddlewareInterface), aceita também
                if (class_exists($name) && method_exists($name, 'handle')) {
                    $callable = [$name, 'handle'];
                } else {
                    if (!isset($this->middlewareRegistry[$name])) {
                        ErrorPage::render(500, "Middleware {$name} não registrado");
                        return false;
                    }
                    $callable = $this->middlewareRegistry[$name];
                }
            }

            if (!is_callable($callable)) {
                ErrorPage::render(500, "Middleware inválido");
                return false;
            }

            $result = $param === null ? call_user_func($callable) : call_user_func($callable, $param);

            if ($result === false) {
                return false;
            }
        }

        return true;
    }
}
