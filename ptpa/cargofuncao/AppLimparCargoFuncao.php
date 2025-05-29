<?php
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
    'api_get_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/exibir',
    'api_post_filter_cargofuncao' => 'index.php/fia/ptpa/cargofuncao/api/filtrarlixo',
);
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
?>

<div class="app_limpar_cargofuncao" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppLimparCargoFuncao = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_limpar_cargofuncao').getAttribute('data-result'));

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const user_session = parametros.user_session.FIA || {};
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;

        //Base Lista Cargo/Função
        const api_post_filter_cargofuncao = parametros.api_post_filter_cargofuncao;
        const api_get_cargofuncao = parametros.api_get_cargofuncao;
        const api_get_municipio = parametros.api_get_municipio;
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        // Variáveis da API
        const [cargosfuncao, setCargosFuncao] = React.useState([]);
        const [paginacaoCargoFuncao, setPaginacaoCargoFuncao] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

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

            if (apiIdentifier == 'filtro-cargofuncao') {
                console.log('filtro-cargofuncao');
                // Envia uma requisição POST para a API com os dados coletados
                fetch(`${base_url}${api_post_filter_cargofuncao}${getVar_page}`, {
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
                            console.log('filtro-cargofuncao:', data.result.dbResponse);
                            setCargosFuncao(data.result.dbResponse);
                        }
                    })
                    .catch((error) => {
                        setError('Erro ao Enviar Filtro: ' + error.message);
                    });

            }
        };

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect');
            // Fetch para obter as Cargo/Função
            const fetchCargosFuncao = async () => {
                try {
                    const response = await fetch(base_url + api_get_cargofuncao + getVar_page);
                    console.log('URL:: ', base_url + api_get_cargofuncao + getVar_page);
                    const data = await response.json();
                    console.log('CargoFuncao: ', data);
                    if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                        setCargosFuncao(data.result.dbResponse);
                        setPagination(true);
                        setDataLoading(false);
                    }
                    if (data.result.linksArray && data.result.linksArray.length > 0) {
                        setPaginacaoCargoFuncao(data.result.linksArray);
                    }
                } catch (error) {
                    setError('Erro ao carregar Cargo/Função: ' + error.message);
                }

            };

            // Informe todos os FETCHs Desenvolvidos aqui
            const loadData = async () => {

                if (!debugMyPrint) {
                    console.log('loadData');
                }
                await fetchCargosFuncao();
                setIsLoading(false);
            };

            loadData();

        }, []);

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

        if (debugMyPrint && isLoading) {
            return <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        }

        if (debugMyPrint && error) {
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
                    <table className="table table-striped">
                        <thead className="border border-2 border-dark border-start-0 border-end-0">
                            <tr>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-cargofuncao" type="text" name="cargofuncao" className="form-control form-control-sm" style={formControlStyle} placeholder="CARGO/FUNÇÃO" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    CARGO/FUNÇÃO
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-cargofuncao" type="text" name="form_on" className="form-control form-control-sm" style={formControlStyle} placeholder="FORM_ON" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    FORM_ON
                                </th>
                                <th scope="col" className="text-nowrap">
                                    RESTAURAR
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <button className="btn" onClick={() => submitAllForms('filtro-cargofuncao')} type="submit" style={{ width: '100%', fontSize: '0.8rem', padding: '0.375rem 0.75rem', borderRadius: '0.25rem', boxSizing: 'border-box' }}>Filtrar</button>
                                    </div>
                                    ELIMINAR
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            {dataLoading && (
                                <tr>
                                    <td colSpan="8">
                                        <AppLoading parametros={{
                                            tipoLoading: "progress",
                                            carregando: dataLoading
                                        }} />
                                    </td>
                                </tr>
                            )}

                            {cargosfuncao.map((cargosfuncao_value, index) => (
                                <tr key={index} className="col-12 col-sm-3 mb-5">
                                    <td>{cargosfuncao_value.cargo_funcao}</td>
                                    <td>{cargosfuncao_value.form_on}</td>
                                    <td>
                                        <a className="btn btn-outline-success" href={`${base_url}index.php/fia/ptpa/cargofuncao/api/deletar/${cargosfuncao_value.id}/restaurar${getVar_page}`} role="button">
                                            <i className="bi bi-reply-all"></i>
                                        </a>
                                    </td>
                                    <td>
                                        {/* Button trigger modal */}
                                        <button type="button" className="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target={`#EliminarCargoFuncaoModal${index}`}>
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
                    {cargosfuncao.map((cargosfuncao_value, index) => (
                        <div key={index}>
                            {/* Modal */}
                            <div className="modal fade" id={`EliminarCargoFuncaoModal${index}`} tabIndex="-1" aria-labelebdy={`EliminarCargoFuncaoModalLabel${index}`} aria-hidden="true">
                                <div className="modal-dialog">
                                    <div className="modal-content">
                                        <div className="modal-header">
                                            <h5 className="modal-title" id={`EliminarCargoFuncaoModalLabel${index}`}>Confirme a ELIMINAÇÃO permanente do Registro:</h5>
                                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div className="modal-body">
                                            <div className="d-flex justify-content-center">
                                                <b>Cargo/Função:&nbsp;</b>{cargosfuncao_value.cargofuncao}<br />
                                            </div>
                                            <hr />
                                            <div className="d-flex justify-content-center">
                                                <b>Form_on:&nbsp;</b>{cargosfuncao_value.form_on}<br />
                                            </div>
                                            <hr />
                                        </div>
                                        <div className="modal-footer">
                                            <a className="btn btn-outline-danger" href={`${base_url}index.php/fia/ptpa/cargofuncao/api/deletar/${cargosfuncao_value.id}/eliminar${getVar_page}`} role="button">
                                                Eliminar
                                            </a>
                                            <button type="button" className="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                    <nav aria-label="CargoFuncao">
                        <ul className="pagination">
                            {pagination === true && paginacaoCargoFuncao.map((paginacao_cargosfuncao_value, index) => {
                                // Converte ambos para string para garantir uma comparação correta
                                const isActive = paginacao_cargosfuncao_value.text.trim() === String(page);

                                return (
                                    <li key={index} className={`page-item ${isActive ? 'active' : ''} ${paginacao_cargosfuncao_value.disabled ? 'disabled' : ''}`}>
                                        <a
                                            className="page-link"
                                            href={paginacao_cargosfuncao_value.href}
                                            tabIndex={paginacao_cargosfuncao_value.disabled ? '-1' : '0'}
                                            aria-disabled={paginacao_cargosfuncao_value.disabled ? 'true' : 'false'}>
                                            {paginacao_cargosfuncao_value.text.trim()}
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
    const rootElement = document.querySelector('.app_limpar_cargofuncao');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppLimparCargoFuncao />);
</script>