<script type="text/babel">
    const AppMenu = ({
        parametros = {},
    }) => {

        // APIs
        const base_url = parametros.base_url || '';
        const token_csrf = parametros.token_csrf || '';
        const api_post_filtrar_menu = parametros.api_post_filtrar_menu || '';
        const api_post_menu_atualizar = parametros.api_post_menu_atualizar || '';
        const getVar_page = parametros.getVar_page || '1';

        // Variáveis
        const [isLoadingPage, setIsLoadingPage] = React.useState(false);
        const [isLoading, setIsLoading] = React.useState(true);
        const [listApi, setListApi] = React.useState([]);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);
        const [message, setMessage] = React.useState({
            show: false,
            type: 'light',
            message: ''
        });

        // POST Padrão 
        const fetchPostFilterMenu = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_filtrar_menu, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage + '&limit=9000';
            console.log('fetchPostFilterMenu :: url :: ', url);
            const setData = formData;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                console.log('fetchPostFilterMenu :: data :: ', data);

                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    setListApi(dbResponse);
                    setPagination('list');
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas objeto cadastradas'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        const fetchMenuAtualizar = async (custonBaseURL = base_url, custonGet = api_post_menu_atualizar, get_id = 'erro', get_permitido = 'N') => {
            const url = `${custonBaseURL}${custonGet}`;
            // console.log('fetchMenuAtualizar URL:', url);
            setIsLoadingPage(true);
            console.log('fetchSeguranca URL:', url);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(
                        {
                            token_csrf: token_csrf,
                            json: '1',
                            id: get_id,
                            permissao: get_permitido
                        }
                    )
                });

                if (!response.ok) throw new Error(`Erro na requisição: ${response.statusText}`);

                const dataReturn = await response.json();
                console.log('dataReturn: ', dataReturn);

                if (dataReturn.result && dataReturn.result.affectedRows && dataReturn.result.affectedRows > 0) {
                    console.log('dataReturn: ', dataReturn.result);
                    await fetchPostFilterMenu();
                    setIsLoadingPage(false);
                }
                
                return dataReturn.result.dbResponse || [];
                
            } catch (error) {
                console.error('Erro na requisição POST:', error.message);
                return [];
            }
            setIsLoadingPage(false);
        };

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');
            console.log('parametros :: ', parametros);
            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    await fetchPostFilterMenu();

                } catch (error) {
                    console.error('Erro ao carregar dados:', error);

                } finally {
                    setIsLoading(false);
                    console.log('Termino React.useEffect');

                }
            };

            loadData();

        }, []);

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
                <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                    <table className="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">###</th>
                                <th scope="col">PERFIL</th>
                                <th scope="col">DESCRIÇÃO</th>
                                <th scope="col">MENU</th>
                            </tr>
                        </thead>
                        <tbody>

                            {(isLoading && listApi.length < 1) && (
                                <tr>
                                    <td colSpan="4">
                                        <div className="m-5">
                                            <AppLoading parametros={{
                                                tipoLoading: "progress",
                                                carregando: isLoading
                                            }} />
                                        </div>
                                    </td>
                                </tr>
                            )}
                            {listApi.map((var_list_api_coluna, index_var_list_api) => (
                                <tr key={index_var_list_api}>
                                    <td>
                                        {/* Positivo/Negativo */}
                                        {var_list_api_coluna.permissao === "Y" ? (
                                            <div>
                                                <button
                                                    type="button"
                                                    className="btn btn-success btn-sm me-1"
                                                    onClick={() => fetchMenuAtualizar(base_url, api_post_menu_atualizar, var_list_api_coluna.id, 'Y')}
                                                    disabled
                                                >
                                                    <i className="bi bi-hand-thumbs-up"></i>
                                                </button>
                                                <button
                                                    type="button"
                                                    className="btn btn-outline-danger btn-sm ms-1"
                                                    onClick={() => fetchMenuAtualizar(base_url, api_post_menu_atualizar, var_list_api_coluna.id, 'N')}
                                                >
                                                    <i className="bi bi-hand-thumbs-down"></i>
                                                </button>
                                            </div>
                                        ) : (
                                            <div>
                                                <button
                                                    type="button"
                                                    className="btn btn-outline-success btn-sm me-1"
                                                    onClick={() => fetchMenuAtualizar(base_url, api_post_menu_atualizar, var_list_api_coluna.id, 'Y')}
                                                >
                                                    <i className="bi bi-hand-thumbs-up"></i>
                                                </button>
                                                <button
                                                    type="button"
                                                    className="btn btn-danger btn-sm ms-1"
                                                    onClick={() => fetchMenuAtualizar(base_url, api_post_menu_atualizar, var_list_api_coluna.id, 'N')}
                                                    disabled
                                                >
                                                    <i className="bi bi-hand-thumbs-down"></i>
                                                </button>
                                            </div>
                                        )}
                                    </td>
                                    <td>{var_list_api_coluna.PerfilDescricao || ''}</td>
                                    <td>{var_list_api_coluna.CargoFuncaoDescricao || ''}</td>
                                    <td>{var_list_api_coluna.MenuDescricao || ''}</td>
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

                    {typeof AppJson !== "undefined" ? (
                        <div>
                            <AppJson
                                parametros={parametros}
                                dbResponse={listApi}
                            />
                        </div>
                    ) : (
                        <div>
                            <p className="text-danger">AppJson não lacançado.</p>
                        </div>
                    )}

                    {typeof AppLoadingPage !== "undefined" ? (
                        <div>
                            <AppLoadingPage
                                parametros={{
                                    isLoadingPage: isLoadingPage
                                }}
                            />
                        </div>
                    ) : (
                        <div>
                            <p className="text-danger">AppLoadingPage não lacançado.</p>
                        </div>
                    )}

                </div>
            </div>
        );
    };
</script>