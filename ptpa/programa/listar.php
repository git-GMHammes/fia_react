<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'result' => isset($result) ? ($result) : (array()),
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'page' => isset($metadata['getVar_page']) ? ($metadata['getVar_page']) : ('1'),
    'base_url' => base_url(),
    'api_get_programa' => 'index.php/fia/ptpa/programa/api/exibir',
    'api_post_filter_programas' => 'index.php/fia/ptpa/programa/api/filtrar',
);
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
?>

<div class="app_listar_programa" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListarPrograma = () => {
        
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar_programa').getAttribute('data-result'));
        
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf;

        //Base Lista Programa
        const api_get_programa = parametros.api_get_programa;
        const base_paginator = base_url+parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;
        const api_post_filter_programas = parametros.api_post_filter_programas;

        // Variáveis da API
        const [programas, setProgramas] = React.useState([]);
        const [paginacaoProgramas, setPaginacaoProgramas] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            id: null,
            Sigla : null,
            Descricao : null,
            Link: null,
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
        const handleOpenModal = (programa) => {
            setFormData(programa);
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

            if(apiIdentifier == 'filtro-programa'){
                console.log('filtro-programa');
                    // Envia uma requisição POST para a API com os dados coletados
                    fetch(`${base_url}${api_post_filter_programas}`, {
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
                        // console.log('filtro-programa:', data.result.dbResponse);
                        setProgramas(data.result.dbResponse);
                    }
                })
                .catch((error) => {
                    setError('Erro ao ENviar Filtro: ' + error.message);
                });
            }
        };

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchProgramas();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter as Programas
        const fetchProgramas = async () => {
                try {
                    const response = await fetch(base_url + api_get_programa + getVar_page);
                    const data = await response.json();
                    // console.log('Programas: ', data);
                    if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                        setProgramas(data.result.dbResponse);
                        setPagination(true);
                    }
                    if (data.result.linksArray && data.result.linksArray.length > 0) {
                        setPaginacaoProgramas(data.result.linksArray);
                    }
                } catch (error) {
                    setError('Erro ao carregar Programa: ' + error.message);
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

        if(isLoading){
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
                {debugMyPrint?(
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                                Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ):null}
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
                                        <input data-api="filtro-programa" className="form-control form-control-sm" style={formControlStyle} type="text" name="Sigla" placeholder="Sigla" aria-label=".form-control-sm example"/>
                                    </form>
                                </div>
                                Sigla
                            </th>
                            <th scope="col" className="text-nowrap">
                                <div className="collapse" style={formGroupStyle} id="collapseExample">
                                    <form action="">
                                        <input data-api="filtro-programa" className="form-control form-control-sm" style={formControlStyle} type="text" name="Descricao" placeholder="Descrição" aria-label=".form-control-sm example"/>
                                    </form>
                                </div>
                                Descrição
                            </th>
                            <th scope="col" className="text-nowrap">
                                {/*Submit data-api*/}
                                <div className="collapse" style={formGroupStyle} id="collapseExample">
                                <button className="btn" onClick={() => submitAllForms('filtro-programa')} type="submit" style={{width: '100%',fontSize: '0.8rem',padding: '0.375rem 0.75rem',borderRadius: '0.25rem',boxSizing: 'border-box'}}>Filtrar</button>
                                </div>
                                EDITAR
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            {programas.map((programas_value, index_lista_programa) => (
                                <tr key={index_lista_programa} className="col-12 col-sm-3 mb-5">
                                    <td>{programas_value.Sigla}</td>
                                    <td>{programas_value.Descricao}</td>
                                    <td>
                                        {/* Botão para acionar o modal */}
                                        <button type="button" className="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target={`#staticBackdropPrograma${index_lista_programa}`} onClick={() => handleOpenModal(programas_value)}>
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

                    {/* Modais para cada programa */}
                    {programas.map((programa, index) => (
                        <div key={index} className="modal fade" id={`staticBackdropPrograma${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropProgramaLabel${index}`} aria-hidden="true">
                            {/* modal-fullscreen / modal-x1 */}
                            <div className="modal-dialog modal-xl">
                                <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title" id={`staticBackdropProgramaLabel${index}`}>Detalhes do Programa</h5>
                                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div className="modal-body">
                                    {/* formulario Programa*/}
                                    <form action={base_url+'index.php/fia/ptpa/programa/api/atualizar'} method="post" className="row was-validated m-2">
                                    
                                        {/*Caompos Ocultos*/}
                                        <input data-api="dados-programa" type="hidden" name="id" value={formData.id || ''} onChange={handleChange} className="form-control" id="id"/>
                                        <input type="hidden" name="token_csrf" className="form-control" id="token_csrf" value={token_csrf} required />

                                        <div className="row">
                                            <div className="col-12 col-sm-12">
                                                <div className="card mb-4">
                                                    <div className="card-body">
                                                        <div className="row">
                                                            <div className="col-12 col-sm-4 mb-3">
                                                                <div style={formGroupStyle}>
                                                                    <label htmlFor="Sigla" style={formLabelStyle} className="form-label">Sigla<strong style={requiredField}>*</strong></label>
                                                                    <input data-api="dados-programa" type="text" name="Sigla" value={formData.Sigla || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="Sigla" required />
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-4 mb-3">
                                                                <div style={formGroupStyle}>
                                                                    <label htmlFor="Descricao" style={formLabelStyle} className="form-label">Descrição<strong style={requiredField}>*</strong></label>
                                                                    <input data-api="dados-programa" type="text" name="Descricao" value={formData.Descricao || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="Descricao" required />
                                                                </div>
                                                            </div>
                                                            <div className="col-12 col-sm-1 mb-3">
                                                                <div style={formGroupStyle} className="d-flex align-items-center justify-content-center">
                                                                    <label htmlFor="Link" style={formLabelStyle} className="form-label">Link<strong style={requiredField}>*</strong></label>
                                                                    <input data-api="dados-programa" type="checkbox" name="Link" value={formData.Link || ''} onChange={handleChange} className="form-check-input" id="Link" />
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
                                    {/* formulario Programa*/}
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                                </div>
                            </div>
                        </div>
                    ))}

                    <nav aria-label="Programas">
                        <ul className="pagination">
                            {pagination == true && paginacaoProgramas.map((paginacao_programas_value, index_pag) => {
                            // Converte ambos para string para garantir uma comparação correta
                            const isActive = paginacao_programas_value.text.trim() === String(page);
                            return (
                                <li key={index_pag} className={`page-item ${isActive ? 'active' : ''} ${paginacao_programas_value.disabled ? 'disabled' : ''}`}>
                                <a 
                                    className="page-link" 
                                    href={paginacao_programas_value.href}
                                    tabIndex={paginacao_programas_value.disabled ? '-1' : '0'}
                                    aria-disabled={paginacao_programas_value.disabled ? 'true' : 'false'}
                                >
                                    {paginacao_programas_value.text.trim()}
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
    //ReactDOM.render(<AppListarPrograma />, document.querySelector('.app_listar_programa'));

    const rootElement = document.querySelector('.app_listar_programa');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListarPrograma />);

</script>
<?php
$parametros_backend = array();
?>