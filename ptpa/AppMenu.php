<?php
$user_session = (session()->get('user_session')) ? (session()->get('user_session')) : (array());
// myPrint('$user_session :: ', $user_session, true);
$permissao_menu = (session()->get('permissao_menu_objeto')) ? (session()->get('permissao_menu_objeto')) : (array());
// myPrint('$permissao_menu :: ', $permissao_menu);

$parametros_backend = array(
    'DEBUG_MY_PRINT' => false,
    'user_session' => $user_session,
    'permissao_menu' => $permissao_menu,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
);

// myPrint($parametros_backend, 'parametros_backend');
?>

<div class="app_menu_react" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppMenu = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_menu_react').getAttribute('data-result'));
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const permissao_menu = parametros.permissao_menu.menu || {};
        // console.log('permissao_menu :: ', permissao_menu);
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;

        const [isCollapsed, setIsCollapsed] = React.useState(false);

        // Busca a palavra em um Array
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        const toggleCollapse = () => {
            setIsCollapsed(!isCollapsed);
        };
        const linkStyle = {
            color: '#53D7DC',
            fontFamily: '"Roboto", sans-serif',
            fontWeight: 700,
            fontStyle: 'normal'
        };
        const headerTextStyle = {
            backgroundImage: 'linear-gradient(to right, #330033, #14007A)',
            color: 'white',
            textDecoration: 'none',
            padding: '10px'
        };
        return (
            <div>
                <nav className="navbar navbar-expand-lg" style={headerTextStyle}>
                    <div className="container-fluid">
                        <button className="navbar-toggler bg-info" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span className="navbar-toggler-icon" />
                        </button>
                        <div className="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                                {(checkWordInArray(permissao_menu, 'Unidades')) && (
                                    <li className="nav-item">
                                        <a
                                            className="nav-link active"
                                            aria-current="page"
                                            href={`${base_url}index.php/fia/ptpa/unidade/endpoint/exibir`}
                                            style={linkStyle}
                                        >
                                            Unidades
                                        </a>
                                    </li>
                                )}
                                {(checkWordInArray(permissao_menu, 'Períodos')) && (
                                    <li className="nav-item">
                                        <a
                                            className="nav-link active"
                                            aria-current="page"
                                            href={`${base_url}index.php/fia/ptpa/periodo/endpoint/exibir`} style={linkStyle}>
                                            Períodos
                                        </a>
                                    </li>
                                )}
                                {(checkWordInArray(permissao_menu, 'Funcionários')) && (
                                    <li className="nav-item">
                                        <a
                                            className="nav-link active"
                                            aria-current="page"
                                            href={`${base_url}index.php/fia/ptpa/profissional/endpoint/exibir`}
                                            style={linkStyle}
                                        >
                                            Funcionários
                                        </a>
                                    </li>
                                )}
                                {(checkWordInArray(permissao_menu, 'Alocar Funcionário')) && (
                                    <li className="nav-item">
                                        <a
                                            className="nav-link active"
                                            aria-current="page"
                                            href={`${base_url}index.php/fia/ptpa/alocarfuncionario/endpoint/exibir`}
                                            style={linkStyle}
                                        >
                                            Alocar Funcionário
                                        </a>
                                    </li>
                                )}
                                {(checkWordInArray(permissao_menu, 'Adolescente')) && (
                                    <li className="nav-item">
                                        <a
                                            className="nav-link active"
                                            aria-current="page"
                                            href={`${base_url}index.php/fia/ptpa/adolescente/endpoint/exibir`} // 
                                            style={linkStyle}
                                        >
                                            Adolescentes
                                        </a>
                                    </li>
                                )}
                                {(checkWordInArray(permissao_menu, 'Prontuário')) && (
                                    <li className="nav-item">
                                        <a
                                            className="nav-link active"
                                            aria-current="page"
                                            href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/exibir`} // 
                                            style={linkStyle}
                                        >
                                            Prontuários
                                        </a>
                                    </li>
                                )}
                            </ul>
                            <form className="d-flex" role="search">
                                <div className="input-group mb-3">
                                    <input className="form-control me-0" type="search" placeholder="Buscar" aria-label="Search" />
                                    <button className="btn btn-light" type="submit"><i className="bi bi-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </nav>
                <div>
                    <button className="btn btn-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                        <i className="bi bi-gear-wide-connected" />
                    </button>
                    <div className="offcanvas offcanvas-start" tabIndex={-1} id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                        <div className="offcanvas-header">
                            <h5 className="offcanvas-title" id="offcanvasExampleLabel">Menu Lateral</h5>
                            <button type="button" className="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" />
                        </div>
                        <div className="offcanvas-header">
                            <h5 className="offcanvas-title" id="offcanvasExampleLabel">ACESSO PERMITIDO APENAS PARA GFS</h5>
                        </div>
                        <div className="offcanvas-body">
                            <div>
                                Menu Exclusivo para a Equipe de Desenvolvimento da GFS.
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Adolescente
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/adolescente/endpoint/cadastrar`} target="_blank">Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/adolescente/endpoint/drupal`} target="_blank">Drupal</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/adolescente/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Responsável
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/responsavel/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/responsavel/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Funcionário
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/profissional/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/profissional/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Cargo/Função
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/cargofuncao/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/cargofuncao/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Profissão
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/profissao/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/profissao/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Unidades
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/unidade/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/unidade/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Prontuário
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Gênero Identidade
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/genero/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/genero/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Perfil
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/perfil/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/perfil/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Programa
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/programa/endpoint/cadastrar`}>Cadastrar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/programa/endpoint/exibir`}>Listar</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Exemplo
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}exemple/group/endpoint/teste`}>Teste</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}exemple/group/endpoint/formatualizar`}>Formulario Cadastrar e Atualizar</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}exemple/group/endpoint/reactselect`}>React Select</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}exemple/group/endpoint/funcaobase`}>Função Base</a></li>
                                </ul>
                            </div>
                            <div className="dropdown mt-3">
                                <button className="btn btn-light dropdown-toggle w-100 d-flex justify-content-between" type="button" data-bs-toggle="dropdown">
                                    Usuário
                                </button>
                                <ul className="dropdown-menu">
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/usuario/endpoint/seguranca`}>Segurança</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/usuario/endpoint/menu`}>Menu</a></li>
                                    <li><a className="dropdown-item" href={`${base_url}index.php/fia/ptpa/log/endpoint/listar`}>Listar LOG</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    };
    const rootElement = document.querySelector('.app_menu_react');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppMenu />);
    // ReactDOM.render(<AppMenu />, document.querySelector('.App_menu_react'));
</script>

<?php
$parametros_backend = array();
?>