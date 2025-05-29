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
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_periodo' => 'index.php/fia/ptpa/periodo/api/filtrar',
    'api_get_municipio' => 'index.php/fia/ptpa/municipio/api/filtrar',
    'api_post_cadastrar_alocarfuncionario' => 'index.php/fia/ptpa/alocarfuncionario/api/cadastrar',
    'api_post_atualizar_alocarfuncionario' => 'index.php/fia/ptpa/alocarfuncionario/api/atualizar',
    'api_filter_alocarfuncionario' => 'index.php/fia/ptpa/alocarfuncionario/api/filtrar',
    'api_post_filter_profissional' => 'index.php/fia/ptpa/profissional/api/filtrar',
);
$parametros_backend['api_get_atualizar_unidade'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/unidade/api/exibir' . $atualizar_id) : ('index.php/fia/ptpa/unidade/api/exibir/erro');
$parametros_backend['api_get_atualizar_profissional'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/profissional/api/exibir' . $atualizar_id) : ('index.php/fia/ptpa/profissional/api/exibir/erro');
$parametros_backend['api_get_profissionais'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/alocarfuncionario/api/consultar' . $atualizar_id) : ('index.php/fia/ptpa/alocarfuncionario/api/consultar/erro');
?>

<div class="app_consultar_unidade" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppConsultarUnidade = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_consultar_unidade').getAttribute('data-result'));
        parametros.origemForm = 'alocarfuncionario'

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const base_url = parametros.base_url;
        const title = parametros.title;
        const token_csrf = parametros.token_csrf;
        const atualizar_id = parametros.atualizar_id;
        console.log('atualizar_id:: ', atualizar_id);

        const verticalBarStyle = {
            width: '5px',
            height: '60px',
            backgroundColor: '#00BFFF',
            margin: '10px',
            Right: '10px',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
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
                <div className="ms-2 me-2">
                    <div className="d-flex justify-content-end">
                        <div className="ms-2">
                            <a className="btn btn-primary btn" href={`${base_url}index.php/fia/ptpa/alocarfuncionario/endpoint/cadastrar${atualizar_id}`} role="button">
                                <i className="bi bi-plus"></i>&nbsp;Cadastrar Funcionários
                            </a>
                        </div>
                    </div>
                </div>
                <div className="col-12 col-sm-2">
                    &nbsp;
                </div>

                {/* Consultar de Unidade */}
                <AppForm
                    parametros={parametros}
                />
                {/* Consultar de Unidade */}

                {/* Detalhes Funcionários */}
                <div className="m-3">
                    <div className="card card-body">
                        <AppListarConteudo
                            parametros={parametros}
                        />
                    </div>
                </div>
                {/* Detalhes Funcionários */}

                <div className="m-3">
                    <a
                        className="btn btn-danger"
                        href={`${base_url}index.php/fia/ptpa/alocarfuncionario/endpoint/exibir`}
                        role="button"> Voltar
                    </a>
                </div>
            </div>
        );
    };
    const rootElement = document.querySelector('.app_consultar_unidade');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppConsultarUnidade />);

</script>
<?php
$parametros_backend = array();
?>