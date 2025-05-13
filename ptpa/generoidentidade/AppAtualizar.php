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
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_genero' => 'index.php/fia/ptpa/genero/api/filtrar',
    'api_get_programa' => 'index.php/fia/ptpa/programa/api/filtrar',
    'api_get_perfil' => 'index.php/fia/ptpa/perfil/api/filtrar',
    'api_get_unidade' => 'index.php/fia/ptpa/unidade/api/filtrar',
    'api_post_cadastrar_genero' => 'index.php/fia/ptpa/genero/api/cadastrar',
    'api_post_atualizar_genero' => 'index.php/fia/ptpa/genero/api/atualizar',
);
$parametros_backend['api_get_atualizar_genero'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/genero/api/exibir' . $atualizar_id) : ('fia/ptpa/genero/api/exibir/erro');
// myPrint($parametros_backend, 'src\app\Views\fia\ptpa\genero\AppAtualizar.php');
?>

<div class="app_cadastrar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppAtualizar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_cadastrar').getAttribute('data-result'));
        parametros.origemForm = 'genero'
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const base_url = parametros.base_url;

        return (
            <div>
                <AppForm parametros={parametros} />
            </div>
        );
    };
    // const rootElement = document.querySelector('.app_cadastrar');
    // const root = ReactDOM.createRoot(rootElement);
    // root.render(<AppCadastrar />);

</script>
<?php
$parametros_backend = array();
?>