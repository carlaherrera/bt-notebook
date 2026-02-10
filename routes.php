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

// Recuperação de Senha
$router->get('/esqueci-senha', ['Auth\\PasswordResetController', 'esqueci']);
$router->post('/esqueci-senha', ['Auth\\PasswordResetController', 'enviarToken']);
$router->get('/redefinir-senha', ['Auth\\PasswordResetController', 'redefinir']);
$router->post('/redefinir-senha', ['Auth\\PasswordResetController', 'atualizarSenha']);


// Painéis por perfil (protegidos por middleware)
$router->get('/admin', ['Admin\\PainelController', 'index'], ['auth', 'role:admin']);
$router->get('/admin/perfil', ['Admin\\PerfilController', 'index'], ['auth', 'role:admin']);
$router->post('/admin/perfil', ['Admin\\PerfilController', 'atualizar'], ['auth', 'role:admin']);
$router->get('/admin/parceiros', ['Admin\\ParceirosController', 'index'], ['auth', 'role:admin']);
$router->get('/admin/parceiros/novo', ['Admin\\ParceirosController', 'create'], ['auth', 'role:admin']);
$router->post('/admin/parceiros', ['Admin\\ParceirosController', 'store'], ['auth', 'role:admin']);
$router->get('/admin/parceiros/{id}/editar', ['Admin\\ParceirosController', 'edit'], ['auth', 'role:admin']);
$router->post('/admin/parceiros/{id}/editar', ['Admin\\ParceirosController', 'update'], ['auth', 'role:admin']);
$router->post('/admin/parceiros/{id}/toggle', ['Admin\\ParceirosController', 'toggle'], ['auth', 'role:admin']);
$router->get('/admin/parceiros/{id}/ver', ['Admin\\ParceirosController', 'show'], ['auth', 'role:admin']);
$router->get('/admin/parceiros/{id}/relatorio', ['Admin\\ParceirosController', 'relatorio'], ['auth', 'role:admin']);
$router->get('/admin/produtos', ['Admin\\ProdutosController', 'index'], ['auth', 'role:admin']);
$router->get('/admin/produtos/novo', ['Admin\\ProdutosController', 'create'], ['auth', 'role:admin']);
$router->post('/admin/produtos', ['Admin\\ProdutosController', 'store'], ['auth', 'role:admin']);
$router->get('/admin/produtos/{id}/editar', ['Admin\\ProdutosController', 'edit'], ['auth', 'role:admin']);
$router->post('/admin/produtos/{id}/editar', ['Admin\\ProdutosController', 'update'], ['auth', 'role:admin']);
$router->post('/admin/produtos/{id}/toggle', ['Admin\\ProdutosController', 'toggle'], ['auth', 'role:admin']);
$router->post('/admin/produtos/{id}/excluir', ['Admin\\ProdutosController', 'destroy'], ['auth', 'role:admin']);
$router->get('/admin/produtos/{id}/ver', ['Admin\\ProdutosController', 'show'], ['auth', 'role:admin']);
$router->get('/admin/movimentacoes', ['Admin\\MovimentacoesController', 'index'], ['auth', 'role:admin']);
$router->get('/admin/movimentacoes/nova', ['Admin\\MovimentacoesController', 'nova'], ['auth', 'role:admin']);
$router->post('/admin/movimentacoes', ['Admin\\MovimentacoesController', 'store'], ['auth', 'role:admin']);
$router->get('/admin/movimentacoes/{id}/editar', ['Admin\\MovimentacoesController', 'edit'], ['auth', 'role:admin']);
$router->post('/admin/movimentacoes/{id}/editar', ['Admin\\MovimentacoesController', 'update'], ['auth', 'role:admin']);
$router->post('/admin/movimentacoes/{id}/excluir', ['Admin\\MovimentacoesController', 'destroy'], ['auth', 'role:admin']);
$router->get('/admin/relatorios', ['Admin\\RelatoriosController', 'index'], ['auth', 'role:admin']);
$router->get('/admin/auditoria', ['Admin\\AuditoriaController', 'index'], ['auth', 'role:admin']);
$router->get('/admin/auditoria/nova', ['Admin\\AuditoriaController', 'create'], ['auth', 'role:admin']);
$router->post('/admin/auditoria', ['Admin\\AuditoriaController', 'store'], ['auth', 'role:admin']);
$router->get('/admin/auditoria/{id}/editar', ['Admin\\AuditoriaController', 'edit'], ['auth', 'role:admin']);
$router->post('/admin/auditoria/{id}/editar', ['Admin\\AuditoriaController', 'update'], ['auth', 'role:admin']);
$router->post('/admin/auditoria/{id}/excluir', ['Admin\\AuditoriaController', 'destroy'], ['auth', 'role:admin']);
$router->get('/admin/consignado', ['Admin\\ConsignadoController', 'index'], ['auth', 'role:admin']);
$router->get('/admin/consignado/parceiro/{id}/ver', ['Admin\\ConsignadoController', 'parceiro'], ['auth', 'role:admin']);
$router->post('/admin/consignado/transferir', ['Admin\\ConsignadoController', 'transferir'], ['auth', 'role:admin']);
$router->post('/admin/consignado/devolver', ['Admin\\ConsignadoController', 'devolver'], ['auth', 'role:admin']);
$router->get('/admin/usuarios', [Admin\UsuariosController::class, 'index'], ['auth', 'role:admin']);
$router->get('/admin/usuarios/novo', [Admin\UsuariosController::class, 'create'], ['auth', 'role:admin']);
$router->post('/admin/usuarios', [Admin\UsuariosController::class, 'store'], ['auth', 'role:admin']);
$router->get('/admin/usuarios/{id}', [Admin\UsuariosController::class, 'show'], ['auth', 'role:admin']);
$router->get('/admin/usuarios/{id}/editar', [Admin\UsuariosController::class, 'edit'], ['auth', 'role:admin']);
$router->post('/admin/usuarios/{id}/editar', [Admin\UsuariosController::class, 'update'], ['auth', 'role:admin']);
$router->post('/admin/usuarios/{id}/toggle', [Admin\UsuariosController::class, 'toggle'], ['auth', 'role:admin']);
$router->get('/admin/configuracoes', [Admin\ConfiguracoesController::class, 'index'], ['auth', 'role:admin']);
$router->post('/admin/configuracoes', [Admin\ConfiguracoesController::class, 'salvar'], ['auth', 'role:admin']);
$router->get('/admin/configuracoes/cep/{cep}', [Admin\ConfiguracoesController::class, 'cep'], ['auth', 'role:admin']);

$router->get('/colaborador', ['Colaborador\\PainelController', 'index'], ['auth', 'role:colaborador']);
$router->get('/colaborador/perfil', ['Colaborador\\PerfilController', 'index'], ['auth', 'role:colaborador']);
$router->post('/colaborador/perfil', ['Colaborador\\PerfilController', 'atualizar'], ['auth', 'role:colaborador']);
$router->get('/colaborador/preferencias', ['Colaborador\\PreferenciasController', 'index'], ['auth', 'role:colaborador']);
$router->post('/colaborador/preferencias', ['Colaborador\\PreferenciasController', 'salvar'], ['auth', 'role:colaborador']);

$router->get('/cliente', ['Cliente\\PainelController', 'index'], ['auth', 'role:cliente']);

// Pedidos
$router->get('/cliente/pedidos', ['Cliente\\PedidosController', 'index'], ['auth', 'role:cliente']);
$router->get('/cliente/pedidos/novo', ['Cliente\\PedidosController', 'create'], ['auth', 'role:cliente']);
$router->post('/cliente/pedidos', ['Cliente\\PedidosController', 'store'], ['auth', 'role:cliente']);
$router->get('/cliente/pedidos/{id}', ['Cliente\\PedidosController', 'show'], ['auth', 'role:cliente']);
$router->post('/cliente/pedidos/{id}/cancelar', ['Cliente\\PedidosController', 'cancel'], ['auth', 'role:cliente']);

// Endereços
$router->get('/cliente/enderecos', ['Cliente\\EnderecosController', 'index'], ['auth', 'role:cliente']);
$router->get('/cliente/enderecos/novo', ['Cliente\\EnderecosController', 'create'], ['auth', 'role:cliente']);
$router->post('/cliente/enderecos', ['Cliente\\EnderecosController', 'store'], ['auth', 'role:cliente']);
$router->get('/cliente/enderecos/{id}/editar', ['Cliente\\EnderecosController', 'edit'], ['auth', 'role:cliente']);
$router->post('/cliente/enderecos/{id}/editar', ['Cliente\\EnderecosController', 'update'], ['auth', 'role:cliente']);
$router->post('/cliente/enderecos/{id}/excluir', ['Cliente\\EnderecosController', 'destroy'], ['auth', 'role:cliente']);

// Pagamentos
$router->get('/cliente/pagamentos', ['Cliente\\PagamentosController', 'index'], ['auth', 'role:cliente']);
$router->get('/cliente/pagamentos/novo', ['Cliente\\PagamentosController', 'create'], ['auth', 'role:cliente']);
$router->post('/cliente/pagamentos', ['Cliente\\PagamentosController', 'store'], ['auth', 'role:cliente']);
$router->get('/cliente/pagamentos/{id}/editar', ['Cliente\\PagamentosController', 'edit'], ['auth', 'role:cliente']);
$router->post('/cliente/pagamentos/{id}/editar', ['Cliente\\PagamentosController', 'update'], ['auth', 'role:cliente']);
$router->post('/cliente/pagamentos/{id}/excluir', ['Cliente\\PagamentosController', 'destroy'], ['auth', 'role:cliente']);

// Suporte
$router->get('/cliente/suporte', ['Cliente\\SuporteController', 'index'], ['auth', 'role:cliente']);
$router->get('/cliente/suporte/novo', ['Cliente\\SuporteController', 'create'], ['auth', 'role:cliente']);
$router->post('/cliente/suporte', ['Cliente\\SuporteController', 'store'], ['auth', 'role:cliente']);
$router->get('/cliente/suporte/{id}', ['Cliente\\SuporteController', 'show'], ['auth', 'role:cliente']);
$router->post('/cliente/suporte/{id}/responder', ['Cliente\\SuporteController', 'responder'], ['auth', 'role:cliente']);
$router->post('/cliente/suporte/{id}/fechar', ['Cliente\\SuporteController', 'fechar'], ['auth', 'role:cliente']);

// Notas Fiscais
$router->get('/cliente/notas', ['Cliente\\NotasController', 'index'], ['auth', 'role:cliente']);
$router->get('/cliente/notas/{id}', ['Cliente\\NotasController', 'show'], ['auth', 'role:cliente']);

// Perfil e Preferências
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

