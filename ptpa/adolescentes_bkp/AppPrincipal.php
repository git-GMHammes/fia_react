
<script type="text/babel">
    const AppPrincipal = () => {

        // Definindo o estado para controlar a aba ativa
        const [tabNav, setTabNav] = React.useState('dadosPessoais');

        // Função para trocar de aba
        const handleTabClick = (tab) => {
            setTabNav(tab); // Atualiza a aba selecionada
        };

        const renderCertidao = () => {
            return (
                <div>
                    <h1>Certidão</h1>
                    <p>Texto da Certidão</p>
                </div>
            );
        };
        

        return (
            <div className="m-3">
                <div className="container">
                    <ul className="nav nav-tabs border border-top-0 border-start-0 border-end-0 rounded-top">
                        <li className="nav-item">
                            <a
                                className={`nav-link ${tabNav === 'dadosPessoais' ? 'active' : ''}`}
                                href="#"
                                onClick={() => handleTabClick('dadosPessoais')}
                            >
                                Dados Pessoais CPF
                            </a>
                        </li>
                        <li className="nav-item">
                            <a
                                className={`nav-link ${tabNav === 'certidao' ? 'active' : ''}`}
                                href="#"
                                onClick={() => handleTabClick('certidao')}
                            >
                                Dados Certidão de Nascimento
                            </a>
                        </li>
                    </ul>

                    {/* Carrega todas as funções acima */}
                    <div className="border border-top-0 rounded-bottom p-3">
                        {tabNav === 'dadosPessoais' && <AppForm parametros={parametros} />}
                        {/* tabNav === 'aba2' && renderForm() */}
                        {/* tabNav === 'aba3' && renderText() */}
                    </div>
                </div>
            </div>
        );
    }
    const rootElement = document.querySelector('.app_principal');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppPrincipal />);
</script>
<?php
$parametros_backend = array();
?>