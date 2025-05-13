<?php
$parametros_backend = array(
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
);
?>

<div class="app_footer mt-5 pt-5" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppFooter = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_footer').getAttribute('data-result'));
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const [isFixed, setIsFixed] = React.useState(false);

        React.useEffect(() => {
            // Função para calcular dimensões e atualizar o estado
            const checkDimensions = () => {
                const windowHeight = window.innerHeight;
                const bodyHeight = document.body.scrollHeight;
                setIsFixed(bodyHeight <= windowHeight);
            };

            // Verificação inicial
            checkDimensions();

            // Observador de redimensionamento
            const resizeObserver = new ResizeObserver(checkDimensions);

            // Observar mudanças no body
            resizeObserver.observe(document.body);

            // Adicionar listener para redimensionamento da janela
            window.addEventListener('resize', checkDimensions);

            // Cleanup
            return () => {
                resizeObserver.disconnect();
                window.removeEventListener('resize', checkDimensions);
            };
        }, []);

        const footerStyle = {
            backgroundColor: '#f8f9fa',
            padding: '20px',
            width: '100%',
            backgroundColor: '#14007A',
            color: 'white',
            ...(isFixed ? {
                position: 'fixed',
                bottom: 0,
                left: 0
            } : {
                position: 'relative'
            })
        };
        const linkText = {
            textDecoration: 'none'
        };

        return (
            <div style={footerStyle}>
                {/* Conteúdo do rodapé aqui */}
                <div className="row">
                    <div className="col-12 col-sm-4">
                        <div className="d-flex align-items-center h-100 justify-content-center">
                            <img src={`${base_url}assets/img/proderj/LogoProderj.png`} alt="Logo Proderj" style={{ height: '60px' }} />
                        </div>
                    </div>
                    <div className="col-12 col-sm-4">
                        <div className="d-flex flex-column align-items-center">
                            <p className="fs-6 m-0">Rua Voluntários da Pátria, 120, Botafogo</p>
                            <p className="fs-6 m-0">Rio de Janeiro - RJ, CEP 22.270-010</p>
                            <p className="fs-6 m-0">E-mail: fia@fia.rj.gov.br</p>
                            <p className="fs-6 m-0">Telefone(s): (21) 2334-8030 | (21) 2334-8031</p>
                            {debugMyPrint && (
                                <div>
                                    <p>Rolagem: {resultado_rolagem.rolagem_rodape} Body: {resultado_rolagem.alturaBody}px Janela: {resultado_rolagem.alturaJanela}px</p>
                                </div>
                            )}
                        </div>
                    </div>
                    <div className="col-12 col-sm-4">
                        <div className="row">
                            <div className="col-12 col-sm-6">
                                <div className="d-flex align-items-center h-100 justify-content-center">
                                    <div className="d-flex flex-column align-items-start">
                                        <a className="link-offset-2 link-underline link-underline-opacity-0 text-white" style={linkText} href="https://www.rj.gov.br/fia/node/48">Estrutura</a>
                                        <a className="link-offset-2 link-underline link-underline-opacity-0 text-white" style={linkText} href="https://www.rj.gov.br/fia/licita%C3%A7%C3%A3o">Contato</a>
                                        <a className="link-offset-2 link-underline link-underline-opacity-0 text-white" style={linkText} href="https://www.rj.gov.br/fia/node/45">Quem Somos</a>
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-6">
                                <a className="link-offset-2 link-underline link-underline-opacity-0 text-white" style={linkText} href="https://twitter.com/GovRJ">
                                    <i className="bi bi-twitter-x" /> &nbsp;
                                </a>
                                <a className="link-offset-2 link-underline link-underline-opacity-0 text-white" style={linkText} href="https://www.facebook.com/FIARJ">
                                    <i className="bi bi-facebook" /> &nbsp;
                                </a>
                                <a className="link-offset-2 link-underline link-underline-opacity-0 text-white" style={linkText} href="https://www.instagram.com/fiarjoficial">
                                    <i className="bi bi-instagram" /> &nbsp;
                                </a>
                                <a className="link-offset-2 link-underline link-underline-opacity-0 text-white" style={linkText} href="https://www.linkedin.com/company/fia-rj/">
                                    <i className="bi bi-linkedin" /> &nbsp;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    };

    // Renderização
    const rootElement = document.querySelector('.app_footer');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppFooter />);
</script>

<?php
$parametros_backend = array();
?>