<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');
$usersession = (session()->get('user_session')) ? (session()->get('user_session')) : (array());

# 
$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'DEBUG_MY_PRINT' => false,
    'user_session' => $usersession,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'environment' => isset($metadata['environment']) ? ($metadata['environment']) : ('PRD'),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_profissao' => 'index.php/fia/ptpa/profissao/api/filtrar',
    'api_get_programa' => 'index.php/fia/ptpa/programa/api/filtrar',
    'api_get_perfil' => 'index.php/fia/ptpa/perfil/api/filtrar',
    'api_get_cargo' => 'index.php/fia/ptpa/cargofuncao/api/filtrar',
    'api_get_unidade' => 'index.php/fia/ptpa/unidade/api/filtrar',
    'api_post_filter_unidade' => 'index.php/fia/ptpa/unidade/api/filtrar',
    'api_post_cadastrar_profissional' => 'index.php/fia/ptpa/profissional/api/cadastrar',
    'api_post_atualizar_profissional' => 'index.php/fia/ptpa/profissional/api/atualizar',
);
$parametros_backend['api_get_atualizar_profissional'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/profissional/api/exibir' . $atualizar_id) : ('fia/ptpa/profissional/api/exibir/erro');
$parametros_backend['api_get_atualizar_unidade'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/unidade/api/exibir' . $atualizar_id) : ('index.php/fia/ptpa/unidade/api/exibir/erro');
#
?>

<div class="app_cadastrar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppCadastrar = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_cadastrar').getAttribute('data-result'));
        parametros.origemForm = 'profissional'

        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const getURI = parametros.getURI;
        const environment = parametros.environment;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const base_url = parametros.base_url;
        const title = parametros.title;

        const verticalBarStyle = {
            width: '5px',
            height: '60px',
            backgroundColor: '#00BFFF',
            margin: '10px',
            Right: '10px',
        };

        return (
            <div>
                {(debugMyPrint && environment === 'DEV') ? (
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                            Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ) : null}
                <div className="row">
                    <div className="col-12 col-sm-6">
                        <div className="d-flex align-items-center">
                            <div className="ms-4" style={verticalBarStyle}></div>
                            <h2 className="myBold">{title}</h2>
                        </div>
                    </div>
                    <div className="col-12 col-sm-6">
                        &nbsp;
                    </div>
                </div>
                <AppForm parametros={parametros} />
            </div>
        );
    };

    const rootElement = document.querySelector('.app_cadastrar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppCadastrar />);
    // ReactDOM.render(<AppCadastrar />, document.querySelector('.app_cadastrar'));

</script>
<?php
$parametros_backend = array();
?>