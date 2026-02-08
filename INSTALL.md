# Instalação em Produção (cPanel / Plesk)

## Requisitos
- PHP 8.1+
- Extensões: PDO, pdo_mysql, fileinfo, gd, openssl
- MySQL 5.7+ / MariaDB 10.3+

## 1) Subir os arquivos
Envie todo o projeto para o servidor via FTP/SFTP/Git.

## 2) Definir o DocumentRoot
### Opção A (recomendada): DocumentRoot apontando para `public/`
- cPanel: Domínios/Subdomínios -> Document Root -> selecione a pasta `public`
- Plesk: Domínios -> Hosting Settings -> Document root -> `public`

Acesso do instalador:
- `https://SEU-DOMINIO/install/`

### Opção B: sem alterar DocumentRoot
Se o provedor não permitir apontar o DocumentRoot para `public/`, deixe o docroot na raiz do projeto.

Acesso do instalador:
- `https://SEU-DOMINIO/public/install/`

## 3) Criar o banco de dados MySQL
Crie no painel (cPanel/Plesk):
- Banco de dados
- Usuário do banco
- Associe usuário ao banco com permissões

Guarde:
- Host do MySQL (geralmente `localhost`)
- Nome do banco
- Usuário
- Senha

## 4) Rodar o instalador
Acesse o instalador (URLs acima) e preencha:
- Host MySQL
- Nome do banco
- Usuário/Senha
/admin/usuarios/novo#
- Email e senha do admin

Ao concluir, o instalador irá:
- Criar `config.local.php` (configuração do MySQL)
- Criar `.env` (APP_ENV)
- Executar migrations (`database/migrations`)
- Criar o primeiro usuário admin
- Criar `install.lock` (bloqueia o instalador)

Observações:
- O instalador testa permissões do usuário MySQL (CREATE/INSERT/DROP) antes de prosseguir.
- Se você informar um prefixo (ex: `app_`), as tabelas serão criadas como `app_usuarios`, `app_settings`, etc.

## 5) Pós-instalação (obrigatório)
- Confirme que existe `install.lock` na raiz do projeto.
- O instalador pode tentar remover `public/install/` automaticamente (se o servidor permitir). Se não remover, você pode remover manualmente.
- Garanta que `config.local.php` e `.env` não estão acessíveis publicamente.

## 6) Permissões
Garanta permissão de escrita em:
- `public/uploads/` (e subpastas)
- `database/` (somente se ainda usar SQLite em algum ambiente)

## 7) Troca para MySQL
O instalador já configura o projeto para MySQL criando `config.local.php` com:
- `database.driver = mysql`

## 8) Solução de problemas
- Erro 500/rewrite: confirme que `mod_rewrite` está ativo e que existe `.htaccess` em `public/`.
- Erro de conexão MySQL: confirme host/usuário/senha/permissões.
- Tela em branco: verifique logs do servidor e o arquivo `php-error.log`.
