<?php
// /routes.php
// Registro de rotas da aplicação (URLs em PT-BR)


// Rotas públicas
$router->get('/', ['Publico\\InicioController', 'index']);


// Autenticação (entrar/sair/registrar)
$router->get('/entrar', ['Auth\\LoginController', 'index']);
$router->post('/entrar', ['Auth\\LoginController', 'autenticar']);
$router->get('/criar-conta', ['Auth\\RegisterController', 'index']);
$router->post('/criar-conta', ['Auth\\RegisterController', 'registrar']);
$router->get('/sair', ['Auth\\LoginController', 'sair']);

// Termos de uso
$router->get('/termos', ['Publico\\TermsController', 'index']);
$router->post('/termos', ['Publico\\TermsController', 'aceitar']);

// Recuperação de Senha
$router->get('/esqueci-senha', ['Auth\\PasswordResetController', 'esqueci']);
$router->post('/esqueci-senha', ['Auth\\PasswordResetController', 'enviarToken']);
$router->get('/redefinir-senha', ['Auth\\PasswordResetController', 'redefinir']);
$router->post('/redefinir-senha', ['Auth\\PasswordResetController', 'atualizarSenha']);


// Painéis por perfil (protegidos por middleware)
$router->get('/admin', ['Admin\\PainelController', 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/perfil', ['Admin\\PerfilController', 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->post('/admin/perfil', ['Admin\\PerfilController', 'atualizar'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/parceiros', ['Admin\\ParceirosController', 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/parceiros/{id}/ver', ['Admin\\ParceirosController', 'show'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/parceiros/{id}/relatorio', ['Admin\\ParceirosController', 'relatorio'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/produtos', ['Admin\\ProdutosController', 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/produtos/{id}/ver', ['Admin\\ProdutosController', 'show'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/movimentacoes', ['Admin\\MovimentacoesController', 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/movimentacoes/nova', ['Admin\\MovimentacoesController', 'nova'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/relatorios', ['Admin\\RelatoriosController', 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/auditoria', ['Admin\\AuditoriaController', 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/consignado', ['Admin\\ConsignadoController', 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/consignado/parceiro/{id}/ver', ['Admin\\ConsignadoController', 'parceiro'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/usuarios', [Admin\UsuariosController::class, 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/usuarios/novo', [Admin\UsuariosController::class, 'create'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->post('/admin/usuarios', [Admin\UsuariosController::class, 'store'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/usuarios/{id}', [Admin\UsuariosController::class, 'show'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/usuarios/{id}/editar', [Admin\UsuariosController::class, 'edit'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->post('/admin/usuarios/{id}/editar', [Admin\UsuariosController::class, 'update'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->post('/admin/usuarios/{id}/toggle', [Admin\UsuariosController::class, 'toggle'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/configuracoes', [Admin\ConfiguracoesController::class, 'index'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->post('/admin/configuracoes', [Admin\ConfiguracoesController::class, 'salvar'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);
$router->get('/admin/configuracoes/cep/{cep}', [Admin\ConfiguracoesController::class, 'cep'], ['auth', 'role:admin', \App\Middlewares\TermsMiddleware::class]);

$router->get('/colaborador', ['Colaborador\\PainelController', 'index'], ['auth', 'role:colaborador']);
$router->get('/colaborador/perfil', ['Colaborador\\PerfilController', 'index'], ['auth', 'role:colaborador']);
$router->post('/colaborador/perfil', ['Colaborador\\PerfilController', 'atualizar'], ['auth', 'role:colaborador']);
$router->get('/colaborador/preferencias', ['Colaborador\\PreferenciasController', 'index'], ['auth', 'role:colaborador']);
$router->post('/colaborador/preferencias', ['Colaborador\\PreferenciasController', 'salvar'], ['auth', 'role:colaborador']);

$router->get('/cliente', ['Cliente\\PainelController', 'index'], ['auth', 'role:cliente']);
$router->get('/cliente/perfil', ['Cliente\\PerfilController', 'index'], ['auth', 'role:cliente']);
$router->post('/cliente/perfil', ['Cliente\\PerfilController', 'atualizar'], ['auth', 'role:cliente']);
$router->get('/cliente/preferencias', ['Cliente\\PreferenciasController', 'index'], ['auth', 'role:cliente']);
$router->post('/cliente/preferencias', ['Cliente\\PreferenciasController', 'salvar'], ['auth', 'role:cliente']);


// Rota teste database
$router->get('/teste-db', ['TesteController', 'index']);

// Rota teste controller
$router->get('/teste-usuario', ['TesteUserController', 'index']);

// Preferências rápidas (tema)
$router->post('/preferencias/tema', ['PreferenceController', 'salvarTema'], ['auth']);

