<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');
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
    'token_csrf' => $token_csrf,
);
$parametros_backend['api_get_atualizar_genero'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/genero/api/exibir' . $atualizar_id) : ('fia/ptpa/genero/api/exibir/erro');
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
?>

<div class="app_form_generoidentidade" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppFormGeneroIdentidade = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_form_generoidentidade').getAttribute('data-result'));

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf;

        //Base Cadastro Genero Identidade
        const api_get_atualizar_genero = parametros.api_get_atualizar_genero;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        // Declare todas as Listas, NO PLURAL de APIs aqui:
        const [generos, setGeneros] = React.useState([]);

        //Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            id: null,
            genero: null,
            descricao : null,
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
        };

        // Inicializando o debounceTimeout com useRef
        const debounceTimeout = React.useRef(null);

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
            if(apiIdentifier == 'form-genero'){
                console.log('form-genero');
                // Envia uma requisição POST para a API com os dados coletados
                fetch(`${base_url}${api_post_filter_generos}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data), // Converte o objeto 'data' em uma string JSON para enviar no corpo da requisição
                })
                .then(response => response.json())
                .then(data => {
                    if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                        // console.log('form-genero:', data.result.dbResponse);
                        setProfissoes(data.result.dbResponse);
                    }
                })
                .catch((error) => {
                    setError('Erro ao Enviar Filtro: ' + error.message);
                });
            };
        };

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchGeneros();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter os dados das Generos
        const fetchGeneros = async () => {
                try {
                    const response = await fetch(base_url + api_get_atualizar_genero);
                    const data = await response.json();
                    console.log('Gêneros: ', data);
                    if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                        setFormData(data.result.dbResponse[0]);
                    }
                } catch (error) {
                    setError('Erro ao carregar Gêneros: ' + error.message);
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
            marginRight: '10px',
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
            fontSize: '1.rem',
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
                    <div className="col-12 ">
                        <div className="d-flex align-items-center">
                            <div className="ms-3" style={verticalBarStyle}></div>
                            <h2 className="myBold">{title}</h2>
                        </div>
                    </div>
                </div>
                {/* formulario Genero Identidade*/}
                <form action={base_url+'index.php/fia/ptpa/genero/api/cadastrar'} method="post" className="row needs-validation m-2">

                    <input type="hidden" id="id" name="id" value={formData.id || ''} className="form-control" required />
                    <input type="hidden" id="token_csrf" name="token_csrf" value={token_csrf} className="form-control" required />
                    
                    <div className="row">
                        <div className="col-12 col-sm-12">
                            <div className="card mb-4">
                                <div className="card-body">
                                    <div className="row">
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="genero" style={formLabelStyle} className="form-label">Genero</label>
                                                <input data-api="dados-genero" type="text" id="genero" name="genero" value={formData.genero || ''} onChange={handleChange} style={formControlStyle} className="form-control" required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="descricao" style={formLabelStyle} className="form-label">Descrição</label>
                                                <input data-api="dados-genero" type="text" id="descricao" name="descricao" value={formData.descricao || ''} onChange={handleChange} style={formControlStyle} className="form-control" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-12">
                            <button className="btn btn-outline-success mb-5" onClick={() => submitAllForms('dados-genero')} type="submit">Enviar</button>
                        </div>
                    </div>
                </form>
                 {/* formulario Genero Identidade*/}
            </div>
        );
    };
    //ReactDOM.render(<AppFormGeneroIdentidade />, document.querySelector('.app_form_generoidentidade'));

    const rootElement = document.querySelector('.app_form_generoidentidade');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppFormGeneroIdentidade />);
</script>
<?php
$parametros_backend = array();
?>