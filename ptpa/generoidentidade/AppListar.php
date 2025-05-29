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
    'api_get_genero' => 'index.php/fia/ptpa/genero/api/exibir',
    'api_post_filter_generos' => 'index.php/fia/ptpa/genero/api/filtrar',
);
$parametros_backend['api_get_atualizar_profissional'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/profissional/api/exibir' . $atualizar_id) : ('fia/ptpa/profissional/api/exibir/erro');
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
// myPrint($parametros_backend, '');

?>

<div class="app_listar" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListar = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar').getAttribute('data-result'));
        parametros.origemForm = 'profissional'
        // console.log('parametros: ', parametros);
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf;
        const api_post_filter_profissional = parametros.api_post_filter_profissional;

        // Base Lista Profissional
        const api_get_profissionais = parametros.api_get_profissionais;
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        // Variáveis da API
        const [profissionais, setProfissionais] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoProfissionais, setPaginacaoProfissionais] = React.useState([]);

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

        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
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
            PerfilId: debugMyPrint == true ? '1' : null,
            PerfilDescricao: null,
            ProgramasId: debugMyPrint == true ? '1' : null,
            SiglaProgramas: null,
            AcessoCadastroID: '1',
            UnidadeId: null,
            Unidade: null,
            NomeUnidade: null,
            AcessoId: null,
            AcessoDescricao: null,
            ProntuarioId: null,
            Nome: debugMyPrint === true ? 'Clara Beatriz Monteiro Alves da Silva' : null,
            CPF: debugMyPrint === true ? '779.276.880-57' : null,
            TelefoneFixo: debugMyPrint === true ? '(21)2444-8097' : null,
            TelefoneMovel: debugMyPrint === true ? '(21)99999-0105' : null,
            TelefoneRecado: debugMyPrint === true ? '(21)99921-1236' : null,
            Email: debugMyPrint === true ? 'clarabe-monteiro99@globomail.com' : null,
            DataCadastramento: debugMyPrint === true ? "2024-10-23" : null,
            DataTermUnid: debugMyPrint === true ? "2022-05-04" : null,
            DataInicioUnid: debugMyPrint === true ? "2021-06-04" : null,
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

        const submitAllForms = async (filtro) => {
            console.log('submitAllForms...');
            const setData = formData;
            let data = '';
            let dbResponse = [];
            let response = '';
            // console.log('Dados a serem enviados:', setData);

            if (filtro === 'filtro-profissional') {
                // Convertendo os dados do setPost em JSON
                response = await fetch(base_url + api_post_filter_profissional, {
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

                // Processa os dados recebidos da resposta
                if (
                    data.result && data.result.dbResponse && data.result.dbResponse[0]
                ) {
                    dbResponse = data.result.dbResponse;
                    setProfissionais(dbResponse);
                    // console.log('dbResponse: ', dbResponse);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Mensagem de Sucesso'
                    });
                    return dbResponse;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Mensagem de Erro.'
                    });
                    return null;
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
                    await fetchProfissionais();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter os Profissionais
        const fetchProfissionais = async () => {
            try {
                const response = await fetch(base_url + api_get_profissionais + getVar_page);
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse[0]) {
                    // console.log('Profissionais: ', data.result);
                    setProfissionais(data.result.dbResponse);
                    setPagination(true);
                }
                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoProfissionais(data.result.linksArray);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Profissionais Carregados com sucesso'
                    });
                    setDataLoading(false);
                }
            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Profissionais: ' + error.message
                });
                // setError('Erro ao carregar Profissional: ' + error.message);
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

        const formGroupStyle = {
            position: 'relative',
            marginTop: '20px',
            padding: '5px',
            borderRadius: '8px',
            border: '1px solid #000',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                <div>
                    {debugMyPrint ? (
                        <div className="row">
                            <div className="alert alert-danger" role="alert">
                                Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                            </div>
                        </div>
                    ) : null}
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <div className="d-flex align-items-center">
                                <div className="ms-4" style={verticalBarStyle}></div>
                                <h2 className="myBold">{title}</h2>
                            </div>
                        </div>
                        <div className="col-12 col-sm-4">
                            <button className="btn btn-outline-primary mt-3 ms-4 me-4 ps-4 pe-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i className="bi bi-search"></i>
                            </button>
                        </div>
                        <div className="col-12 col-sm-2">
                            &nbsp;
                        </div>
                        <div className="col-12 col-sm-2">
                            &nbsp;
                        </div>
                    </div>

                    <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                        <table className="table table-striped">
                            <thead className="border border-2 border-dark border-start-0 border-end-0">
                                <tr>
                                    <th scope="col" className="text-nowrap">
                                        <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-profissional`, formData);
                                            }}>
                                                <input
                                                    data-api="filtro-profissional"
                                                    type="text" name="Nome"
                                                    value={formData.Nome || ''}
                                                    onChange={handleChange}
                                                    placeholder="Nome"
                                                    className="form-control form-control-sm"
                                                    style={formControlStyle}
                                                    aria-label=".form-control-sm example"
                                                />
                                            </form>
                                        </div>
                                        NOME
                                    </th>
                                    <th scope="col" className="text-nowrap">
                                        <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-profissional`, formData);
                                            }}>
                                                <input
                                                    data-api="filtro-profissional"
                                                    type="text"
                                                    name="CPF"
                                                    value={formData.CPF || ''}
                                                    onChange={handleChange}
                                                    placeholder="CPF"
                                                    className="form-control form-control-sm"
                                                    style={formControlStyle}
                                                    aria-label=".form-control-sm example"
                                                />
                                            </form>
                                        </div>
                                        CPF
                                    </th>
                                    <th scope="col" className="text-nowrap">
                                        <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-profissional`, formData);
                                            }}>
                                                <input
                                                    data-api="filtro-profissional"
                                                    type="text"
                                                    name="Unidade"
                                                    value={formData.Unidade || ''}
                                                    onChange={handleChange}
                                                    placeholder="Unidade"
                                                    className="form-control form-control-sm"
                                                    style={formControlStyle}
                                                    aria-label=".form-control-sm example"
                                                />
                                            </form>
                                        </div>
                                        UNIDADE
                                    </th>
                                    <th scope="col" className="text-nowrap">
                                        <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-profissional`, formData);
                                            }}>
                                                <input
                                                    data-api="filtro-profissional"
                                                    type="text"
                                                    name="TelefoneMovel"
                                                    value={formData.TelefoneMovel || ''}
                                                    onChange={handleChange}
                                                    placeholder="Telefone"
                                                    className="form-control form-control-sm"
                                                    style={formControlStyle}
                                                    aria-label=".form-control-sm example"
                                                />
                                            </form>
                                        </div>
                                        TEL MOVEL
                                    </th>
                                    <th scope="col" className="text-nowrap">
                                        {/*Submit data-api*/}
                                        <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-profissional`, formData);
                                            }}>
                                                <input className="btn btn-sm" style={formControlStyle} type="submit" value="Enviar" />
                                            </form>
                                        </div>
                                        EDITAR
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                {dataLoading && (
                                    <tr>
                                        <td colSpan="8">
                                            <div className="m-5">
                                                <AppLoading parametros={{
                                                    tipoLoading: "progress",
                                                    carregando: dataLoading
                                                }} />
                                            </div>
                                        </td>
                                    </tr>
                                )}

                                {profissionais.map((profissional_value, index_lista_ado) => (
                                    <tr key={index_lista_ado}>
                                        <td>{profissional_value.Nome}</td>
                                        <td>{profissional_value.CPF}</td>
                                        <td>{profissional_value.NomeUnidade}</td>
                                        <td>{profissional_value.TelefoneMovel}</td>
                                        <td>
                                            {/* Botão para acionar o modal *
                                            <button type="button" className="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target={`#staticBackdropProfissional${index_lista_ado}`}>
                                            <i className="bi bi-pencil-square" />
                                            </button>
                                            {/* Botão para acionar o modal */}
                                            <div>
                                                <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/profissional/endpoint/atualizar/${profissional_value.id}`} role="button">
                                                    <i className="bi bi-pencil-square" />
                                                </a>
                                            </div>

                                        </td>
                                    </tr>
                                ))}
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </tfoot>
                        </table>

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
                        <AppMessageCard parametros={message} modalId="modal_form" />

                    </div>
                </div>
            </div>
        );
    };

    const rootElement = document.querySelector('.app_listar');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListar />);
</script>
<?php
$parametros_backend = array();
?>