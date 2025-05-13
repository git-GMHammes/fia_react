<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');

$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'result' => isset($result) ? ($result) : (array()),
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'atualizar_id' => $atualizar_id,
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'server_port' => $_SERVER['SERVER_PORT'],
    'token_csrf' => $token_csrf,
    'DEBUG_MY_PRINT' => false,
    'api_get_historico_funcionario' => 'index.php/fia/ptpa/historicoprofissional/api/exibir',
    'api_filter_historico_funcionario' => 'index.php/fia/ptpa/historicoprofissional/api/filtrar',
    'api_update_objeto' => 'index.php/api/projeto/objeto/api/filtrar',
);
$parametros_backend['api_update_objeto'] = ($atualizar_id !== 'erro') ? ('projeto/sub/projeto/api/exibir/' . $atualizar_id) : ('projeto/sub/projeto/api/exibir/erro');
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
// myPrint('$parametros_backend :: ', $parametros_backend);
?>

<div class="app_listar_historico" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListarHistorico = (
        {
            // parametros = {}
        }
    ) => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar_historico').getAttribute('data-result'));
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const request_scheme = parametros.request_scheme || 'http';
        const server_name = parametros.server_name || 'localhost';
        const token_csrf = parametros.token_csrf || 'erro';
        const server_port = parametros.server_port || '80';
        const base_url = parametros.base_url || 'http://localhost';
        const atualizar_id = parametros.atualizar_id || 'http://localhost';
        const filer_profissional_id = parseInt(atualizar_id.replace('/', ''));

        // Variáveis de estado
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const [apiobjeto, setApiobjeto] = React.useState([]);

        // Base Lista
        const api_filter_historico_funcionario = parametros.api_filter_historico_funcionario || '';
        const api_get_historico_funcionario = parametros.api_get_historico_funcionario || '';
        const base_paginator = base_url;
        const getVar_page = parametros.getVar_page || '?page=1';
        const [apiUrlList, setApiUrlList] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);
        const [dataLoading, setDataLoading] = React.useState(true);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            variavel_id: null,
            variavel_001: null,
            variavel_002: null,
            variavel_003: null
        });

        // Função para gerar uma string aleatória com letras maiúsculas e números
        const gerarContagemAleatoria = (comprimento) => {
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Letras maiúsculas e números
            let resultado = '';
            for (let i = 0; i < comprimento; i++) {
                resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
            return resultado;
        };

        // Função handleFocus para receber foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

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

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança é no campo 'variavel_001'
            if (name === 'variavel_001') {
                console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
            setMessage({ show: false, type: null, message: null });
        };
        // Função que executa após a retirada do foco
        const handleBlur = (event) => {
            const { name, value } = event.target;
    
            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança é no campo 'variavel_001'
            if (name === 'variavel_001') {
                // submitAllForms('filtro-api');
            }
            setMessage({ show: false, type: null, message: null });
        }

        // Função que gerencia atualizações do MODAL
        const handleOpenModal = (parameter) => {
            setFormData(prontuario);
            // Exemplo
            // {apiUrlList.map((select_value, index) => (...
            // <button type="button" className="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target={`#staticBackdropProntuario${index}`} onClick={() => handleOpenModal(select_value)}>
            //      <i className="bi bi-pencil-square" />
            // </button>
        };

        const submitAllForms = async (apiIdentifier) => {
            // Cria um objeto para armazenar os dados dos inputs
            const inputs = document.querySelectorAll(`[data-api="filtro"]`);
            const inputValues = {};
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    // Para checkbox, considera o estado "checked"
                    inputValues[input.name] = input.checked;
                } else if (input.type === 'radio') {
                    // Para radio, seleciona o que está marcado
                    if (input.checked) {
                        inputValues[input.name] = input.value;
                    }
                } else if (input.tagName === 'SELECT') {
                    // Para select, pega o valor selecionado
                    inputValues[input.name] = input.value;
                } else {
                    // Para outros tipos de input (text, number, etc.)
                    inputValues[input.name] = input.value;
                }
            });

            // Adiciona os valores dos inputs ao formData
            setDataFields((prevDataFields) => ({
                ...formData,
                ...inputValues,
                ...prevDataFields,
            }));

            switch (apiIdentifier) {

                case 'filtro-001':
                    console.log('filtro-001');
                    const updateData = captureFormData('filtro-001');
                    const response = await fetchData(`${base_url}/${api_url_001}`, updateData);
                    if (response && response.result && response.result.affectedRows > 0) {
                        console.log('insertID:', response.result.insertID);
                        setFormData(prev => ({
                            ...prev,
                            id: response.result.insertID
                        }));
                        const modal = new bootstrap.Modal(document.getElementById('MensagemSucessoSalvar'));
                        modal.show();
                    }
                    break;

                case 'filtro-002':
                    console.log(apiIdentifier, '- OK');
                    const data = captureFormData('filtro-002');
                    const responsavelData = await fetchData(`${base_url}/${api_url_002}`, data);
                    if (responsavelData && responsavelData.result && responsavelData.result.dbResponse && responsavelData.result.dbResponse.length > 0) {
                        console.log('form-responsavel:', responsavelData.result.dbResponse);
                        setResponsaveis(responsavelData.result.dbResponse);
                    }
                    break;

                case 'filtro-003':
                    console.log(apiIdentifier, '- OK');
                    break;

                default:
                    console.log('Identificador de API desconhecido:', apiIdentifier);
                    break;
            }
        };

        React.useEffect(() => {

            if (!debugMyPrint) {
                fetchFilterHistpricoFuncionario();
                // ...
            }

            setTimeout(() => {
                setIsLoading(false);
            }, 1000);
        }, []);

        // Fetch para POST Filter Histórico Funcionário'
        const fetchFilterHistpricoFuncionario = async (custonBaseURL = base_url, custonApiPostObjeto = api_filter_historico_funcionario, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            console.log('url :: ', url);
            // Adiciona os valores dos inputs ao formData
            const setData = {
                hpu_profissional_id: filer_profissional_id,
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                console.log('data fetchFilterHistpricoFuncionario :: ', data);

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    console.log('dbResponse :: ', dbResponse);
                    // 
                    setApiUrlList(dbResponse);
                    setPagination('list');
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas unidades cadastradas'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // Fetch para GET
        const fetchApiGetHistpricoFuncionario = async (custonBaseURL = base_url, custonApiGetObjeto = api_get_historico_funcionario, customPage = getVar_page) => {
            console.log('fetchApiGetHistpricoFuncionario...');
            console.log('src/app/Views/fia/ptpa/alocarFuncionario/AppListarHistorico.php');
            const url = custonBaseURL + custonApiGetObjeto + customPage;
            // console.log('url :: ', url)
            try {
                const response = await fetch(url);
                const data = await response.json();
                // console.log('data fetchApiGetHistpricoFuncionario :: ', data)
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    seNutApiUrlList(dbResponse);
                    setPagination('list');

                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontrados objeto cadastrados'
                    });
                    setIsLoading(false);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);
                    setIsLoading(false);
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

        const fetchApiGet002 = async () => {
            try {
                const response = await fetch(base_url + api_url_002);
                const data = await response.json();
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    console.log('api_url_002: ', data);
                    setProntuarios(data.result.dbResponse);
                    // Não esquecer do dbResponse[0]
                    setFormData(data.result.dbResponse[0]);
                }
            } catch (error) {
                setError('Erro ao carregar api_url_002: ' + error.message);
            }
        };

        // Variável para style
        const myMinimumHeight = {
            minHeight: '600px'
        }

        const formGroupStyle = {
            position: 'relative',
            marginTop: '20px',
            padding: '5px',
            borderRadius: '8px',
            border: '1px solid #000',
        };

        if (isLoading && debugMyPrint) {
            return <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>

                <div className="d-flex justify-content-center align-items-center min-vh-100">
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

        }

        if (error && debugMyPrint) {
            return <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>

                <div className="d-flex justify-content-center align-items-center min-vh-100">
                    <div className="alert alert-danger" role="alert">
                        {error}
                    </div>
                </div>
            </div>

        }

        return (
            <div>
                <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                    <table className="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        NOME DA UNIDADE
                                    </div>
                                </th>
                                <th scope="col">
                                    <div className="d-flex justify-content-center">
                                        DATA DE TRANSFERENCIA
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
                            </tr>
                        </thead>

                        <tbody>
                            {apiUrlList.map((profissional, index) => (
                                <tr key={index}>
                                    <td>
                                        <div className="d-flex justify-content-center">
                                            {profissional.uni_Nome}
                                        </div>
                                    </td>
                                    <td>
                                        <div className="d-flex justify-content-center">
                                            {profissional.hpu_dtTransferencia}
                                        </div>
                                    </td>
                                    <td>
                                        <div className="d-flex justify-content-center">
                                            {profissional.hpu_data_admissao}
                                        </div>
                                    </td>
                                    <td>
                                        <div className="d-flex justify-content-center">
                                            {profissional.hpu_data_demissao}
                                        </div>
                                    </td>
                                    <td>
                                        <div className="d-flex justify-content-center">
                                            <a className="btn btn-outline-primary btn-sm" href={`${base_url}index.php/fia/ptpa/alocarfuncionario/endpoint/atualizar/${profissional.hpu_profissional_id}`} role="button">
                                                <i className="bi bi-pencil-square" />
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>

                    <div className="m-3">
                        {/* Paginação */}
                        {(pagination == 'list') && (
                            <nav aria-label="Page navigation example">
                                <ul className="pagination">
                                    {paginacaoLista.map((paginacao_value, index) => (
                                        <li key={index} className={`page-item $${paginacao_value.active ? 'active' : ''}`}>
                                            <button
                                                className="page-link"
                                                onClick={() => fetchobjetos(base_url, api_get_objeto, paginacao_value.href)}
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
                                        <li key={index} className={`page-item $${paginacao_value.active ? 'active' : ''}`}>
                                            <button
                                                type="button"
                                                className="page-link"
                                                onClick={() => submitAllForms('filtro-objeto', paginacao_value.href)}
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

                {/* Modais para cada Objeto */}
                {apiUrlList.map((mapList_value, index) => (
                    <div key={index} className="modal fade" id={`staticBackdropObjeto${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropObjetoLabel${index}`} aria-hidden="true">
                        <div className="modal-dialog modal-xl">
                            <div className="modal-content">

                                <div className="modal-header">
                                    <h5 className="modal-title" id={`staticBackdropObjetoLabel${index}`}>Detalhes do Modal</h5>
                                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div className="modal-body">

                                    {/* formulário conteudo Modal */}
                                    <div>{mapList_value.variavel_id}</div>
                                    <div>{mapList_value.variavel_001}</div>
                                    <div>{mapList_value.variavel_002}</div>
                                    <div>{mapList_value.variavel_003}</div>
                                    <div>{/*Coluna do botão*/}</div>
                                    {/* formulário conteudo Modal */}

                                </div>

                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>

                            </div>
                        </div>
                    </div>
                ))}
                {/* Mensagem */}
                {typeof AppMessageCard !== "undefined" ? (
                    <div>
                        <AppMessageCard
                            parametros={message}
                            modalId={`modal_token_csrf_${gerarContagemAleatoria(2)}`}
                        />
                    </div>
                ) : (
                    <div>
                        <p className="text-danger">AppMessageCard não lacançado.</p>
                    </div>
                )}

                {/* JSON */}
                {typeof AppJson !== "undefined" ? (
                    <AppJson
                        parametros={parametros}
                        dbResponse={parametros}
                    />
                ) : (
                    <div>
                        <p className="text-danger">AppJson não lacançado.</p>
                    </div>
                )}
            </div>
        );
    };
    const rootElement = document.querySelector('.app_listar_historico');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListarHistorico />);
    // ReactDOM.render(<AppListarHistorico />, document.querySelector('.app_listar_historico'));
</script>
<?php
$parametros_backend = array();
?>