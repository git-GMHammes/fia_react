<script type="text/babel">
    const AppListar_conteudo = ({ parametros = {} }) => {
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const title = parametros.title || '';
        const getURI = parametros.getURI || [];
        const token_csrf = parametros.token_csrf;
        const base_url = parametros.base_url || '';
        const origemForm = parametros.origemForm || ''
        const server_name = parametros.server_name || '';
        const server_port = parametros.server_port || '';
        const request_scheme = parametros.request_scheme || '';
        const user_session = parametros.user_session.FIA || {};
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;

        // Base Lista
        const base_paginator = base_url;
        const getVar_page = parametros.getVar_page || '';
        const page = parametros.page;
        const api_get_prontuariopsicosocial = parametros.api_get_prontuariopsicosocial || '';
        const api_get_adolescente = parametros.api_get_adolescente || '';
        const api_post_filter_prontuario = parametros.api_post_filter_prontuario || '';
        const api_post_filter_adolescente = parametros.api_post_filter_adolescente || '';

        // Busca a palavra em um Array
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Variáveis da API
        const [prontuario, setProntuario] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [campoFiltro, setCampoFiltro] = React.useState('');
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        const fieldTitles = {
            prontuario_data_inicio: "Data de Cadastro do Inscrito",
            prontuario_data_fim: "Data do Fim do Cadastro",
            adolescente_Nome: "Nome",
            created_at: "Criado em",
            deleted_at: "Deletado em",
            updated_at: "Atualizado em",
            adolescente_CPF: "CPF"
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            // 
            id: null,
            prontuario_id: null,
            prontuario_profissional_id: null,
            prontuario_adolescente_id: null,
            prontuario_MedidasSocioEducativas: null,
            prontuario_MedidasSocioEducativas_pts: null,
            prontuario_MedidasSocioEducativasDesc: null,
            prontuario_UsodeDrogas: null,
            prontuario_UsoDrogas_pts: null,
            prontuario_UsoDrogasDesc: null,
            prontuario_CadUnico: null,
            prontuario_CadUnico_pts: null,
            prontuario_CadUnicoDesc: null,
            prontuario_EncaminhamentoOrgao: null,
            prontuario_EncaminhamentoOrgao_pts: null,
            prontuario_EncaminhamentoOrgaoDesc: null,
            prontuario_Deficiencia: null,
            prontuario_Deficiencia_pts: null,
            prontuario_DeficienciaDesc: null,
            prontuario_NecesMediador: null,
            prontuario_NecesMediador_pts: null,
            prontuario_NecesMediadorDesc: null,
            prontuario_DataCadPsicoSocial: null,
            prontuario_PontuacaoVulnerabilidade: null,
            prontuario_PontuacaoTotal: null,
            created_at: null,
            deleted_at: null,
            updated_at: null,
            // Profissional
            profissional_id: null,
            profissional_assinatura: null,
            profissional_unidade_id: null,
            profissional_sexo_biologico_id: null,
            profissional_sexo_biologico: null,
            profissional_genero_identidade_id: null,
            profissional_genero_identidade: null,
            profissional_genero_identidade_descricao: null,
            profissional_acesso_id: null,
            profissional_cadastro_id: null,
            profissional_municipio_id: null,
            profissional_perfil_id: null,
            profissional_cargo_funcao_id: null,
            profissional_profissao_id: null,
            profissional_prontuario_id: null,
            profissional_periodo_unidade_id: null,
            profissional_programas_id: null,
            profissional_turno_escolar: null,
            profissional_DataCadastramento: null,
            profissional_Nome: null,
            profissional_CPF: null,
            profissional_RG: null,
            profissional_ExpedidorRG: null,
            profissional_ExpedicaoRG: null,
            profissional_NomeMae: null,
            profissional_Etnia: null,
            profissional_TelefoneFixo: null,
            profissional_TelefoneMovel: null,
            profissional_TelefoneRecado: null,
            profissional_Email: null,
            profissional_Nascimento: null,
            profissional_CEP: null,
            profissional_Logradouro: null,
            profissional_Numero: null,
            profissional_Complemento: null,
            profissional_Bairro: null,
            profissional_Municipio: null,
            profissional_Estado: null,
            profissional_UF: null,
            profissional_DDD: null,
            profissional_GIA: null,
            profissional_IBGE: null,
            profissional_Regiao: null,
            profissional_SIAFI: null,
            profissional_Unidade: null,
            profissional_DataInicioUnid: null,
            profissional_DataTermUnid: null,
            profissional_DataAdmissao: null,
            profissional_DataDemissao: null,
            profissional_Escolaridade: null,
            profissional_NomeEscola: null,
            profissional_NMatricula: null,
            profissional_TipoEscola: null,
            profissional_Certidao: null,
            profissional_NumRegistro: null,
            profissional_Livro: null,
            profissional_Folha: null,
            profissional_Circunscricao: null,
            profissional_Zona: null,
            profissional_UFRegistro: null,
            profissional_created_at: null,
            profissional_updated_at: null,
            profissional_deleted_at: null,
            // Adolescentes
            adolescente_id: null,
            adolescente_assinatura: null,
            adolescente_unidade_id: null,
            adolescente_sexo_biologico_id: null,
            adolescente_sexo_biologico: null,
            adolescente_genero_identidade_id: null,
            adolescente_genero_identidade: null,
            adolescente_genero_identidade_descricao: null,
            adolescente_acesso_id: null,
            adolescente_cadastro_id: null,
            adolescente_municipio_id: null,
            adolescente_perfil_id: null,
            adolescente_cargo_funcao_id: null,
            adolescente_profissao_id: null,
            adolescente_prontuario_id: null,
            adolescente_periodo_unidade_id: null,
            adolescente_programas_id: null,
            adolescente_turno_escolar: null,
            adolescente_DataCadastramento: null,
            adolescente_Nome: null,
            adolescente_CPF: null,
            adolescente_RG: null,
            adolescente_ExpedidorRG: null,
            adolescente_ExpedicaoRG: null,
            adolescente_NomeMae: null,
            adolescente_Etnia: null,
            adolescente_TelefoneFixo: null,
            adolescente_TelefoneMovel: null,
            adolescente_TelefoneRecado: null,
            adolescente_Email: null,
            adolescente_Nascimento: null,
            adolescente_CEP: null,
            adolescente_Logradouro: null,
            adolescente_Numero: null,
            adolescente_Complemento: null,
            adolescente_Bairro: null,
            adolescente_Municipio: null,
            adolescente_Estado: null,
            adolescente_UF: null,
            adolescente_DDD: null,
            adolescente_GIA: null,
            adolescente_IBGE: null,
            adolescente_Regiao: null,
            adolescente_SIAFI: null,
            adolescente_Unidade: null,
            adolescente_DataInicioUnid: null,
            adolescente_DataTermUnid: null,
            adolescente_DataAdmissao: null,
            adolescente_DataDemissao: null,
            adolescente_Escolaridade: null,
            adolescente_NomeEscola: null,
            adolescente_NMatricula: null,
            adolescente_TipoEscola: null,
            adolescente_Certidao: null,
            adolescente_NumRegistro: null,
            adolescente_Livro: null,
            adolescente_Folha: null,
            adolescente_Circunscricao: null,
            adolescente_Zona: null,
            adolescente_UFRegistro: null,
            adolescente_created_at: null,
            adolescente_updated_at: null,
            adolescente_deleted_at: null,
            //
            prontuario_data_inicio: null,
            prontuario_data_fim: null
        });

        const validateFormData = () => {
            // Lista de campos que devem ser validados
            const fieldsToValidate = [
                'prontuario_data_inicio',
                'prontuario_data_fim',
                'adolescente_bairro',
                'adolescente_CPF',
                'adolescente_data_inicio_unidade',
                'adolescente_data_termino_unidade',
                'adolescente_email',
                'adolescente_endereco',
                'adolescente_escolaridade',
                'adolescente_etnia',
                'adolescente_expedicao_rg',
                'adolescente_expedidor_rg',
                'adolescente_id',
                'adolescente_Nascimento',
                'adolescente_Nome',
                'adolescente_nome_escola',
                'adolescente_nome_mae',
                'adolescente_numero_matricula',
                'adolescente_rg',
                'responsavel_TelefoneMovel',
                'adolescente_telefone_movel',
                'adolescente_telefone_recado',
                'adolescente_tipo_escola',
                'adolescente_uf',
                'profissional_bairro',
                'profissional_cpf',
                'profissional_data_admissao',
                'profissional_data_demissao',
                'profissional_email',
                'profissional_endereco',
                'profissional_escolaridade',
                'profissional_expedicao_rg',
                'profissional_expedidor_rg',
                'profissional_id',
                'profissional_nascimento',
                'profissional_Nome',
                'profissional_rg',
                'profissional_telefone_fixo',
                'profissional_telefone_movel',
                'profissional_telefone_recado',
                'profissional_uf',
                'prontuario_cad_unico',
                'prontuario_cad_unico_pts',
                'prontuario_data_cadastro_psicossocial',
                'prontuario_deficiencia',
                'prontuario_deficiencia_pts',
                'prontuario_descricao_deficiencia',
                'prontuario_descricao_referencia_na_rede',
                'prontuario_diagnostico_psicologico',
                'prontuario_diagnostico_psicologico_pts',
                'prontuario_encaminhamento_drogas',
                'prontuario_medidas_socioeducativas',
                'prontuario_medidas_socioeducativas_pts',
                'prontuario_necessita_mediador',
                'prontuario_necessita_mediador_pts',
                'prontuario_PontuacaoTotal',
                'prontuario_programa_social',
                'prontuario_referenciado_na_rede',
                'prontuario_referenciado_na_rede_pts',
                'prontuario_uso_de_drogas',
                'prontuario_uso_de_drogas_pts'
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

        const dateFields = [
            'prontuario_data_inicio',
            'prontuario_data_fim',
        ];

        const formatDateToPTBR = (dateString) => {
            if (!dateString) return '';
            const parts = dateString.split('-');
            if (parts.length !== 3) return dateString;
            const [year, month, day] = parts;
            return `${day}-${month}-${year}`;
        };

        const formatarVulnerabilidade = (valor) => ({
            "extremamente-vulneravel": "Extremamente Vulnerável",
            "muito-vulneravel": "Muito Vulnerável",
            "vulneravel": "Vulnerável"
        }[valor] || "Não informado");

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

        const handleFocus = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função que executa após a retirada do foco
        const handleBlur = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

        };

        const filterFormData = (data) => {
            return Object.fromEntries(Object.entries(data).filter(([key, value]) => value != null));
        };

        const submitAllForms = async (filtro, href = '') => {
            const setData = filterFormData(formData);
            let data = '';
            let dbResponse = [];
            let response = '';

            if (!validateFormData()) {
                return false;
            }

            if (filtro === 'filtro-prontuario') {
                await fetchPostProntuarioFiltrar();
                return true;
            }

            // if (filtro === 'filtro-prontuario') {
            //     // Convertendo os dados do setPost em JSON
            //     response = await fetch(url, {
            //         method: 'POST',
            //         body: JSON.stringify(setData),
            //         headers: {
            //             'Content-Type': 'application/json',
            //         },
            //     });

            //     if (!response.ok) {
            //         console.error('Erro na requisição:', response.statusText);
            //         throw new Error(`Erro na requisição: ${response.statusText}`);
            //     }

            //     data = await response.json();

            //     // Processa os dados recebidos da resposta
            //     if (
            //         data.result && data.result.dbResponse && data.result.dbResponse.length > 0
            //     ) {
            //         dbResponse = data.result.dbResponse;
            //         setProntuario(dbResponse);
            //         setPagination('filter');
            //         // console.log('dbResponse: ', dbResponse);
            //     } else {
            //         setMessage({
            //             show: true,
            //             type: 'light',
            //             message: "Certifique-se de que a combinação informada corresponde a um único período. Para datas, verifique se coincidem com as informações cadastradas."
            //         });
            //     }

            //     if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
            //         setPaginacaoLista(data.result.linksArray);
            //         setIsLoading(false);
            //     }
            // }

            // const url = href ? `${base_url + api_post_filter_prontuario}${href}` : `${base_url + api_post_filter_prontuario}`;

            // if (filtro === 'filtro-prontuario') {
            //     // Convertendo os dados do setPost em JSON
            //     response = await fetch(url, {
            //         method: 'POST',
            //         body: JSON.stringify(setData),
            //         headers: {
            //             'Content-Type': 'application/json',
            //         },
            //     });

            //     if (!response.ok) {
            //         console.error('Erro na requisição:', response.statusText);
            //         throw new Error(`Erro na requisição: ${response.statusText}`);
            //     }

            //     data = await response.json();

            //     // Processa os dados recebidos da resposta
            //     if (
            //         data.result && data.result.dbResponse && data.result.dbResponse.length > 0
            //     ) {
            //         dbResponse = data.result.dbResponse;
            //         setProntuario(dbResponse);
            //         setPagination('filter');
            //         // console.log('dbResponse: ', dbResponse);
            //     } else {
            //         setMessage({
            //             show: true,
            //             type: 'light',
            //             message: "Certifique-se de que a combinação informada corresponde a um único período. Para datas, verifique se coincidem com as informações cadastradas."
            //         });
            //     }

            //     if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
            //         setPaginacaoLista(data.result.linksArray);
            //         setIsLoading(false);
            //     }
            // }
        };


        React.useEffect(() => {

            const loadData = async () => {
                // sconsole.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    fetchGetProntuarioListar();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para GET
        const fetchGetProntuarioListar = async (custonBaseURL = base_url, custonApiGetObjeto = api_get_prontuariopsicosocial, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiGetObjeto + customPage;
            // console.log('fetchGetProntuarioListar(url): ', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                // console.log("Resposta bruta da API:", data);

                // console.log('fetchGetProntuarioListar(data): ', data);
                // return data;
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // 
                    setProntuario(dbResponse);
                    setPagination('list');
                    //
                    setFormData((prev) => ({
                        ...prev,
                        ...dbResponse
                    }));
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas objeto cadastradas'
                    });
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);
                }
            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar ProntuárioPSC: ' + error.message
                });
            }
        };

        // POST Padrão 
        // const fetchPostProntuarioFiltrar = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_filter_prontuario, customPage = getVar_page) => {
        //     const url = custonBaseURL + custonApiPostObjeto + customPage;
        //     console.log('fetchPostProntuarioFiltrar(url): ', url);
        //     const SetData = setFormData;
        //     try {
        //         const response = await fetch(url, {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //             },
        //             body: JSON.stringify(SetData),
        //         });
        //         const data = await response.json();
        //         console.log('fetchPostProntuarioFiltrar(data): ', data);
        //         // return data;
        //         if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
        //             const dbResponse = data.result.dbResponse;
        //             // 
        //             setProntuario(dbResponse);
        //             setPagination('list');
        //             //
        //             setFormData((prev) => ({
        //                 ...prev,
        //                 ...dbResponse
        //             }));
        //         } else {
        //             setMessage({
        //                 show: true,
        //                 type: 'light',
        //                 message: 'Não foram encontradas prontuários cadastrados'
        //             });
        //         }
        //     } catch (error) {
        //         console.error('Erro ao enviar dados:', error);
        //         // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
        //         return null;
        //     }
        // };

        // POST Padrão 
        const fetchPostProntuarioFiltrar = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_filter_prontuario, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage + '&limit=90000';
            const SetData = formData;
            // console.log('url :: ', url);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(SetData),
                });
                const data = await response.json();
                // console.log('data(fetchPostProntuarioFiltrar2) ::', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('data(fetchPostProntuarioFiltrar) :: ', data.result.dbResponse);
                    const dbResponse = data.result.dbResponse;
                    setProntuario(dbResponse);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontrados cadastros de Prontuario'
                    });
                    setIsLoading(false);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    // console.log('data.result.linksArray ::', data.result.linksArray);
                    setPaginacaoLista(data.result.linksArray);
                    setPagination('filter');
                    setIsLoading(false);
                }

                // if (checkWordInArray(getURI, 'cadastrar')) {
                //     resposta = 'Cadastro';
                // } else if (checkWordInArray(getURI, 'atualizar')) {
                //     resposta = 'Atualização';
                // } else if (checkWordInArray(getURI, 'consultar')) {
                //     resposta = 'Consulta'
                // } else if (checkWordInArray(getURI, 'exibir')) {
                //     resposta = 'Exibir'
                // } else {
                //     resposta = 'Ação';
                // }

            } catch (error) {

                console.error('Erro ao enviar formulário:', error.message);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao processar a solicitação. Tente novamente.',
                });

            }
        };

        if (isLoading && debugMyPrint) {
            return <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        }

        if (error && debugMyPrint) {
            return <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>
                <div className="alert alert-danger" role="alert">
                    {error}
                </div>
            </div>
        }

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

        const verticalBarStyle = {
            width: '5px',
            height: '60px',
            backgroundColor: '#00BFFF',
            margin: '10px',
            Right: '10px',
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
                                submitAllForms(`filtro-prontuario`);
                            }}>
                                <input
                                    className="btn btn-secondary"
                                    type="submit"
                                    value="Filtrar"
                                />
                            </form>
                        </div>

                        <div className="ms-2">
                            <a className="btn btn-primary" href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/cadastrar`} role="button">
                                <i className="bi bi-plus"></i>&nbsp;Cadastrar Prontuário
                            </a>
                        </div>

                    </div>

                    <nav className="navbar navbar-expand-lg">
                        <div className="container-fluid p-0 ">
                            <button
                                className="navbar-toggler"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarTogglerFiltroProntuario"
                                aria-controls="navbarTogglerFiltroProntuario"
                                aria-expanded="false"
                                aria-label="Toggle navigation">
                                <i className="bi bi-filter" />
                            </button>

                            <div className="collapse navbar-collapse" id="navbarTogglerFiltroProntuario">
                                <div className="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                                    <div className="d-flex flex-column flex-lg-row justify-content-between w-100" style={{ gap: '0.3rem' }}>

                                        {/* toggle filtros */}
                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <div style={{ ...formGroupStyle }}>
                                                    <div>
                                                        <label
                                                            htmlFor="adolescente_Nome"
                                                            style={formLabelStyle}
                                                            className="form-label">
                                                            Adolescente
                                                        </label>
                                                        <input
                                                            data-api="filtro-adolescente"
                                                            type="text"
                                                            name="adolescente_Nome"
                                                            value={formData.adolescente_Nome || ''}
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
                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}>
                                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `${origemForm}`,
                                                        labelField: 'CPF',
                                                        labelColor: 'gray', // gray, red, black,
                                                        nameField: 'adolescente_CPF',
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 2, // minlength 
                                                        attributeMaxlength: 100, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                                                        nameField: 'adolescente_numero_matricula',
                                                        attributePlaceholder: '', // placeholder 
                                                        attributeMinlength: 2, // minlength 
                                                        attributeMaxlength: 100, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                        attributeAutocomplete: 'on', // on, off ]
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                                    }}
                                                />
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}>
                                                <div style={{ ...formGroupStyle }}>
                                                    <AppResponsavelTelefoneMovelFiltro formData={formData} setFormData={setFormData} parametros={parametros} />
                                                </div>
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
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
                                                        labelField: 'Data de Nascimento',
                                                        nameField: 'adolescente_Nascimento',
                                                        attributeMax: '', //  maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20 
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: 'Adolescente', // Adolescente, Filtro-Unidades, Filtro-Profissional, Filtro-Periodo, Profissional, Filtro-ALocarFuncionário, Periodo. 
                                                    }} />
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
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
                                                        nameField: 'prontuario_data_inicio',
                                                        attributeMax: '', // maxDate - Profissional, Periodo. 
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: '',
                                                    }} />
                                            </form>
                                        </div>
                                        <div style={{ flex: '0 1 18%' }} className="col-12 col-lg-2">
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
                                                        labelField: 'Término da Consulta',
                                                        nameField: 'prontuario_data_fim',
                                                        attributeMax: '', // maxDate - Profissional, Periodo.
                                                        attributeRequired: false,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: false,
                                                        attributeMask: '',
                                                    }} />
                                            </form>
                                        </div>
                                    </div>
                                    {/* toggle filtros */}

                                </div>
                            </div>
                        </div>
                    </nav >
                </div>

                <div className="table-responsive ms-2 ps-2 pe-2">
                    <table className="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        NOME ADOLESCENTE
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        CPF/Certidao
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        GRAU DE VULNERABILIDADE
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        TELEFONE RESPONSÁVEL
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        NOME FUNCIONÁRIO
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        DATA DE NASCIMENTO
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        PONTUAÇÃO TOTAL
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        EDITAR
                                    </div>
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="d-flex justify-content-center">
                                        CONSULTAR
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {(isLoading) ? (
                                <tr>
                                    <td colSpan="8">
                                        <AppLoading parametros={{ tipoLoading: "progress" }} />
                                        <div className="d-flex justify-content-center align-items-center min-vh-100">
                                            &nbsp;
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                prontuario && prontuario.length > 0 ? (
                                    prontuario.map((prontuario_value, index_lista_pront) => (
                                        <tr key={index_lista_pront}>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {prontuario_value.adolescente_Nome}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {(prontuario_value.adolescente_CPF !== '') ? (
                                                        <div>{prontuario_value.adolescente_CPF}</div>
                                                    ) : (
                                                        <div>{prontuario_value.adolescente_Certidao}</div>
                                                    )}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {formatarVulnerabilidade(prontuario_value.prontuario_Vulnerabilidade)}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {prontuario_value.responsavel_TelefoneMovel}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {prontuario_value.profissional_Nome}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <AppDataPtBr parametros={prontuario_value.adolescente_Nascimento} />
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    {prontuario_value.prontuario_PontuacaoTotal}
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/atualizar/${prontuario_value.id}`} role="button">
                                                        <i className="bi bi-pencil-square" />
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div className="d-flex justify-content-center">
                                                    <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/consultar/${prontuario_value.id}`} role="button">
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
                                            onClick={() => fetchGetProntuarioListar(base_url, api_get_prontuariopsicosocial, paginacao_value.href)}
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
                                            onClick={() => submitAllForms('filtro-prontuario', paginacao_value.href)}
                                        >
                                            {paginacao_value.text.trim()}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </nav>
                    )}
                </div>

                {/* Modais para cada Pronturario */}
                {prontuario.map((mapList_value, index) => (
                    <div key={index} className="modal fade" id={`staticBackdropPronturario${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropPronturarioLabel${index}`} aria-hidden="true">
                        <div className="modal-dialog modal-xl">
                            <div className="modal-content">

                                <div className="modal-header">
                                    <h5 className="modal-title" id={`staticBackdropPronturarioLabel${index}`}>Detalhes do Prontuário</h5>
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

                {/* Modais para cada profissional */}
                <AppMessageCard
                    parametros={message}
                    modalId="modal_pront_list"
                />

            </div >
        );
    };
</script>