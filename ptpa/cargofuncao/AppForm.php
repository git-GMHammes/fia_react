<script type="text/babel">
    const AppForm = ({ parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf || 'erro';
        const json = '1';
        const atualizar_id = parametros.atualizar_id || 'erro';
        const origemForm = parametros.origemForm || '';
        const title = parametros.title || '';
        // console.log('origemForm: ', origemForm);

        //Base Cadastro Cargo/Função
        const api_post_atualizar_cargofuncao = parametros.api_post_atualizar_cargofuncao || '';
        const api_post_cadastrar_cargofuncao = parametros.api_post_cadastrar_cargofuncao || '';
        const api_get_atualizar_cargofuncao = parametros.api_get_atualizar_cargofuncao || '';

        // Variáveis da API

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

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

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;
            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Se o campo for o CPF, faz a validação
            if (name === 'cpf' && !isValidCPF(value)) {
                const cpfInput = event.target;
                cpfInput.classList.add('is-invalid');
                setError('CPF inválido');
            }
        };

        const submitAllForms = async (filtro) => {
            console.log('submitAllForms...');
            const setData = formData;
            let data = '';
            let dbResponse = [];
            let response = '';
            console.log('Dados a serem enviados:', setData);

            if (filtro === `filtro-${origemForm}`) {
                // Convertendo os dados do setPost em JSON
                console.log(`filtro-${origemForm}`);
                // console.log(`${base_url}${api_post_cadastrar_cargofuncao}`);

                response = await fetch(`${base_url}${api_post_cadastrar_cargofuncao}`, {
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
                } else {
                    resposta = 'Ação';
                }

                // Processa os dados recebidos da resposta
                // console.log('data: ', data);

                if (
                    data.result && data.result.affectedRows && data.result.affectedRows > 0
                ) {
                    dbResponse = data.result.dbResponse;
                    // Função para exibir o alerta (success, danger, warning, info)
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `${resposta} realizada com sucesso`
                    });
                    redirectTo('fia/ptpa/cargofuncao/endpoint/exibir');
                    return dbResponse;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        type: `Não foi possivel realizar a ${resposta}`,
                    });

                    return null;
                }
            }
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            cargo_funcao: null,
            form_on: null,
        });

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchCargoFuncao();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter as Cargo/Função
        const fetchCargoFuncao = async () => {
            try {
                const response = await fetch(base_url + api_get_atualizar_cargofuncao);
                console.log(base_url + api_get_atualizar_cargofuncao);
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {

                    // console.log('Cargo/Função: ', data);
                    setFormData((prevFormData) => ({
                        ...prevFormData,
                        ...data.result.dbResponse[0]
                    }));
                }
            } catch (error) {
                setError('Erro ao carregar Cargo/Função: ' + error.message);
            }
        };

        if (debugMyPrint && isLoading) {
            return <div className="d-flex justify-content-center align-items-center m-5 p-5">
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        }

        if (debugMyPrint && error) {
            return <div className="d-flex justify-content-center align-items-center m-5 p-5">
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
            <div className="ms-3 me-3">

                {debugMyPrint ? (
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                            Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ) : null}
                <div className="row mb-1">
                    <div className="col-12 mb-1">
                        <div className="d-flex align-items-center">
                            <div className="ms-3" style={verticalBarStyle}></div>
                            <h2 className="myBold">{title}</h2>
                        </div>
                    </div>
                </div>

                {/* Continua Formulário de Cargo/Função */}
                <div className="ms-3 me-3">
                    <div className="card mb-4">
                        <div className="card-body">
                            <div className="row">
                                <div className="col-12 col-sm-6 mb-2">
                                    <form className="needs-validation" onSubmit={(e) => {
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
                                            id="AcessoCadastroID"
                                            name="AcessoCadastroID"
                                            value={formData.AcessoCadastroID || ''}
                                            onChange={handleChange}
                                            required
                                        />
                                        <input
                                            data-api={`filtro-${origemForm}`}
                                            type="hidden"
                                            id="PerfilId"
                                            name="PerfilId"
                                            value={formData.PerfilId || ''}
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

                                    <form className="needs-validation" onSubmit={(e) => {
                                        e.preventDefault();
                                        submitAllForms(`filtro-${origemForm}`, formData);
                                    }}>
                                        <div style={formGroupStyle}>
                                            <label htmlFor="cargo_funcao" style={formLabelStyle} className="form-label">Cargo/Função<strong style={requiredField}>*</strong></label>
                                            <input
                                                data-api={`filtro-${origemForm}`}
                                                type="text"
                                                id="cargo_funcao"
                                                name="cargo_funcao"
                                                value={formData.cargo_funcao || ''}
                                                onChange={handleChange}
                                                style={formControlStyle}
                                                className="form-control form-control-sm"
                                                required
                                            />
                                        </div>
                                    </form>
                                </div>
                                <div className="col-12 col-sm-6 mb-2">
                                    <form className="needs-validation" onSubmit={(e) => {
                                        e.preventDefault();
                                        submitAllForms(`filtro-${origemForm}`, formData);
                                    }}>
                                        <div style={formGroupStyle}>
                                            <label htmlFor="form_on" style={formLabelStyle}>FORM/ON<strong style={requiredField}>*</strong></label>
                                            <select data-api={`filtro-${origemForm}`} id="form_on" name="form_on" value={formData.form_on || ''} className="form-select form-select-sm" onChange={handleChange} style={formControlStyle} required aria-label="Default select" >
                                                <option value="">Seleção Nula</option>
                                                <option value="Y">Sim</option>
                                                <option value="N">Não</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {/* Botão de Enviar */}
                            <div className="row">
                                <div className="col-12">
                                    <form className="needs-validation d-flex justify-content-between align-items-center"
                                        onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`, formData);
                                        }}>
                                        <div className="d-flex gap-2">
                                            {/* Botão Voltar */}
                                            <a className="btn btn-danger"
                                                href={`${base_url}index.php/fia/ptpa/cargofuncao/endpoint/exibir`}
                                                role="button"
                                            >
                                                Voltar
                                            </a>

                                            {/* Botão Salvar */}
                                            <input
                                                className="btn btn-outline-success"
                                                type="submit"
                                                value="Enviar"
                                            />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Exibe o componente de alerta */}
                <AppMessageCard parametros={message} modalId="modal_form" />

            </div >
        );
    };
</script>