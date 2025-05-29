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
        const api_post_filter_unidade = parametros.api_post_filter_unidade;
        const api_post_filter_profissional = parametros.api_post_filter_profissional;
        const api_post_filter_adolescente = parametros.api_post_filter_adolescente;

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
        const [qtdAdolescente, setQtdAdolescente] = React.useState(0);
        const [qtdFuncionario, setQtdFuncionario] = React.useState(0);

        // Variáveis Uteis
        const [enableBtnExcluir, setEnableBtnExcluir] = React.useState('');
        const [campoFiltro, setCampoFiltro] = React.useState('');
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState('list');
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);
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

            // Validação para o campo municipios_nome_municipio
            if (name === 'municipios_nome_municipio') {
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

            console.log('name handleBlur AppFormData: ', name);
            console.log('value handleBlur AppFormData: ', value);

            // Atualiza o valor do campo no estado
            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Validação para o campo municipios_nome_municipio
            if (name === 'municipios_nome_municipio') {
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
                        municipios_nome_municipio: ''
                    }));
                }
            }
        };

        // Dicionário de Títulos dos Campos
        const fieldTitles = {
            municipio_id: "ID do Município",
            idUnidades: "ID da Unidade",
            unidades_nome: "Nome da Unidade",
            unidades_endereco: "Endereço da Unidade",
            unidades_cap_atendimento: "Capacidade de Atendimento da Unidade",
            unidades_data_cadastramento_inicio: "Data de Cadastro do Inscrito",
            unidades_data_cadastramento_fim: "Data do Fim do Cadastro",
            created_at: "Criado em",
            updated_at: "Atualizado em",
            deleted_at: "Deletado em",
            municipios_nome_municipio: "Nome do Município",
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
            unidades_nome: null,
            unidades_Logradouro: null,
            unidades_Bairro: null,
            unidades_CEP: null,
            unidades_Municipio: null,
            unidades_cap_atendimento: null,
            unidades_data_cadastramento_inicio: null,
            unidades_data_cadastramento_fim: null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
            municipios_id: null,
            municipios_id_municipio: null,
            municipios_nome_municipio: null,
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

        // Função para validar os dados do formulário
        const validateFormData = () => {
            // Lista de campos que devem ser validados
            const fieldsToValidate = [
                'municipio_id',
                'idUnidades',
                'unidades_nome',
                'unidades_Logradouro',
                'unidades_Bairro',
                'unidades_CEP',
                'unidades_Municipio',
                'unidades_cap_atendimento',
                'unidades_data_cadastramento_inicio',
                'unidades_data_cadastramento_fim',
                'created_at',
                'updated_at',
                'deleted_at',
                'municipios_id',
                'municipios_id_municipio',
                'municipios_id_regiao',
                'municipios_nome_regiao',
                'municipios_id_mesoregiao',
                'municipios_nome_mesoregiao',
                'municipios_id_uf',
                'municipios_nome_uf',
                'municipio_created_at',
                'municipio_updated_at',
                'municipio_deleted_at'
            ];

            // Verifica se todos os campos estão nulos ou vazios
            const allFieldsEmpty = fieldsToValidate.every(field => formData[field] === null || formData[field] === '');

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

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        const excluirUnidade = async (id) => {
            try {
                const response = await fetch(`${base_url}index.php/fia/ptpa/unidade/api/deletar/${modalData.id}${getVar_page}`, {
                    method: 'GET'
                });

                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Excluido com sucesso'
                });
                redirectTo('index.php/fia/ptpa/unidade/endpoint/exibir');
            } catch (error) {
                console.error('Erro ao excluir unidade:', error);
            }
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

        // Modal/Variáveis
        const [modalData, setModalData] = React.useState({
            id: null,
            unidades_nome: '',
            unidades_endereco: '',
            municipios_nome_municipio: ''
        });

        // Moda/Função
        const handleModalOpen = async (unidade) => {
            setEnableBtnExcluir('disabled');
            setModalData(unidade);
            setQtdAdolescente(0);
            setQtdFuncionario(0);
            const onAdolescente = await fetchFilterAdolescente({ unidade_id: unidade.id });
            const onFuncionario = await fetchFilterFuncionarios({ unidade_id: unidade.id });
            if (onAdolescente && onFuncionario) {
                setEnableBtnExcluir('');
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

            if (!validateFormData()) {
                return false;
            }

            if (filtro === 'filtro-unidade') {
                await fetchFilterUnidade(setData, href);
                return true;
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

        // Fetch Unidades Filtro

        // POST Padrão 
        const fetchFilterUnidade = async (formData = {}, href = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_filter_unidade) => {
            const url = href ? `${custonBaseURL + custonApiPostObjeto}${href}` : `${custonBaseURL + custonApiPostObjeto}`;
            console.log('url :: ', url);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(formData),
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
                    setUnidades(data.result.dbResponse);
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

        // Fetch Unidades Listar
        const fetchUnidades = async (custonBaseURL = base_url, custonApiGetUnidades = api_get_unidades, customPage = getVar_page) => {
            console.log('fetchUnidades...');
            try {
                const response = await fetch(custonBaseURL + custonApiGetUnidades + customPage);
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    console.log('Unidades: ', data.result);
                    setUnidades(data.result.dbResponse);
                    setPagination('list');
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas unidades cadastradas'
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
                    message: 'Erro ao carregar Unidades: ' + error.message
                });
            }
        };

        // Fecth Funcionario
        const fetchFilterFuncionarios = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_filter_profissional, customPage = getVar_page) => {
            console.log('fetchFilterFuncionarios...');
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            const setData = formData;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    console.log('data (fetchFilterFuncionarios): ', data.result);
                    const qtdResult = data.result.dbResponse.length;
                    // 
                    setQtdFuncionario(qtdResult);
                    setPagination('list');
                    //
                } else {
                    return true;
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // Fetch Adolescente
        const fetchFilterAdolescente = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_filter_adolescente, customPage = getVar_page) => {
            console.log('fetchFilterAdolescente... ');
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            const setData = formData;
            console.log('enviando para API:', setData); // deve mostrar { UnidadeId: 1 } por exemplo
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                console.log('data.result.dbResponse:', data.result.dbResponse);
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const qtdResult = data.result.dbResponse.length;
                    console.log('data (fetchFilterAdolescente): ', data.result);
                    // 
                    setQtdAdolescente(qtdResult);
                    setPagination('list');
                    //
                } else {
                    return true;
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        if (debugMyPrint && isLoading) {
            return (
                <div className="d-flex justify-content-center align-items-center min-vh-100">
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Loading...</span>
                    </div>
                </div>
            );
        }

        if (debugMyPrint && error) {
            return (
                <div className="d-flex justify-content-center align-items-center min-vh-100">
                    <div className="alert alert-danger" role="alert">
                        {error}
                    </div>
                </div>
            );
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

        // RENDER FILTRO
        const renderFiltro = () => {
            return (
                <div>
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
                                            <div>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Nome',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'unidades_nome',
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
                                            </div>
                                        </form>
                                    </div>

                                    <div className="flex-grow-1" style={{ flexBasis: '100%' }}>

                                        <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                            fieldAttributes={{
                                                attributeOrigemForm: `${origemForm}`,
                                                labelField: 'Endereço',
                                                labelColor: 'gray', // gray, red, black,
                                                nameField: 'unidades_Logradouro',
                                                errorMessage: '', // Mensagem de Erro personalizada
                                                attributePlaceholder: '', // placeholder 
                                                attributeMinlength: 4, // minlength 
                                                attributeMaxlength: 14, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Processo: 41, Certidão: 38
                                                attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                                attributeAutocomplete: 'on', // on, off ]
                                                attributeRequired: false,
                                                attributeReadOnly: false,
                                                attributeDisabled: false,
                                                attributeMask: '', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                            }}
                                        />

                                    </div>

                                    <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                        <form onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`);
                                        }}>
                                            <div>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Bairro',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'unidades_Bairro',
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
                                            </div>
                                        </form>
                                    </div>

                                    {/* CEP */}
                                    <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                        <form onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`);
                                        }}>
                                            <div>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'CEP',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'unidades_CEP',
                                                        errorMessage: '', // Mensagem de Erro personalizada
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 4, // minlength 
                                                        attributeMaxlength: 10, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'CEP', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </div>
                                        </form>
                                    </div>

                                    <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                        <form onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`);
                                        }}>
                                            <div>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Município',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'unidades_Municipio',
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
                                            </div>
                                        </form>
                                    </div>

                                    <div className="flex-grow-1" style={{ flexBasis: '100%' }}>
                                        <form onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`);
                                        }}>
                                            <div className="col" style={formGroupStyle}>
                                                <AppCapAtendFiltrar formData={formData} setFormData={setFormData} parametros={parametros} />
                                            </div>
                                        </form>
                                    </div>
                                    <div className="flex-grow" style={{ flexBasis: '100%' }}>
                                        <form onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`);
                                        }}>
                                            <div className="d-flex" style={formGroupStyle}>
                                                <AppDate
                                                    parametros={parametros}
                                                    formData={formData}
                                                    setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Período de Consulta',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'unidades_data_cadastramento_inicio',
                                                        attributeMax: '', // maxDate - Profissional, Periodo.
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Filtro-Unidades', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                    }} />
                                                <AppDate
                                                    submitAllForms
                                                    parametros={parametros}
                                                    formData={formData}
                                                    setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Período de Consulta',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'unidades_data_cadastramento_fim',
                                                        attributeMax: '', // maxDate - Profissional, Periodo.
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Filtro-Unidades', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                    }} />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            )
        }

        // RENDERE ENDEREÇO
        const renderEndereco = (unidade_value) => {
            // Cria um array com os componentes do endereço
            const componentesEndereco = [
                unidade_value.unidades_Logradouro,
                unidade_value.unidades_Numero,
                unidade_value.unidades_Complemento,
                unidade_value.unidades_Bairro,
                unidade_value.unidades_Municipio,
                unidade_value.unidades_UF,
                unidade_value.unidades_CEP
            ];

            // Filtra os componentes vazios
            const componentesPreenchidos = componentesEndereco.filter(componente =>
                componente !== undefined &&
                componente !== null &&
                componente !== '' &&
                componente.trim() !== ''
            );

            // Junta os componentes preenchidos com vírgula e espaço
            return componentesPreenchidos.join(', ');
        }

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
                            <a className="btn btn-primary btn" href={`${base_url}index.php/fia/ptpa/unidade/endpoint/cadastrar/`} role="button">
                                <i className="bi bi-plus"></i>&nbsp;Cadastrar Unidade</a>
                        </div>
                    </div>
                    {/* RENDER FILTRO */}
                    {renderFiltro()}

                </div>

                <div>
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
                                            EDITAR
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            CONSULTAR
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
                                                        {/* RENDER ENDEREÇO */}
                                                        {renderEndereco(unidade_value)}
                                                        {/* {unidade_value.unidades_endereco} */}
                                                        {/* {unidade_value.unidades_numero} */}
                                                        {/* {unidade_value.unidades_complemento} */}
                                                        {/* {unidade_value.unidades_bairro} */}
                                                        {/* {unidade_value.municipios_nome_municipio} */}
                                                        {/* {unidade_value.unidades_uf} */}
                                                        {/* {unidade_value.unidades_cep} */}
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
                                                        <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/unidade/endpoint/atualizar/${unidade_value.id}`} role="button">
                                                            <i className="bi bi-pencil-square" />
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div className="d-flex justify-content-center">
                                                        <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/unidade/endpoint/consultar/${unidade_value.id}`} role="button">
                                                            <i className="bi bi-search" />
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div className="d-flex justify-content-center">
                                                        {/* Button trigger modal 
                                                        <button type="button" className="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target={`#exampleModal${index_lista}`}>
                                                            <i className="bi bi-trash"></i>
                                                        </button>
                                                        {/* Button trigger modal */}
                                                        <button
                                                            type="button"
                                                            className="btn btn-outline-danger btn-sm"
                                                            onClick={() => handleModalOpen(unidade_value)}
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
                                                    Não foram encontraddas unidades cadastradas
                                                </div>
                                            </td>
                                        </tr>
                                    )
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Modal único */}
                    <div className="modal fade" id="exclusaoModal" tabIndex="-1" aria-hidden="true">
                        <div className="modal-dialog">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title">Confirmar Exclusão:</h5>
                                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div className="modal-body">
                                    <div className="d-flex justify-content-center">
                                        <b>Nome:&nbsp;</b>{modalData.unidades_nome}<br />
                                    </div>
                                    <div className="d-flex justify-content-center">
                                        <b>Endereço:&nbsp;</b>{modalData.unidades_endereco}<br />
                                    </div>
                                    <div className="d-flex justify-content-center">
                                        <b>Município:&nbsp;</b>{modalData.municipios_nome_municipio}
                                    </div>
                                    {(enableBtnExcluir && qtdAdolescente === 0 && qtdFuncionario === 0) ? (
                                        <div>
                                            <hr />
                                            {typeof AppLoading !== "undefined" ? (
                                                <div>
                                                    <AppLoading
                                                        parametros={{
                                                            tipoLoading: 'progress',
                                                            carregando: true
                                                        }}
                                                    />
                                                </div>
                                            ) : (
                                                <div>
                                                    <p className="text-danger">AppLoading não lançado.</p>
                                                </div>
                                            )}
                                        </div>
                                    ) : (
                                        <>
                                            {(qtdFuncionario > 0 || qtdAdolescente > 0) && <hr />}
                                            {qtdFuncionario > 0 && (
                                                <div>
                                                    Quantidade de Funcionários: {qtdFuncionario}
                                                </div>
                                            )}
                                            {qtdAdolescente > 0 && (
                                                <div>
                                                    Quantidade de Adolescentes: {qtdAdolescente}
                                                </div>
                                            )}
                                        </>
                                    )}
                                </div>
                                <div className="modal-footer">
                                    <button
                                        className={`btn btn-outline-danger ${enableBtnExcluir}`}
                                        aria-disabled={enableBtnExcluir === 'disabled'}
                                        onClick={() => excluirUnidade(modalData.id)}
                                    >
                                        Excluir
                                    </button>
                                    <button type="button" className="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="m-3">
                        {/* Paginação Lista*/}
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

                        {/* Paginação Filtro*/}
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

                {typeof AppJson !== "undefined" ? (
                    <div>

                        <AppJson
                            parametros={parametros}
                            dbResponse={unidades}
                        />

                    </div>
                ) : (
                    <div>
                        <p className="text-danger">AppJson não lacançado.</p>
                    </div>
                )}
                {/* Modais para cada unidade */}

                <AppMessageCard
                    parametros={message}
                    modalId="modal_form_uninidade"
                />

            </div>
        );
    };
</script>