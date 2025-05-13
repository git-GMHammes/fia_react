<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');
$usersession = (session()->get('user_session')) ? (session()->get('user_session')) : (array());

$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'DEBUG_MY_PRINT' => false,
    'token_csrf' => $token_csrf,
    'user_session' => $usersession,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'base_url' => base_url(),
    'atualizar_id' => $atualizar_id,
    'api_get_prontuariopsicosocial' => 'index.php/fia/ptpa/prontuariopsicosocial/api/exibir',
    'api_post_atualizar_prontuario' => 'index.php/fia/ptpa/prontuariopsicosocial/api/atualizar',
    'api_post_cadastrar_prontuario' => 'index.php/fia/ptpa/prontuariopsicosocial/api/cadastrar',
    'api_post_filter_profissional' => 'index.php/fia/ptpa/profissional/api/filtrar',
    'api_post_filter_adolescente' => 'index.php/fia/ptpa/adolescente/api/filtrar',
    'api_post_filter_prontuario' => 'index.php/fia/ptpa/prontuariopsicosocial/api/filtrar',
);
$parametros_backend['api_get_atualizar_prontuariopsicosocial'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/prontuariopsicosocial/api/exibir' . $atualizar_id) : ('index.php/fia/ptpa/prontuariopsicosocial/api/exibir/erro');
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
// myPrint($parametros_backend, '');

?>

<div class="app_listar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar').getAttribute('data-result'));
        parametros.origemForm = 'prontuario'

        return (
            <div>
                <AppListar_conteudo
                    parametros={parametros}
                />
            </div>
        );
    };
    //ReactDOM.render(<AppListar />, document.querySelector('.app_listar'));

    const rootElement = document.querySelector('.app_listar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListar />);
</script>
