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
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_responsavel' => 'index.php/fia/ptpa/responsavel/api/filtrar',
    'api_get_sexo' => 'index.php/fia/ptpa/sexobiologico/api/filtrar',
    'api_get_genero' => 'index.php/fia/ptpa/genero/api/filtrar',
    'api_get_municipio' => 'index.php/fia/ptpa/municipio/api/filtrar',
    'api_get_unidade' => 'index.php/fia/ptpa/unidade/api/filtrar',
    'api_get_periodo' => 'index.php/fia/ptpa/periodo/api/filtrar',
    'api_get_adolescentes' => 'index.php/fia/ptpa/adolescente/api/exibir',
    'api_post_atualizar_adolescente' => 'index.php/fia/ptpa/adolescente/api/atualizar',
    'api_post_cadastrar_adolescente' => 'index.php/fia/ptpa/adolescente/api/cadastrar',
    'api_post_cadastrar_responsavel' => 'index.php/fia/ptpa/responsavel/api/cadastrar',
    'api_filter_unidades' => 'index.php/fia/ptpa/unidade/api/filtrar',
    'api_post_filter_unidade' => 'index.php/fia/ptpa/unidade/api/filtrar',
    'api_post_filter_responsaveis' => 'index.php/fia/ptpa/responsavel/api/filtrar',
    'api_post_confirma_email' => 'index.php/fia/ptpa/adolescente/api/confirmaemail',
);
$parametros_backend['api_get_atualizar_adolescente'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/adolescente/api/exibir' . $atualizar_id) : ('index.php/fia/ptpa/adolescente/api/exibir/erro');
// myPrint($parametros_backend, 'src\app\Views\fia\ptpa\adolescente\AppConsultar.php');
#
?>

<div class="app_consultar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppConsultar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_consultar').getAttribute('data-result'));
        parametros.origemForm = 'adolescente'
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const server_name = parametros.server_name;
        const user_session = parametros.user_session.FIA || {};
        const getURI = parametros.getURI || [];
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
    const rootElement = document.querySelector('.app_consultar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppConsultar />);
    // ReactDOM.render(<AppConsultar />, document.querySelector('.app_consultar'));

</script>
<?php
$parametros_backend = array();
?>