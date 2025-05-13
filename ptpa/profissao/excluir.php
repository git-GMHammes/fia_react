<?php
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
    'api_get_profissao' => 'index.php/fia/ptpa/profissao/api/exibir',
    'api_post_filter_profissoes' => 'index.php/fia/ptpa/profissao/api/filtrar',
);
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
// myPrint($parametros_backend, 'Linha 19');
?>

<div class="app_excluir_profissao" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppExcluirProfissao = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_excluir_profissao').getAttribute('data-result'));
        
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const base_paginator = base_url+parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;
        const api_get_profissao = parametros.api_get_profissao;
        const api_post_filter_profissoes = parametros.api_post_filter_profissoes;

        // Variáveis da API
        const [profissoes, setProfissoes] = React.useState([]);
        const [paginacaoProfissoes, setPaginacaoProfissoes] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);

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
    
                    // Envia uma requisição POST para a API com os dados coletados
                    fetch(`${base_url}${api_post_filter_profissoes}${getVar_page}`, {
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
                            setProfissoes(data.result.dbResponse);
                            setPagination(true);
                        }
                    })
                    .catch((error) => {
                        setError('Erro ao Enviar Filtro: ' + error.message);
                    });
            };

        // React.useEffect
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

        // Fetch para obter os Sexos
        const fetchProfissoes = async () => {
                try {
                    const response = await fetch(base_url + api_get_profissao + getVar_page);
                    const data = await response.json();
                    console.log('Profissões: ', data);
                    if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                        setProfissoes(data.result.dbResponse);
                        setPagination(true);
                    }
                    if (data.result.linksArray && data.result.linksArray.length > 0) {
                        setPaginacaoProfissoes(data.result.linksArray);
                    }
                } catch (error) {
                    setError('Erro ao carregar Profissão: ' + error.message);
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
                            <div className="ms-3" style={verticalBarStyle}></div>
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
                                        <input data-api="filtro-profissao" type="text" name="Codigo" className="form-control form-control-sm" style={formControlStyle} placeholder="Codigo" aria-label=".form-control-sm example"/>
                                    </form>
                                </div>
                                Código da Profissão
                            </th>
                            <th scope="col" className="text-nowrap">
                                <div className="collapse" style={formGroupStyle} id="collapseExample">
                                    <form action="">
                                        <input data-api="filtro-profissao" type="text" name="Descricao" className="form-control form-control-sm" style={formControlStyle} placeholder="Descricao" aria-label=".form-control-sm example"/>
                                    </form>
                                </div>
                                Descrição
                            </th>
                            <th scope="col" className="text-nowrap">
                                <div className="collapse" style={formGroupStyle} id="collapseExample">
                                    <form action="">
                                        <input data-api="filtro-profissao" type="text" name="Favorito" className="form-control form-control-sm" style={formControlStyle} placeholder="Favorito" aria-label=".form-control-sm example"/>
                                    </form>
                                </div>
                                Favorito
                            </th>
                            <th scope="col" className="text-nowrap">
                                <div className="collapse" style={formGroupStyle} id="collapseExample">
                                    <button className="btn" onClick={() => submitAllForms('filtro-profissao')} type="submit">Filtrar</button>
                                </div>
                                EXCLUIR
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            {profissoes.map((profissoes_value, index) => (
                                <tr key={index} className="col-12 col-sm-3 mb-5">
                                    <td>{profissoes_value.Codigo}</td>
                                    <td>{profissoes_value.Descricao}</td>
                                    <td>{profissoes_value.Favorito}</td>
                                    <td>
                                            {/* Button trigger modal */}
                                        <button type="button" className="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target={`#exampleModal${index}`}>
                                            <i className="bi bi-trash"></i>
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
                                <th>&nbsp;</th>
                            </tr>
                        </tfoot>
                    </table>
                    {profissoes.map((profissoes_value, index) => (
                        <div key={index}>
                            {/* Modal */}
                            <div className="modal fade" id={`exampleModal${index}`} tabIndex="-1" aria-labelledby={`exampleModalLabel${index}`} aria-hidden="true">
                                <div className="modal-dialog">
                                    <div className="modal-content">
                                        <div className="modal-header">
                                            <h5 className="modal-title" id={`exampleModalLabel${index}`}>Confirmar Exclusão</h5>
                                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div className="modal-body">
                                            <div className="d-flex justify-content-center">
                                               <b>Profissao:&nbsp;</b>{profissoes_value.Descricao}<br/>
                                            </div>
                                            <hr/>
                                            <div className="d-flex justify-content-center">
                                               <b>Código da Profissão:&nbsp;</b>{profissoes_value.Codigo}<br/>
                                            </div>
                                            <hr/>
                                        </div>
                                        <div className="modal-footer">
                                            <a className="btn btn-outline-danger" href={`${base_url}index.php/fia/ptpa/profissao/api/deletar/${profissoes_value.id}${getVar_page}`} role="button">
                                                Excluir
                                            </a>
                                            <button type="button" className="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                    <nav aria-label="Profissoes">
                        <ul className="pagination">
                            {pagination === true && paginacaoProfissoes.map((paginacao_profissoes_value, index) => {
                            // Converte ambos para string para garantir uma comparação correta
                            const isActive = paginacao_profissoes_value.text.trim() === String(page);

                            return (
                                <li 
                                key={index} 
                                className={`page-item ${isActive ? 'active' : ''} ${paginacao_profissoes_value.disabled ? 'disabled' : ''}`}
                                >
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
    //ReactDOM.render(<AppExcluirProfissao />, document.querySelector('.app_excluir_profissao'));

    const rootElement = document.querySelector('.app_excluir_profissao');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppExcluirProfissao />);
</script>
<?php
$parametros_backend = array();
?>