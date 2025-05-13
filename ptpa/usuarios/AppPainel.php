<?php

$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');
# 
$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_seguranca' => 'index.php/fia/ptpa/usuario/api/seguranca',
    'api_post_seguranca_atualizar' => 'index.php/fia/ptpa/seguranca/api/atualizar',
    'api_post_seguranca_objeto_atualizar' => 'index.php/fia/ptpa/segurancaobjeto/api/atualizar',
    'api_post_seguranca_cadastrar' => 'index.php/fia/ptpa/seguranca/api/cadastrar',
    'api_post_seguranca_filter' => 'index.php/fia/ptpa/seguranca/api/filtrar',
    'api_post_seguranca_filter_objeto' => 'index.php/fia/ptpa/segurancaobjeto/api/filtrar',
    'api_post_perfil_filter' => 'index.php/fia/ptpa/perfil/api/filtrar',
    'api_post_perfil_deletar' => 'index.php/fia/ptpa/perfil/api/deletar',
    'api_post_perfil_cadastrar' => 'index.php/fia/ptpa/perfil/api/cadastrar',
    'api_post_cargo_filter' => 'index.php/fia/ptpa/cargofuncao/api/filtrar',
    'api_post_cargo_cadastrar' => 'index.php/fia/ptpa/cargofuncao/api/cadastrar',
    'api_post_cargo_deletar' => 'index.php/fia/ptpa/cargofuncao/api/deletar',
    'api_post_cadastrar_profissional' => 'index.php/fia/ptpa/profissional/api/cadastrar',
    'api_post_atualizar_profissional' => 'index.php/fia/ptpa/profissional/api/atualizar',
    'api_post_filtrar_menu' => 'index.php/fia/ptpa/menu/api/filtrar',
    'api_post_menu_atualizar' => 'index.php/fia/ptpa/menu/api/atualizar',
    'api_post_gerar_seguranca' => 'index.php/fia/ptpa/security/api/assuredness',
    'api_post_excluir_seguranca' => 'index.php/fia/ptpa/security/api/sheltered',
);
#
$parametros_backend['api_get_atualizar_profissional'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/profissional/api/exibir' . $atualizar_id) : ('fia/ptpa/profissional/api/exibir/erro');
#
?>

<div class="app_painel" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppPainel = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_painel').getAttribute('data-result'));

        const [activeTab, setActiveTab] = React.useState('manutencao');
        // const [activeTab, setActiveTab] = React.useState('menu');
        // const [activeTab, setActiveTab] = React.useState('rota');

        // Seleção TAB
        const handleTabClick = (tab) => {
            setActiveTab(tab);
        };

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;

        const verticalBarStyle = {
            width: '5px',
            height: '60px',
            backgroundColor: '#00BFFF',
            margin: '10px',
            Right: '10px',
        };

        const formGroupStyle = {
            position: 'relative',
            marginTop: '20px',
            padding: '5px',
            borderRadius: '8px',
            border: '1px solid #000',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                {debugMyPrint ? (
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                            Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
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
                    <div className="col-12 col-sm-4">
                        &nbsp;
                    </div>
                </div>
                <div className="mt-4 ms-2 me-2">
                    <ul className="nav nav-tabs">
                        <li className="nav-item">
                            <a
                                className={`nav-link ${activeTab === 'manutencao' ? 'active' : ''}`}
                                onClick={() => handleTabClick('manutencao')}
                                href="#"
                            >
                                Manutenção
                            </a>
                        </li>
                        <li className="nav-item">
                            <a
                                className={`nav-link ${activeTab === 'menu' ? 'active' : ''}`}
                                onClick={() => handleTabClick('menu')}
                                href="#"
                            >
                                Menu
                            </a>
                        </li>
                        <li className="nav-item">
                            <a
                                className={`nav-link ${activeTab === 'rota' ? 'active' : ''}`}
                                onClick={() => handleTabClick('rota')}
                                href="#"
                            >
                                Rotas
                            </a>
                        </li>
                        <li className="nav-item">
                            <a
                                className={`nav-link ${activeTab === 'ajuda' ? 'active' : ''}`}
                                onClick={() => handleTabClick('ajuda')}
                                href="#"
                            >
                                Ajuda
                            </a>
                        </li>
                    </ul>

                    <div className="tab-content border border-top-0 p-2">
                        {activeTab === 'manutencao' && (
                            <div className="tab-pane active">
                                <AppManutencao
                                    parametros={parametros}
                                />
                            </div>
                        )}
                        {activeTab === 'menu' && (
                            <div className="tab-pane active">
                                <AppMenu
                                    parametros={parametros}
                                />
                            </div>
                        )}
                        {activeTab === 'rota' && (
                            <div className="tab-pane active">
                                <AppRota
                                    parametros={parametros}
                                />
                            </div>
                        )}
                        {activeTab === 'ajuda' && (
                            <div className="tab-pane active">
                                ... Ajuda
                            </div>
                        )}
                    </div>
                </div>
            </div>
        );
    };
    const rootElement = document.querySelector('.app_painel');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppPainel />);
</script>