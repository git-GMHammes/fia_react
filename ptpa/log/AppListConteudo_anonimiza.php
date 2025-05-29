<script type="text/babel">

    const LogDecript = ({
        setLabel,
        setValue,
        fetchPostDecript
    }) => {
        // console.log('LogDecript setLabel :: ', setLabel);
        // console.log('LogDecript setValue :: ', setValue);
        const [decriptedValue, setDecriptedValue] = React.useState('');
        const [isLoading, setIsLoading] = React.useState(false);
        const [error, setError] = React.useState(null);

        React.useEffect(() => {
            const decriptData = async () => {
                if (!setValue || !setLabel) return;

                setIsLoading(true);
                try {
                    const result = await fetchPostDecript(setLabel, setValue);
                    if (result) {
                        setDecriptedValue(result);
                    }
                } catch (err) {
                    setError('Erro ao descriptografar valor');
                    console.error('Erro na descriptografia:', err);
                } finally {
                    setIsLoading(false);
                }
            };

            decriptData();
        }, [setValue, setLabel, fetchPostDecript]);
        if (isLoading) {
            return <div className="ms-2 text-secondary"><small>{setValue}</small></div>;
        }

        if (error) {
            return <div className="ms-2 text-danger"><small>{error}</small></div>;
        }

        return (
            <div className="ms-2">
                {decriptedValue && (
                    <small className="text-dark">{decriptedValue}</small>
                )}
            </div>
        );
    };

    const AppListConteudo = ({
        parametros = {}
    }) => {
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const title = parametros.title || '';
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const request_scheme = parametros.request_scheme || '';
        const server_name = parametros.server_name || '';
        const server_port = parametros.server_port || '';
        const base_url = parametros.base_url || '';
        const token_csrf = parametros.token_csrf || '';
        const api_post_filter_log = parametros.api_post_filter_log || '';
        const api_post_decript_log = parametros.api_post_decript_log || '';

        // Base Lista Log
        const api_get_log = parametros.api_get_log;
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        // Variáveis da API
        const [log, setLog] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);


        // Adicione este state junto com os outros states no início do componente
        const [decodedUrls, setDecodedUrls] = React.useState(false);
        const [decodedUrlsData, setDecodedUrlsData] = React.useState({});
        const [decript, setDecript] = React.useState('');

        const fetchPostDecript = async (label = '', valor = '', custonBaseURL = base_url, custonApiPostObjeto = api_post_decript_log, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log('fetchPostDecript url :: ', url);
            if (label === '' || valor === '') {
                return `nulo`;
            }
            const setData = {
                [label]: valor
            };
            // console.log('fetchPostDecript setData :: ', setData);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('data :: ', data);
                // console.log('data.result :: ', data.result);
                console.log('data.result :: ', data.result[label]);
                if (data.result[label]) {
                    return data.result[label];
                }
                return false;
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                return false;
            }
        }

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

        // Função handleFocus para receber foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange para alterar o valor
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função que executa após a retirada do foco
        const handleBlur = (event) => {
            const { name, value } = event.target;

            console.log('name handleBlur: ', name);
            console.log('value handleBlur: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            setMessage({ show: false, type: null, message: null });
        }

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            filterResponsavel: null,
            //
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            url_carga: null,
            url_link: null,
            user_session: null,
            serverAddr: null,
            remoteAddr: null,
            carga: null,
            created_at: null,
            updated_at: null
        });

        const submitAllForms = async (filtro, getVar_page) => {
            console.log('submitAllForms...');
            const setData = formData;
            let data = '';
            let dbResponse = [];
            let response = '';
            //console.log('Dados a serem enviados:', setData);

            if (filtro === 'filtro-log') {
                fetchPostLog(setData, base_url, api_post_filter_log, getVar_page);
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
                    await fetchGetLog();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                    setTimeout(function () {
                        setDecodedUrls(true);
                    }, 1000);
                }
            };

            loadData();
        }, []);

        // Fetch para GET
        const fetchGetLog = async (custonBaseURL = base_url, custonApiGetObjeto = api_get_log, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiGetObjeto + customPage + '&limit=10';
            console.log('fetchGetLog url :: ', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                console.log('data(fetchLog) :: ', data);
                return true;
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    setLog(dbResponse);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas objeto cadastradas'
                    });
                    setIsLoading(false);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    // console.log('data.result.linksArray ::', data.result.linksArray);
                    setPaginacaoLista(data.result.linksArray);
                    setPagination('list');
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Unidades: ' + error.message
                });
            }
        };

        // Fetch para POST
        const fetchPostLog = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_filter_log, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage + '&limit=10';
            console.log('fetchPostLog url :: ', url);
            // return false;
            const SetData = formData;
            console.log('url :: ', url);
            console.log('SetData :: ', SetData);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(SetData),
                });
                const data = await response.json();
                console.log('data(fetchPostLog) :: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    console.log('data(fetchPostLog) :: ', data.result.dbResponse);
                    const dbResponse = data.result.dbResponse;
                    setLog(dbResponse);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas unidades cadastradas'
                    });
                    setIsLoading(false);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    console.log('data.result.linksArray ::', data.result.linksArray);
                    setPaginacaoLista(data.result.linksArray);
                    setPagination('filter');
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        if (error || debugMyPrint) {
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

        const formLabelStyle = {
            position: 'absolute',
            top: '-15px',
            left: '20px',
            backgroundColor: 'white',
            padding: '0 5px',
            color: 'gray',
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

                <div className="d-flex justify-content-start">
                    <div className="ms-4" style={verticalBarStyle}></div>
                    <h2 className="myBold mt-3">{title}</h2>
                </div>

                <div className="ms-2 me-2">
                    <div className="d-flex justify-content-end p-1">
                        <div>
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-log`);
                            }}>
                                <input
                                    className="btn btn-secondary"
                                    type="submit"
                                    value="Filtrar"
                                />
                            </form>
                        </div>
                    </div>

                    {/* toggle filtros */}
                    <nav className="navbar navbar-expand-lg">
                        <div className="container-fluid p-0 ">
                            <button
                                className="navbar-toggler ms-2"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarTogglerFiltroLog"
                                aria-controls="navbarTogglerFiltroLog"
                                aria-expanded="false"
                                aria-label="Toggle navigation"
                            >
                                <i className="bi bi-filter" />
                            </button>
                            <div className="collapse navbar-collapse" id="navbarTogglerFiltroLog">
                                <div className="navbar-nav me-auto mb-2 mb-lg-0 w-100">

                                    <div className="d-flex flex-column flex-lg-row justify-content-between w-100" style={{ gap: '0.3rem' }}>

                                        <div style={{ flex: '0 1 20%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-log`);
                                            }}>
                                                <div style={{ ...formGroupStyle }}>
                                                    <div>
                                                        <label
                                                            htmlFor="url_carga"
                                                            style={formLabelStyle}
                                                            className="form-label">
                                                            URL_CARGA
                                                        </label>
                                                        <input
                                                            data-api="filtro-profissional"
                                                            type="text"
                                                            name="url_carga"
                                                            value={formData.url_carga || ''}
                                                            onChange={handleChange}
                                                            onBlur={handleBlur}
                                                            onFocus={handleFocus}
                                                            className="form-control form-control-sm"
                                                            style={formControlStyle}
                                                        />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 20%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-log`);
                                            }}>
                                                <div style={{ ...formGroupStyle }}>
                                                    <div>
                                                        <label
                                                            htmlFor="url_link"
                                                            style={formLabelStyle}
                                                            className="form-label">
                                                            URL_LINK
                                                        </label>
                                                        <input
                                                            data-api="filtro-log"
                                                            type="text"
                                                            name="url_link"
                                                            value={formData.url_link || ''}
                                                            onChange={handleChange}
                                                            className="form-control form-control-sm"
                                                            style={formControlStyle}
                                                            aria-label=".form-control-sm example"
                                                        />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 20%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-log`);
                                            }}>
                                                <div style={{ ...formGroupStyle }}>
                                                    <div>
                                                        <label
                                                            htmlFor="user_session"
                                                            style={formLabelStyle}
                                                            className="form-label">
                                                            USER_SESSION
                                                        </label>
                                                        <input
                                                            data-api="filtro-log"
                                                            type="text"
                                                            name="user_session"
                                                            value={formData.user_session || ''}
                                                            onChange={handleChange}
                                                            className="form-control form-control-sm"
                                                            style={formControlStyle}
                                                            aria-label=".form-control-sm example"
                                                        />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 20%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-log`);
                                            }}>
                                                <div style={{ ...formGroupStyle }}>
                                                    <div>
                                                        <label
                                                            htmlFor="serverAddr"
                                                            style={formLabelStyle}
                                                            className="form-label">
                                                            SERVER_ADDR
                                                        </label>
                                                        <input
                                                            data-api="filtro-log"
                                                            type="text"
                                                            name="serverAddr"
                                                            value={formData.serverAddr || ''}
                                                            onChange={handleChange}
                                                            className="form-control form-control-sm"
                                                            style={formControlStyle}
                                                            aria-label=".form-control-sm example"
                                                        />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 20%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-log`);
                                            }}>
                                                <div style={{ ...formGroupStyle }}>
                                                    <div>
                                                        <label
                                                            htmlFor="remoteAddr"
                                                            style={formLabelStyle}
                                                            className="form-label"
                                                        >
                                                            REMOTE_ADDR
                                                        </label>
                                                        <input
                                                            data-api="filtro-log"
                                                            type="text"
                                                            name="remoteAddr"
                                                            value={formData.remoteAddr || ''}
                                                            onChange={handleChange}
                                                            className="form-control form-control-sm"
                                                            style={formControlStyle} aria-label=".form-control-sm example"
                                                        />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div >
                    </nav>
                    {/* toggle filtros */}
                </div>

                <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                    {/* Tabela */}
                    <table className="table table-striped">
                        <thead className="border border-2 border-dark border-start-0 border-end-0">
                            <tr>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        URL_CARGA
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        URL_LINK
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        USER_SESSION
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        SERVER-ADDR
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        REMOTE_ADDR
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        EDITAR
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {isLoading ? (
                                <tr>
                                    <td colSpan="8">
                                        <div className="m-5">
                                            <AppLoading
                                                parametros={{
                                                    tipoLoading: "progress"
                                                }}
                                            />
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                log && log.length > 0 ? (
                                    log.map((log_value, index_lista_log) => (
                                        <tr key={index_lista_log}>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <LogDecript
                                                        setLabel="url_carga"
                                                        setValue={log_value.url_carga}
                                                        fetchPostDecript={fetchPostDecript}
                                                    />
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {log_value.url_link}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {log_value.user_session}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <LogDecript
                                                        setLabel="serverAddr"
                                                        setValue={log_value.serverAddr}
                                                        fetchPostDecript={fetchPostDecript}
                                                    />
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <LogDecript
                                                        setLabel="remoteAddr"
                                                        setValue={log_value.remoteAddr}
                                                        fetchPostDecript={fetchPostDecript}
                                                    />
                                                </div>
                                            </td>
                                            <td>
                                                {/* Botão para acionar o modal */}
                                                <div className="d-flex justify-content-center">
                                                    <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/log/endpoint/atualizar/${log_value.id}`} role="button">
                                                        <i className="bi bi-pencil-square" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="8">
                                            <div className="m-5">
                                                Não foram encontrados logs cadastrados.
                                            </div>
                                        </td>
                                    </tr>
                                )
                            )
                            }
                        </tbody>
                        < tfoot >
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

                    {/* Paginação */}
                    {(pagination == 'list') && (
                        <nav aria-label="Page navigation example">
                            <ul className="pagination">
                                {paginacaoLista.map((paginacao_value, index) => (
                                    <li key={index} className={`page-item ${paginacao_value.active ? 'active' : ''}`}>
                                        <button
                                            className="page-link"
                                            onClick={() => fetchGetLog(base_url, api_get_log, paginacao_value.href)}
                                        >
                                            {paginacao_value.text.trim()}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </nav>
                    )}

                    {/* Paginação */}
                    {(pagination == 'filter') && (
                        <nav aria-label="Page navigation example">
                            <ul className="pagination">
                                {paginacaoLista.map((paginacao_value, index) => (
                                    <li key={index} className={`page-item ${paginacao_value.active ? 'active' : ''}`}>
                                        <button
                                            type="button"
                                            className="page-link"
                                            onClick={() => submitAllForms('filtro-log', paginacao_value.href)}
                                        >
                                            {paginacao_value.text.trim()}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </nav>
                    )}

                </div>

                {/* Modais para cada log */}
                {log.map((log, index) => (
                    <div key={index} className="modal fade" id={`staticBackdropLog${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropLogLabel${index}`} aria-hidden="true">
                        {/* modal-fullscreen / modal-xl*/}
                        <div className="modal-dialog modal-xl">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title" id={`staticBackdropLogLabel${index}`}>Detalhes do Log</h5>
                                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div className="modal-body">
                                    ...
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
                {/* Modais para cada log */}
            </div>
        );
    };

</script>