# FerramentasAi - Projeto PHP MVC

Aplicação em PHP com MVC enxuto, rotas próprias e suporte a SQLite/MySQL.

## Visão geral
- Router simples com suporte a parâmetros dinâmicos e CSRF global para POST.
- Autenticação por sessão (roles: admin, colaborador, cliente) com regeneração de ID e cookies HttpOnly/SameSite=Strict.
- Módulos principais: painel público, auth (login/registro), painéis por perfil, gestão de usuários e configurações.
- Upload de imagens com conversão/validação (WebP/PNG/JPG/SVG/ICO).
- Banco: SQLite por padrão; MySQL opcional via `config.php`.
- Sem pipeline de build: views PHP + HTML/CSS usando CDNs (ex.: ícones Lucide) e CSS utilitário existente; assets servidos diretamente de `public/`.
- Visual: tema claro/escuro com cores configuráveis, componentes estilizados com utilitários; fonte padrão stack do navegador (sem bundler).
- Responsividade: layout fluido com grids flex/stack; páginas de login/painéis e tabelas possuem variações mobile (cards) e desktop (tabelas).

## Estrutura
- `bootstrap.php`: inicialização (sessão, autoload, paths, erros).
- `routes.php`: definição de rotas em PT-BR.
- `app/Core`: Router, Controller, Auth, Security (CSRF, throttle login), Database, View, Upload/Image helpers.
- `app/Controllers`: lógicas de domínio (Auth, Admin, Colaborador, Cliente, Público).
- `app/Repositories`: acesso a dados via PDO genérico (`Repository`).
- `app/Views`: templates PHP com partials e componentes.
- `public/`: assets públicos e ponto de entrada web.

## Requisitos
- PHP 8.1+
- Extensões: PDO, pdo_sqlite ou pdo_mysql, fileinfo, gd (para conversões de imagem), openssl.

## Configuração rápida
1. Instale dependências do PHP necessárias (extensões acima).
2. Garanta permissão de escrita em `database/` e `public/uploads/`.
3. Ajuste `config.php` para escolher driver (`sqlite` padrão) ou credenciais MySQL.
4. Rode migrations/seeds conforme scripts disponíveis (ex: `php run_migrations.php`).
5. Sirva `public/` via servidor web (Apache/Nginx) ou `php -S localhost:8000 -t public`.

## Segurança
- CSRF validado globalmente em POST via Router; inclua `<input name="_csrf">` nos formulários.
- Cookies: HttpOnly + SameSite=Strict; `secure` depende de HTTPS ativo.
- Login: throttle por IP/email (5 tentativas/15min) com logs em `debug.log`.
- Sessão: `session_regenerate_id` em login/logout.
- Uploads: validação de MIME e conversão; verifique permissões/owner em produção.

## Scripts úteis
- `run_migrations.php`: aplica migrações (ajuste conforme seu mecanismo de DB).

## Roadmap sugerido
- Ajustar `display_errors` para off em produção e configurar `error_log`.
- Ativar WAL no SQLite ou migrar para MySQL/PostgreSQL com `.env`.
- Recuperação de senha e verificação de email.
- Webhooks/API para tickets/usuários com assinatura HMAC.
- Logs/auditoria de ações sensíveis e cabeçalhos HTTP de segurança.

## Licença
Defina aqui a licença do projeto (ex.: MIT).
