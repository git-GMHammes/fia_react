<?php

$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');
# 
$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_seguranca' => 'index.php/fia/ptpa/usuario/api/seguranca',
    'api_post_seguranca_atualizar' => 'index.php/fia/ptpa/seguranca/api/atualizar',
    'api_post_seguranca_objeto_atualizar' => 'index.php/fia/ptpa/segurancaobjeto/api/atualizar',
    'api_post_seguranca_cadastrar' => 'index.php/fia/ptpa/seguranca/api/cadastrar',
    'api_get_perfil' => 'index.php/fia/ptpa/perfil/api/filtrar',
    'api_get_cargo' => 'index.php/fia/ptpa/cargofuncao/api/filtrar',
    'api_post_seguranca_filter' => 'index.php/fia/ptpa/seguranca/api/filtrar',
    'api_post_seguranca_filter_objeto' => 'index.php/fia/ptpa/segurancaobjeto/api/filtrar',
    'api_post_cadastrar_profissional' => 'index.php/fia/ptpa/profissional/api/cadastrar',
    'api_post_atualizar_profissional' => 'index.php/fia/ptpa/profissional/api/atualizar',
    'api_post_filtrar_menu' => 'index.php/fia/ptpa/menu/api/filtrar',
);
#
$parametros_backend['api_get_atualizar_profissional'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/profissional/api/exibir' . $atualizar_id) : ('fia/ptpa/profissional/api/exibir/erro');
#
?>

<div class="app_painel_seguranca" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">

    

    const rootElement = document.querySelector('.app_painel_seguranca');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppPainelSeguranca />);
    // ReactDOM.render(<AppPainelSeguranca />, document.querySelector('.app_painel_seguranca'));
</script>