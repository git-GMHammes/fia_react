<?php
$parametros_backend = array(
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'result' => isset($result) ? ($result) : (array()),
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
);
?>

<div class="app_tela_sso" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppTelaSSO = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_tela_sso').getAttribute('data-result'));
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const api_get_gender = parametros.base_url;

        const bodySSO = {
            background: `url(${base_url}assets/img/fia/tela_sso_semfundo.png) no-repeat left, linear-gradient(to bottom, #1C2743, #40276B)`,
            backgroundSize: 'cover, cover',
            minHeight: '100vh',
            margin: '0',
            color: 'white',
            textDecoration: 'none'
        };

        const imgFia = {
            width: 'auto',
            height: '50px',
        }

        const imgPro = {
            width: 'auto',
            height: '50px',
        }

        const imgGovBr = {
            width: 'auto',
            height: '25px',
        }

        const inForm = {
            minHeight: '100vh',
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'center'
        }

        // Aplicando estilo ao body
        Object.assign(document.body.style, bodySSO);
        return (
            <div>
                {debugMyPrint ? (
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                            Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ) : null}
                <div style={inForm} className="border border-warning">
                    <div className="container font-sans">
                        <div className="row mb-3">
                            <div className="col-12 col-sm-6">
                                <div className="d-flex justify-content-center">
                                    <p className="h3">Sistema da Fundação</p>
                                </div>
                                <div className="d-flex justify-content-center">
                                    <p className="h3">a Infância e Adolescência</p>
                                </div>
                                <div className="d-flex justify-content-center">
                                    <p className="h3">FIA RJ - Módulo: PTPA</p>
                                </div>
                            </div>
                            <div className="col-12 col-sm-6">
                                &nbsp;
                            </div>
                        </div>
                        <div className="row mb-5">
                            <div className="col-6 col-sm-3 d-flex justify-content-end">
                                <img className="img-fluid" style={imgFia} src={`${base_url}assets/img/fia/logo_composta.png`} alt="assets/img/fia/logo_composta.png" />
                            </div>
                            <div className="col-6 col-sm-3 d-flex justify-content-start">
                                <img className="img-fluid" style={imgPro} src={`${base_url}assets/img/proderj/LogoProderj.png`} alt="assets/img/proderj/LogoProderj.png" />
                            </div>
                            <div className="col-12 col-sm-6">
                                &nbsp;
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-12 col-sm-6">
                                <div className="d-flex justify-content-center">
                                    {/*<a className="btn btn-light rounded-3" href={`${base_url}exemple/group/endpoint/gov_br`} role="button">Acesso com <img className="img-fluid" style={imgGovBr} src={`${base_url}assets/img/fia/gov_br_logo.webp`} alt="assets/img/fia/gov_br_logo.webp" /></a>*/}
                                    <a className="btn btn-light rounded-3" href={`${base_url}exemple/group/endpoint/gov_br`} role="button">Acesso com <img className="img-fluid" style={imgGovBr} src={`${base_url}assets/img/fia/gov_br_logo.webp`} alt="assets/img/fia/gov_br_logo.webp" /></a>
                                    {/*
                                        <a className="btn btn-light rounded-3 ms-2" href={`${base_url}index.php/fia/ptpa/principal/endpoint/indicadores`} role="button">
                                            <i className="bi bi-gear me-2"></i>GAR
                                        </a>
                                    */}
                                    
                                </div>
                            </div>
                            <div className="col-12 col-sm-6">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    };
    // ReactDOM.render(<AppTelaSSO />, document.querySelector('.app_tela_sso'));

    const rootElement = document.querySelector('.app_tela_sso');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppTelaSSO />);
</script>
<?php
$parametros_backend = array();
?>