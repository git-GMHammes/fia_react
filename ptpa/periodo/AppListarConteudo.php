<script type="text/babel">
    const AppListarConteudo = ({ parametros = {} }) => {
        // console.log('parametros: ', parametros);
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf;
        const origemForm = parametros.origemForm || '';
        const api_get_periodo = parametros.api_get_periodo;
        const api_post_filter_periodo = parametros.api_post_filter_periodo;

        // Base Lista
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        // Variáveis da API
        const [periodo, setPeriodo] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

        // Definindo mensagens do Sistema
        // const [tabNav, setTabNav] = React.useState('form');
        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            if (
                (name === 'periodo_data_termino' && value === '') ||
                (name === 'periodo_data_inicio' && value === '')
            ) {
                return true;
            }

            console.log('name handleBlur: ', name);
            console.log('value handleBlur: ', value);

            // Verificar se a data é válida
            const date = new Date(value);
            const [year, month, day] = value.split('-').map(Number);

            // Atualiza o valor do campo se a data for válida
            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

        };

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        const excluirPeriodo = async (id) => {
            try {
                const response = await fetch(`${base_url}index.php/fia/ptpa/periodo/api/deletar/${id}${getVar_page}`, {
                    method: 'GET'
                });

                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Excluido com sucesso'
                });
                redirectTo('index.php/fia/ptpa/periodo/endpoint/exibir');
            } catch (error) {
                console.error('Erro ao excluir periodo:', error);
            }
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            id: null,
            periodo_ano: null,
            periodo_capacidade_vagas: null,
            periodo_created_at: null,
            periodo_data_inicio: null,
            periodo_data_termino: null,
            periodo_deleted_at: null,
            periodo_numero: null,
            periodo_unidade_id: null,
            municipio_created_at: null,
            municipio_deleted_at: null,
            municipio_id_importa: null,
            municipio_id_mesoregiao: null,
            municipio_id_regiao: null,
            municipio_id_uf: null,
            municipio_nome: null,
            municipio_nome_mesoregiao: null,
            municipio_nome_regiao: null,
            municipio_nome_uf: null,
            municipio_updated_at: null,
            MunicipioId: null,
            periodo_updated_at: null,
            unidade_capacidade_atendimento: null,
            unidade_data_cadastramento: null,

            unidade_Logradouro: null,
            unidade_Bairro: null,
            unidade_CEP: null,
            unidade_Municipio: null,

            unidade_importa_id: null,
            unidade_municipio_id: null,
            unidade_nome: null,
            UnidadeId: null,
            updated_at: null,
        });


        const validateFormData = () => {
            const fieldsToValidate = [
                'ano',
                'unidade_nome',
                'unidade_Municipio',
                'periodo_data_inicio',
                'periodo_data_termino',
                'unidade_nome',
            ];

            const allFieldsEmpty = fieldsToValidate.every(field => {
                const val = formData[field];
                return val === null || val === '' || typeof val === 'undefined';
            });

            if (allFieldsEmpty) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: "Preencha ao menos um dos campos com um valor válido para que o filtro seja aplicado."
                });
                return false;
            }

            return true;
        };


        const submitAllForms = async (filtro, href = '') => {
            console.log('submitAllForms...');
            setMessage({ show: false, type: null, message: null });

            if (!validateFormData()) {
                return false;
            }
            const setData = formData;
            console.log('formData atual:', formData);
            let data = '';
            let dbResponse = [];
            let response = '';

            const url = href ? `${base_url + api_post_filter_periodo}${href}` : `${base_url + api_post_filter_periodo}`;
            console.log("url :: ", url);

            if (filtro === 'filtro-periodo') {
                // Convertendo os dados do setPost em JSON
                response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(setData),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    console.error('Erro na requisição:', response.statusText);
                    throw new Error(`Erro na requisição: ${response.statusText}`);
                }

                data = await response.json();

                console.log('data :: ', data);

                // Processa os dados recebidos da resposta
                if (
                    data.result && data.result.dbResponse && data.result.dbResponse.length > 0
                ) {
                    dbResponse = data.result.dbResponse;
                    setPeriodo(dbResponse);
                    setPagination('filter');
                    console.log('dbResponse: ', dbResponse);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "Certifique-se de que a combinação informada corresponde a um único período. Para datas, verifique se coincidem com as informações cadastradas."
                    });
                    console.log('setMessage :: ', 'Certifique-se de que a combinação informada corresponde a um único período. Para datas, verifique se coincidem com as informações cadastradas.');
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);
                    setDataLoading(false);
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
                    await fetchPeriodos();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter os Periodos
        const fetchPeriodos = async (custonBaseURL = base_url, custonApiGetPeriodos = api_get_periodo, customPage = getVar_page) => {
            try {
                const response = await fetch(custonBaseURL + custonApiGetPeriodos + customPage);
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse[0]) {
                    console.log('Periodos: ', data.result);
                    setPeriodo(data.result.dbResponse);
                    setPagination('list');
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontrados periodos cadastrados'
                    });
                    setDataLoading(false);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);

                    setDataLoading(false);
                }

            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Períodos: ' + error.message
                });
                // setError('Erro ao carregar Profissional: ' + error.message);
            }
        };

        // RENDER FILTER
        const renderFilter = () => {
            return (
                <div>

                    {/* Toggle Filtros */}
                    <nav className="navbar navbar-expand-lg">
                        <div className="container-fluid p-0">
                            <button
                                className="navbar-toggler"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarTogglerFiltroPeriodo"
                                aria-controls="navbarTogglerFiltroPeriodo"
                                aria-expanded="false"
                                aria-label="Toggle navigation">
                                <i className="bi bi-filter"></i>
                            </button>
                            <div className="collapse navbar-collapse" id="navbarTogglerFiltroPeriodo">
                                <div className="navbar-nav me-auto mb-2 mb-lg-0 w-100">

                                    <div className="d-flex flex-column flex-lg-row justify-content-between w-100" style={{ gap: '0.3rem' }}>
                                        <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <div className="col" style={formGroupStyle}>
                                                    <AppAnoFiltro formData={formData} setFormData={setFormData} parametros={parametros} />
                                                </div>
                                            </form>
                                        </div>

                                        <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <div>
                                                    <AppNomeUnidadeFiltro
                                                        formData={formData}
                                                        setFormData={setFormData}
                                                        parametros={parametros}
                                                    />
                                                </div>
                                            </form>
                                        </div>

                                        <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Município',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'unidade_Municipio',
                                                        errorMessage: '', // Mensagem de Erro personalizada
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 4, // minlength 
                                                        attributeMaxlength: 50, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </form>
                                        </div>

                                        <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppDate
                                                    submitAllForms
                                                    parametros={parametros}
                                                    formData={formData}
                                                    setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Inicio do Período',
                                                        nameField: 'periodo_data_inicio',
                                                        attributeMax: '', // maxDate - Profissional, Periodo.
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Filtro-Periodo', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                    }} />
                                            </form>
                                        </div>

                                        <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppDate
                                                    submitAllForms
                                                    parametros={parametros}
                                                    formData={formData}
                                                    setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Término do Período',
                                                        nameField: 'periodo_data_termino',
                                                        attributeMax: '', // maxDate - Profissional, Periodo.
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Filtro-Periodo', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                    }} />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            );
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

        // Estilos personalizados
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
                    <div className="d-flex justify-content-end">
                        <div>
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <input
                                    className="btn btn-secondary"
                                    type="submit"
                                    value="Filtrar"
                                />
                            </form>
                        </div>
                        <div className="ms-2">
                            <a className="btn btn-primary" href={`${base_url}index.php/fia/ptpa/periodo/endpoint/cadastrar`} role="button">
                                <i className="bi bi-plus"></i>&nbsp;Cadastrar Períodos
                            </a>
                        </div>
                    </div>
                    {/* RENDER FILTER */}
                    {renderFilter()}

                    {/* Divisor */}
                    <hr style={{ borderColor: 'gray', borderWidth: '1px' }} />
                </div>

                {/* Tabela */}
                <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                    <table className="table table-hover">
                        <thead>
                            <tr>

                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        ANO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        PERÍODO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        CAPACIDADE PERÍODO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        MUNICÍPIO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        UNIDADE
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        CAPACIDADE UNIDADE
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        INÍCIO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        TÉRMINO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        CONSULTAR
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
                            {dataLoading ? (
                                <tr>
                                    <td colSpan="11">
                                        <div className="m-5">
                                            <AppLoading
                                                parametros={{
                                                    tipoLoading: "progress",
                                                    carregando: dataLoading
                                                }} />
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                periodo && periodo.length > 0 ? (
                                    periodo.map((periodo_value, index_lista) => (
                                        <tr key={index_lista}>

                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {periodo_value.periodo_ano}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {periodo_value.periodo_numero}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {periodo_value.periodo_capacidade_vagas}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {periodo_value.unidade_Municipio}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {periodo_value.unidade_nome}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {periodo_value.unidade_capacidade_atendimento}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <AppDataPtBr parametros={periodo_value.periodo_data_inicio} />
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <AppDataPtBr parametros={periodo_value.periodo_data_termino} />
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/periodo/endpoint/consultar/${periodo_value.id}`} role="button">
                                                        <i className="bi bi-search" />
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <div>
                                                        <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/periodo/endpoint/atualizar/${periodo_value.id}`} role="button">
                                                            <i className="bi bi-pencil-square" />
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {/* Button trigger modal */}
                                                    <button type="button" className="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target={`#exampleModal${index_lista}`}>
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
                                                Não foram encontrados periodos cadastrados
                                            </div>
                                        </td>
                                    </tr>
                                )
                            )}

                        </tbody>
                    </table>
                </div>

                {periodo.map((periodo_value, index_lista) => (
                    <div key={index_lista}>
                        {/* Modal */}
                        <div className="modal fade" id={`exampleModal${index_lista}`} tabIndex="-1" aria-labelledby={`exampleModalLabel${index_lista}`} aria-hidden="true">
                            <div className="modal-dialog">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5 className="modal-title" id={`exampleModalLabel${index_lista}`}>Confirmar Exclusão:</h5>
                                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div className="modal-body">
                                        <div className="d-flex justify-content-center">
                                            <b>Unidade:&nbsp;</b>{periodo_value.unidade_nome}<br />
                                        </div>
                                        <hr />
                                        <div className="d-flex justify-content-center mb-1">
                                            <b>Município:&nbsp;</b>{periodo_value.municipio_nome}
                                        </div>
                                        <div className="d-flex justify-content-center">
                                            <b className="mt-2">Data de Inicio:&nbsp;</b><AppDataPtBr parametros={periodo_value.periodo_data_inicio} />
                                        </div>
                                        <div className="d-flex justify-content-center">
                                            <b className="mt-2">Data de Término:&nbsp;</b><AppDataPtBr parametros={periodo_value.periodo_data_termino} />
                                        </div>
                                    </div>
                                    <div className="modal-footer">
                                        <button
                                            className="btn btn-outline-danger"
                                            onClick={() => excluirPeriodo(periodo_value.id)}
                                        >
                                            Excluir
                                        </button>
                                        <button type="button" className="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}

                <div className="m-3">
                    {/* Paginação */}
                    {(pagination == 'list') && (
                        <nav aria-label="Page navigation example">
                            <ul className="pagination">
                                {paginacaoLista.map((paginacao_value, index) => (
                                    <li key={index} className={`page-item ${paginacao_value.active ? 'active' : ''}`}>
                                        <button
                                            className="page-link"
                                            onClick={() => fetchPeriodos(base_url, api_get_periodo, paginacao_value.href)}
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
                                            onClick={() => submitAllForms('filtro-periodo', paginacao_value.href)}
                                        >
                                            {paginacao_value.text.trim()}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </nav>
                    )}
                </div>


                {/* Modais para cada periodo */}
                {periodo.map((periodo, index) => (
                    <div key={index} className="modal fade" id={`staticBackdropPeriodo${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropPeriodoLabel${index}`} aria-hidden="true">
                        {/* modal-fullscreen / modal-xl*/}
                        <div className="modal-dialog modal-xl">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title" id={`staticBackdropPeriodoLabel${index}`}>Detalhes do Periodo</h5>
                                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div className="modal-body">
                                    ...
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
                {debugMyPrint && (
                    <div className="border border-primary m-5 p-5">
                        <AppJson
                            parametros={parametros}
                            dbResponse={periodo} />
                    </div>
                )}

                {/* Modais para cada periodo */}
                <AppMessageCard parametros={message} modalId="modal_list_periodo" />
            </div>
        );
    };
</script>