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
    'api_get_unidades' => 'index.php/fia/ptpa/unidade/api/exibir',
    'api_post_cadastrar_unidade' => 'index.php/fia/ptpa/unidade/api/cadastrar',
    'api_post_filter_unidade' => 'index.php/fia/ptpa/unidade/api/filtrar',
    // 'api_get_exibir_unidade' => 'index.php/fia/ptpa/unidade/api/exibir',
    // 'api_get_municipio' => 'index.php/fia/ptpa/municipio/api/filtrar',
    // 'api_get_periodo' => 'index.php/fia/ptpa/periodo/api/filtrar',
    // 'api_post_atualizar_unidade' => 'index.php/fia/ptpa/unidade/api/atualizar',
);
$parametros_backend['api_get_atualizar_unidade'] = ($atualizar_id !== 'erro') ? ('index.php/fia/ptpa/unidade/api/exibir' . $atualizar_id) : ('index.php/fia/ptpa/unidade/api/exibir/erro');
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
// myPrint($parametros_backend, '');
?>

<div class="app_listar_unidades" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListarUnidade = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar_unidades').getAttribute('data-result'));
        parametros.origemForm = 'unidade'

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
        const api_post_filter_unidade = parametros.api_post_filter_unidade;

        // Base Lista Unidades
        const api_get_unidades = parametros.api_get_unidades;
        const api_post_cadastrar_unidade = parametros.api_post_cadastrar_unidade || '';
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        // Busca a palavra em um Array
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Variáveis da API
        const [unidades, setUnidades] = React.useState([]);

        // Variáveis Uteis
        const [campoFiltro, setCampoFiltro] = React.useState('');
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

        // Definindo mensagens do Sistema
        // const [tabNav, setTabNav] = React.useState('form');
        // const [showAlert, setShowAlert] = React.useState(false);
        // const [alertType, setAlertType] = React.useState('');
        // const [alertMessage, setAlertMessage] = React.useState('');
        const [showModal, setShowModal] = React.useState(false); // Controle do modal
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            // Validação para o campo unidades_Municipio
            if (name === 'unidades_Municipio') {
                // Remove caracteres inválidos (números e caracteres especiais)
                const filteredValue = value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s]/g, '');

                // Atualiza o estado com o valor filtrado
                setFormData((prev) => ({
                    ...prev,
                    [name]: filteredValue
                }));
            } else {
                // Para outros campos, apenas atualiza o valor normalmente
                setFormData((prev) => ({
                    ...prev,
                    [name]: value
                }));
            }
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            // console.log('name handleBlur AppFormData: ', name);
            // console.log('value handleBlur AppFormData: ', value);

            // Função para validar se a data é válida
            const isDateValid = (date) => {
                const parsedDate = new Date(date);
                return parsedDate instanceof Date && !isNaN(parsedDate);
            };

            // Ignorar se o campo estiver vazio
            if (value === null || value.trim() === '') return;

            // Função para validar ano bissexto
            const isValidLeapYearDate = (date) => {
                const [year, month, day] = date.split('-').map(Number);
                const isLeapYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
                return month === 2 && day === 29 ? isLeapYear : isDateValid(date);
            };

            const currentDate = new Date().toISOString().split('T')[0];
            const dataInicio = formData.unidades_data_cadastramento_inicio;
            const dataFim = formData.unidades_data_cadastramento_fim;

            // Validações para data de início e fim
            if (name === 'unidades_data_cadastramento_inicio' && value) {
                const [year] = value.split('-').map(Number);
                if (year < 1999) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "Você está pesquisando uma data anterior ao Decreto Estadual nº 25.162, de 01/01/1999"
                    });
                    setFormData((prev) => ({
                        ...prev,
                        unidades_data_cadastramento_inicio: ''
                    }));
                    return;
                }
                if (value > currentDate) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "A data INICIAL de consulta não pode ser superior à data de hoje"
                    });
                    setFormData((prev) => ({
                        ...prev,
                        unidades_data_cadastramento_inicio: ''
                    }));
                    return;
                }
            }

            if (name === 'unidades_data_cadastramento_fim' && value) {
                if (value > currentDate) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "A data FINAL de consulta não pode ser superior à data de hoje"
                    });
                    setFormData((prev) => ({
                        ...prev,
                        unidades_data_cadastramento_fim: ''
                    }));
                    return;
                }
            }

            // Validação de data inválida
            if (
                !isValidLeapYearDate(value) &&
                (
                    name === 'unidades_data_cadastramento_inicio'
                )) {
                console.log('name handleBlur: ', name);
                console.log('value handleBlur: ', value);
                setMessage({
                    show: true,
                    type: 'light',
                    message: "A data INICIAL deve ser válida"
                });

                setFormData((prev) => ({
                    ...prev,
                    unidades_data_cadastramento_inicio: null,
                }));
                return;
            }

            // Validação de data inválida
            if (
                !isValidLeapYearDate(value) &&
                (
                    name === 'unidades_data_cadastramento_fim'
                )) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: "A data FINAL deve ser válida"
                });
                setFormData((prev) => ({
                    ...prev,
                    unidades_data_cadastramento_fim: ''
                }));
                return;
            }

            // Verifica se a data de início é maior que a data de fim
            if (dataInicio && dataFim && new Date(dataInicio) > new Date(dataFim)) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: "O campo data Inicial não deve ser maior ou igual à data Final"
                });
                setFormData((prev) => ({
                    ...prev,
                    [name]: ''
                }));
                return;
            }

            // Validação para o campo unidades_Municipio
            if (name === 'unidades_Municipio') {
                // Permite campo em branco
                if (value.trim() === '') {
                    setMessage({ show: false, type: null, message: null });
                    return;
                }

                // Regex para validar apenas letras e espaços
                const isValidMunicipio = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/.test(value);

                if (!isValidMunicipio) {
                    // Exibe mensagem de erro se o valor contiver números ou caracteres especiais
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "O nome do município deve conter apenas letras e espaços."
                    });

                    // Limpa o campo no estado
                    setFormData((prev) => ({
                        ...prev,
                        unidades_Municipio: ''
                    }));
                }
            }

            //console.log("updatedFormData : ", updatedFormData);
            // Atualiza o formData
            setFormData((prev) => {
                const updatedFormData = {
                    ...prev,
                    [name]: value
                };

                // Atualiza campoFiltro
                updateCampoFiltro(updatedFormData);

                return updatedFormData;
            });
        };

        // Dicionário de Títulos dos Campos
        const fieldTitles = {
            municipio_id: "ID do Município",
            idUnidades: "ID da Unidade",
            unidade_nome: "Nome da Unidade",
            unidades_Logradouro: "Endereço da Unidade",
            unidades_Numero: "Número do Endereço",
            unidades_Bairro: "Bairro do Endereço",
            unidades_Complemento: "Complemento do Endereço",
            unidades_cap_atendimento: "Capacidade de Atendimento da Unidade",
            unidades_data_cadastramento_inicio: "Data de Cadastro do Inscrito",
            unidades_data_cadastramento_fim: "Data do Fim do Cadastro",
            created_at: "Criado em",
            updated_at: "Atualizado em",
            deleted_at: "Deletado em",
            unidades_Municipio: "Nome do Município",
            municipios_id_regiao: "ID da Região",
            municipios_nome_regiao: "Nome da Região",
            municipios_id_mesoregiao: "ID da Mesorregião",
            municipios_nome_mesoregiao: "Nome da Mesorregião",
            municipios_id_uf: "ID do Estado",
            municipios_nome_uf: "Nome do Estado"
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            municipio_id: null,
            idUnidades: null,
            unidade_nome: null,
            unidades_Logradouro: null,
            unidades_Numero: null,
            unidades_Bairro: null,
            unidades_Complemento: null,
            unidades_cap_atendimento: null,
            unidades_data_cadastramento_inicio: null,
            unidades_data_cadastramento_fim: null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
            municipios_id: null,
            municipios_id_municipio: null,
            unidades_Municipio: null,
            municipios_id_regiao: null,
            municipios_nome_regiao: null,
            municipios_id_mesoregiao: null,
            municipios_nome_mesoregiao: null,
            municipios_id_uf: null,
            municipios_nome_uf: null,
            municipio_created_at: null,
            municipio_updated_at: null,
            municipio_deleted_at: null,
        });

        const validateFormData = () => {
            // Lista de campos que devem ser validados
            const fieldsToValidate = [
                'municipio_id', 'idUnidades', 'unidade_nome', 'unidades_Logradouro', 'unidades_cap_atendimento', 'unidades_Numero',
                'unidades_Bairro', 'unidades_Complemento', 'unidades_data_cadastramento_inicio', 'unidades_data_cadastramento_fim',
                'created_at', 'updated_at', 'deleted_at', 'municipios_id', 'municipios_id_municipio', 'unidades_Municipio',
                'municipios_id_regiao', 'municipios_nome_regiao', 'municipios_id_mesoregiao', 'municipios_nome_mesoregiao',
                'municipios_id_uf', 'municipios_nome_uf', 'municipio_created_at', 'municipio_updated_at', 'municipio_deleted_at'
            ];

            // Verifica se todos os campos estão nulos ou vazios
            const allFieldsEmpty = fieldsToValidate.every(field => formData[field] === null || formData[field] === '');

            if (allFieldsEmpty) {
                setMessage({
                    show: false,
                    type: 'light',
                    message: "Preencha ao menos um dos campos com um valor válido para que o filtro seja aplicado."
                });
                return false;
            }

            return true;
        };

        // lista contendo as chaves que representam campos de data
        const dateFields = [
            'unidades_data_cadastramento_inicio',
            'unidades_data_cadastramento_fim',
            // Adicione outras chaves de data conforme necessário
        ];

        // função auxiliar que recebe uma string de data no formato YYYY-MM-DD e retorna no formato PT-BR DD-MM-AAAA.
        const formatDateToPTBR = (dateString) => {
            if (!dateString) return '';
            const parts = dateString.split('-');
            if (parts.length !== 3) return dateString; // Retorna a string original se o formato estiver incorreto
            const [year, month, day] = parts;
            return `${day}-${month}-${year}`;
        };

        // Função para atualizar campoFiltro com os campos preenchidos
        const updateCampoFiltro = (updatedFormData) => {
            const filledFields = Object.entries(updatedFormData)
                .filter(([key, val]) => val && !['token_csrf', 'json', 'id'].includes(key))
                .map(([key, val]) => {
                    // Verifica se a chave é um campo de data
                    const displayValue = dateFields.includes(key) ? formatDateToPTBR(val) : val;
                    return `${fieldTitles[key] || key}: ${displayValue}`;
                })
                .join(', ');

            // console.log("Não foram encontrados opções para o filtro aplicado:", filledFields);
            setCampoFiltro("Não foram encontrados opções para o filtro aplicado: " + filledFields);
        };

        const filterFormData = (data) => {
            return Object.fromEntries(Object.entries(data).filter(([key, value]) => value != null));
        };

        // Submit All Forms para o filtro
        const submitAllForms = async (filtro, href = '') => {
            console.log('submitAllForms...');
            console.log('filtro :: ', filtro);
            console.log('href :: ', href);
            const setData = filterFormData(formData);

            updateCampoFiltro(setData);

            if (!validateFormData()) {
                return;
            }

            const url = href ? `${base_url + api_post_filter_unidade}${href}` : `${base_url + api_post_filter_unidade}`;
            console.log('url :: ', url);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(setData),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.statusText}`);
                }

                const data = await response.json();
                console.log('Resposta do servidor:', data);

                let resposta = '';

                if (checkWordInArray(getURI, 'cadastrar')) {
                    resposta = 'Cadastro';
                } else if (checkWordInArray(getURI, 'atualizar')) {
                    resposta = 'Atualização';
                } else if (checkWordInArray(getURI, 'consultar')) {
                    resposta = 'Consulta'
                } else if (checkWordInArray(getURI, 'exibir')) {
                    resposta = 'Exibir'
                } else {
                    resposta = 'Ação';
                }

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setUnidades(data.result.dbResponse);
                    setPagination('filter');
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: campoFiltro
                    });
                }

                if (data.result && data.result.linksArray) {
                    setPaginacaoLista(data.result.linksArray);
                    setDataLoading(false);
                }

            } catch (error) {
                console.error('Erro ao enviar formulário:', error.message);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao processar a solicitação. Tente novamente.',
                });
            }
        };

        React.useEffect(() => {
            updateCampoFiltro(formData);
        }, [formData]);

        // useEffect para carregar os dados na inicialização do componente
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchUnidades();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter os Unidades
        const fetchUnidades = async (custonBaseURL = base_url, custonApiGetUnidades = api_get_unidades, customPage = getVar_page) => {
            // console.log('custonBaseURL: ', custonBaseURL);
            // console.log('custonApiGetUnidades: ', custonApiGetUnidades);
            // console.log('customPage: ', customPage);
            try {
                const response = await fetch(custonBaseURL + custonApiGetUnidades + customPage);
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse[0]) {
                    // console.log('Unidades: ', data.result);
                    setUnidades(data.result.dbResponse);
                    setPagination('list');
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas unidades cadastradas'
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
                    message: 'Erro ao carregar Unidades: ' + error.message
                });
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
                                <input
                                    className="btn btn-secondary"
                                    style={formControlStyle}
                                    type="submit"
                                    value="Filtrar"
                                    onClick={() => setShowModal(true)} // Abre o modal
                                />
                            </div>
                        </div>

                        {/* toggle filtros */}
                        <nav className="navbar navbar-expand-lg">
                            <div className="container-fluid p-0">
                                <button
                                    className="navbar-toggler"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#navbarFiltroAlocarFuncionario"
                                    aria-controls="navbarFiltroAlocarFuncionario"
                                    aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <i className="bi bi-filter" />
                                </button>
                                <div className="collapse navbar-collapse" id="navbarFiltroAlocarFuncionario">
                                    <ul className="navbar-nav w-100 d-flex gap-2">

                                        <li className="nav-item flex-grow-1">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Nome (Unidade)',
                                                        labelColor: 'gray', // gray, red, black
                                                        nameField: 'unidades_nome',
                                                        errorMessage: '', // Mensagem de Erro personalizada
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 4, // minlength 
                                                        attributeMaxlength: 100, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </form>
                                        </li>
                                        <li className="nav-item flex-grow-1">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }} onKeyDown={(e) => {
                                                if (e.key === 'Enter') e.preventDefault();
                                            }}>
                                                <div style={formGroupStyle}>
                                                    <label
                                                        htmlFor="unidades_Logradouro"
                                                        style={formLabelStyle}
                                                        className="form-label">Endereço (Unidade)
                                                    </label>
                                                    <input
                                                        data-api="filtro-unidade"
                                                        type="text"
                                                        name="unidades_Logradouro"
                                                        value={formData.unidades_Logradouro || ''}
                                                        onChange={handleChange}
                                                        onBlur={handleBlur}
                                                        className="form-control form-control-sm"
                                                        style={formControlStyle}
                                                    />
                                                </div>
                                            </form>
                                        </li>
                                        <li className="nav-item flex-grow-1">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }} onKeyDown={(e) => {
                                                if (e.key === 'Enter') e.preventDefault();
                                            }}>
                                                <div style={formGroupStyle}>
                                                    <label
                                                        htmlFor="unidades_Municipio"
                                                        style={formLabelStyle}
                                                        className="form-label">Município
                                                    </label>
                                                    <input
                                                        data-api="filtro-unidade"
                                                        type="text"
                                                        name="unidades_Municipio"
                                                        value={formData.unidades_Municipio || ''}
                                                        onChange={handleChange}
                                                        onBlur={handleBlur}
                                                        className="form-control form-control-sm"
                                                        style={formControlStyle}
                                                    />
                                                </div>
                                            </form>
                                        </li>
                                    </ul>
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
                                                NOME
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                ENDEREÇO
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                MUNICÍPIO
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                CAPACIDADE
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                DATA/CADASTRO
                                            </div>
                                        </th>
                                        <th scope="col">
                                            <div className="d-flex justify-content-center">
                                                CONSULTAR
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {dataLoading ? (
                                        <tr>
                                            <td colSpan="10">
                                                <AppLoading parametros={{ tipoLoading: "progress", carregando: dataLoading }} />
                                            </td>
                                        </tr>
                                    ) : (
                                        unidades && unidades.length > 0 ? (
                                            unidades.map((unidade_value, index_lista) => (
                                                <tr key={index_lista}>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            {unidade_value.unidades_nome}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            {unidade_value.unidades_Logradouro}, {unidade_value.unidades_Numero}
                                                            {unidade_value.unidades_Bairro ? ` - ${unidade_value.unidades_Bairro}` : ''}
                                                            {unidade_value.unidades_Complemento ? `, ${unidade_value.unidades_Complemento}` : ''}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            {unidade_value.unidades_Municipio}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            {unidade_value.unidades_cap_atendimento}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            <AppDataPtBr parametros={unidade_value.unidades_data_cadastramento} />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/alocarfuncionario/endpoint/consultar/${unidade_value.id}`} role="button">
                                                                <i className="bi bi-search" />
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr>
                                                <td colSpan="10">
                                                    <div className="m-5">
                                                        Não foram encontrados Unidaes para alocação de funcionários.
                                                    </div>
                                                </td>
                                            </tr>
                                        )
                                    )}
                                </tbody>
                            </table>
                        </div>

                        <div className="m-3">
                            {/* Paginação */}
                            {(pagination == 'list') && (
                                <nav aria-label="Page navigation example">
                                    <ul className="pagination">
                                        {paginacaoLista.map((paginacao_value, index) => (
                                            <li key={index} className={`page-item ${paginacao_value.active ? 'active' : ''}`}>
                                                <button
                                                    className="page-link"
                                                    onClick={() => fetchUnidades(base_url, api_get_unidades, paginacao_value.href)}
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
                                                    onClick={() => submitAllForms('filtro-unidade', paginacao_value.href)}
                                                >
                                                    {paginacao_value.text.trim()}
                                                </button>
                                            </li>
                                        ))}
                                    </ul>
                                </nav>
                            )}
                        </div>

                    </div>

                    {/* Modais para cada unidade */}
                    {unidades.map((unidade, index) => (
                        <div key={index} className="modal fade" id={`staticBackdropUnidade${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropUnidadeLabel${index}`} aria-hidden="true">
                            {/* modal-fullscreen / modal-xl*/}
                            <div className="modal-dialog modal-xl">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5 className="modal-title" id={`staticBackdropUnidadeLabel${index}`}>Detalhes da Unidade</h5>
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

                    {/* Modais para cada unidade */}

                    <AppMessageCard parametros={message} modalId="modal_form" />

                    {showModal && (
                        <div className="modal fade show d-block" tabIndex="-1" role="dialog">
                            <div className="modal-dialog modal-dialog-centered" role="document">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5 className="modal-title">Selecione o período de cadastro das unidades a serem consultadas, utilizando os campos:</h5>
                                        <button
                                            type="button"
                                            className="btn-close"
                                            onClick={() => setShowModal(false)} // Fecha o modal
                                            aria-label="Close"
                                        ></button>
                                    </div>
                                    <div className="modal-body">
                                        <div>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <div className="mb-3">
                                                    <AppDate
                                                        submitAllForms
                                                        parametros={parametros}
                                                        formData={formData}
                                                        setFormData={setFormData}
                                                        fieldAttributes={{
                                                            attributeOrigemForm: `${origemForm}`,
                                                            labelField: 'Data de Início da Consulta',
                                                            labelColor: 'gray', // gray, red, black,
                                                            nameField: 'unidades_data_cadastramento_inicio',
                                                            attributeMax: '', // maxDate - Profissional, Periodo. 
                                                            attributeRequired: false,
                                                            attributeReadOnly: false,
                                                            attributeDisabled: false,
                                                            attributeMask: 'Filtro-ALocarFuncionário', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                        }} />
                                                </div>
                                            </form>
                                        </div>
                                        <div className="mb-3">
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
                                                        labelField: 'Data de Término da Consulta',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'unidades_data_cadastramento_fim',
                                                        attributeMax: '', // maxDate - Profissional, Periodo.
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Filtro-ALocarFuncionário', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                    }} />
                                            </form>
                                        </div>
                                    </div>
                                    <div className="modal-footer">
                                        <button
                                            className="btn btn-danger"
                                            onClick={() => setShowModal(false)} // Fecha o modal
                                        >
                                            Fechar
                                        </button>
                                        <button
                                            className="btn btn-primary"
                                            onClick={() => {
                                                setShowModal(false); // Fecha o modal
                                                submitAllForms('filtro-unidade'); // Aplica os filtros
                                            }}
                                            type="submit"
                                        >
                                            Aplicar Filtro
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                </div>
            </div>
        );
    };

    const rootElement = document.querySelector('.app_listar_unidades');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListarUnidade />);
</script>
<?php
$parametros_backend = array();
?>