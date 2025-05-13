<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
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
    'api_get_profissao' => 'index.php/fia/ptpa/profissao/api/exibir',
    'api_post_filter_profissoes' => 'index.php/fia/ptpa/profissao/api/filtrar',
);
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
?>

<div class="app_listar_profissao" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListarProfissao = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar_profissao').getAttribute('data-result'));

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

        //Base Lista Profissao
        const api_get_profissao = parametros.api_get_profissao;
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;
        const api_post_filter_profissoes = parametros.api_post_filter_profissoes;


        // Variáveis da API
        const [profissoes, setProfissoes] = React.useState([]);
        const [paginacaoProfissoes, setPaginacaoProfissoes] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            id: null,
            Codigo: null,
            Descricao: null,
            Favorito: null,
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

            setFormSelect((prev) => ({
                ...prev,
                [name]: value
            }));

            // Implementa debounce para evitar múltiplas requisições
            if (debounceTimeout.current) {
                clearTimeout(debounceTimeout.current);
            }

            debounceTimeout.current = setTimeout(() => {
                // Submete o formulário automaticamente para o identificador correspondente ao data-api
                const apiIdentifier = event.target.getAttribute('data-api');
                if (apiIdentifier) {
                    submitAllForms(apiIdentifier);
                }
            }, 300);

        };

        // Inicializando o debounceTimeout com useRef
        const debounceTimeout = React.useRef(null);

        // Função que gerencia atualizações do MODAL
        const handleOpenModal = (profissao) => {
            setFormData(profissao);
        };

        // Função que será chamada para submeter todos os formulários de uma vez
        const submitAllForms = (apiIdentifier) => {
            const data = {};

            // Seleciona apenas os inputs que possuem o atributo data-api correspondente ao identificador
            const inputs = document.querySelectorAll(`input[data-api="${apiIdentifier}"]`);

            // Itera sobre cada input encontrado
            inputs.forEach(input => {
                // Adiciona o valor do input ao objeto 'data', usando o nome do input como chave
                data[input.name] = input.value;
            });

            if (apiIdentifier == 'filtro-profissao') {
                console.log('filtro-profissao');
                // Envia uma requisição POST para a API com os dados coletados
                fetch(`${base_url}${api_post_filter_profissoes}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data), // Converte o objeto 'data' em uma string JSON para enviar no corpo da requisição
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data.result.dbResponse);
                        if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                            // console.log('filtro-profissao:', data.result.dbResponse);
                            setProfissoes(data.result.dbResponse);
                        }
                    })
                    .catch((error) => {
                        setError('Erro ao ENviar Filtro: ' + error.message);
                    });
            }
        };

        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchProfissoes();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter as Profissoes
        const fetchProfissoes = async () => {
            try {
                const response = await fetch(base_url + api_get_profissao + getVar_page);
                const data = await response.json();
                console.log('Profissoes: ', data);
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setProfissoes(data.result.dbResponse);
                    setPagination(true);
                }
                if (data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoProfissoes(data.result.linksArray);
                }
            } catch (error) {
                setError('Erro ao carregar Profissao: ' + error.message);
            }
        };

        // Visual
        const myMinimumHeight = {
            minHeight: '600px'
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
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        const requiredField = {
            color: '#FF0000',
        };

        if (isLoading) {
            return <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        }

        if (error) {
            return <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>
                <div className="alert alert-danger" role="alert">
                    {error}
                </div>
            </div>
        }

        // 

        return (
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
                    <table className="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-profissao" type="text" name="Codigo" className="form-control form-control-sm" style={formControlStyle} placeholder="Código" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    Código da Profissão
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-profissao" type="text" name="Descricao" className="form-control form-control-sm" style={formControlStyle} placeholder="Descrição" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    Descrição
                                </th>
                                <th scope="col" className="text-nowrap">
                                    {/*Submit data-api*/}
                                    <div className="collapse" style={formGroupStyle} id="collapseExample">
                                        <button className="btn" onClick={() => submitAllForms('filtro-profissao')} type="submit" style={{ width: '100%', fontSize: '0.8rem', padding: '0.375rem 0.75rem', borderRadius: '0.25rem', boxSizing: 'border-box' }}>Filtrar</button>
                                    </div>
                                    EDITAR
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {profissoes.map((profissoes_value, index_lista_profissao) => (
                                <tr key={index_lista_profissao} className="col-12 col-sm-3 mb-5">
                                    <td>{profissoes_value.Codigo}</td>
                                    <td>{profissoes_value.Descricao}</td>
                                    <td>
                                        {/* Botão para acionar o modal */}
                                        <button type="button" className="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target={`#staticBackdropProfissao${index_lista_profissao}`} onClick={() => handleOpenModal(profissoes_value)}>
                                            <i className="bi bi-pencil-square" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </tfoot>
                    </table>

                    {/* Modais para cada profissao */}
                    {profissoes.map((profissao, index) => (
                        <div key={index} className="modal fade" id={`staticBackdropProfissao${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropProfissaoLabel${index}`} aria-hidden="true">
                            {/* modal-fullscreen / modal-x1 */}
                            <div className="modal-dialog modal-xl">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5 className="modal-title" id={`staticBackdropProfissaoLabel${index}`}>Detalhes da Profissao</h5>
                                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div className="modal-body">
                                        {/* formulario Profissao*/}
                                        <form action={base_url + 'index.php/fia/ptpa/profissao/api/atualizar'} method="post" className="row was-validated m-2">

                                            {/*Caompos Ocultos*/}
                                            <input data-api="dados-profissao" type="hidden" id="id" name="id" value={formData.id || ''} onChange={handleChange} className="form-control" />
                                            <input type="hidden" id="token_csrf" name="token_csrf" value={token_csrf} className="form-control" required />

                                            <div className="row">
                                                <div className="col-12 col-sm-12">
                                                    <div className="card mb-4">
                                                        <div className="card-body">
                                                            <div className="row">
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Codigo" style={formLabelStyle} className="form-label">Código da Profissão<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-profissao" type="text" id="Codigo" name="Codigo" value={formData.Codigo || ''} onChange={handleChange} style={formControlStyle} className="form-control" required />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Descricao" style={formLabelStyle} className="form-label">Descrição<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-profissao" type="text" id="Descricao" name="Descricao" value={formData.Descricao || ''} onChange={handleChange} style={formControlStyle} className="form-control" required />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-1 mb-3">
                                                                    <div style={formGroupStyle} className="d-flex align-items-center justify-content-center">
                                                                        <label htmlFor="Favorito" style={formLabelStyle} className="form-label">Favorito</label>
                                                                        <div className="ps-0"></div>
                                                                        <input
                                                                            data-api="dados-profissao"
                                                                            type="checkbox"
                                                                            id="Favorito"
                                                                            name="Favorito"
                                                                            value={formData.Favorito || ''}
                                                                            onChange={handleChange}
                                                                            className="form-check-input m-2 p-2"
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="col-12 col-sm-12">
                                                    <input className="btn btn-outline-primary" type="submit" value="Enviar" />
                                                    {/*
                                                    <button className="btn btn-outline-primary mb-5" onClick={() => submitAllForms('dados-adolescente')} type="submit">Enviar</button>
                                                */}
                                                </div>
                                            </div>
                                        </form>
                                        {/* formulario Profissao*/}
                                    </div>
                                    <div className="modal-footer">
                                        <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}

                    <nav aria-label="Profissoes">
                        <ul className="pagination">
                            {pagination == true && paginacaoProfissoes.map((paginacao_profissoes_value, index_pag) => {
                                // Converte ambos para string para garantir uma comparação correta
                                const isActive = paginacao_profissoes_value.text.trim() === String(page);
                                return (
                                    <li key={index_pag} className={`page-item ${isActive ? 'active' : ''} ${paginacao_profissoes_value.disabled ? 'disabled' : ''}`}>
                                        <a
                                            className="page-link"
                                            href={paginacao_profissoes_value.href}
                                            tabIndex={paginacao_profissoes_value.disabled ? '-1' : '0'}
                                            aria-disabled={paginacao_profissoes_value.disabled ? 'true' : 'false'}
                                        >
                                            {paginacao_profissoes_value.text.trim()}
                                        </a>
                                    </li>
                                );
                            })}
                        </ul>
                    </nav>
                </div>
            </div>
        );
    };
    const rootElement = document.querySelector('.app_listar_profissao');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListarProfissao />);
    // ReactDOM.render(<AppListarProfissao />, document.querySelector('.app_listar_profissao'));
</script>
<?php
$parametros_backend = array();
?>