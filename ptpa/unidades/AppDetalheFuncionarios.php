<script type="text/babel">
    const AppDetalheFuncionarios = ({ parametros = {} }) => {

        // console.log('parametros: ', parametros);
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const atualizar_id = parametros.atualizar_id ? parametros.atualizar_id.replace("/", "") : '';
        // console.log('atualizar_id: ', atualizar_id);

        const getURI = parametros.getURI || '';
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const base_url = parametros.base_url || '';

        // Base Lista Profissional
        const api_post_filter_profissional = parametros.api_post_filter_profissional || '';

        // Variáveis da API
        const [profissionais, setProfissionais] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);

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
                console.log('fetchProfissionais iniciando...');
                console.log(base_url + api_post_filter_profissional);
                const response = await fetch(base_url + api_post_filter_profissional, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'UnidadeId': atualizar_id
                    })
                });
                const dataReturn = await response.json();
                console.log('Profissional: ', dataReturn);
                if (dataReturn.result && dataReturn.result.dbResponse && dataReturn.result.dbResponse.length > 0) {
                    setProfissionais(dataReturn.result.dbResponse);
                    setDataLoading(false);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Nenhum Funcionário encontrado para esta Unidade'
                    });
                    setDataLoading(false);
                }
            } catch (error) {
                console.error('Erro ao carregar Profissionais: ' + error.message);
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

                    <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                        <table className="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            NOME
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            E-MAIL
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            TELEFONE
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            CARGO/FUNÇÃO
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            PROGRAMA/FIA
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            PERFIL
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            DT/ADMISSÃO
                                        </div>
                                    </th>
                                    <th scope="col">
                                        <div className="d-flex justify-content-center">
                                            DT/DEMISSÃO
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

                                {dataLoading && (
                                    <tr>
                                        <td colSpan="9">
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
                                        <td>
                                            <div className="d-flex justify-content-center">
                                                {profissional_value.Nome}
                                            </div>
                                        </td>
                                        <td>
                                            <div className="d-flex justify-content-center">
                                                {profissional_value.Email}
                                            </div>
                                        </td>
                                        <td>
                                            <div className="d-flex justify-content-center">
                                                {profissional_value.TelefoneRecado}
                                            </div>
                                        </td>
                                        <td>
                                            <div className="d-flex justify-content-center">
                                                {profissional_value.CargoFuncao}
                                            </div>
                                        </td>
                                        <td>
                                            <div className="d-flex justify-content-center">
                                                {profissional_value.ProgramaSigla}
                                            </div>
                                        </td>
                                        <td>
                                            <div className="d-flex justify-content-center">
                                                {profissional_value.PerfilDescricao}
                                            </div>
                                        </td>
                                        <td>
                                            <div className="d-flex justify-content-center">
                                                {profissional_value.DataAdmissao}
                                            </div>
                                        </td>
                                        <td>
                                            <div className="d-flex justify-content-center">
                                                {profissional_value.DataDemissao}
                                            </div>
                                        </td>
                                        <td>
                                            <div className="d-flex justify-content-center">
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
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </tfoot>
                        </table>

                        {/* Paginação */}
                        <div>
                            <nav aria-label="Page navigation example">
                                <ul className="pagination">
                                    {paginacaoLista.map((paginacao_value, index) => (
                                        <li key={index} className={`page-item ${paginacao_value.active ? 'active' : ''}`}>
                                            <button
                                                className="page-link"
                                                onClick={() => fetchProfissionais(base_url, api_get_profissionais, paginacao_value.href)}
                                            >
                                                {paginacao_value.text.trim()}
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </nav>
                        </div>

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
                                            <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}

                        {/* Modais para cada profissional */}
                        <AppMessageCard parametros={message} modalId="modal_detalhes_funcionario" />
                    </div>
                </div>
            </div >
        );
    };

</script>
<?php
$parametros_backend = array();
?>