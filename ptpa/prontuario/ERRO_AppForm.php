<script type="text/babel">
    const AppForm = ({ parametros = {} }) => {

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

        //Base Cadastro Prontuario
        const api_get_exibir_adolescente = parametros.api_get_exibir_adolescente || '';
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

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));
        };

        const handleRadioChange = (event) => {
            const { name, value } = event.target;

            setFormData((prevFormData) => ({
                ...prevFormData,
                [name]: value,
                [`${name}DetailsVisible`]: value === "Y",
                ...(name === "prontuario_referenciado_na_rede" && { prontuario_pontuacao_total: pontuacaoMap[value] || 0 })
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

        // Decalre Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            //
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            profissional_id: null,
            profissional_Nome: null,
            adolescente_id: null,
            adolescente_Nome: null,
            prontuario_medidas_socioeducativas: null,
            prontuario_uso_de_drogas: null,
            prontuario_deficiencia: null,
            prontuario_necessita_mediador: null,
            prontuario_cad_unico: null,
            prontuario_referenciado_na_rede: null,
            prontuario_pontuacao_total: 0,
            PontuacaoIdade: 0,
            PontuacaoEscolaridade: 0,
            created_at: null,
            updated_at: null,
            deleted_at: null,
        });
        // console.log('[DEBUG] Token enviado:', formData.token_csrf);
        //console.log('[DEBUG] Dados enviados:', formData);

        const submitAllForms = async (filtro) => {
            const setData = formData;
            let data = '';
            let dbResponse = [];
            let response = '';

            // Mapeamento dos campos com nomes amigáveis
            const camposObrigatorios = {
                // profissional_id: 'Selecione um profissional',
                adolescente_id: 'Selecione um adolescente',
                prontuario_medidas_socioeducativas: 'Medidas socioeducativas em branco',
                prontuario_uso_de_drogas: 'Uso de Drogas em branco',
                prontuario_cad_unico: 'Encaminhamento de órgãos',
                prontuario_deficiencia: 'Deficie_ncia em branco',
                prontuario_necessita_mediador: 'Necessidade de mediador em branco',
                prontuario_referenciado_na_rede: 'Grau de Vulnerabilidade',
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
                        Prontuário psicossocial registrado no sistema com pontuação total de <b>${formData.prontuario_pontuacao_total}</b> pontos!`
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

        React.useEffect(() => {
            // Função para carregar todos os dados necessários
            const loadData = async () => {
                // console.log('loadData chamado');

                try {
                    // Chama as funções de fetch para carregar os dados
                    fetchProntuarios();
                    fetchProfissionais();
                    fetchAdolescentes();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // console.log('URL fetch:', base_url + api_get_atualizar_prontuariopsicosocial);
        // console.log('base_url:', base_url);
        // console.log('api_get_atualizar_prontuariopsicosocial:', api_get_atualizar_prontuariopsicosocial);

        // Fetch para obter os Prontuário
        const fetchProntuarios = async () => {
            // console.log('fetchProntuarios chamado');
            // console.log('[DEBUG] Token enviado:', formData.token_csrf);
            try {
                if (checkWordInArray(getURI, 'cadastrar')) {
                    setFormData((prev) => ({
                        ...prev,
                    }));
                    return false;
                }
                console.log(api_get_atualizar_prontuariopsicosocial)
                const response = await fetch(base_url + api_get_atualizar_prontuariopsicosocial);
                const data = await response.json();
                // console.log('Prontuários:: ', data);
                console.log('[DEBUG] Prontuário carregado:', data.result.dbResponse[0]);
                console.log('[DEBUG] RESPOSTA API]:', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {

                    setFormData((prev) => ({
                        ...prev,
                        ...data.result.dbResponse[0]
                    }));
                }
                if (data.result.dbResponse[0]?.adolescente_id) {
                    await fetchGetAdolescentes(data.result.dbResponse[0].adolescente_id);
                }
            } catch (error) {
                console.error('Erro ao carregar Prontuários: ' + error.message);
            }
        };

        // Fetch para obter os Profissionais
        const fetchProfissionais = async () => {
            try {
                const response = await fetch(base_url + api_post_filtrar_profissional, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                const data = await response.json();
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

        React.useEffect(() => {
            if (formData.Nascimento || formData.Escolaridade) {
                if (!checkWordInArray(getURI, 'consultar')) {
                    atualizaVulnerabilidade();
                }
            }
        }, [formData.Nascimento, formData.Escolaridade]);

        React.useEffect(() => {
            if (!checkWordInArray(getURI, 'consultar')) {
                const totalPontuacao =
                    (pontuacaoMap[formData.prontuario_referenciado_na_rede] || 0) +
                    (formData.PontuacaoIdade || 0) +
                    (formData.PontuacaoEscolaridade || 0);

                setFormData((prevFormData) => ({
                    ...prevFormData,
                    prontuario_pontuacao_total: totalPontuacao
                }));
            }
        }, [formData.prontuario_referenciado_na_rede, formData.PontuacaoIdade, formData.PontuacaoEscolaridade]);

        // Função para identificar o usuário
        React.useEffect(() => {
            if (
                user_session &&
                user_session.CargoFuncaoId === '5' &&
                user_session.PerfilId === '5'
            ) {
                setFormData((prev) => ({
                    ...prev,
                    profissional_id: user_session.profissional_id,
                    profissional_Nome: user_session.Nome
                }));
            }
        }, []);

        // Função para pegar o nome do usuário e pegar o id dele
        React.useEffect(() => {
            const nome = parametros.user_session?.FIA?.Nome;

            if (nome) {
                fetch(`${base_url}${api_post_filtrar_profissional}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ Nome: nome })
                })
                    .then(res => res.json())
                    .then(data => {
                        const profissional = data.result?.dbResponse?.[0];
                        if (profissional) {
                            setFormData((prev) => ({
                                ...prev,
                                profissional_id: profissional.id,
                                profissional_Nome: profissional.Nome
                            }));
                        }
                    })
                    .catch(err => {
                        console.error("Erro ao buscar profissional:", err);
                    });
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

        //console.log('user_session:', user_session)
        //console.log('formData:', formData);
        return (
            <div className="ms-3 me-3">
                {/* Fomulário Prontuário */}
                <div className="col-12 col-sm-12">
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="row">

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
                                            onChange={handleChange}
                                            required
                                        />
                                    )}
                                    <input
                                        data-api={`filtro-${origemForm}`}
                                        type="hidden"
                                        id="token_csrf"
                                        name="token_csrf"
                                        value={formData.token_csrf || token_csrf}
                                        onChange={handleChange}
                                        required
                                    />
                                    <input
                                        data-api={`filtro-${origemForm}`}
                                        type="hidden"
                                        id="json"
                                        name="json"
                                        value={formData.json || json}
                                        onChange={handleChange}
                                        required
                                    />
                                </form>
                            </div>

                            {/* Profissional */}
                            <div className="row">
                                <div className="col-6">
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
                                                style={{
                                                    appearance: 'none',
                                                    WebkitAppearance: 'none',
                                                    MozAppearance: 'none',
                                                    backgroundImage: 'none'
                                                }}
                                                id="profissional_id"
                                                name="profissional_id"
                                                value={formData.profissional_id || ''}
                                                onChange={handleChange}
                                                className="form-select"
                                                required
                                                disabled
                                            >
                                                <option value={formData.profissional_id}>
                                                    {formData.profissional_Nome || 'Selecionado automaticamente'}
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div className="col-6">
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

                    <div className="card mb-4">
                        <div className="card-body">

                            {/* Medidas Socio educativas */}
                            <div className="row">
                                <div className="col-6 mb-2">
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_medidas_socioeducativas" style={formLabelStyle} className="form-label">
                                            Cumpre medidas socioeducativas?
                                        </label>
                                        {checkWordInArray(getURI, 'consultar') ? (
                                            <div className="p-2">
                                                {formData.prontuario_medidas_socioeducativas === "Y" ? "Sim" : formData.prontuario_medidas_socioeducativas === "N" ? "Não" : "Não informado"}
                                            </div>
                                        ) : (
                                            <div className="row">
                                                <div className="col-12 col-sm-6">
                                                    <div className="form-check">
                                                        <input
                                                            data-api="form-prontuario"
                                                            type="radio"
                                                            className="form-check-input"
                                                            id="prontuario_medidas_socioeducativas1"
                                                            name="prontuario_medidas_socioeducativas"
                                                            value="Y"
                                                            checked={formData.prontuario_medidas_socioeducativas === "Y"}
                                                            onChange={handleRadioChange} />
                                                        <label className="form-check-label" htmlFor="prontuario_medidas_socioeducativas1">Sim</label>
                                                    </div>
                                                    <div className="form-check">
                                                        <input
                                                            data-api="form-prontuario"
                                                            type="radio"
                                                            className="form-check-input"
                                                            id="prontuario_medidas_socioeducativas2"
                                                            name="prontuario_medidas_socioeducativas"
                                                            value="N"
                                                            checked={formData.prontuario_medidas_socioeducativas === "N"}
                                                            onChange={handleRadioChange} />
                                                        <label className="form-check-label" htmlFor="prontuario_medidas_socioeducativas2">Não</label>
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <div className="col-6 mb-2">
                                    {/* Uso de Drogas */}
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_uso_de_drogas" style={formLabelStyle} className="form-label">
                                            Em tratamento por uso de drogas?
                                        </label>
                                        {checkWordInArray(getURI, 'consultar') ? (
                                            <div className="p-2">
                                                {formData.prontuario_uso_de_drogas === "Y" ? "Sim" : formData.prontuario_uso_de_drogas === "N" ? "Não" : "Não informado"}
                                            </div>
                                        ) : (
                                            <div className="row">
                                                <div className="col-12 col-sm-6">
                                                    <div className="form-check">
                                                        <input
                                                            data-api="form-prontuario"
                                                            type="radio"
                                                            className="form-check-input"
                                                            id="prontuario_uso_de_drogas1"
                                                            name="prontuario_uso_de_drogas"
                                                            value="Y"
                                                            checked={formData.prontuario_uso_de_drogas === "Y"}
                                                            onChange={handleRadioChange}
                                                        />
                                                        <label className="form-check-label" htmlFor="prontuario_uso_de_drogas1">Sim</label>
                                                    </div>
                                                    <div className="form-check">
                                                        <input
                                                            data-api="form-prontuario"
                                                            type="radio"
                                                            className="form-check-input"
                                                            id="prontuario_uso_de_drogas2"
                                                            name="prontuario_uso_de_drogas"
                                                            value="N"
                                                            checked={formData.prontuario_uso_de_drogas === "N"}
                                                            onChange={handleRadioChange}
                                                        />
                                                        <label className="form-check-label" htmlFor="prontuario_uso_de_drogas2">Não</label>
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                    {/* Uso de Drogas */}
                                </div>

                                <div className="col-6 mb-2">
                                    {/* Encaminhamento de órgãos */}
                                    <div style={formGroupStyle}>
                                        <label htmlFor="prontuario_cad_unico" style={formLabelStyle} className="form-label">
                                            Encaminhamento de órgãos?
                                        </label>
                                        {checkWordInArray(getURI, 'consultar') ? (
                                            <div className="p-2">
                                                {formData.prontuario_cad_unico === "Y" ? "Sim" : formData.prontuario_cad_unico === "N" ? "Não" : "Não informado"}
                                            </div>
                                        ) : (
                                            <div className="row">
                                                <div className="col-12 col-sm-6">
                                                    <div className="form-check">
                                                        <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_cad_unico1" name="prontuario_cad_unico"
                                                            value="Y"
                                                            checked={formData.prontuario_cad_unico === "Y"}
                                                            onChange={handleRadioChange}
                                                        />
                                                        <label className="form-check-label" htmlFor="prontuario_cad_unico1">Sim</label>
                                                    </div>
                                                    <div className="form-check">
                                                        <input
                                                            data-api="form-prontuario"
                                                            type="radio"
                                                            className="form-check-input"
                                                            id="prontuario_cad_unico2"
                                                            name="prontuario_cad_unico"
                                                            value="N"
                                                            checked={formData.prontuario_cad_unico === "N"}
                                                            onChange={handleRadioChange}
                                                        />
                                                        <label className="form-check-label" htmlFor="prontuario_cad_unico2">Não</label>
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                    {/* Encaminhamento de órgãos */}
                                </div>

                                <div className="col-6 mb-2">
                                    {/* Deficiência */}
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_deficiencia"
                                            style={formLabelStyle}
                                            className="form-label">
                                            Adolescente com Deficiência?
                                        </label>
                                        {checkWordInArray(getURI, 'consultar') ? (
                                            <div className="p-2">
                                                {formData.prontuario_deficiencia === "Y" ? "Sim" : formData.prontuario_deficiencia === "N" ? "Não" : "Não informado"}
                                            </div>
                                        ) : (
                                            <div className="row">
                                                <div className="col-12 col-sm-6">
                                                    <div className="form-check">
                                                        <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_deficiencia1" name="prontuario_deficiencia"
                                                            value="Y"
                                                            checked={formData.prontuario_deficiencia === "Y"}
                                                            onChange={handleRadioChange}
                                                        />
                                                        <label className="form-check-label" htmlFor="prontuario_deficiencia1">Sim</label>
                                                    </div>
                                                    <div className="form-check">
                                                        <input
                                                            data-api="form-prontuario"
                                                            type="radio"
                                                            className="form-check-input"
                                                            id="prontuario_deficiencia2"
                                                            name="prontuario_deficiencia"
                                                            value="N"
                                                            checked={formData.prontuario_deficiencia === "N"}
                                                            onChange={handleRadioChange}
                                                        />
                                                        <label className="form-check-label" htmlFor="prontuario_deficiencia2">Não</label>
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                    {/* Deficiência */}
                                </div>

                                <div className="col-6 mb-2">
                                    {/* Necessita mediador */}
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_necessita_mediador"
                                            style={formLabelStyle}
                                            className="form-label">
                                            Necessidade de mediador?
                                        </label>
                                        {checkWordInArray(getURI, 'consultar') ? (
                                            <div className="p-2">
                                                {formData.prontuario_necessita_mediador === "Y" ? "Sim" : formData.prontuario_necessita_mediador === "N" ? "Não" : "Não informado"}
                                            </div>
                                        ) : (
                                            <div className="row">
                                                <div className="col-12 col-sm-6">
                                                    <div className="form-check">
                                                        <input data-api="form-prontuario" type="radio" className="form-check-input" id="prontuario_necessita_mediador1" name="prontuario_necessita_mediador"
                                                            value="Y"
                                                            checked={formData.prontuario_necessita_mediador === "Y"}
                                                            onChange={handleRadioChange}
                                                        />
                                                        <label className="form-check-label" htmlFor="prontuario_necessita_mediador1">Sim</label>
                                                    </div>
                                                    <div className="form-check">
                                                        <input
                                                            data-api="form-prontuario"
                                                            type="radio"
                                                            className="form-check-input"
                                                            id="prontuario_necessita_mediador2"
                                                            name="prontuario_necessita_mediador"
                                                            value="N"
                                                            checked={formData.prontuario_necessita_mediador === "N"}
                                                            onChange={handleRadioChange}
                                                        />
                                                        <label className="form-check-label" htmlFor="prontuario_necessita_mediador2">Não</label>
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                    {/* Necessita mediador */}
                                </div>

                                <div className="col-6 mb-2">
                                    {/* Grau de Vulnerabilidade */}
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_referenciado_na_rede"
                                            style={formLabelStyle}
                                            className="form-label"
                                        >
                                            Grau de Vulnerabilidade
                                        </label>
                                        {checkWordInArray(getURI, 'consultar') ? (
                                            <div className="p-2">
                                                {(
                                                    {
                                                        "extremamente-vulneravel": "Extremamente Vulnerável",
                                                        "muito-vulneravel": "Muito Vulnerável",
                                                        "vulneravel": "Vulnerável"
                                                    }[formData.prontuario_referenciado_na_rede] || "Não informado"
                                                )}
                                            </div>
                                        ) : (
                                            <div className="row">
                                                <div className="col-12 col-sm-6">
                                                    <div className="d-flex flex-wrap gap-3">
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
                                            </div>
                                        )}
                                    </div>
                                    {/* Grau de Vulnerabilidade */}
                                </div>

                                {/*
                                <div className="col-6 mb-2">

                                     Data de cadastro psicossocial 
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="DataCadPsicoSocial"
                                            style={formLabelStyle}
                                            className="form-label"
                                        >
                                            Data de cadastro psicossocial
                                        </label>
                                        <input
                                            data-api="form-prontuario"
                                            type="date"
                                            id="DataCadPsicoSocial"
                                            name="DataCadPsicoSocial"
                                            value={formData.DataCadPsicoSocial || ''}
                                            onChange={handleChange}
                                            className="form-control"
                                            style={formControlStyle}
                                            aria-describedby="DataCadPsicoSocial"
                                            required
                                        />
                                        <div className="m-1 p-1"></div>
                                    </div>
                                    {/* Data de cadastro psicossocial 

                                </div>
                                */}

                                <div className="col-12 col-sm-12">
                                    {/* Pontuação total */}
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="prontuario_pontuacao_total"
                                            style={formLabelStyle}
                                            className="form-label"
                                        >
                                            Pontuação total
                                        </label>
                                        <input
                                            data-api="form-prontuario"
                                            type="number"
                                            value={formData.prontuario_pontuacao_total || 0}
                                            onChange={handleChange}
                                            className="form-control"
                                            style={formControlStyle}
                                            id="prontuario_pontuacao_total"
                                            name="prontuario_pontuacao_total"
                                            aria-describedby="prontuario_pontuacao_total"
                                            disabled
                                            required
                                        />
                                    </div>
                                    {/* Pontuação total */}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Botão de voltar e salvar */}
                {!checkWordInArray(getURI, 'detalhar') && (
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
                )}

                {typeof AppJson !== "undefined" ? (
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
                )}

                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message} modalId={'modal_form'}
                />
            </div >
        );
    };
</script>