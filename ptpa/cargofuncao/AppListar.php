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
    'result' => isset($result) ? ($result) : (array()),
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'page' => isset($metadata['getVar_page']) ? ($metadata['getVar_page']) : ('1'),
    'base_url' => base_url(),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/exibir',
    'api_post_atualizar_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/atualizar',
    'api_post_cadastrar_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/cadastrar',
    'api_post_filter_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/filtrar',
);
$parametros_backend['api_get_atualizar_cargofuncao'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/cargofuncao/api/exibir' . $atualizar_id) : ('fia/ptpa/cargofuncao/api/exibir/erro');
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
// myPrint($parametros_backend, '');

?>

<div class="app_listar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar').getAttribute('data-result'));
        parametros.origemForm = 'cargofuncao'
        // console.log('parametros: ', parametros);
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf;
        const api_post_filter_cargofuncao = parametros.api_post_filter_cargofuncao;

        // Base Lista Cargo/Função
        const api_get_cargofuncao = parametros.api_get_cargofuncao;
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        // Variáveis da API
        const [cargofuncao, setCargoFuncao] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

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

        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            cargo_funcao: null,
            form_on: null,
        });

        const submitAllForms = async (filtro) => {
            console.log('submitAllForms...');
            const setData = formData;
            let data = '';
            let dbResponse = [];
            let response = '';
            // console.log('Dados a serem enviados:', setData);

            if (filtro === 'filtro-cargofuncao') {
                // Convertendo os dados do setPost em JSON
                response = await fetch(base_url + api_post_filter_cargofuncao, {
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
                    setCargoFuncao(dbResponse);
                    // console.log('dbResponse: ', dbResponse);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Mensagem de Sucesso'
                    });
                    return null;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Mensagem de Erro.'
                    });
                    return null;
                }
            }
        };

        // useEffect para carregar os dados na inicialização do componente
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchCargoFuncao();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter os Cargo/Funcao
        const fetchCargoFuncao = async (custonBaseURL = base_url, custonApiGetCargoFuncao = api_get_cargofuncao, customPage = getVar_page) => {
            try {
                const response = await fetch(custonBaseURL + custonApiGetCargoFuncao + customPage);
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse[0]) {
                    // console.log('Cargo/Função: ', data.result);
                    setCargoFuncao(data.result.dbResponse);
                    setPagination(true);
                }
                setDataLoading(false);
            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Cargo/Função: ' + error.message
                });
                // setError('Erro ao carregar Cargo/Função: ' + error.message);
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

        const formLabelStyle = {
            position: 'absolute',
            top: '-15px',
            left: '20px',
            backgroundColor: 'white',
            padding: '0 5px',
            color: 'gray',
        };

        return (
            <div>
                <div className="container font-sans">
                    {debugMyPrint ? (
                        <div className="row">
                            <div className="alert alert-danger" role="alert">
                                Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                            </div>
                        </div>
                    ) : null}

                    <div className="d-flex justify-content-start">
                        <div className="ms-4" style={verticalBarStyle}></div>
                        <h2 className="myBold mt-3">{title}</h2>
                    </div>

                    <div className="container font-sans">
                        <div className="d-flex justify-content-end">
                            <div>
                                <form onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`);
                                }}>
                                    <input
                                        className="btn btn-secondary"
                                        style={formControlStyle}
                                        type="submit"
                                        value="Filtrar"
                                    />
                                </form>
                            </div>
                            <div className="ms-2">
                                <a className="btn btn-primary btn" href={`${base_url}index.php/fia/ptpa/cargofuncao/endpoint/cadastrar/`} role="button">
                                    <i className="bi bi-plus"></i>&nbsp;Cadastrar Cargo/Função</a>
                            </div>
                        </div>

                        {/* toggle filtros */}
                        <nav className="navbar navbar-expand-lg">
                            <div className="container-fluid p-0">
                                <button
                                    className="navbar-toggler"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#navbarTogglerFiltroUnidade"
                                    aria-controls="navbarTogglerFiltroUnidade"
                                    aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <i className="bi bi-filter" />
                                </button>
                                <div className="collapse navbar-collapse" id="navbarTogglerFiltroUnidade">
                                    <div className="d-flex flex-lg-nowrap flex-wrap w-100" style={{ gap: '0.5rem' }}>

                                        <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <div style={formGroupStyle}>
                                                    <label
                                                        htmlFor="filtro-cargofuncao"
                                                        style={formLabelStyle}
                                                        className="form-label">CARGO/FUNÇÃO
                                                    </label>
                                                    <input
                                                        data-api="filtro-cargofuncao"
                                                        type="text"
                                                        name="cargofuncao"
                                                        value={formData.cargofuncao || ''}
                                                        onChange={handleChange}
                                                        className="form-control form-control-sm"
                                                        style={formControlStyle}
                                                        aria-label=".form-control-sm example"
                                                    />
                                                </div>
                                            </form>
                                        </div>

                                        <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <div style={formGroupStyle}>
                                                    <label
                                                        htmlFor="filtro-cargofuncao"
                                                        style={formLabelStyle}
                                                        className="form-label">FORM_ON
                                                    </label>
                                                    <input
                                                        data-api="filtro-cargofuncao"
                                                        type="text"
                                                        name="form_on"
                                                        value={formData.form_on || ''}
                                                        onChange={handleChange}
                                                        className="form-control form-control-sm"
                                                        style={formControlStyle}
                                                        aria-label=".form-control-sm example"
                                                    />
                                                </div>
                                            </form>
                                        </div>

                                        <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <div style={formGroupStyle}>
                                                    <label
                                                        htmlFor="cargofuncao"
                                                        style={formLabelStyle}
                                                        className="form-label">CRIADO EM
                                                    </label>
                                                    <input
                                                        data-api="filtro-cargofuncao"
                                                        type="text"
                                                        name="created_at"
                                                        value={formData.created_at || ''}
                                                        onChange={handleChange}
                                                        className="form-control form-control-sm"
                                                        style={formControlStyle}
                                                        aria-label=".form-control-sm example"
                                                    />
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <div className="container font-sans">
                        {/* Divisor */}
                        <hr style={{ borderColor: 'gray', borderWidth: '1px' }} />

                        {/* Tabela */}
                        <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                            <table className="table table-striped">
                                <thead className="border border-2 border-dark border-start-0 border-end-0">
                                    <tr>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                PERFIL
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                FORM_ON
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                CRIADO EM
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                EDITAR
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                EXCLUIR
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {isLoading ? (
                                        <tr>
                                            <td colSpan="10">
                                                <div className="m-5">
                                                    <AppLoading
                                                        parametros={{
                                                            tipoLoading: "progress",
                                                            carregando: isLoading
                                                        }}
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                    ) : cargofuncao.length > 0 ? (
                                        cargofuncao.map((perfil_value, index_lista_ado) => (
                                            <tr key={index_lista_ado}>
                                                <td>
                                                    <div className="d-flex justify-content-center">
                                                        {perfil_value.cargo_funcao}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div className="d-flex justify-content-center">
                                                        {perfil_value.form_on}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div className="d-flex justify-content-center">
                                                        {perfil_value.created_at}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div className="d-flex justify-content-center">
                                                        <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/cargofuncao/endpoint/atualizar/${perfil_value.id}`} role="button">
                                                            <i className="bi bi-pencil-square" />
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div className="d-flex justify-content-center">
                                                        <button
                                                            type="button"
                                                            className="btn btn-outline-danger btn-sm"
                                                            onClick={() => handleModalOpen(perfil_value)}
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#exclusaoModal"
                                                        >
                                                            <i className="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="10">
                                                <div className="m-5">
                                                    Não foram encontrados Cargo/Funções cadastradas.
                                                </div>
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>

                        {/* Modais para cada profissional */}
                        <AppMessageCard parametros={message} modalId="modal_form" />
                    </div>
                </div>
            </div>
        );
    };

    const rootElement = document.querySelector('.app_listar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListar />);
</script>
<?php
$parametros_backend = array();
?>