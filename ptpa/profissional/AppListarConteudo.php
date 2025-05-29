<script type="text/babel">
    const AppListarConteudo = ({
        parametros = {}
    }) => {

        // console.log('parametros: ', parametros);
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
        const origemForm = parametros.origemForm || '';

        // Base Lista Profissional
        const api_post_filter_profissional = parametros.api_post_filter_profissional || '';
        const api_get_profissionais = parametros.api_get_profissionais || '';
        const getVar_page = parametros.getVar_page || '';
        // const base_paginator = base_url + parametros.base_paginator;
        // const page = parametros.page;

        // Variáveis da API
        const [profissionais, setProfissionais] = React.useState([]);

        // Busca a palavra em um Array
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [campoFiltro, setCampoFiltro] = React.useState('');
        const [pagination, setPagination] = React.useState('list');
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);
        const [exibirFuncionarioComponente, setFuncionarioComponente] = React.useState(checkWordInArray(getURI, 'profissional') && checkWordInArray(getURI, 'exibir') ? true : false);
        const [labelCargoFuncao, setLabelCargoFuncao] = React.useState('Cargo/Função');
        const [labelProgramaFia, setLabelProgramaFia] = React.useState('Programa FIA');

        // Largura
        const [width, setWidth] = React.useState(window.innerWidth);

        // Definindo mensagens do Sistema
        // const [tabNav, setTabNav] = React.useState('form');
        // const [showAlert, setShowAlert] = React.useState(false);
        // const [alertType, setAlertType] = React.useState('');
        // const [alertMessage, setAlertMessage] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Dicionário de Títulos dos Campos
        const fieldTitles = {
            Nome: "Nome",
            CPF: "CPF",
            Email: "Email",
            TelefoneRecado: "Telefone",
            CargoFuncao: "Cargo/Função",
            ProgramaSigla: "Programa FIA",
            PerfilDescricao: "Perfil",
            DataAdmissao: "Data Admissão",
            DataDemissao: "Data Demissão",
            created_at: "Criado em",
            updated_at: "Atualizado em",
            deleted_at: "Deletado em",
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            //
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            PeriodoId: null,
            CadastroId: null,
            CargoFuncao: null,
            PerfilId: null,
            PerfilDescricao: null,
            ProgramasId: null,
            ProgramaSigla: null,
            AcessoCadastroID: null,
            UnidadeId: null,
            Unidade: null,
            NomeUnidade: null,
            AcessoId: null,
            AcessoDescricao: null,
            ProntuarioId: null,
            Nome: null,
            CPF: null,
            TelefoneFixo: null,
            TelefoneMovel: null,
            TelefoneRecado: null,
            Email: null,
            DataAdmissao: null,
            DataDemissao: null,
            DataCadastramento: null,
            DataTermUnid: null,
            DataInicioUnid: null,
            CodProfissao: null,
            AcessoCreatedAt: null,
            AcessoUpdatedAt: null,
            ProfissaoCodigo: null,
            ProfissaoDescricao: null,
            ProfissaoFavorito: null,
            ProfissaoCreatedAt: null,
            ProfissaoUpdatedAt: null,
            ProfissaoDeletedAt: null
        });

        const validateFormData = () => {
            // Lista de campos que devem ser validados
            const fieldsToValidate = [
                'id',
                'PeriodoId',
                'CadastroId',
                'CargoFuncao',
                'PerfilId',
                'PerfilDescricao',
                'ProgramasId',
                'ProgramaSigla',
                'AcessoCadastroID',
                'UnidadeId',
                'Unidade',
                'NomeUnidade',
                'AcessoId',
                'AcessoDescricao',
                'ProntuarioId',
                'Nome',
                'CPF',
                'TelefoneFixo',
                'TelefoneMovel',
                'TelefoneRecado',
                'Email',
                'DataCadastramento',
                'DataTermUnid',
                'DataInicioUnid',
                'CodProfissao',
                'AcessoCreatedAt',
                'AcessoUpdatedAt',
                'ProfissaoCodigo',
                'ProfissaoDescricao',
                'ProfissaoFavorito',
                'ProfissaoCreatedAt',
                'ProfissaoUpdatedAt',
                'ProfissaoDeletedAt',
                'DataAdmissao',
                'DataDemissao'
            ];

            // Verifica se todos os campos estão nulos ou vazios
            const allFieldsEmpty = fieldsToValidate.every(field => formData[field] === null || formData[field] === '');

            if (allFieldsEmpty) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: "Preencha ao menos um dos campos com um valor válido para que o filtro seja aplicado."
                });
                return false; // Retorna falso indicando que a validação falhou
            }

            return true; // Retorna verdadeiro indicando que pelo menos um campo tem valor
        };

        // lista contendo as chaves que representam campos de data
        const dateFields = [
            'DataAdmissao',
            'DataDemissao'
        ];

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        const formatDateToPTBR = (dateString) => {
            if (!dateString) return '';
            const parts = dateString.split('-');
            if (parts.length !== 3) return dateString;
            const [year, month, day] = parts;
            return `${day}-${month}-${year}`;
        };

        const excluirProfissional = async (id) => {
            try {
                const response = await fetch(`${base_url}index.php/fia/ptpa/profissional/api/deletar/${id}${getVar_page}`, {
                    method: 'GET'
                });

                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Excluido com sucesso'
                });
                redirectTo('index.php/fia/ptpa/profissional/endpoint/exibir');
            } catch (error) {
                console.error('Erro ao excluir profissional:', error);
            }
        };


        const handleChange = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
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

            setCampoFiltro("Não foram encontradas opções para o filtro aplicado: " + filledFields);
        };

        // Função handleBlur para capturar o valor de cada campo e atualizar formData
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
            const dataInicio = formData.DataAdmissao;
            const dataFim = formData.DataDemissao;

            // Validações para data de início e fim
            if (name === 'DataAdmissao' && value) {
                // console.log(DataAdmissao);
                const [year] = value.split('-').map(Number);
                if (year < 1999) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "Você está pesquisando uma data anterior ao Decreto Estadual nº 25.162, de 01/01/1999"
                    });
                    setFormData((prev) => ({
                        ...prev,
                        DataAdmissao: ''
                    }));
                    return;
                }
                if (value > currentDate) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "A data de Admissão não pode ser superior à data de hoje"
                    });
                    setFormData((prev) => ({
                        ...prev,
                        DataAdmissao: ''
                    }));
                    return;
                }
            }
            if (name === 'DataDemissao' && value) {
                // console.log(DataDemissao);
                if (value > currentDate) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: "A data de Demissão não pode ser superior à data de hoje"
                    });
                    setFormData((prev) => ({
                        ...prev,
                        DataDemissao: ''
                    }));
                    return;
                }
            }

            // Validação de data inválida
            if (
                !isValidLeapYearDate(value) &&
                (
                    name === 'DataAdmissao'
                )) {
                console.log('name handleBlur: ', name);
                console.log('value handleBlur: ', value);
                setMessage({
                    show: true,
                    type: 'light',
                    message: "A data de Admissão deve ser válida"
                });

                setFormData((prev) => ({
                    ...prev,
                    DataAdmissao: null,
                }));
                return;
            }

            // Validação de data inválida
            if (
                !isValidLeapYearDate(value) &&
                (
                    name === 'DataDemissao'
                )) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: "A data de Demissão deve ser válida"
                });
                setFormData((prev) => ({
                    ...prev,
                    DataDemissao: ''
                }));
                return;
            }

            // Verifica se a data de início é maior que a data de fim
            if (dataInicio && dataFim && new Date(dataInicio) > new Date(dataFim)) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: "O campo de Admissão não deve ser maior ou igual à data de Demissão"
                });
                setFormData((prev) => ({
                    ...prev,
                    [name]: ''
                }));
                return;
            }
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

            console.log("setData :: ", setData);

            updateCampoFiltro(setData);

            const unidadeId = getURI.find((item) => !isNaN(item) && item !== '') || null;

            if (checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'consultar')) {
                if (!unidadeId) {
                    return;
                }
                setData.UnidadeId = unidadeId; // Força o filtro para o ID da unidade
            }

            if (!validateFormData()) {
                return;
            }

            const url = href ? `${base_url + api_post_filter_profissional}${href}` : `${base_url + api_post_filter_profissional}`;
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

                console.log("data.result && data.result.dbResponse && data.result.dbResponse.length > 0 :: ", data.result);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setProfissionais(data.result.dbResponse);
                    setPagination('filter');
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: campoFiltro
                    });
                }


                console.log("data.result && data.result.linksArray :: ", data.result);
                if (data.result && data.result.linksArray) {
                    setPaginacaoLista(data.result.linksArray);
                    setIsLoading(false);
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
                    await fetchProfissionais();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        React.useEffect(() => {
            if (width < 1500) {
                setLabelCargoFuncao('Carg/Funç');
                setLabelProgramaFia('Prog/FIA');
            } else {
                setLabelCargoFuncao('Carg/Funç');
                setLabelProgramaFia('Prog/FIA');
            }
        }, [width]);

        // Fetch para obter os Profissionais
        const fetchProfissionais = async (custonBaseURL = base_url, custonApiGetProfissionais = api_get_profissionais, customPage = getVar_page) => {
            console.log('fetchProfissionais...');
            console.log('src/app/Views/fia/ptpa/profissional/AppListarConteudo.php');
            const url = `${custonBaseURL}${custonApiGetProfissionais}${customPage}`;
            // console.log('url (src/app/Views/fia/ptpa/profissional/AppListarConteudo.php): ', url);
            // console.log('custonBaseURL: ', custonBaseURL);
            // console.log('custonApiGetProfissionais: ', custonApiGetProfissionais);
            // console.log('customPage: ', customPage);
            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setProfissionais(data.result.dbResponse);
                    setPagination('list');
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontrados funcionários cadastrados'
                    });
                    setIsLoading(false);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);
                    setIsLoading(false);
                }

            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Profissionais: ' + error.message
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
                {debugMyPrint ? (
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                            Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ) : null}


                {exibirFuncionarioComponente && (
                    <div className="d-flex justify-content-start">
                        <div className="ms-4" style={verticalBarStyle}></div>
                        <h2 className="myBold mt-3">{title}</h2>
                    </div>
                )}

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

                        {exibirFuncionarioComponente && (
                            <div className="ms-2">
                                <a className="btn btn-primary" href={`${base_url}index.php/fia/ptpa/profissional/endpoint/cadastrar`} role="button">
                                    <i className="bi bi-plus"></i>&nbsp;Cadastrar Funcionários
                                </a>
                            </div>
                        )}

                    </div>

                    {/* toggle filtros */}
                    <nav className="navbar navbar-expand-lg">
                        <div className="container-fluid p-0 ">
                            <button
                                className="navbar-toggler"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarTogglerFiltroProfissional"
                                aria-controls="navbarTogglerFiltroProfissional"
                                aria-expanded="false"
                                aria-label="Toggle navigation" >
                                <i className="bi bi-filter" />
                            </button>

                            <div className="collapse navbar-collapse" id="navbarTogglerFiltroProfissional">
                                <div className="navbar-nav me-auto mb-2 mb-lg-0 w-100">

                                    <div className="d-flex flex-column flex-lg-row justify-content-between w-100" style={{ gap: '0.3rem' }}>

                                        <div style={{ flex: '0 1 12%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Nome',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'Nome',
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 2, // minlength 
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
                                        </div>

                                        <div style={{ flex: '0 1 12%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'CPF',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'CPF',
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 2, // minlength 
                                                        attributeMaxlength: 15, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'CPF', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </form>
                                        </div>

                                        <div style={{ flex: '0 1 12%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'E-mail',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'Email',
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 2, // minlength 
                                                        attributeMaxlength: 100, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Senha', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </form>
                                        </div>

                                        <div style={{ flex: '0 1 12%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Telefone',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'TelefoneRecado',
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 2, // minlength 
                                                        attributeMaxlength: 100, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Telefone', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </form>
                                        </div>

                                        <div style={{ flex: '0 1 12%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: `${labelCargoFuncao}`,
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'CargoFuncao',
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 2, // minlength 
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
                                        </div>
                                        <div style={{ flex: '0 1 12%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: `${labelProgramaFia}`,
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'ProgramaSigla',
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 2, // minlength 
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
                                        </div>

                                        <div style={{ flex: '0 1 12%' }} className="col-12 col-lg-2">
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
                                                        labelField: 'Data Admissão',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'DataAdmissao',
                                                        attributeMax: '', // maxDate - Profissional, Periodo. 
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Filtro-Profissional', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                    }} />
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 12%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppDate
                                                    parametros={parametros}
                                                    formData={formData}
                                                    setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Data Demissão',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'DataDemissao',
                                                        attributeMax: '', // maxDate - Profissional, Periodo.
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Filtro-Profissional', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                    }} />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                    {/* Divisor */}
                    <hr style={{ borderColor: 'gray', borderWidth: '1px' }} />
                </div>

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
                                        E-MAIL
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        TELEFONE
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        CARGO/FUNÇÃO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        PROGRAMA/FIA
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        PERFIL
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        ADMISSÃO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        DEMISSÃO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        EDITAR
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        CONSULTAR
                                    </div>
                                </th>
                                {(checkWordInArray(getURI, 'alocarfuncionario') || checkWordInArray(getURI, 'consultar')) ? null : (
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            EXCLUIR
                                        </div>
                                    </th>
                                )}
                            </tr>
                        </thead>
                        <tbody>
                            {isLoading ? (
                                <tr>
                                    <td colSpan="11">
                                        <div className="m-5">
                                            <AppLoading parametros={{ tipoLoading: "progress" }} />
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                profissionais && profissionais.length > 0 ? (
                                    profissionais.map((profissional_value, index_lista) => (
                                        <tr key={index_lista}>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {profissional_value.Nome}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {profissional_value.Email}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {profissional_value.TelefoneRecado}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {profissional_value.CargoFuncao}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {profissional_value.ProgramaSigla}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {profissional_value.PerfilDescricao}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <AppDataPtBr parametros={profissional_value.DataAdmissao} />
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <AppDataPtBr parametros={profissional_value.DataDemissao} />
                                                </div>
                                            </td>
                                            {checkWordInArray(getURI, 'alocarfuncionario') ? (
                                                <>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/alocarfuncionario/endpoint/atualizar/${profissional_value.id}`} role="button">
                                                                <i className="bi bi-pencil-square" />
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/alocarfuncionario/endpoint/consultarfunc/${profissional_value.id}`} role="button">
                                                                <i className="bi bi-search" />
                                                            </a>
                                                        </div>
                                                    </td>
                                                </>
                                            ) : (
                                                <>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/profissional/endpoint/atualizar/${profissional_value.id}`} role="button">
                                                                <i className="bi bi-pencil-square" />
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/profissional/endpoint/consultar/${profissional_value.id}`} role="button">
                                                                <i className="bi bi-search" />
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            <button type="button" className="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target={`#exampleModal${index_lista}`}>
                                                                <i className="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </>
                                            )}
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="10">
                                            <div className="m-5">
                                                Nenhum funcionário encontrado.
                                            </div>
                                        </td>
                                    </tr>
                                )
                            )}
                        </tbody>
                    </table>
                </div>

                {profissionais.map((profissionais_value, index_lista) => (
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
                                            <b>Nome:&nbsp;</b>{profissionais_value.Nome}<br />
                                        </div>
                                        <hr />
                                        <div className="d-flex justify-content-center">
                                            <b>Cargo/Função:&nbsp;</b>{profissionais_value.CargoFuncao}
                                        </div>
                                        <hr />
                                        <div className="d-flex justify-content-center">
                                            <b>Perfil:&nbsp;</b>{profissionais_value.PerfilDescricao}
                                        </div>
                                    </div>
                                    <div className="modal-footer">
                                        <button
                                            className="btn btn-outline-danger"
                                            onClick={() => excluirProfissional(profissionais_value.id)}
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
                                            onClick={() => fetchProfissionais(base_url, api_get_profissionais, paginacao_value.href)}
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
                                            onClick={() => submitAllForms('filtro-profissional', paginacao_value.href)}
                                        >
                                            {paginacao_value.text.trim()}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </nav>
                    )}
                </div>

                {/* Modais para cada profissional */}
                {profissionais.map((profissional, index) => (
                    <div key={index} className="modal fade" id={`staticBackdropProfissional${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropProfissionalLabel${index}`} aria-hidden="true">
                        {/* modal-fullscreen / modal-xl*/}
                        <div className="modal-dialog modal-xl">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title" id={`staticBackdropProfissionalLabel${index}`}>Detalhes do Profissional</h5>
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

                {/* Modais para cada profissional */}
                <AppMessageCard
                    parametros={message}
                    modalId="modal_form"
                />

            </div>
        );
    };
</script>