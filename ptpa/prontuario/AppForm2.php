<script type="text/babel">
    const AppForm2 = ({ parametros = {} }) => {

        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        // const atualizar_id = parametros.atualizar_id || 'erro';
        const user_session = parametros.user_session.FIA || {};
        const nomeUsuario = parametros.user_session?.FIA?.Nome;
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const token_csrf = parametros.token_csrf || 'erro';
        const origemForm = parametros.origemForm || '';
        const atualizar_id = parametros.atualizar_id;
        const base_url = parametros.base_url || '';
        const getURI = parametros.getURI || [];
        const json = '1';

        // console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
        // console.log('debugMyPrint ::', debugMyPrint);

        //Base Cadastro Prontuario
        const api_get_adolescente = parametros.api_get_adolescente || '';
        const api_get_exibir_adolescente = parametros.api_get_exibir_adolescente || '';
        const api_get_exibir_profissional = parametros.api_get_exibir_profissional || '';
        const api_post_filtrar_adolescente = parametros.api_post_filtrar_adolescente || '';
        const api_post_filtrar_profissional = parametros.api_post_filtrar_profissional || '';
        const api_get_atualizar_prontuariopsicosocial = parametros.api_get_atualizar_prontuariopsicosocial || '';
        const api_post_cadastrar_prontuariopsicosocial = parametros.api_post_cadastrar_prontuariopsicosocial || '';

        // Variáveis da API
        const [profissionais, setProfissionais] = React.useState([]);
        const [listFuncionarios, setListFuncionarios] = React.useState([]);
        const [listAdolescentes, setListAdolescentes] = React.useState([]);
        const [guardaFuncionarios, setGuardaFuncionarios] = React.useState([]);
        const [guardaAdolescentes, setGuardaAdolescentes] = React.useState([]);
        const [adolescentes, setAdolescentes] = React.useState([]);
        const [prontuario, setProntuarios] = React.useState([]);

        // Variáveis Uteis
        const [resumoConfirmado, setResumoConfirmado] = React.useState(false);
        const [isChoiceMade, setIsChoiceMade] = React.useState(false);
        const [pagination, setPagination] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [idade, setIdade] = React.useState([0, 0]);
        const [error, setError] = React.useState(null);

        // Vulnerabilidade
        const [ptsTotal, setPtsTotal] = React.useState(0);
        const [ptsIdade, setPtsIdade] = React.useState(0);
        const [ptsEscolaridade, setPtsEscolaridade] = React.useState(0);
        const [ptsVulnerabilidade, setPtsVulnerabilidade] = React.useState(0);

        {/* CAMPO FUNCIONARIO */ }
        const [selectFuncionarioShow, setSelectFuncionarioShow] = React.useState(false);
        const funcionarioRef = React.useRef(null);
        const [errorFuncionario, setErrorFuncionario] = React.useState(false);

        {/* CAMPO DOLESCENTE */ }
        const [selectAdolescenteShow, setSelectAdolescenteShow] = React.useState(false);
        const adolescenteRef = React.useRef(null);
        const [errorAdolescente, setErrorAdolescente] = React.useState(false);

        const debounceRef = React.useRef(null);
        const validationTimeoutRef = React.useRef(null);

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

        {/* ALTURA x LARGURA */ }
        const [altura, setAltura] = React.useState(window.innerHeight);
        const [largura, setLargura] = React.useState(window.innerWidth);

        // Definindo mensagens do Sistema
        const [showModal, setShowModal] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        {/* ALTURA x LARGURA */ }
        React.useEffect(() => {
            const atualizarDimensoes = () => {
                setAltura(window.innerHeight);
                setLargura(window.innerWidth);
            };

            window.addEventListener("resize", atualizarDimensoes);

            return () => {
                window.removeEventListener("resize", atualizarDimensoes);
            };
        }, []);

        const pontuacaoMap = {
            "extremamente-vulneravel": 40,
            "muito-vulneravel": 25,
            "vulneravel": 10
        };

        // Função do onlyFocus
        const handleFocus = (event) => {
            const { name, value } = event.target;

            // console.log('handleFocus: ', name);
            // console.log('handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });
            {/* CAMPO FUNCIONARIO */ }
            if (name === "profissional_Nome") {
                setSelectFuncionarioShow(true);
                setListFuncionarios(guardaFuncionarios);
                setTimeout(() => {
                    funcionarioRef.current?.focus();
                }, 0);
            }
        };

        // FUNÇÃO HANDLECHANGE CORRIGIDA
        const handleChange = (event) => {
            const { name, value } = event.target;
            // console.log('--------------------');
            // console.log('handleRadioChange');
            // console.log('--------------------');
            // console.log('NOME/VALOR', name, value);

            {/* CAMPO FUNCIONARIO */ }
            if (name === "profissional_Nome") {
                setSelectFuncionarioShow(true);

                // Limpa timeouts anteriores
                if (debounceRef.current) {
                    clearTimeout(debounceRef.current);
                }

                // Timeout para filtrar a lista
                debounceRef.current = setTimeout(() => {
                    const termo = value
                        .replace(/\s+/g, ' ')
                        .trimEnd()
                        .toLowerCase();

                    if (termo.length === 0) {
                        setListFuncionarios(guardaFuncionarios);
                        return;
                    }

                    const filtrados = guardaFuncionarios.filter((m) =>
                        m.Nome.toLowerCase().includes(termo)
                    );

                    // console.log('filtrados :: ', filtrados);

                    if (filtrados.length === 0) {
                        // console.log('filtrados.length === 0');
                        setListFuncionarios(guardaFuncionarios);
                        setErrorFuncionario(true);
                    } else {
                        // console.log('else');
                        setListFuncionarios(filtrados);
                        setErrorFuncionario(false);
                    }
                }, 300);

                // Timeout para validar se o município é válido (apenas se não foi selecionado via click)
                // Verifica se o valor exato existe na lista original
                const funcionarioExato = guardaFuncionarios.find((m) =>
                    m.Nome.toLowerCase() === value.toLowerCase()
                );

                if (!funcionarioExato && value.trim() !== '') {
                    // Se não encontrou correspondência exata e o campo não está vazio
                    // Aplica timeout maior para dar tempo do usuário selecionar
                    if (validationTimeoutRef.current) {
                        clearTimeout(validationTimeoutRef.current);
                    }

                    validationTimeoutRef.current = setTimeout(() => {
                        // Verifica novamente se ainda não há correspondência exata
                        const currentValue = formData.profissional_Nome;
                        const funcionarioAtual = guardaFuncionarios.find((m) =>
                            m.Nome.toLowerCase() === currentValue?.toLowerCase()
                        );

                        if (!funcionarioAtual && currentValue && currentValue.trim() !== '') {
                            setFormData((prev) => ({
                                ...prev,
                                profissional_Nome: 'Escolha um Funcionário abaixo para prosseguir.'
                            }));
                            setErrorFuncionario(true);
                        }
                    }, 4000);
                }
            }

            {/* CAMPO ADOLESCENTE */ }
            if (name === "adolescente_Nome") {
                setSelectAdolescenteShow(true);

                // Limpa timeouts anteriores
                if (debounceRef.current) {
                    clearTimeout(debounceRef.current);
                }

                // Timeout para filtrar a lista
                debounceRef.current = setTimeout(() => {
                    const termo = value
                        .replace(/\s+/g, ' ')
                        .trimEnd()
                        .toLowerCase();

                    if (termo.length === 0) {
                        setListAdolescentes(guardaAdolescentes);
                        return;
                    }

                    const filtrados = guardaAdolescentes.filter((m) =>
                        m.Nome.toLowerCase().includes(termo)
                    );

                    // console.log('filtrados :: ', filtrados);

                    if (filtrados.length === 0) {
                        // console.log('filtrados.length === 0');
                        setListAdolescentes(guardaAdolescentes);
                        setErrorAdolescente(true);
                    } else {
                        // console.log('else');
                        setListAdolescentes(filtrados);
                        setErrorAdolescente(false);
                    }
                }, 300);

                // Timeout para validar se o município é válido (apenas se não foi selecionado via click)
                // Verifica se o valor exato existe na lista original
                const adolescenteExato = guardaAdolescentes.find((m) =>
                    m.Nome.toLowerCase() === value.toLowerCase()
                );

                if (!adolescenteExato && value.trim() !== '') {
                    // Se não encontrou correspondência exata e o campo não está vazio
                    // Aplica timeout maior para dar tempo do usuário selecionar
                    if (validationTimeoutRef.current) {
                        clearTimeout(validationTimeoutRef.current);
                    }

                    validationTimeoutRef.current = setTimeout(() => {
                        // Verifica novamente se ainda não há correspondência exata
                        const currentValue = formData.adolescente_Nome;
                        const adolescenteAtual = guardaAdolescentes.find((m) =>
                            m.Nome.toLowerCase() === currentValue?.toLowerCase()
                        );

                        if (!adolescenteAtual && currentValue && currentValue.trim() !== '') {
                            setFormData((prev) => ({
                                ...prev,
                                adolescente_Nome: 'Escolha um Adolescente abaixo para prosseguir.'
                            }));
                            setErrorAdolescente(true);
                        }
                    }, 4000);
                }
            }

            if (name === "adolescente_id") {
                // Chama a função fetchGetAdolescente com o id do adolescente
                fetchGetAdolescente(value);
                setFormData((prev) => ({
                    ...prev,
                    [name]: value
                }));
                return;
            }

            // Forçar atualização imediata
            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            return;
        };

        {/* HANDLERADIOCHANGE */ }
        const handleRadioChange = (event) => {
            const { name, value } = event.target;

            console.log('--------------------');
            console.log('handleRadioChange');
            console.log('--------------------');
            console.log('name, value :: ', name, value);

            if (name === 'prontuario_Vulnerabilidade') {
                switch (value) {
                    case 'extremamente-vulneravel':
                        setPtsVulnerabilidade(40);
                        setFormData((prev) => ({
                            ...prev,
                            prontuario_PontuacaoVulnerabilidade: 40,
                            prontuario_Vulnerabilidade: 'extremamente-vulneravel',
                        }));
                        break;

                    case 'muito-vulneravel':
                        setPtsVulnerabilidade(25);
                        setFormData((prev) => ({
                            ...prev,
                            prontuario_PontuacaoVulnerabilidade: 25,
                            prontuario_Vulnerabilidade: 'muito-vulneravel',
                        }));
                        break;

                    case 'vulneravel':
                        setPtsVulnerabilidade(10);
                        setFormData((prev) => ({
                            ...prev,
                            prontuario_PontuacaoVulnerabilidade: 10,
                            prontuario_Vulnerabilidade: 'vulneravel',
                        }));
                        break;

                    default:
                        break;
                }
            }

            setFormData(prev => ({
                ...prev,
                [name]: value
            }));

        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));

            atualizaVulnerabilidade();
        };

        const handleClick = (event) => {
            // console.log('-----------');
            // console.log('handleClick');
            const campo = event.target.getAttribute('data-campo');
            // console.log('handleClick: ', campo);
            const value = event.target.value;
            // console.log('value: ', value);

            {/* CAMPO FUNCIONARIO */ }
            if (campo === "profissional_Nome") {
                // Limpa o timeout de validação ao selecionar um município
                if (validationTimeoutRef.current) {
                    clearTimeout(validationTimeoutRef.current);
                }
                setFormData(prev => ({
                    ...prev,
                    profissional_Nome: value,
                    profissional_id: profissionalExato.id || null
                }));
                setErrorFuncionario(false);
            }

            const profissionalExato = guardaFuncionarios.find((m) =>
                m.Nome.toLowerCase() === value.toLowerCase()
            );

            {/* CAMPO ADOLESCENTE */ }
            if (campo === "adolescente_Nome") {
                // Limpa o timeout de validação ao selecionar um município
                if (validationTimeoutRef.current) {
                    clearTimeout(validationTimeoutRef.current);
                }
                setFormData(prev => ({
                    ...prev,
                    adolescente_Nome: value,
                    adolescente_id: adolescenteExato.id
                }));
                setErrorAdolescente(false);

                const adolescenteExato = guardaAdolescentes.find((m) =>
                    m.Nome.toLowerCase() === value.toLowerCase()
                );

                if (adolescenteExato && value.trim() !== '') {

                    if (validationTimeoutRef.current) {
                        clearTimeout(validationTimeoutRef.current);
                    }

                    validationTimeoutRef.current = setTimeout(() => {
                        console.log('adolescenteExato.Nome :: ', adolescenteExato.Nome);
                        console.log('adolescenteExato.id :: ', adolescenteExato.id);
                        fetchGetAdolescente(adolescenteExato.id);
                    }, 400);
                }
            }
        }

        {/* FORMDATA */ }
        const [formData, setFormData] = React.useState({
            //
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            prontuario_id: null,
            prontuario_funcionario_id: null,
            prontuario_adolescente_id: null,
            prontuario_MedidasSocioEducativas: null,
            prontuario_MedidasSocioEducativas_pts: null,
            prontuario_MedidasSocioEducativasDesc: null,
            prontuario_UsodeDrogas: null,
            prontuario_UsoDrogas_pts: null,
            prontuario_UsoDrogasDesc: null,
            prontuario_CadUnico: null,
            prontuario_CadUnico_pts: null,
            prontuario_CadUnicoProgramaSocial: null,
            prontuario_EncaminhamentoOrgao: null,
            prontuario_EncaminhamentoOrgao_pts: null,
            prontuario_EncaminhamentoOrgaoDesc: null,
            prontuario_Deficiencia: null,
            prontuario_Deficiencia_pts: null,
            prontuario_DeficienciaDesc: null,
            prontuario_NecesMediador: null,
            prontuario_NecesMediador_pts: null,
            prontuario_NecesMediadorTipoFamiliar: null,
            prontuario_DataCadPsicoSocial: null,
            prontuario_PontuacaoVulnerabilidade: null,
            prontuario_Vulnerabilidade: null,
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
        });

        const submitAllForms = async (filtro) => {
            const setData = formData;
            let data = '';
            let dbResponse = [];
            let response = '';

            // console.log('formData :: ', formData);

            // Mapeamento dos campos com nomes amigáveis
            const camposObrigatorios = {
                // profissional_id: 'Selecione um profissional',
                adolescente_id: 'Selecione um adolescente',
                prontuario_MedidasSocioEducativas: 'Medidas socioeducativas em branco',
                prontuario_UsodeDrogas: 'Uso de Drogas em branco',
                prontuario_CadUnico: 'Cadastro Único em branco',
                prontuario_EncaminhamentoOrgao: 'Encaminhamento de Orgão em branco',
                prontuario_Deficiencia: 'Deficiência em branco',
                prontuario_NecesMediador: 'Necessidade de mediador em branco',
                prontuario_Vulnerabilidade: 'Grau de Vulnerabilidade',
            };

            // Verificar se algum dos campos está vazio ou nulo
            const camposVazios = Object.keys(camposObrigatorios).filter(campo => !setData[campo]);

            if (camposVazios.length > 0) {
                const nomesCamposVazios = camposVazios.map(campo => camposObrigatorios[campo]);
                setMessage({
                    show: true,
                    type: 'light',
                    message: `<b>Os campos</b>: <br/> ${nomesCamposVazios.join("<br/>")}<br/> <b>não podem estar em branco</b>`,
                });
                return;
            }

            const camposComErro = {
                profissional_Nome: 'Funcionário inválido. Escolha um funcionário válido da lista.',
                // outros campos no futuro...
            };
            const errosDeValidacao = [];
            if (errorFuncionario) {
                errosDeValidacao.push(camposComErro.profissional_Nome);
            }
            // outros campos: if (errorOutroCampo) { errosDeValidacao.push(camposComErro.outroCampo); }
            if (errosDeValidacao.length > 0) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: `<b>Os seguintes campos apresentam erro:</b><br/>${errosDeValidacao.join("<br/>")}`,
                });
                return;
            }

            if (filtro === `filtro-${origemForm}`) {
                // Convertendo os dados do setPost em JSON
                response = await fetch(`${base_url}${api_post_cadastrar_prontuariopsicosocial}`, {
                    method: 'POST',
                    body: JSON.stringify(setData),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                // console.log('----------------------------------');
                // console.log('setData :: ', setData);
                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.statusText}`);
                }

                data = await response.json();

                let resposta = '';

                if (checkWordInArray(getURI, 'cadastrar')) {
                    resposta = 'Cadastro';
                } else if (checkWordInArray(getURI, 'atualizar')) {
                    resposta = 'Atualização';
                } else if (checkWordInArray(getURI, 'consultar')) {
                    resposta = 'Consulta'
                } else if (checkWordInArray(getURI, 'consultarfunc')) {
                    resposta = 'Consulta Funcionário'
                } else if (checkWordInArray(getURI, '')) {
                    resposta = 'Alocar'
                } else {
                    resposta = 'Ação';
                }

                if (
                    data.status &&
                    data.status === 'success' &&
                    data.result &&
                    data.result.affectedRows &&
                    data.result.affectedRows > 0
                ) {
                    dbResponse = data.result.dbResponse;
                    // Função para exibir o alerta (success, danger, warning, info)
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `${resposta} realizado com sucesso. <br/> 
                        Prontuário psicossocial registrado no sistema com pontuação total de <b>${formData.prontuario_PontuacaoTotal}</b> pontos!`
                    });

                    redirectTo('index.php/fia/ptpa/prontuariopsicosocial/endpoint/exibir')

                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `Não foi possivel realizar o ${resposta}`,
                    });
                }
            }
        };

        // Fetch para GET
        const fetchProfissionais = async (customBaseURL = base_url, customApiGetObjeto = api_post_filtrar_profissional, customPage = '?limit=100&page=1') => {
            const url = customBaseURL + customApiGetObjeto + customPage;
            // console.log('-------------------------------------');
            // console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
            // console.log('url :: ', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('dbResponse :: ', dbResponse);
                    setProfissionais(dbResponse);
                    setListFuncionarios(dbResponse);
                    setGuardaFuncionarios(dbResponse);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas Funcionários cadastrados'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Funcionários: ' + error.message
                });
            }
        };

        {/* SELECT CAMPO ADOLESCENTE */ }
        const fetchAdolescentes = async (custonBaseURL = base_url, custonApiGetObjeto = api_get_adolescente, customPage = '?limit=100&page=1') => {
            // console.log('-------------------------------------');
            // console.log('fetchAdolescentes...');
            // console.log('-------------------------------------');
            // console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
            const url = custonBaseURL + custonApiGetObjeto + customPage;
            // console.log('url :: ', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('dbResponse :: ', dbResponse);
                    setAdolescentes(dbResponse);
                    setListAdolescentes(dbResponse);
                    setGuardaAdolescentes(dbResponse);
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

        // Fetch para GET
        const fetchProntuarios = async (custonBaseURL = base_url, custonApiGetObjeto = api_get_atualizar_prontuariopsicosocial, customPage = '?limit=100&page=1') => {
            const url = custonBaseURL + custonApiGetObjeto + customPage;
            // console.log('-------------------------------------');
            // console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
            // console.log('url :: ', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('dbResponse :: ', dbResponse[0]);

                    setFormData((prev) => ({
                        ...prev,
                        ...dbResponse[0]
                    }));

                    setPtsVulnerabilidade(dbResponse[0]?.prontuario_PontuacaoVulnerabilidade || 0);

                    return dbResponse
                }

            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Prontuário: ' + error.message
                });
            }
        };

        const fetchGetAdolescente = async (id = null) => {

            setFormData((prev) => ({
                ...prev,
                "prontuario_PontuacaoTotal": formData.prontuario_PontuacaoTotal || 0,
                "prontuario_Vulnerabilidade": formData.prontuario_Vulnerabilidade || 'Nenhum',
                "prontuario_PontuacaoVulnerabilidade": formData.prontuario_PontuacaoVulnerabilidade || 0,
            }));

            if (id === null) {
                return;
            }
            console.log('-------------------------------------');
            console.log('fetchGetAdolescente...');
            console.log('-------------------------------------');
            console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');

            const url = base_url + api_get_exibir_adolescente + '/' + id;
            console.log('url :: ', url);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const adolescente = data.result.dbResponse[0];
                    console.log('adolescente :: ', adolescente);

                    const meses_vida = calcularMesesDeVida(adolescente.Nascimento);
                    console.log('meses_vida :: ', meses_vida);


                    setTimeout(() => {
                        calcularVulnerabilidadeIdade(meses_vida);
                    }, 300);

                    const recebe_escolaridade = adolescente.Escolaridade;
                    console.log('recebe_escolaridade :: ', recebe_escolaridade);

                    setTimeout(() => {
                        calcularVulnerabilidadeEscolaridade(recebe_escolaridade);
                    }, 300);

                }
            } catch (error) {
                setError('Erro ao carregar Adolescentes: ' + error.message);
            }
        };

        const calcularMesesDeVida = (dataNascimento) => {
            // console.log('----------------------');
            // console.log('calcularMesesDeVida');
            // console.log('----------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ prontuario/ AppForm2.php');

            if (!dataNascimento) return 0; // Retorna 0 se não houver data válida

            const dataNasc = new Date(dataNascimento);
            const hoje = new Date();

            const anos = hoje.getFullYear() - dataNasc.getFullYear();
            const meses = hoje.getMonth() - dataNasc.getMonth();

            const totalMeses = anos * 12 + meses;

            return totalMeses;
        };

        const calcularVulnerabilidadeIdade = (idade) => {
            // console.log('----------------------');
            // console.log('calcularVulnerabilidadeIdade');
            // console.log('----------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ prontuario/ AppForm2.php');
            // console.log('idade :: ', idade);

            if (idade >= 204 && idade <= 210) {
                setPtsIdade(25);
                console.log('setPtsIdade(25)');
                return 25;
            }
            if (idade >= 198 && idade <= 201) {
                setPtsIdade(20);
                console.log('setPtsIdade(20)');
                return 25
            }
            if (idade >= 192 && idade <= 197) {
                setPtsIdade(10);
                console.log('setPtsIdade(10)');
                return 10;
            }

        };

        const calcularVulnerabilidadeEscolaridade = (escolaridade) => {
            // console.log('----------------------');
            // console.log('calcularVulnerabilidadeEscolaridade');
            // console.log('----------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ prontuario/ AppForm2.php');

            let pontuacao = 0;

            if (["6º Ano Ensino Fundamental", "7º Ano Ensino Fundamental", "8º Ano Ensino Fundamental", "9º Ano Ensino Fundamental", "EJA"].includes(escolaridade)) {
                pontuacao = 5;
            } else if (escolaridade === "1º Ano do Ensino Médio") {
                pontuacao = 10;
            } else if (escolaridade === "2º Ano do Ensino Médio") {
                pontuacao = 15;
            } else if (escolaridade === "3º Ano do Ensino Médio") {
                pontuacao = 20;
            }

            console.log("PontuacaoEscolaridade :: ", pontuacao);

            setPtsEscolaridade(Number(pontuacao));

            return;

        };

        if (debugMyPrint && isLoading) {
            return <div className="d-flex justify-content-center align-items-center min-vh-100">
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        }

        {/* SOCIO EDUCATIVA */ }
        const renderQstSocioEducativa = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_MedidasSocioEducativas === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_MedidasSocioEducativas1"
                                name="prontuario_MedidasSocioEducativas"
                                value="Y"
                                checked={formData.prontuario_MedidasSocioEducativas === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_MedidasSocioEducativas1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_MedidasSocioEducativas2"
                                name="prontuario_MedidasSocioEducativas"
                                value="N"
                                checked={formData.prontuario_MedidasSocioEducativas === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_MedidasSocioEducativas2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* TRATAMENTO DE DROGAS */ }
        const renderQstTratamentoDrogas = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_UsodeDrogas === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_UsodeDrogas1"
                                name="prontuario_UsodeDrogas"
                                value="Y"
                                checked={formData.prontuario_UsodeDrogas === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_UsodeDrogas1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_UsodeDrogas2"
                                name="prontuario_UsodeDrogas"
                                value="N"
                                checked={formData.prontuario_UsodeDrogas === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_UsodeDrogas2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* CADASTRO UNICO*/ }
        const renderQstCadUnico = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_CadUnico === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_CadUnico1"
                                name="prontuario_CadUnico"
                                value="Y"
                                checked={formData.prontuario_CadUnico === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_CadUnico1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_CadUnico2"
                                name="prontuario_CadUnico"
                                value="N"
                                checked={formData.prontuario_CadUnico === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_CadUnico2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* ENCAMINHAMENTO ÓRGÃOS */ }
        const renderQstEncaminhamentoOrgao = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_EncaminhamentoOrgao === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    < div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `
                    }>
                        <div className="form-check">
                            <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_EncaminhamentoOrgao1" name="prontuario_EncaminhamentoOrgao"
                                value="Y"
                                checked={formData.prontuario_EncaminhamentoOrgao === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_EncaminhamentoOrgao1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_EncaminhamentoOrgao2"
                                name="prontuario_EncaminhamentoOrgao"
                                value="N"
                                checked={formData.prontuario_EncaminhamentoOrgao === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_EncaminhamentoOrgao2">Não</label>
                        </div>
                    </div >
                )
            );
        }

        {/* DEFICIÊNCIA */ }
        const renderQstDeficiencia = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_Deficiencia === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_Deficiencia1" name="prontuario_Deficiencia"
                                value="Y"
                                checked={formData.prontuario_Deficiencia === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_Deficiencia1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_Deficiencia2"
                                name="prontuario_Deficiencia"
                                value="N"
                                checked={formData.prontuario_Deficiencia === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_Deficiencia2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* NECESSIDADE MEDIADOR */ }
        const renderQstNecessidadeMediador = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_NecesMediador === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_NecesMediador1" name="prontuario_NecesMediador"
                                value="Y"
                                checked={formData.prontuario_NecesMediador === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_NecesMediador1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_NecesMediador2"
                                name="prontuario_NecesMediador"
                                value="N"
                                checked={formData.prontuario_NecesMediador === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_NecesMediador2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        // Campos novos aqui

        {/* DIAGNÓSTICO PSICOLÓGICO */ }
        const renderQstDiagnosticoPsicologico = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_Diagnostico === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_Diagnostico1" name="prontuario_Diagnostico"
                                value="Y"
                                checked={formData.prontuario_Diagnostico === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_Diagnostico1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_Diagnostico2"
                                name="prontuario_Diagnostico"
                                value="N"
                                checked={formData.prontuario_Diagnostico === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_Diagnostico2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* RENDA FAMILIAR */ }
        const renderQstRenderFamiliar = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_RendaFamiliar === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_RendaFamiliar1" name="prontuario_RendaFamiliar"
                                value="Y"
                                checked={formData.prontuario_RendaFamiliar === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_RendaFamiliar1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_RendaFamiliar2"
                                name="prontuario_RendaFamiliar"
                                value="N"
                                checked={formData.prontuario_RendaFamiliar === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_RendaFamiliar2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* REFERÊNCIA NA REDE */ }
        const renderQstReferenciaRede = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_ReferenciadoNaRede === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_ReferenciaNaRede1" name="prontuario_ReferenciadoNaRede"
                                value="Y"
                                checked={formData.prontuario_ReferenciadoNaRede === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_ReferenciaNaRede1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_ReferenciaNaRede2"
                                name="prontuario_ReferenciadoNaRede"
                                value="N"
                                checked={formData.prontuario_ReferenciadoNaRede === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_ReferenciaNaRede2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* TIPO FAMILIAR */ }
        const renderQstTipoFamiliar = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_TipoFamiliar === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_TipoFamiliar1" name="prontuario_TipoFamiliar"
                                value="Y"
                                checked={formData.prontuario_TipoFamiliar === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_TipoFamiliar1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_TipoFamiliar2"
                                name="prontuario_TipoFamiliar"
                                value="N"
                                checked={formData.prontuario_TipoFamiliar === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_TipoFamiliar2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* PROGRAMAS SOCIAIS */ }
        const renderQstParticipacaoProgramasSociais = () => {
            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {formData.prontuario_ParticipacaoProg === "Y" ? "Sim" : "Não"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
                        <div className="form-check">
                            <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_ParticipacaoProgramasSociais1" name="prontuario_ParticipacaoProg"
                                value="Y"
                                checked={formData.prontuario_ParticipacaoProg === "Y"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_ParticipacaoProgramasSociais1">Sim</label>
                        </div>
                        <div className="form-check">
                            <input
                                data-api="form-prontuario"
                                type="radio"
                                className="form-check-input"
                                id="prontuario_ParticipacaoProgramasSociais2"
                                name="prontuario_ParticipacaoProg"
                                value="N"
                                checked={formData.prontuario_ParticipacaoProg === "N"}
                                onChange={handleRadioChange}
                            />
                            <label className="form-check-label" htmlFor="prontuario_ParticipacaoProgramasSociais2">Não</label>
                        </div>
                    </div>
                )
            );
        }

        {/* GRAU VULNERABILIADE */ }
        const renderQstVulnerabilidade = () => {

            const vulnerabilidadeLabels = {
                "extremamente-vulneravel": "Extremamente Vulnerável",
                "muito-vulneravel": "Muito Vulnerável",
                "vulneravel": "Vulnerável"
            };

            return (
                checkWordInArray(getURI, 'consultar') ? (
                    <div className="p-2">
                        {vulnerabilidadeLabels[formData.prontuario_Vulnerabilidade] || "Não Informado"}
                    </div>
                ) : (
                    <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'd-flex gap-3 p-2'}`} style={{ flexWrap: 'nowrap' }}>

                        <div className="row w-100">
                            <div className="col-12 col-sm-4">
                                <div className="form-check">
                                    <input
                                        data-api="form-prontuario"
                                        type="radio"
                                        className="form-check-input"
                                        id="vulnerabilidade1"
                                        name="prontuario_Vulnerabilidade"
                                        value="extremamente-vulneravel"
                                        checked={formData.prontuario_Vulnerabilidade === "extremamente-vulneravel"}
                                        onChange={handleRadioChange}
                                    />
                                    <label className="form-check-label" htmlFor="vulnerabilidade1">
                                        Extremamente Vulnerável
                                    </label>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div className="form-check">
                                    <input
                                        data-api="form-prontuario"
                                        type="radio"
                                        className="form-check-input"
                                        id="vulnerabilidade2"
                                        name="prontuario_Vulnerabilidade"
                                        value="muito-vulneravel"
                                        checked={formData.prontuario_Vulnerabilidade === "muito-vulneravel"}
                                        onChange={handleRadioChange}
                                    />
                                    <label className="form-check-label" htmlFor="vulnerabilidade2">
                                        Muito Vulnerável
                                    </label>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div className="form-check">
                                    <input
                                        data-api="form-prontuario"
                                        type="radio"
                                        className="form-check-input"
                                        id="vulnerabilidade3"
                                        name="prontuario_Vulnerabilidade"
                                        value="vulneravel"
                                        checked={formData.prontuario_Vulnerabilidade === "vulneravel"}
                                        onChange={handleRadioChange}
                                    />
                                    <label className="form-check-label" htmlFor="vulnerabilidade3">
                                        Vulnerável
                                    </label>
                                </div>
                            </div>
                        </div>
                        {/* Campo hidden para armazenar a pontuação */}
                        <input
                            type="hidden"
                            name="prontuario_PontuacaoVulnerabilidade"
                            value={pontuacaoMap[formData.prontuario_PontuacaoVulnerabilidade] || ''}
                        />
                    </div>
                )
            );
        }

        const renderComandosResumo = () => {
            if (checkWordInArray(getURI, 'detalhar')) return null;
            return (
                <div className="ms-3 me-3">
                    <div className="row">
                        <div className="col-12">

                            {checkWordInArray(getURI, 'atualizar') && isChoiceMade && (
                                <AppResumo
                                    parametros={parametros}
                                    formData={formData}
                                    setFormData={setFormData}
                                    setResumoConfirmado={setResumoConfirmado}
                                />
                            )}

                            <form
                                className="was-validated d-flex justify-content-between align-items-center"
                                onSubmit={(e) => {
                                    e.preventDefault();

                                    // Evita envio se estiver no modo atualizar sem aceite
                                    if (checkWordInArray(getURI, 'atualizar')) {
                                        if (!isChoiceMade || !resumoConfirmado) return;
                                    }

                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}
                            >
                                <div className="d-flex gap-2">
                                    {/* BOTÃO VOLTAR */}
                                    <a
                                        className="btn btn-danger"
                                        href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/exibir`}
                                        role="button"
                                    >
                                        Voltar
                                    </a>

                                    {/* BOTÃO SALVAR (somente em cadastrar) */}
                                    {checkWordInArray(getURI, 'cadastrar') && (
                                        <input
                                            className="btn btn-success"
                                            type="submit"
                                            value="Salvar"
                                        />
                                    )}

                                    {/* BOTÃO SALVAR (somente em atualizar, após resumo aceito) */}
                                    {checkWordInArray(getURI, 'atualizar') &&
                                        isChoiceMade &&
                                        resumoConfirmado && (
                                            <input
                                                className="btn btn-success"
                                                type="submit"
                                                value="Salvar"
                                            />
                                        )}
                                </div>
                            </form>

                            {/* BOTÃO ATUALIZAR (inicia resumo) */}
                            {checkWordInArray(getURI, 'atualizar') && !isChoiceMade && (
                                <button
                                    type="button"
                                    className="btn btn-primary mt-3"
                                    onClick={() => setIsChoiceMade(true)}
                                >
                                    Atualizar
                                </button>
                            )}

                            {/* BOTÃO CANCELAR REVISÃO */}
                            {checkWordInArray(getURI, 'atualizar') && isChoiceMade && (
                                <button
                                    type="button"
                                    className="btn btn-danger mt-3"
                                    onClick={() => {
                                        setIsChoiceMade(false);
                                        setResumoConfirmado(false);
                                    }}
                                >
                                    Cancelar revisão
                                </button>
                            )}
                        </div>
                    </div>
                </div>
            );
        };


        if (debugMyPrint && error) {
            return <div className="d-flex justify-content-center align-items-center min-vh-100">
                <div className="alert alert-danger" role="alert">
                    {error}
                </div>
            </div>
        }

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        {/* CHAMA GERAL */ }
        React.useEffect(() => {
            setIsLoading(true);
            // Função para carregar todos os dados necessários
            const loadData = async () => {
                // console.log('-------------------------------');
                // console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
                // console.log('React.useEffect(()...');
                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchProfissionais();
                    await fetchAdolescentes();
                    await fetchProntuarios();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    // console.log('Fim do React.useEffect...');
                    // console.log('formData :: ', formData);
                    // console.log('user_session :: ', user_session);
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        {/* FORMDATA.ADOLESCENTE_ID */ }
        React.useEffect(() => {
            // console.log('--------------------------------');
            console.log('React.useEffect(()...');
            // console.log('--------------------------------');
            // console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
            if (!formData.adolescente_id) {
                console.log('ID não fornecido');
                return;
            }

            if (isNaN(Number(formData.adolescente_id))) {
                console.log('ID não é um número válido');
                return;
            }

            // console.log('--------------------------------');
            // console.log('formData.adolescente_id :: ', formData.adolescente_id);

            if (Number(formData.adolescente_id) < 0) {
                const idPositivo = Math.abs(Number(formData.adolescente_id));
                setFormData(prevFormData => ({
                    ...prevFormData,
                    adolescente_id: idPositivo
                }));
            }

            setTimeout(() => {
                fetchGetAdolescente(formData.adolescente_id);
            }, 300);

        }, [formData.adolescente_id]);

        {/* [ptsIdade, ptsEscolaridade, ptsVulnerabilidade] */ }
        React.useEffect(() => {
            console.log('---------------');
            console.log('ptsIdade :: ', Number(ptsIdade));
            console.log('ptsEscolaridade :: ', Number(ptsEscolaridade));
            console.log('ptsVulnerabilidade :: ', Number(ptsVulnerabilidade));

            const total = Number(ptsIdade) + Number(ptsEscolaridade) + Number(ptsVulnerabilidade);
            // console.log('--------------------------------');
            console.log('total :: ', total);
            setPtsTotal(Number(total));

            setFormData(prev => ({
                ...prev,
                prontuario_PontuacaoTotal: total
            }));

        }, [ptsIdade, ptsEscolaridade, ptsVulnerabilidade]);

        {/* CAMPO FUNCIONARIO */ }
        React.useEffect(() => {
            if (!selectFuncionarioShow) return;

            function handleClickOutside(event) {
                if (funcionarioRef.current && !funcionarioRef.current.contains(event.target)) {
                    setSelectFuncionarioShow(false);
                }
            }

            document.addEventListener("mousedown", handleClickOutside);
            return () => {
                document.removeEventListener("mousedown", handleClickOutside);
            };
        }, [selectFuncionarioShow]);

        {/* CAMPO ADOLESCENTE */ }
        React.useEffect(() => {
            if (!selectAdolescenteShow) return;

            function handleClickOutside(event) {
                if (adolescenteRef.current && !adolescenteRef.current.contains(event.target)) {
                    setSelectAdolescenteShow(false);
                }
            }

            document.addEventListener("mousedown", handleClickOutside);
            return () => {
                document.removeEventListener("mousedown", handleClickOutside);
            };
        }, [selectAdolescenteShow]);

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
        };

        const requiredField = {
            color: '#FF0000',
        };

        const formControlStyle = {
            fontSize: '1.rem',
            borderColor: '#fff',
        };

        const verticalBarStyle = {
            width: '5px',
            height: '60px',
            backgroundColor: '#00BFFF',
            margin: '10px',
            Right: '10px',
        };

        {/* RENDER CAMPO FUNCIONARIO */ }
        const renderCampoFuncionario = (tipoCampo, selectFuncionarioShow, setSelectFuncionarioShow) => (
            <>
                {(tipoCampo === 'drop_select') && (
                    <div className="dropdown w-100">
                        <input
                            className={`form-control border-0 ${errorFuncionario ? 'is-invalid' : formData.profissional_Nome ? 'is-valid' : ''}`}
                            type="text"
                            id="profissional_Nome"
                            name="profissional_Nome"
                            value={formData.profissional_Nome || ''}
                            onChange={handleChange}
                            onFocus={handleFocus}
                            autoComplete="off"
                            required={true}
                            aria-expanded={selectFuncionarioShow}
                            onClick={() => {
                                setSelectFuncionarioShow(true);
                                setListFuncionarios(guardaFuncionarios);
                            }}
                        />
                        <div
                            ref={funcionarioRef}
                            className={`dropdown-menu w-100  border border-1 border-top-0 border-dark mt-2 ${selectFuncionarioShow ? 'show' : ''}`}
                        >
                            <div className="m-0 p-0" style={{ height: "300px", overflowY: "auto", overflowX: "hidden" }}>
                                {listFuncionarios.map((list_funcionarios, index) => (
                                    <React.Fragment key={index}>
                                        <input
                                            type="radio"
                                            className="btn-check"
                                            name="funcionario-radio"
                                            id={`funcionario-option${index}`}
                                            autoComplete="off"
                                            value={list_funcionarios.Nome}
                                            data-campo="profissional_Nome"
                                            checked={formData.profissional_Nome === list_funcionarios.Nome}
                                            onChange={handleClick}
                                        />
                                        <label
                                            className="btn w-100 text-start"
                                            htmlFor={`funcionario-option${index}`}
                                        >
                                            {list_funcionarios.Nome}
                                        </label>
                                    </React.Fragment>
                                ))}
                            </div>
                        </div>
                    </div>
                )}
            </>
        );

        {/* RENDER CAMPO ADOLESCENTE */ }
        const renderCampoAdolescente = (tipoCampo, selectAdolescenteShow, setSelectAdolescenteShow) => (
            <>
                {(tipoCampo === 'drop_select') && (
                    <div className="dropdown w-100">
                        <input
                            className={`form-control border-0 ${errorAdolescente ? 'is-invalid' : formData.adolescente_Nome ? 'is-valid' : ''}`}
                            type="text"
                            id="adolescente_Nome"
                            name="adolescente_Nome"
                            value={formData.adolescente_Nome || ''}
                            onChange={handleChange}
                            onFocus={handleFocus}
                            autoComplete="off"
                            required={true}
                            aria-expanded={selectAdolescenteShow}
                            onClick={() => {
                                setSelectAdolescenteShow(true);
                                setListAdolescentes(guardaAdolescentes);
                            }}
                        />
                        <div
                            ref={adolescenteRef}
                            className={`dropdown-menu w-100  border border-1 border-top-0 border-dark mt-2 ${selectAdolescenteShow ? 'show' : ''}`}
                        >
                            <div className="m-0 p-0" style={{ height: "300px", overflowY: "auto", overflowX: "hidden" }}>
                                {listAdolescentes.map((list_adolescentes, index) => (
                                    <React.Fragment key={index}>
                                        <input
                                            type="radio"
                                            className="btn-check"
                                            name="adolescente-radio"
                                            id={`adolescente-option${index}`}
                                            autoComplete="off"
                                            value={list_adolescentes.Nome}
                                            data-campo="adolescente_Nome"
                                            checked={formData.adolescente_Nome === list_adolescentes.Nome}
                                            onChange={handleClick}
                                        />
                                        <label
                                            className="btn w-100 text-start"
                                            htmlFor={`adolescente-option${index}`}
                                        >
                                            {list_adolescentes.Nome}
                                        </label>
                                    </React.Fragment>
                                ))}
                            </div>
                        </div>
                    </div>
                )}
            </>
        );

        if (isLoading) {
            return (
                <div className="d-flex justify-content-center align-items-center p-5 m-5">
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Carregando...</span>
                    </div>
                </div>
            );
        }

        return (
            <div className="ms-3 me-3">
                <div>
                    {debugMyPrint ? `Altura: ${altura}px | Largura: ${largura}px` : ''}
                </div>
                {/* Fomulário Prontuário */}
                <form
                    className="needs-validation" noValidate
                    onSubmit={(e) => {
                        e.preventDefault();
                        submitAllForms(`filtro-${origemForm}`, formData);
                    }}>
                    {formData.id !== 'erro' && (
                        <input
                            data-api={`filtro-${origemForm}`}
                            type="hidden"
                            id="id"
                            name="id"
                            value={formData.id || ''}
                            onChange={handleRadioChange}
                            required
                        />
                    )}
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="token_csrf"
                        name="token_csrf"
                        value={formData.token_csrf || token_csrf}
                        onChange={handleRadioChange}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="json"
                        name="json"
                        value={formData.json || json}
                        onChange={handleRadioChange}
                        required
                    />
                </form>
                <div className="col-12 col-sm-12">
                    <div className="card mb-4">
                        <div className="card-body">
                            {/* Profissional */}
                            <div className="row">
                                <div className="col-12 col-sm-6">
                                    <form
                                        className="needs-validation" noValidate
                                        onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`, formData);
                                        }}>
                                        <div style={formGroupStyle}>
                                            <label htmlFor="profissional_id"
                                                style={formLabelStyle}
                                                className="form-label">
                                                Funcionário<strong style={requiredField}>*</strong>
                                            </label>
                                            {(checkWordInArray(getURI, 'consultar')) && (
                                                <div className="p-2">
                                                    {formData.Nome || 'Funcionário selecionado'}
                                                </div>
                                            )}
                                            {(!checkWordInArray(getURI, 'consultar')) && (
                                                <>
                                                    {/* CAMPO FUNCIONARIO */}
                                                    {renderCampoFuncionario('drop_select', selectFuncionarioShow, setSelectFuncionarioShow)}
                                                </>
                                            )}
                                        </div>
                                    </form>
                                </div>
                                <div className="col-12 col-sm-6">

                                    {/* Adolescente */}
                                    <form
                                        className="needs-validation" noValidate
                                        onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`, formData);
                                        }}>
                                        <div style={formGroupStyle}>
                                            <label htmlFor="adolescente_id"
                                                style={formLabelStyle}
                                                className="form-label">
                                                Adolescente
                                                {checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'atualizar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                            </label>
                                            {checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'atualizar') ? (
                                                <div className="p-2">
                                                    {formData.adolescente_Nome || 'Adolescente selecionado'}
                                                </div>
                                            ) : (
                                                <>
                                                    {renderCampoAdolescente('drop_select', selectAdolescenteShow, setSelectAdolescenteShow)}
                                                </>
                                            )}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* SIM/NAO */}
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="row">
                                <div className="col-12 col-sm-6">
                                    {/* SOCIO EDUCATIVA */}
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_MedidasSocioEducativas" style={formLabelStyle} className="form-label">
                                            Cumpre medidas socioeducativas?<strong style={requiredField}>*</strong>
                                        </label>
                                        <div>
                                            {/* CAMPO / DESCRIÇÃO */}
                                            <div className="row g-1">
                                                <div className="col-12 col-sm-3">
                                                    <div className="border-0" style={{ height: '75px' }}>
                                                        {renderQstSocioEducativa()}
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-9">
                                                    {/* DESCRIÇÃO */}
                                                    <div className="border-0" style={{ height: '150px' }}>
                                                        {(formData.prontuario_MedidasSocioEducativas === 'Y') && (
                                                            <textarea
                                                                className="form-control m-0 p-2 w-100 h-100"
                                                                id="prontuario_MedidasSocioEducativasDesc"
                                                                name="prontuario_MedidasSocioEducativasDesc"
                                                                placeholder="Informe"
                                                                value={formData.prontuario_MedidasSocioEducativasDesc || ''}
                                                                onChange={handleChange}
                                                                readOnly={checkWordInArray(getURI, 'consultar')}
                                                                required>
                                                            </textarea>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-12 col-sm-6">
                                    {/* TRATAMENTO DE DROGAS */}
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_UsodeDrogas" style={formLabelStyle} className="form-label">
                                            Em tratamento de Drogas?<strong style={requiredField}>*</strong>
                                        </label>
                                        <div>
                                            {/* CAMPO / DESCRIÇÃO */}
                                            <div className="row g-1">
                                                <div className="col-12 col-sm-3">
                                                    <div className="border-0" style={{ height: '75px' }}>
                                                        {renderQstTratamentoDrogas()}
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-9">
                                                    <div className="border-0" style={{ height: '150px' }}>
                                                        {(formData.prontuario_UsodeDrogas === 'Y') && (
                                                            <textarea
                                                                className="form-control m-0 p-2 w-100 h-100"
                                                                id="prontuario_UsoDrogasDesc"
                                                                name="prontuario_UsoDrogasDesc"
                                                                placeholder="Informe"
                                                                value={formData.prontuario_UsoDrogasDesc || ''}
                                                                onChange={handleChange}
                                                                readOnly={checkWordInArray(getURI, 'consultar')}
                                                                required>
                                                            </textarea>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-12 col-sm-6">
                                    {/* CADASTRO ÚNICO */}
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_CadUnico" style={formLabelStyle} className="form-label">
                                            Tem Cadastro Único?<strong style={requiredField}>*</strong>
                                        </label>
                                        <div>
                                            {/* CAMPO / DESCRIÇÃO */}
                                            <div className="row g-1">
                                                <div className="col-12 col-sm-3">
                                                    <div className="border-0" style={{ height: '75px' }}>
                                                        {renderQstCadUnico()}
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-9">
                                                    <div className="border-0" style={{ height: '150px' }}>
                                                        {(formData.prontuario_CadUnico === 'Y') && (
                                                            <textarea
                                                                className="form-control m-0 p-2 w-100 h-100"
                                                                id="prontuario_CadUnicoProgramaSocial"
                                                                name="prontuario_CadUnicoProgramaSocial"
                                                                placeholder="Informe"
                                                                value={formData.prontuario_CadUnicoProgramaSocial || ''}
                                                                onChange={handleChange}
                                                                readOnly={checkWordInArray(getURI, 'consultar')}
                                                                required>
                                                            </textarea>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-12 col-sm-6">
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_EncaminhamentoOrgao" style={formLabelStyle} className="form-label">
                                            Encaminhamento de órgãos?<strong style={requiredField}>*</strong>
                                        </label>
                                        <div>
                                            {/* CAMPO / DESCRIÇÃO */}
                                            <div className="row g-1">
                                                <div className="col-12 col-sm-3">
                                                    <div className="border-0" style={{ height: '75px' }}>
                                                        {renderQstEncaminhamentoOrgao()}
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-9">
                                                    <div className="border-0" style={{ height: '150px' }}>
                                                        {(formData.prontuario_EncaminhamentoOrgao === 'Y') && (
                                                            <textarea
                                                                className="form-control m-0 p-2 w-100 h-100"
                                                                id="prontuario_EncaminhamentoOrgaoDesc"
                                                                name="prontuario_EncaminhamentoOrgaoDesc"
                                                                placeholder="Informe"
                                                                value={formData.prontuario_EncaminhamentoOrgaoDesc || ''}
                                                                onChange={handleChange}
                                                                readOnly={checkWordInArray(getURI, 'consultar')}
                                                                required>
                                                            </textarea>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-12 col-sm-6">
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_Deficiencia" style={formLabelStyle} className="form-label">
                                            Adolescente com Deficiência?<strong style={requiredField}>*</strong>
                                        </label>
                                        <div>
                                            {/* CAMPO / DESCRIÇÃO */}
                                            <div className="row g-1">
                                                <div className="col-12 col-sm-3">
                                                    <div className="border-0" style={{ height: '75px' }}>
                                                        {renderQstDeficiencia()}
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-9">
                                                    <div className="border-0" style={{ height: '150px' }}>
                                                        {(formData.prontuario_Deficiencia === 'Y') && (
                                                            <textarea
                                                                className="form-control m-0 p-2 w-100 h-100"
                                                                id="prontuario_DeficienciaDesc"
                                                                name="prontuario_DeficienciaDesc"
                                                                placeholder="Informe"
                                                                value={formData.prontuario_DeficienciaDesc || ''}
                                                                onChange={handleChange}
                                                                readOnly={checkWordInArray(getURI, 'consultar')}
                                                                required>
                                                            </textarea>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-12 col-sm-6">
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_NecesMediador" style={formLabelStyle} className="form-label">
                                            Necessidade de mediador?<strong style={requiredField}>*</strong>
                                        </label>
                                        <div>
                                            {/* CAMPO / DESCRIÇÃO */}
                                            <div className="row g-1">
                                                <div className="col-12 col-sm-3">
                                                    <div className="border-0" style={{ height: '75px' }}>
                                                        {renderQstNecessidadeMediador()}
                                                    </div>
                                                </div>
                                                <div className="col-12 col-sm-9">
                                                    <div className="border-0" style={{ height: '150px' }}>
                                                        {(formData.prontuario_NecesMediador === 'Y') && (
                                                            <textarea
                                                                className="form-control m-0 p-2 w-100 h-100"
                                                                id="prontuario_NecesMediadorTipoFamiliar"
                                                                name="prontuario_NecesMediadorTipoFamiliar"
                                                                placeholder="Informe"
                                                                value={formData.prontuario_NecesMediadorTipoFamiliar || ''}
                                                                onChange={handleChange}
                                                                readOnly={checkWordInArray(getURI, 'consultar')}
                                                                required>
                                                            </textarea>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {checkWordInArray(getURI, 'atualizar') && (
                                <>
                                    <div className="row">
                                        <div className="col-12 col-sm-6">
                                            <div style={formGroupStyle}>
                                                <label
                                                    htmlFor="prontuario_Diagnostico" style={formLabelStyle} className="form-label">
                                                    Diagnóstico Psicológico?<strong style={requiredField}>*</strong>
                                                </label>
                                                <div>
                                                    {/* CAMPO / DESCRIÇÃO */}
                                                    <div className="row g-1">
                                                        <div className="col-12 col-sm-3">
                                                            <div className="border-0" style={{ height: '75px' }}>
                                                                {renderQstDiagnosticoPsicologico()}
                                                            </div>
                                                        </div>
                                                        <div className="col-12 col-sm-9">
                                                            <div className="border-0" style={{ height: '150px' }}>
                                                                {(formData.prontuario_Diagnostico === 'Y') && (
                                                                    <textarea
                                                                        className="form-control m-0 p-2 w-100 h-100"
                                                                        id="prontuario_DiagnosticoDesc"
                                                                        name="prontuario_DiagnosticoDesc"
                                                                        placeholder="Informe"
                                                                        value={formData.prontuario_Diagnostico || ''}
                                                                        onChange={handleChange}
                                                                        readOnly={checkWordInArray(getURI, 'consultar')}
                                                                        required>
                                                                    </textarea>
                                                                )}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-6">
                                            <div style={formGroupStyle}>
                                                <label
                                                    htmlFor="prontuario_RendaFamiliar" style={formLabelStyle} className="form-label">
                                                    Renda Familiar?<strong style={requiredField}>*</strong>
                                                </label>
                                                <div>
                                                    {/* CAMPO / DESCRIÇÃO */}
                                                    <div className="row g-1">
                                                        <div className="col-12 col-sm-3">
                                                            <div className="border-0" style={{ height: '75px' }}>
                                                                {renderQstRenderFamiliar()}
                                                            </div>
                                                        </div>
                                                        <div className="col-12 col-sm-9">
                                                            <div className="border-0" style={{ height: '150px' }}>
                                                                {(formData.prontuario_RendaFamiliar === 'Y') && (
                                                                    <textarea
                                                                        className="form-control m-0 p-2 w-100 h-100"
                                                                        id="prontuario_RendaFamiliarDesc"
                                                                        name="prontuario_RendaFamiliarDesc"
                                                                        placeholder="Informe"
                                                                        value={formData.prontuario_RendaFamiliar || ''}
                                                                        onChange={handleChange}
                                                                        readOnly={checkWordInArray(getURI, 'consultar')}
                                                                        required>
                                                                    </textarea>
                                                                )}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-6">
                                            <div style={formGroupStyle}>
                                                <label
                                                    htmlFor="prontuario_ReferenciadoNaRede" style={formLabelStyle} className="form-label">
                                                    Referência na Rede?<strong style={requiredField}>*</strong>
                                                </label>
                                                <div>
                                                    {/* CAMPO / DESCRIÇÃO */}
                                                    <div className="row g-1">
                                                        <div className="col-12 col-sm-3">
                                                            <div className="border-0" style={{ height: '75px' }}>
                                                                {renderQstReferenciaRede()}
                                                            </div>
                                                        </div>
                                                        <div className="col-12 col-sm-9">
                                                            <div className="border-0" style={{ height: '150px' }}>
                                                                {(formData.prontuario_ReferenciadoNaRede === 'Y') && (
                                                                    <textarea
                                                                        className="form-control m-0 p-2 w-100 h-100"
                                                                        id="prontuario_ReferenciadoNaRedeDesc"
                                                                        name="prontuario_ReferenciadoNaRedeDesc"
                                                                        placeholder="Informe"
                                                                        value={formData.prontuario_ReferenciadoNaRedeDesc || ''}
                                                                        onChange={handleChange}
                                                                        readOnly={checkWordInArray(getURI, 'consultar')}
                                                                        required>
                                                                    </textarea>
                                                                )}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-6">
                                            <div style={formGroupStyle}>
                                                <label
                                                    htmlFor="prontuario_TipoFamiliar" style={formLabelStyle} className="form-label">
                                                    Tipo Familiar?<strong style={requiredField}>*</strong>
                                                </label>
                                                <div>
                                                    {/* CAMPO / DESCRIÇÃO */}
                                                    <div className="row g-1">
                                                        <div className="col-12 col-sm-3">
                                                            <div className="border-0" style={{ height: '75px' }}>
                                                                {renderQstTipoFamiliar()}
                                                            </div>
                                                        </div>
                                                        <div className="col-12 col-sm-9">
                                                            <div className="border-0" style={{ height: '150px' }}>
                                                                {formData.prontuario_TipoFamiliar === 'Y' && (
                                                                    <select
                                                                        className="form-select m-0 p-2 w-100 h-100"
                                                                        id="prontuario_TipoFamiliarDesc"
                                                                        name="prontuario_TipoFamiliarDesc"
                                                                        value={formData.prontuario_TipoFamiliarDesc || ''}
                                                                        onChange={handleChange}
                                                                        readOnly={checkWordInArray(getURI, 'consultar')}
                                                                        required
                                                                    >
                                                                        <option value="">Selecione o tipo familiar</option>
                                                                        <option value="nuclear">Nuclear</option>
                                                                        <option value="extensa">Extensa</option>
                                                                        <option value="monoparental">Monoparental</option>
                                                                        <option value="adotiva">Adotiva</option>
                                                                        <option value="homoparental">Homoparental</option>
                                                                        <option value="reconstituída">Reconstituída</option>
                                                                    </select>
                                                                )}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-6">
                                            <div style={formGroupStyle}>
                                                <label
                                                    htmlFor="prontuario_ParticipacaoProgramasSociais" style={formLabelStyle} className="form-label">
                                                    Participação em Programas Sociais?<strong style={requiredField}>*</strong>
                                                </label>
                                                <div>
                                                    {/* CAMPO / DESCRIÇÃO */}
                                                    <div className="row g-1">
                                                        <div className="col-12 col-sm-3">
                                                            <div className="border-0" style={{ height: '75px' }}>
                                                                {renderQstParticipacaoProgramasSociais()}
                                                            </div>
                                                        </div>
                                                        <div className="col-12 col-sm-9">
                                                            <div className="border-0" style={{ height: '150px' }}>
                                                                {(formData.prontuario_ParticipacaoProgDesc === 'Y') && (
                                                                    <textarea
                                                                        className="form-control m-0 p-2 w-100 h-100"
                                                                        id="prontuario_ParticipacaoProgDesc"
                                                                        name="prontuario_ParticipacaoProgDesc"
                                                                        placeholder="Informe"
                                                                        value={formData.prontuario_ParticipacaoProgDesc || ''}
                                                                        onChange={handleChange}
                                                                        readOnly={checkWordInArray(getURI, 'consultar')}
                                                                        required>
                                                                    </textarea>
                                                                )}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </>
                            )}
                            <div className="row">
                                <div className="col-12 col-sm-12">
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_Vulnerabilidade" style={formLabelStyle} className="form-label">
                                            Grau de Vulnerabilidade<strong style={requiredField}>*</strong>
                                        </label>
                                        <div>
                                            {/* CAMPO / DESCRIÇÃO */}
                                            <div className="row g-1">
                                                <div className="col-12 col-sm-9">
                                                    <div className="border-0" style={{ minHeight: '40px' }}>
                                                        {renderQstVulnerabilidade()}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="card mb-4 p-2">
                        <div className="row">
                            <div className="col-12 col-sm-4">
                                {/* Pontuação Idade */}
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="ptsIdade"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Vulnerabilidade Idade
                                    </label>
                                    <input
                                        data-api="form-prontuario"
                                        type="number"
                                        value={ptsIdade || 0}
                                        className="form-control"
                                        style={formControlStyle}
                                        id="ptsIdade"
                                        name="ptsIdade"
                                        aria-describedby="ptsIdade"
                                        readOnly
                                        required
                                    />
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                {/* Pontuação escolaridade */}
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="ptsEscolaridade"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Pontuação por escolaridade
                                    </label>
                                    <input
                                        data-api="form-prontuario"
                                        type="number"
                                        value={ptsEscolaridade || 0}
                                        className="form-control"
                                        style={formControlStyle}
                                        id="ptsEscolaridade"
                                        name="ptsEscolaridade"
                                        aria-describedby="ptsEscolaridade"
                                        readOnly
                                        required
                                    />
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                {/* Pontuação Grau de Vulnerabilidade */}
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="prontuario_PontuacaoVulnerabilidade"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Grau de Vulnerabilidade
                                    </label>
                                    <input
                                        data-api="form-prontuario"
                                        type="number"
                                        value={formData.prontuario_PontuacaoVulnerabilidade || ptsVulnerabilidade}
                                        className="form-control"
                                        style={formControlStyle}
                                        id="prontuario_PontuacaoVulnerabilidade"
                                        name="prontuario_PontuacaoVulnerabilidade"
                                        aria-describedby="prontuario_PontuacaoVulnerabilidade"
                                        readOnly
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-12">
                                {/* Pontuação total */}
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="prontuario_PontuacaoTotal"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Pontuação total
                                    </label>
                                    <input
                                        data-api="form-prontuario"
                                        type="number"
                                        value={formData.prontuario_PontuacaoTotal || ptsTotal}
                                        className="form-control"
                                        style={formControlStyle}
                                        id="prontuario_PontuacaoTotal"
                                        name="prontuario_PontuacaoTotal"
                                        aria-describedby="prontuario_PontuacaoTotal"
                                        readOnly
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Botão de voltar e salvar 
                {
                    !checkWordInArray(getURI, 'detalhar') && (
                        <div className="ms-3 me-3">
                            <div className="row">
                                <div className="col-12">
                                    <form
                                        className="was-validated d-flex justify-content-between align-items-center"
                                        onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`, formData);
                                        }}
                                    >
                                        <div className="d-flex gap-2">
                                            
                                            {!checkWordInArray(getURI, 'alocarfuncionario') && (
                                                <a
                                                    className="btn btn-danger"
                                                    href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/exibir`}
                                                    role="button"
                                                >
                                                    Voltar
                                                </a>
                                            )}
                                            
                                            {!checkWordInArray(getURI, 'consultar') && (
                                                <input
                                                    className="btn btn-success"
                                                    type="submit"
                                                    value="Salvar"
                                                />
                                            )}
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    )
                } */}

                {
                    typeof AppJson !== "undefined" ? (
                        <div>

                            <AppJson
                                parametros={parametros}
                                dbResponse={formData}
                            />

                        </div>
                    ) : (
                        <div>
                            <p className="text-danger">AppJson não lacançado.</p>
                        </div>
                    )
                }

                {/* RENDER COMANDOS E RESUMO */}
                {renderComandosResumo()}

                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message}
                    modalId={'modal_form'}
                />

            </div >
        );
    };
</script>