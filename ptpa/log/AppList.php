<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');
$usersession = (session()->get('user_session')) ? (session()->get('user_session')) : (array());

$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'DEBUG_MY_PRINT' => false,
    'user_session' => $usersession,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'result' => isset($result) ? ($result) : (array()),
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=1'),
    'page' => isset($metadata['getVar_page']) ? ($metadata['getVar_page']) : ('1'),
    'base_url' => base_url(),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_log' => 'index.php/fia/ptpa/log/api/listar',
    'api_post_atualizar_log' => 'index.php/fia/ptpa/log/api/atualizar',
    'api_post_cadastrar_log' => 'index.php/fia/ptpa/log/api/cadastrar',
    'api_post_filter_log' => 'index.php/fia/ptpa/log/api/filtrar',
    'api_post_decript_log' => 'index.php/fia/ptpa/anonimo/api/decript',
);
$parametros_backend['api_get_atualizar_log'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/log/api/listar' . $atualizar_id) : ('index.php/fia/ptpa/log/api/exibir/erro');
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
// myPrint($parametros_backend, '');

?>

<div class="app_list" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppList = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_list').getAttribute('data-result'));
        parametros.origemForm = 'log'

        return (
            <div>
                <AppListConteudo
                    parametros={parametros}
                />
            </div>
        );
    };

    const rootElement = document.querySelector('.app_list');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppList />);
</script>
<?php
$parametros_backend = array();
?>