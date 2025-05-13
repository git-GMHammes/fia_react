<script type="text/babel">
    const AppForm2 = ({ parametros = {} }) => {

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
        const api_get_exibir_adolescente = parametros.api_get_exibir_adolescente || '';
        const api_get_exibir_profissional = parametros.api_get_exibir_profissional || '';
        const api_post_filtrar_adolescente = parametros.api_post_filtrar_adolescente || '';
        const api_post_filtrar_profissional = parametros.api_post_filtrar_profissional || '';
        const api_get_atualizar_prontuariopsicosocial = parametros.api_get_atualizar_prontuariopsicosocial || '';
        const api_post_cadastrar_prontuariopsicosocial = parametros.api_post_cadastrar_prontuariopsicosocial || '';

        // Variáveis da API
        const [profissionais, setProfissionais] = React.useState([]);
        const [adolescentes, setAdolescentes] = React.useState([]);
        const [prontuario, setProntuarios] = React.useState([]);

        // Variáveis Uteis
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const [pagination, setPagination] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [idade, setIdade] = React.useState([0, 0]);
        const [error, setError] = React.useState(null);

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

        const pontuacaoMap = {
            "extremamente-vulneravel": 40,
            "muito-vulneravel": 25,
            "vulneravel": 10
        };

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

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('--------------------');
            console.log('handleRadioChange');
            console.log('--------------------');
            console.log('NOME/VALOR', name, value);

            // Forçar atualização imediata
            setFormData((prevFormData) => {
                const updatedData = {
                    ...prevFormData,
                    [name]: value
                };

                // Se necessário, também atualize outros estados dependentes
                // como por exemplo, no caso de pontuações
                if (name === "prontuario_referenciado_na_rede") {
                    updatedData.prontuario_PontuacaoTotal = pontuacaoMap[value] || 0;
                }

                return updatedData;
            });
        };

        // Função handleChange simplificada
        const handleRadioChange = (event) => {
            const { name, value } = event.target;

            console.log('--------------------');
            console.log('handleRadioChange');
            console.log('--------------------');
            console.log('NOME/VALOR', name, value);

            /// Abordagem mais direta
            event.target.checked = true;

            // Atualizar o estado de forma assíncrona
            setTimeout(() => {
                setFormData((prevFormData) => {
                    return {
                        ...prevFormData,
                        [name]: value
                    };
                });
            }, 0);
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));

            atualizaVulnerabilidade();
        };

        {/* formData */ }
        const [formData, setFormData] = React.useState({
            //
            token_csrf: token_csrf,
            json: '1',
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
                prontuario_EncaminhamentoOrgao: 'Grau de Vulnerabilidade',
                prontuario_Deficiencia: 'Deficie_ncia em branco',
                prontuario_NecesMediador: 'Necessidade de mediador em branco',
                prontuario_CadUnico: 'Cadastro Único',
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

        // console.log('URL fetch:', base_url + api_get_atualizar_prontuariopsicosocial);
        // console.log('base_url:', base_url);
        // console.log('api_get_atualizar_prontuariopsicosocial:', api_get_atualizar_prontuariopsicosocial);

        // Fetch para obter os Profissionais
        const fetchProfissionais = async () => {
            const url = base_url + api_post_filtrar_profissional;
            // console.log('-------------------------------------');
            // console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
            // console.log('url :: ', url);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                const data = await response.json();
                // console.log('Profissionais :: ', data);

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setProfissionais(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Profissionais: ' + error.message);
            }
        };

        // Fetch para obter os Adolescentes
        const fetchAdolescentes = async () => {
            try {
                const response = await fetch(base_url + api_post_filtrar_adolescente, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setAdolescentes(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Adolescentes: ' + error.message);
            }
        };

        // Fetch para obter os Prontuário
        const fetchProntuarios = async () => {
            const url = base_url + api_get_atualizar_prontuariopsicosocial;
            console.log('-------------------------------------');
            console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
            console.log('url :: ', url);

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    console.log('fetchProntuarios :: ', data.result.dbResponse[0]);

                    setFormData((prev) => ({
                        ...prev,
                        ...data.result.dbResponse[0]
                    }));
                }
            } catch (error) {
                console.error('Erro ao carregar Prontuários: ' + error.message);
            }
        };

        const fetchGetAdolescentes = async (id) => {
            try {
                const response = await fetch(base_url + api_get_exibir_adolescente + '/' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const adolescente = data.result.dbResponse[0];

                    setFormData((prev) => {
                        const novoFormData = {
                            ...prev,
                            "adolescente_id": id,
                            "adolescente_Nome": adolescente.Nome,
                            "Nascimento": adolescente.Nascimento,
                            "Escolaridade": adolescente.Escolaridade
                        };

                        return novoFormData;
                    });
                } else {
                    return false;
                }
            } catch (error) {
                setError('Erro ao carregar Adolescentes: ' + error.message);
            }
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

        {/* CONSULTAR ESCOLARIDADE */ }
        React.useEffect(() => {
            if (formData.Nascimento || formData.Escolaridade) {
                if (!checkWordInArray(getURI, 'consultar')) {
                    atualizaVulnerabilidade();
                }
            }
        }, [formData.Nascimento, formData.Escolaridade]);

        {/* TOTAL */ }
        React.useEffect(() => {
            if (!checkWordInArray(getURI, 'consultar')) {
                const totalPontuacao =
                    (pontuacaoMap[formData.prontuario_referenciado_na_rede] || 0) +
                    (formData.PontuacaoIdade || 0) +
                    (formData.PontuacaoEscolaridade || 0);

                setFormData((prevFormData) => ({
                    ...prevFormData,
                    prontuario_PontuacaoTotal: totalPontuacao
                }));
            }
        }, [formData.prontuario_referenciado_na_rede, formData.PontuacaoIdade, formData.PontuacaoEscolaridade]);

        // Função para identificar o usuário
        React.useEffect(() => {
            // console.log('-------------------------);
            // console.log('src/app/Views/fia/ptpa/prontuario/AppForm2.php');
            // console.log('React.useEffect - Sessão');
            if (
                user_session &&
                user_session.CargoFuncaoId === '5' &&
                user_session.PerfilId === '5' &&
                checkWordInArray(getURI, 'cadastrar') &&
                formData.profissional_id === null
            ) {
                setFormData((prev) => ({
                    ...prev,
                    profissional_id: user_session.profissional_id,
                    profissional_Nome: user_session.Nome
                }));
            }
        }, []);

        const calcularVulnerabilidadeIdade = (idade) => {
            if (idade >= 204 && idade <= 210) {
                setFormData((prevFormData) => ({
                    ...prevFormData,
                    "PontuacaoIdade": 25
                }));

                return true
            }
            if (idade >= 198 && idade <= 201) {
                setFormData((prevFormData) => ({
                    ...prevFormData,
                    "PontuacaoIdade": 20
                }));

                return true
            }
            if (idade >= 192 && idade <= 197) {
                setFormData((prevFormData) => ({
                    ...prevFormData,
                    "PontuacaoIdade": 10
                }));

                return true
            }
        };

        const calcularVulnerabilidadeEscolaridade = (escolaridade) => {
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

            setFormData((prevFormData) => ({
                ...prevFormData,
                "PontuacaoEscolaridade": pontuacao
            }));
        };

        const calcularMesesDeVida = (dataNascimento) => {
            if (!dataNascimento) return 0; // Retorna 0 se não houver data válida

            const dataNasc = new Date(dataNascimento);
            const hoje = new Date();

            const anos = hoje.getFullYear() - dataNasc.getFullYear();
            const meses = hoje.getMonth() - dataNasc.getMonth();

            const totalMeses = anos * 12 + meses;

            return totalMeses;
        };

        const atualizaVulnerabilidade = () => {

            const calcAdolescente = async (id) => {
                if (formData.adolescente_id !== null) {
                    fetchGetAdolescentes(id);
                }
            };

            calcAdolescente(formData.adolescente_id);

            const totalMeses = calcularMesesDeVida(formData.Nascimento);

            if (totalMeses > 0) {
                calcularVulnerabilidadeIdade(totalMeses);
            }
            if (formData.Escolaridade) {
                calcularVulnerabilidadeEscolaridade(formData.Escolaridade);
            }
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
                            onChange={handleRadioChange} />
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
            );
        }

        {/* TRATAMENTO DE DROGAS */ }
        const renderQstTratamentoDrogas = () => {
            return (
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
            );
        }

        {/* CADASTRO UNICO*/ }
        const renderQstCadUnico = () => {
            return (
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
            );
        }

        {/* ENCAMINHAMENTO ÓRGÃOS */ }
        const renderQstEncaminhamentoOrgao = () => {
            return (
                <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'p-2'} `}>
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
                </div>
            );
        }

        {/* DEFICIÊNCIA */ }
        const renderQstDeficiencia = () => {
            return (
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
            );
        }

        {/* NECESSIDADE MEDIADOR */ }
        const renderQstNecessidadeMediador = () => {
            return (
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
            );
        }

        {/* GRAU VULNERABILIADE */ }
        const renderQstVulnerabilidade = () => {
            return (
                <div className={`${largura < 415 ? 'd-flex justify-content-evenly p-4' : 'd-flex gap-3 p-2'}`} style={{ flexWrap: 'nowrap' }}>
                    <div className="form-check">
                        <input
                            data-api="form-prontuario"
                            type="radio"
                            className="form-check-input"
                            id="prontuario_referenciado_na_red1"
                            name="prontuario_referenciado_na_rede"
                            value="extremamente-vulneravel"
                            checked={formData.prontuario_referenciado_na_rede === "extremamente-vulneravel"}
                            onChange={handleRadioChange}
                        />
                        <label className="form-check-label" htmlFor="prontuario_referenciado_na_red1">
                            Extremamente Vulnerável
                        </label>
                    </div>
                    <div className="form-check">
                        <input
                            data-api="form-prontuario"
                            type="radio"
                            className="form-check-input"
                            id="prontuario_referenciado_na_red2"
                            name="prontuario_referenciado_na_rede"
                            value="muito-vulneravel"
                            checked={formData.prontuario_referenciado_na_rede === "muito-vulneravel"}
                            onChange={handleRadioChange}
                        />
                        <label className="form-check-label" htmlFor="prontuario_referenciado_na_red2">
                            Muito Vulnerável
                        </label>
                    </div>
                    <div className="form-check">
                        <input
                            data-api="form-prontuario"
                            type="radio"
                            className="form-check-input"
                            id="prontuario_referenciado_na_red3"
                            name="prontuario_referenciado_na_rede"
                            value="vulneravel"
                            checked={formData.prontuario_referenciado_na_rede === "vulneravel"}
                            onChange={handleRadioChange}
                        />
                        <label className="form-check-label" htmlFor="prontuario_referenciado_na_red3">
                            Vulnerável
                        </label>
                    </div>
                </div>
            );
        }

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
                    {`Altura: ${altura}px | Largura: ${largura}px`}
                </div>
                {/* Fomulário Prontuário */}
                <form className="was-validated" onSubmit={(e) => {
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
                                    <form className="was-validated" onSubmit={(e) => {
                                        e.preventDefault();
                                        submitAllForms(`filtro-${origemForm}`, formData);
                                    }}>
                                        <div style={formGroupStyle}>
                                            <label htmlFor="profissional_id"
                                                style={formLabelStyle}
                                                className="form-label">
                                                Funcionário<strong style={requiredField}></strong>
                                            </label>
                                            <select
                                                id="profissional_id"
                                                name="profissional_id"
                                                value={formData.profissional_id || ''}
                                                onChange={handleRadioChange}
                                                className="form-select"
                                                required
                                                readOnly={checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'atualizar')}
                                            >
                                                <option value="">Seleção Nula</option>
                                                {profissionais.map((profissional) => (
                                                    <option key={profissional.id} value={profissional.id}>
                                                        {profissional.Nome}
                                                    </option>
                                                ))}
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div className="col-12 col-sm-6">

                                    {/* Adolescente */}
                                    <form className="was-validated" onSubmit={(e) => {
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
                                                <select
                                                    data-api={`filtro-${origemForm}`}
                                                    id="adolescente_id"
                                                    name="adolescente_id"
                                                    value={formData.adolescente_id || ''}
                                                    onChange={(event) => {
                                                        handleChange(event);
                                                        fetchGetAdolescentes(event.target.value);
                                                    }}
                                                    style={formControlStyle}
                                                    className="form-select"
                                                    aria-label="Default select"
                                                    required
                                                >
                                                    <option value="">Seleção Nula</option>
                                                    {adolescentes.map((adolescente_select) => (
                                                        <option key={adolescente_select.id} value={(adolescente_select.id)}>
                                                            {adolescente_select.Nome}
                                                        </option>
                                                    ))}
                                                </select>
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
                                            Cumpre medidas socioeducativas?
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
                                            Em tratamento de Drogas?
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
                                            Tem Cadastro Único?
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
                                                                id="prontuario_CadUnicoDesc"
                                                                name="prontuario_CadUnicoDesc"
                                                                placeholder="Informe"
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
                                            Encaminhamento de órgãos?
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
                                            Adolescente com Deficiência?
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
                                            Necessidade de mediador?
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
                                                                id="prontuario_NecesMediadorDesc"
                                                                name="prontuario_NecesMediadorDesc"
                                                                placeholder="Informe"
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
                                <div className="col-12 col-sm-12">
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_EncaminhamentoOrgao" style={formLabelStyle} className="form-label">
                                            Grau de Vulnerabilidade
                                        </label>
                                        <div>
                                            {/* CAMPO / DESCRIÇÃO */}
                                            <div className="row g-1">
                                                <div className="col-12 col-sm-9">
                                                    <div className="border-0" style={{ height: '40px' }}>
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
                    <div className="card mb-4">
                        <div className="card-body">
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
                                    value={formData.prontuario_PontuacaoTotal || 0}
                                    onChange={handleRadioChange}
                                    className="form-control"
                                    style={formControlStyle}
                                    id="prontuario_PontuacaoTotal"
                                    name="prontuario_PontuacaoTotal"
                                    aria-describedby="prontuario_PontuacaoTotal"
                                    disabled
                                    required
                                />
                            </div>
                            {/* Pontuação total */}
                        </div>
                    </div>
                </div>

                {/* Botão de voltar e salvar */}
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
                                            {/* Botão Voltar */}
                                            {!checkWordInArray(getURI, 'alocarfuncionario') && (
                                                <a
                                                    className="btn btn-secondary"
                                                    href={`${base_url}index.php/fia/ptpa/prontuariopsicosocial/endpoint/exibir`}
                                                    role="button"
                                                >
                                                    Voltar
                                                </a>
                                            )}

                                            {/* Botão Salvar */}
                                            {!checkWordInArray(getURI, 'consultar') && (
                                                <input
                                                    className="btn btn-primary"
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
                }

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

                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message} modalId={'modal_form'}

                />

            </div >
        );
    };
</script>