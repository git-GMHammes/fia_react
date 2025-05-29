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
    'environment' => isset($metadata['environment']) ? ($metadata['environment']) : ('PRD'),
    'base_url' => base_url(),
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_profissional' => 'index.php/fia/ptpa/profissional/api/filtrar',
    'api_get_adolescente' => 'index.php/fia/ptpa/adolescente/api/filtrar',
    'api_post_filtrar_profissional' => 'index.php/fia/ptpa/profissional/api/filtrar',
    'api_post_cadastrar_prontuariopsicosocial' => 'index.php/fia/ptpa/prontuariopsicosocial/api/cadastrar',
    'api_post_atualizar_prontuariopsicosocial' => 'index.php/fia/ptpa/prontuariopsicosocial/api/atualizar',
    'api_post_filtrar_adolescente' => 'index.php/fia/ptpa/adolescente/api/filtrar',
    'api_get_exibir_adolescente' => 'index.php/fia/ptpa/adolescente/api/exibir',
    'api_get_exibir_profissional' => 'index.php/fia/ptpa/profissional/api/exibir'
);
$parametros_backend['api_get_atualizar_prontuariopsicosocial'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/prontuariopsicosocial/api/exibir' . $atualizar_id) : ('fia/ptpa/prontuariopsicosocial/api/exibir/erro');
?>

<div class="app_atualizar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppAtualizar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_atualizar').getAttribute('data-result'));
        parametros.origemForm = 'prontuariopsicosocial'

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const title = parametros.title || '';
        const base_url = parametros.base_url;
        const getURI = parametros.getURI;

        // console.log('src/app/Views/fia/ptpa/prontuario/AppAtualizar.php');
        // console.log('debugMyPrint ::', debugMyPrint);

        // Variável para style
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
                <AppForm2
                    parametros={parametros}
                />

            </div>
        );
    };

    const rootElement = document.querySelector('.app_atualizar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppAtualizar />);
    // ReactDOM.render(<AppAtualizar />, document.querySelector('.app_atualizar'));

</script>
<?php
$parametros_backend = array();
?>