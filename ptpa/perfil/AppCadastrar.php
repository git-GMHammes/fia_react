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
    'api_post_cadastrar_perfil' => 'index.php/fia/ptpa/perfil/api/cadastrar',
    'api_post_atualizar_perfil' => 'index.php/fia/ptpa/perfil/api/atualizar',
);
$parametros_backend['api_get_atualizar_perfil'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/perfil/api/exibir' . $atualizar_id) : ('fia/ptpa/perfil/api/exibir/erro');

?>

<div class="app_cadastrar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppCadastrar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_cadastrar').getAttribute('data-result'));
        parametros.origemForm = 'perfil'
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const base_url = parametros.base_url;

        return (
            <div className="container font-sans">
                <AppForm parametros={parametros} />
            </div>
        );
    };

    // const rootElement = document.querySelector('.app_cadastrar');
    // const root = ReactDOM.createRoot(rootElement);
    // root.render(<AppCadastrar />);
    const rootElement = document.querySelector('.app_cadastrar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppCadastrar />);

</script>
<?php
$parametros_backend = array();
?>