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
    'api_post_cadastrar_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/cadastrar',
    'api_post_atualizar_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/atualizar',
    'api_post_filter_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/filtrar',
);
$parametros_backend['api_get_atualizar_cargofuncao'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/cargofuncao/api/exibir/' . $atualizar_id) : ('fia/ptpa/cargofuncao/api/exibir/erro');
// myPrint($parametros_backend, 'src\app\Views\fia\ptpa\cargofuncao\AppAtualizar.php');
?>

<div class="app_atualizar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppAtualizar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_atualizar').getAttribute('data-result'));
        parametros.origemForm = 'cargofuncao'
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const getURI = parametros.getURI;
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
            <div className="container font-sans">
                {debugMyPrint ? (
                    <div className="row ms-2 me-2">
                        <div className="col-12 col-sm-12">
                            <div className="alert alert-danger" role="alert">
                                Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                            </div>
                        </div>
                    </div>
                ) : null}
                <div className="row">
                    <div className="col-12 col-sm-4">
                        <div className="d-flex align-items-center">
                            <div className="ms-4" style={verticalBarStyle}></div>
                            <h2 className="myBold">{title}</h2>
                        </div>
                    </div>
                    <div className="col-12 col-sm-4">
                        &nbsp;
                    </div>
                    <div className="col-12 col-sm-2">
                        &nbsp;
                    </div>
                    <div className="col-12 col-sm-2">
                        &nbsp;
                    </div>
                </div>
                {/* Formulário de Cargo/Função */}
                <AppForm parametros={parametros} />
                {/* Formulário de Cargo/Função */}

            </div>
        );
    };
    const rootElement = document.querySelector('.app_atualizar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppAtualizar />);

</script>
<?php
$parametros_backend = array();
?>