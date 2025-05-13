<script type="text/babel">
    const AppListar_conteudo = ({
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
        const origemForm = parametros.origemForm || '';
        const api_post_filter_adolescente = parametros.api_post_filter_adolescente || '';

        // Base Lista Adolescentes
        const api_get_adolescentes = parametros.api_get_adolescentes;
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        // Variáveis da API
        const [adolescentes, setAdolescentes] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);

        // Largura
        const [width, setWidth] = React.useState(window.innerWidth);
        const [labelFieldTermino, setLabelFieldTermino] = React.useState('Término da Consulta');

        // Definindo mensagens do Sistema
        const [tabNav, setTabNav] = React.useState('form');
        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
        const [campoFiltro, setCampoFiltro] = React.useState('');
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

            // Verifica se a mudança de campo
            if (name === 'variavel_001') {
                console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
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

            // Verifica se a mudança é no campo 'variavel_001'
            if (name === 'variavel_001') {
                console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
        }

        const fieldTitles = {
            adolescente_data_cadastramento_inicio: "Data de Cadastro do Inscrito",
            adolescente_data_cadastramento_fim: "Data do Fim do Cadastro",
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            filterResponsavel: null,
            //
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            Nome: null,
            CPF: null,
            RG: null,
            ExpedidorRG: null,
            ExpedicaoRG: null,
            CEP: null,
            Endereco: null,
            Numero: null,
            Municipio: null,
            Nascimento: null,
            PeriodoId: null,
            CadastroId: null,
            PerfilId: null,
            PerfilDescricao: null,
            SexoId: null,
            SexoBiologico: null,
            GeneroIdentidadeId: null,
            Genero: null,
            GeneroIdentidadeDescricao: null,
            AcessoCadastroID: null,
            UnidadeId: null,
            Unidade: null,
            NomeUnidade: null,
            AcessoId: null,
            AcessoDescricao: null,
            ProntuarioId: null,
            NomeMae: null,
            Complemento: null,
            Bairro: null,
            UF: null,
            TelefoneFixo: null,
            TelefoneMovel: null,
            TelefoneRecado: null,
            Email: null,
            NMatricula: null,
            Certidao: null,
            Etnia: null,
            Escolaridade: null,
            NumRegistro: null,
            Folha: null,
            Livro: null,
            Circunscricao: null,
            Zona: null,
            UFRegistro: null,
            TipoEscola: null,
            turno_escolar: null,
            NomeEscola: null,
            DataCadastramento: null,
            DataTermUnid: null,
            DataInicioUnid: null,
            DataAdmissao: null,
            DataDemissao: null,
            CodProfissao: null,
            ResponsavelID: null,
            Responsavel_Nome: null,
            Responsavel_Email: null,
            Responsavel_TelefoneFixo: null,
            Responsavel_TelefoneMovel: null,
            Responsavel_TelefoneRecado: null,
            Responsavel_Endereco: null,
            Responsavel_CPF: null,
            ProfissaoCodigo: null,
            ProfissaoDescricao: null,
            ProfissaoFavorito: null,
            adolescente_data_cadastramento_inicio: null,
            adolescente_data_cadastramento_fim: null,
        });

        const validateFormData = () => {
            const fieldsToValidate = [
                'Nome',
                'CPF',
                'Certidao',
                'Responsavel_CPF',
                'adolescente_data_cadastramento_inicio',
                'adolescente_data_cadastramento_fim'
            ];

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


        const submitAllForms = async (filtro) => {
            const setData = formData;

            // Validação
            if (!validateFormData()) {
                return false;
            }

            if (filtro === 'filtro-adolescente') {
                fetchPostAdolescentes();
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
                    await fetchGetAdolescentes();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // useEffect para atualizar o campoFiltro quando formData mudar
        React.useEffect(() => {
            updateCampoFiltro(formData);
        }, [formData]);

        // useEffect para atualizar o estado da largura da tela
        React.useEffect(() => {
            const handleResize = () => {
                setWidth(window.innerWidth);
            };

            window.addEventListener('resize', handleResize);

            return () => {
                window.removeEventListener('resize', handleResize);
            };
        }, []);

        React.useEffect(() => {
            if (width < 1500) {
                setLabelFieldTermino('Término');
            } else {
                setLabelFieldTermino('Término da Consulta');
            }
        }, [width]);

        // lista contendo as chaves que representam campos de data
        const dateFields = [
            'adolescente_data_cadastramento_inicio',
            'adolescente_data_cadastramento_fim',
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

        // Fetch para GET
        const fetchGetAdolescentes = async (custonBaseURL = base_url, custonApiGetObjeto = api_get_adolescentes, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiGetObjeto + customPage + '&limit=10';
            try {
                const response = await fetch(url);
                const data = await response.json();
                console.log('data(fetchAdolescentes) :: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    setAdolescentes(dbResponse);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontrados objeto cadastrados'
                    });
                    setIsLoading(false);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    console.log('data.result.linksArray ::', data.result.linksArray);
                    setPaginacaoLista(data.result.linksArray);
                    setPagination('list');
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Adolescentes: ' + error.message
                });
            }
        };

        // Fetch para POST
        const fetchPostAdolescentes = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_filter_adolescente, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage + '&limit=90000';
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
                console.log('data(fetchPostAdolescentes) :: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    console.log('data(fetchPostAdolescentes) :: ', data.result.dbResponse);
                    const dbResponse = data.result.dbResponse;
                    setAdolescentes(dbResponse);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontrados cadastros de Adolescentes'
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
                                submitAllForms(`filtro-adolescente`);
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
                                className="navbar-toggler"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarTogglerFiltroAdolescente"
                                aria-controls="navbarTogglerFiltroAdolescente"
                                aria-expanded="false"
                                aria-label="Toggle navigation"
                            >
                                <i className="bi bi-filter" />
                            </button>
                            <div className="collapse navbar-collapse" id="navbarTogglerFiltroAdolescente">
                                <div className="navbar-nav me-auto mb-2 mb-lg-0 w-100">

                                    <div className="d-flex flex-column flex-lg-row justify-content-between w-100" style={{ gap: '0.3rem' }}>

                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
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
                                                        errorMessage: '', // Mensagem de Erro personalizada
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 4, // minlength 
                                                        attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: true,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </form>
                                        </div>

                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
                                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                fieldAttributes={{
                                                    attributeOrigemForm: `${origemForm}`,
                                                    labelField: 'CPF',
                                                    labelColor: 'gray', // gray, red, black
                                                    nameField: 'CPF',
                                                    errorMessage: '', // Mensagem de Erro personalizada
                                                    attributePlaceholder: '', // placeholder 
                                                    attributeMinlength: 3, // minlength 
                                                    attributeMaxlength: 15, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                    attributePattern: 'Senha', // Inteiro, Caracter, Senha
                                                    attributeAutocomplete: 'on', // on, off ]
                                                    attributeRequired: false,
                                                    attributeReadOnly: false,
                                                    attributeDisabled: false,
                                                    attributeMask: 'CPF', // CPF, Telefone, CEP, , SEI, Processo.
                                                }}
                                            />
                                        </div>
                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'Certidão',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'Certidao',
                                                        errorMessage: '', // Mensagem de Erro personalizada
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 4, // minlength 
                                                        attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: true,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
                                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                fieldAttributes={{
                                                    attributeOrigemForm: `${origemForm}`,
                                                    labelField: 'Responsável CPF',
                                                    labelColor: 'gray', // gray, red, black
                                                    nameField: 'Responsavel_CPF',
                                                    errorMessage: '', // Mensagem de Erro personalizada
                                                    attributePlaceholder: '', // placeholder 
                                                    attributeMinlength: 3, // minlength 
                                                    attributeMaxlength: 14, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                    attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                    attributeAutocomplete: 'on', // on, off ]
                                                    attributeRequired: false,
                                                    attributeReadOnly: false,
                                                    attributeDisabled: false,
                                                    attributeMask: 'CPF', // CPF, Telefone, CEP, , SEI, Processo.
                                                }}
                                            />
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
                                                        labelField: 'Início da Consulta',
                                                        nameField: 'adolescente_data_cadastramento_inicio',
                                                        attributeMax: '', // maxDate - Profissional, Periodo. 
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'filtro-adolescente',
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
                                                        labelField: `${labelFieldTermino}`,
                                                        nameField: 'adolescente_data_cadastramento_fim',
                                                        attributeMax: '', // maxDate - Profissional, Periodo.
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'filtro-adolescente',
                                                    }} />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div >
                    </nav>
                    {/* toggle filtros */}
                </div>

                <div className="table-responsive ms-2 ps-2 pe-2">
                    <table className="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        NOME
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        CPF
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        DATA NASCIMENTO
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        TELEFONE RESPONSÁVEL
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        UNIDADE DESEJADA
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        CPF RESPONSÁVEL
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
                            </tr>
                        </thead>
                        <tbody>
                            {isLoading ? (
                                <tr>
                                    <td colSpan="8">
                                        <AppLoading parametros={{ tipoLoading: "progress" }} />
                                        <div className="d-flex justify-content-center align-items-center min-vh-100">
                                            &nbsp;
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                adolescentes && adolescentes.length > 0 ? (
                                    adolescentes.map((adolescente_value, index_lista_ado) => (
                                        <tr key={index_lista_ado}>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {adolescente_value.Nome}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {adolescente_value.CPF}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <AppDataPtBr parametros={adolescente_value.Nascimento} />
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {adolescente_value.Responsavel_TelefoneMovel}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {adolescente_value.NomeUnidade}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {adolescente_value.Responsavel_CPF}
                                                </div>
                                            </td>
                                            <td>
                                                {/* Botão para acionar o modal */}
                                                <div className="d-flex justify-content-center">
                                                    <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/adolescente/endpoint/atualizar/${adolescente_value.id}`} role="button">
                                                        <i className="bi bi-pencil-square" />
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                {/* Botão para acionar o modal */}
                                                <div className="d-flex justify-content-center">
                                                    <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/adolescente/endpoint/consultar/${adolescente_value.id}`} role="button">
                                                        <i className="bi bi-search" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="8">
                                            <div className="d-flex justify-content-center align-items-center min-vh-100">
                                                Não foram encontrados adolescentes cadastrados.
                                            </div>
                                        </td>
                                    </tr>
                                )
                            )
                            }
                        </tbody>
                    </table>

                    {/* Paginação */}
                    {(pagination == 'list') && (
                        <nav aria-label="Page navigation example">
                            <ul className="pagination">
                                {paginacaoLista.map((paginacao_value, index) => (
                                    <li key={index} className={`page-item ${paginacao_value.active ? 'active' : ''}`}>
                                        <button
                                            className="page-link"
                                            onClick={() => fetchGetAdolescentes(base_url, api_get_adolescentes, paginacao_value.href)}
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
                                            onClick={() => submitAllForms('filtro-adolescente', paginacao_value.href)}
                                        >
                                            {paginacao_value.text.trim()}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </nav>
                    )}

                </div>

                {/* Modais para cada adolescente */}
                {adolescentes.map((adolescente, index) => (
                    <div key={index} className="modal fade" id={`staticBackdropAdolescente${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropAdolescenteLabel${index}`} aria-hidden="true">
                        {/* modal-fullscreen / modal-xl*/}
                        <div className="modal-dialog modal-xl">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title" id={`staticBackdropAdolescenteLabel${index}`}>Detalhes do Adolescente</h5>
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
                {/* Modais para cada adolescente */}

                <AppMessageCard
                    parametros={message}
                    modalId="modal_form_adolescente"
                />
            </div>
        );
    };

</script>