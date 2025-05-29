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
    'api_get_unidade' => 'index.php/fia/ptpa/unidade/api/filtrar',
    'api_post_cadastrar_periodo' => 'index.php/fia/ptpa/periodo/api/cadastrar',
    'api_post_atualizar_periodo' => 'index.php/fia/ptpa/periodo/api/atualizar',
    'api_post_filtrarassinatura_periodo' => 'index.php/fia/ptpa/periodo/api/filtrarassinatura',
);
#
$parametros_backend['api_get_atualizar_periodo'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/periodo/api/exibir' . $atualizar_id) : ('fia/ptpa/periodo/api/exibir/erro');
#
// myPrint('parametros_backend', $parametros_backend);
?>

<div class="app_atualizar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppAtualizar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_atualizar').getAttribute('data-result'));
        parametros.origemForm = 'periodo';
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const title = parametros.title;
        const atualizar_id = parametros.atualizar_id;

        // Nova constante de estilo para o texto "Footer"
        const headerTextStyle = {
            backgroundImage: 'linear-gradient(to right, #330033, #14007A)',
            color: 'white',
            textDecoration: 'none',
            padding: '10px'
        };

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
                </div>
                <div>
                    <AppForm
                        parametros={parametros}
                    />
                </div>
            </div>
        );
    };
    // ReactDOM.render(<AppAtualizar />, document.querySelector('.app_atualizar'));
    const rootElement = document.querySelector('.app_atualizar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppAtualizar />);
</script>
<?php
$parametros_backend = array();
?>