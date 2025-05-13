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
    'api_get_menu' => 'index.php/fia/ptpa/menu/api/exibir',
    'api_post_menu_atualizar' => 'index.php/fia/ptpa/menu/api/atualizar',
    'api_post_menu_cadastrar' => 'index.php/fia/ptpa/menu/api/cadastrar',
    'api_get_perfil' => 'index.php/fia/ptpa/perfil/api/filtrar',
    'api_get_cargo' => 'index.php/fia/ptpa/cargofuncao/api/filtrar',
    'api_post_filter_menu' => 'index.php/fia/ptpa/menu/api/filtrar',
    'api_post_cadastrar_profissional' => 'index.php/fia/ptpa/profissional/api/cadastrar',
    'api_post_atualizar_profissional' => 'index.php/fia/ptpa/profissional/api/atualizar',
);
#
$parametros_backend['api_get_atualizar_profissional'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/profissional/api/exibir' . $atualizar_id) : ('fia/ptpa/profissional/api/exibir/erro');
#
?>

<div class="app_painel_menu" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">

    const AppPainelSeguranca = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_painel_menu').getAttribute('data-result'));
        parametros.origemForm = 'profissional'

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const getVar_page = parametros.getVar_page;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf;
        const api_post_filter_menu = parametros.api_post_filter_menu;

        // Base API/POST
        const api_get_menu = parametros.api_get_menu || '';
        const api_post_menu_atualizar = parametros.api_post_menu_atualizar || '';
        const api_post_menu_cadastrar = parametros.api_post_menu_cadastrar || '';
        const api_get_perfil = parametros.api_get_perfil || '';
        const api_get_cargo = parametros.api_get_cargo || '';

        const api_post_cadastrar_profissional = parametros.api_post_cadastrar_profissional || '';
        const api_post_atualizar_profissional = parametros.api_post_atualizar_profissional || '';

        // Variáveis para APIs Select
        const [listaSeguranca, setListaSeguranca] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [dataLoading, setDataLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);

        // Definindo mensagens do Sistema
        const [tabNav, setTabNav] = React.useState('form');
        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;
            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Se o campo for o campo1, faz a validação
            if (name === 'campo1') {
                console.log('Informar o que é para fazer.');
            }
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            //
            token_csrf: '123',
            json: '1',
            //
            CargoFuncaoCreatedAt: null,
            CargoFuncaoDeletedAt: null,
            CargoFuncaoDescricao: null,
            CargoFuncaoFormOn: null,
            CargoFuncaoUpdatedAt: null,
            id: null,
            MenuCargoFuncaoId: null,
            MenuDescricao: null,
            MenuPerfilId: null,
            PerfilCreatedAt: null,
            PerfilDeletedAt: null,
            PerfilDescricao: null,
            PerfilFormOn: null,
            PerfilUpdatedAt: null,
            created_at: null,
            deleted_at: null,
            id_cargo: null,
            id_perfil: null,
            permissao: null,
            updated_at: null
        });

        const submitAllForms = async (filtro) => {
            // 
            console.log('submitAllForms...');
            const setData = formData;
            console.log('setData :: ', setData);
            let data = '';
            let dbResponse = [];
            let response = '';
            // console.log('Dados a serem enviados:', setData);

            if (filtro === `filtro-menu`) {
                // Convertendo os dados do setPost em JSON
                response = await fetch(base_url + api_post_filter_menu, {
                    method: 'POST',
                    body: JSON.stringify(setData),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.statusText}`);
                }

                data = await response.json();

                // Processa os dados recebidos da resposta
                if (
                    data.result && data.result.dbResponse && data.result.dbResponse[0]
                ) {
                    dbResponse = data.result.dbResponse;
                    console.log('dbResponse: ', dbResponse);
                    setListaSeguranca(dbResponse);
                    setPagination(true);
                    return dbResponse;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "Certifique-se de que todos os campos estão corretos: perfil, cargo função, módulo e método."
                    });
                    return null;
                }
            }
        };

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    await fetchMenu();

                } catch (error) {
                    console.error('Erro ao carregar dados:', error);

                } finally {
                    setDataLoading(false);
                    console.log('Termino React.useEffect');

                }
            };

            loadData();

        }, []);

        // Função handleBlur simplificada
        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Se o campo for o campo1, faz a validação
            if (name === 'campo1') {
                console.log('Informar o que é para fazer.');
            }

            setFormData((prev) => ({
                ...prev,
                [name]: ''
            }));

        };

        // Requisição GET Comum
        const fetchMenu = async (custonBaseURL = base_url, custonApiGet = api_get_menu, customPage = getVar_page) => {
            //
            try {
                const response = await fetch(custonBaseURL + custonApiGet + customPage);
                const data = await response.json();
                console.log("data:: ", data);

                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    setDataLoading(false);
                    setListaSeguranca(data.result.dbResponse);
                    setPagination(true);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);
                }

            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Unidades: ' + error.message
                });
                console.error('Erro ao carregar Profissionais: ' + error.message);
            }
        };

        const fetchMenuAtualizar = async (get_base_url = base_url, get_api = api_post_menu_atualizar, get_id = 'erro', get_permitido = 'N') => {
            try {
                const response = await fetch(get_base_url + get_api, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(
                        {
                            token_csrf: token_csrf,
                            json: '1',
                            id: get_id,
                            permissao: get_permitido
                        }
                    )
                });

                if (!response.ok) throw new Error(`Erro na requisição: ${response.statusText}`);

                const dataReturn = await response.json();

                if (dataReturn.result && dataReturn.result.affectedRows && dataReturn.result.affectedRows > 0) {
                    console.log('dataReturn: ', dataReturn.result);
                    submitAllForms(`filtro-menu`);
                }

                // console.log('formData ::: ', formData);

                return dataReturn.result.dbResponse || [];

            } catch (error) {
                console.error('Erro na requisição POST:', error.message);
                return [];
            }
        };


        if (debugMyPrint && isLoading) {
            return <div className="d-flex justify-content-center align-items-center min-vh-100">
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        }

        if (debugMyPrint && error) {
            return <div className="d-flex justify-content-center align-items-center min-vh-100">
                <div className="alert alert-danger" role="alert">
                    {error}
                </div>
            </div>
        }

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
                    <div className="container">
                        <div className="d-flex justify-content-end">
                            <div className="me-2">
                                <form onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-menu`, formData);
                                }}>
                                    <input
                                        className="btn btn-secondary"
                                        style={formControlStyle}
                                        type="submit"
                                        value="Filtrar"
                                    />
                                </form>
                            </div>
                        </div>

                        {/* toggle filtros */}
                        <nav className="navbar navbar-expand-lg" style={formControlStyle}>
                            <div className="container-fluid">
                                <button className="navbar-toggler bg-info" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
                                    <i className="bi bi-filter" />
                                </button>
                                <div className="collapse navbar-collapse" id="navbarSupportedContent2">
                                    <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li className="nav-item ms-1 me-1">
                                            <div style={formGroupStyle}>
                                                <form onSubmit={(e) => {
                                                    e.preventDefault();
                                                    submitAllForms(`filtro-menu`, formData);
                                                }}>
                                                    <input
                                                        data-api="filtro-menu"
                                                        type="text"
                                                        name="PerfilDescricao"
                                                        value={formData.PerfilDescricao || ''}
                                                        onChange={handleChange}
                                                        placeholder="Perfil"
                                                        className="form-control form-control-sm"
                                                        style={formControlStyle}
                                                    />
                                                </form>
                                            </div>
                                        </li>
                                        <li className="nav-item ms-1 me-1">
                                            <div style={formGroupStyle}>
                                                <form onSubmit={(e) => {
                                                    e.preventDefault();
                                                    submitAllForms(`filtro-menu`, formData);
                                                }}>
                                                    <input
                                                        data-api="filtro-menu"
                                                        type="text"
                                                        name="CargoFuncaoDescricao"
                                                        value={formData.CargoFuncaoDescricao || ''}
                                                        onChange={handleChange}
                                                        placeholder="Cargo Função"
                                                        className="form-control form-control-sm"
                                                        style={formControlStyle}
                                                    />
                                                </form>
                                            </div>
                                        </li>
                                        <li className="nav-item ms-1 me-1">
                                            <div style={formGroupStyle}>
                                                <form onSubmit={(e) => {
                                                    e.preventDefault();
                                                    submitAllForms(`filtro-menu`, formData);
                                                }}>
                                                    <input
                                                        data-api="filtro-menu"
                                                        type="text"
                                                        name="MenuDescricao"
                                                        value={formData.MenuDescricao || ''}
                                                        onChange={handleChange}
                                                        placeholder="Menu"
                                                        className="form-control form-control-sm"
                                                        style={formControlStyle}
                                                    />
                                                </form>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <div className="container">

                        {/* Divisor */}
                        <hr style={{ borderColor: 'gray', borderWidth: '1px' }} />

                        {/* Tabela */}
                        <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                            <table className="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">PERMISSÃO</th>
                                        <th scope="col">#</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">PERFIL</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">CARGO/FUNÇÃO</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">MENU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {dataLoading && (
                                        <tr>
                                            <td colSpan="9">
                                                <div className="m-5">
                                                    <AppLoading parametros={{
                                                        tipoLoading: "progress",
                                                        carregando: dataLoading
                                                    }}
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                    )}

                                    {listaSeguranca.map((menu_coluna, index_lista_ps) => (
                                        <tr key={index_lista_ps}>
                                            <td>
                                                {menu_coluna.permissao === "Y" ? (
                                                    <div>
                                                        <button
                                                            type="button"
                                                            className="btn btn-success btn-sm me-1"
                                                            onClick={() => fetchMenuAtualizar(base_url, api_post_menu_atualizar, menu_coluna.id, 'Y')}
                                                            disabled
                                                        >
                                                            <i className="bi bi-hand-thumbs-up"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            className="btn btn-outline-danger btn-sm ms-1"
                                                            onClick={() => fetchMenuAtualizar(base_url, api_post_menu_atualizar, menu_coluna.id, 'N')}
                                                        >
                                                            <i className="bi bi-hand-thumbs-down"></i>
                                                        </button>
                                                    </div>
                                                ) : (
                                                    <div>
                                                        <button
                                                            type="button"
                                                            className="btn btn-outline-success btn-sm me-1"
                                                            onClick={() => fetchMenuAtualizar(base_url, api_post_menu_atualizar, menu_coluna.id, 'Y')}
                                                        >
                                                            <i className="bi bi-hand-thumbs-up"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            className="btn btn-danger btn-sm ms-1"
                                                            onClick={() => fetchMenuAtualizar(base_url, api_post_menu_atualizar, menu_coluna.id, 'N')}
                                                            disabled
                                                        >
                                                            <i className="bi bi-hand-thumbs-down"></i>
                                                        </button>
                                                    </div>
                                                )}
                                            </td>
                                            <td>
                                                <a className="btn btn-outline-primary btn-sm disabled" href="#" role="button">
                                                    <i className="bi bi-pencil me-2"></i>{menu_coluna.id}
                                                </a>
                                            </td>
                                            <td>{menu_coluna.id_perfil}</td>
                                            <td>{menu_coluna.PerfilDescricao}</td>
                                            <td>{menu_coluna.id_cargo}</td>
                                            <td>{menu_coluna.CargoFuncaoDescricao}</td>
                                            <td>{menu_coluna.MenuCargoFuncaoId}</td>
                                            <td>{menu_coluna.MenuDescricao}</td>
                                        </tr>
                                    ))}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {/* Paginação */}
                        {pagination && (
                            <div>
                                <nav aria-label="Page navigation example">
                                    <ul className="pagination">
                                        {paginacaoLista.map((paginacao_value, index) => (
                                            <li key={index} className={`page-item ${paginacao_value.active ? 'active' : ''}`}>
                                                <button
                                                    className="page-link"
                                                    onClick={() => fetchMenu(base_url, api_get_menu, paginacao_value.href)}
                                                >
                                                    {paginacao_value.text.trim()}
                                                </button>
                                            </li>
                                        ))}
                                    </ul>
                                </nav>
                            </div>
                        )}

                        {/* Modais para cada profissional */}
                        <AppMessageCard parametros={message} modalId="modal_painel" />
                    </div>
                </div>
            </div>
        );
    };

    const rootElement = document.querySelector('.app_painel_menu');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppPainelSeguranca />);
</script>