<?php
$parametros_backend = array(
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'result' => isset($result) ? ($result) : (array()),
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'api_get_pointer' => 'index.php/fia/ptpa/principal/api/indicadores',
    'api_get_programa' => 'index.php/fia/ptpa/programa/api/exibir'
);
// myPrint($parametros_backend, 'src\app\Views\fia\ptpa\principal\main_pointer.php');
?>

<div class="app_tela_sso" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppTelaSSO = () => {
        const { useState, useEffect } = React
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_tela_sso').getAttribute('data-result'));

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const api_get_pointer = parametros.api_get_pointer;
        const api_get_programa = parametros.api_get_programa;

        // Declare todas as Listas, NO PLURAL de APIs aqui
        const [dados, setDados] = useState([]);
        const [pointers, setPointers] = useState([]);
        const [programas, setProgramas] = useState([]);

        const [error, setError] = React.useState(null);
        const [carregando, setCarregando] = useState(true)

        useEffect(() => {
            // Fetch para obter os Apontamentos
            const fetchPointer = async () => {
                console.log('API:: ', base_url + api_get_pointer);
                try {
                    const response = await fetch(base_url + api_get_pointer);
                    const data = await response.json();
                    console.log('Apontamentos:: ', data.result);
                    if (
                        data.result.qtd_adolescente[0].Qtd &&
                        data.result.qtd_profissionais[0].Qtd &&
                        data.result.qtd_unidades[0].Qtd &&
                        data.result.qtd_vagas[0].Qtd
                    ) {
                        setDados([

                            { number: data.result.qtd_adolescente[0].Qtd, text: 'Adolecentes Cadastrados' },
                            { number: data.result.qtd_profissionais[0].Qtd, text: 'Unidades Cadastradas' },
                            { number: data.result.qtd_unidades[0].Qtd, text: 'Profissionais Cadastrados' },
                            { number: data.result.qtd_vagas[0].Qtd, text: `Vagas Livres para ${new Date().getFullYear()}` },
                        ]);
                        // console.log('setDados:: ', dados);
                    } else {
                        setDados([
                            { number: 0, text: 'Adolecentes Cadastrados' },
                            { number: 0, text: 'Unidades Cadastradas' },
                            { number: 0, text: 'Profissionais Cadastrados' },
                            { number: 0, text: `Vagas Livres para ${new Date().getFullYear()}` },
                        ]);
                    }
                } catch (error) {

                    setDados([
                        { number: 0, text: 'Adolecentes Cadastrados' },
                        { number: 0, text: 'Unidades Cadastradas' },
                        { number: 0, text: 'Profissionais Cadastrados' },
                        { number: 0, text: `Vagas Livres para ${new Date().getFullYear()}` },
                    ]);
                    // setError('Erro ao carregar Apontamentos: ' + error.message);
                }
            };

            // Fetch para obter os Programas
            const fetchProgramas = async () => {
                try {
                    const response = await fetch(base_url + api_get_programa);
                    const data = await response.json();
                    // console.log('Programas: ', data);
                    if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                        setProgramas(data.result.dbResponse);
                    }
                } catch (error) {
                    setError('Erro ao carregar Programas: ' + error.message);
                }
            };

            // Informe todos os FETCHs Desenvolvidos aqui
            const loadData = async () => {

                if (!debugMyPrint) {
                    await fetchPointer();
                    await fetchProgramas();
                }

                setCarregando(false);
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
            marginRight: '10px',
        };

        // Definindo o estilo da altura
        const cardBodyStyle = {
            height: '100px',
            overflow: 'hidden', // Adiciona uma rolagem se o conteúdo exceder a altura
        };

        const origemForm = 'adolescente'

        if (carregando) {
            return (
                <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Loading...</span>
                    </div>
                </div>
            );
        }

        if (error) {
            return <div className="d-flex align-items-center justify-content-center" style={myMinimumHeight}>
                <div className="alert alert-danger" role="alert">
                    {error}
                </div>
            </div>
        }

        return (
            <div className="container py-5 position-relative" style={{ minHeight: '100vh' }}>
                {debugMyPrint ? (
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                            Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ) : null}
                <div className="row mb-5">
                    <div className="col-12 mb-3">
                        <div className="d-flex align-items-center">
                            <div style={verticalBarStyle}></div>
                            <h2 className="myBold">Página Inicial</h2>
                        </div>
                    </div>
                </div>
                <div className="row mb-5">
                    {dados.map((pointer_value, index) => (
                        <div key={index} className="col-12 col-sm-4">
                            <div className="card border-info mb-3">
                                <div className="card-header text-light bg-info">{pointer_value.text}</div>
                                <div className="card-body" style={cardBodyStyle}>
                                    <h1 className="display-6 text-center myBold">{parseInt(pointer_value.number).toLocaleString('pt-BR')}</h1>
                                </div>
                            </div>
                        </div>
                    ))}
                    {programas.map((programas_card, index) => (
                        <div key={index} className="col-12 col-sm-4">
                            <div className="card border-info mb-3">
                                <div className="card-header text-light bg-info">{programas_card.Sigla}</div>
                                <div className="card-body" style={cardBodyStyle}>
                                    <div className="m-1">
                                        <a className="btn" href={programas_card.Link} role="button" target="_blank">
                                            {programas_card.Descricao}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        );
    };
    // ReactDOM.render(<AppTelaSSO />, document.querySelector('.app_tela_sso'));

    const rootElement = document.querySelector('.app_tela_sso');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppTelaSSO />);
</script>
<?php
$parametros_backend = array();
?>