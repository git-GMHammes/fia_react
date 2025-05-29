<script type="text/babel">
    const AppObjeto = ({
        parametros = {},
        setFiltro = {},
        isLoading,
        setIsLoading,
        fetchPostFilterSegurancaObjeto,
        fetchSegurançaObjetoAtualizar,
        listaSegurancaObjeto,
        formData,
        setFormData
    }) => {
        // APIs
        const api_post_seguranca_objeto_atualizar = parametros.api_post_seguranca_objeto_atualizar || '';
        const base_url = parametros.base_url || '';

        const fetchSegurançaObjetoAtualizar = async (custonBaseURL = base_url, custonGet = api_post_seguranca_objeto_atualizar, get_id = 'erro', get_permitido = 'N') => {
            const url = `${custonBaseURL}${custonGet}`;
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
                            permitido: get_permitido
                        }
                    )
                });

                if (!response.ok) throw new Error(`Erro na requisição: ${response.statusText}`);

                const dataReturn = await response.json();

                if (dataReturn.result && dataReturn.result.affectedRows && dataReturn.result.affectedRows > 0) {
                    // console.log('dataReturn: ', dataReturn.result);
                    fetchPostFilterSegurancaObjeto();
                }

                return dataReturn.result.dbResponse || [];

            } catch (error) {
                console.error('Erro na requisição POST:', error.message);
                return [];
            }
        };

        // POST Padrão 
        const fetchPostFilterSegurancaObjeto = async (setFiltro, custonBaseURL = base_url, custonApiPostObjeto = api_post_seguranca_filter_objeto, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage + '&limit=90000';
            console.log('fetchPostFilterSegurancaObjeto URL :: ', url);
            const setData = setFiltro;
            // console.log('fetchPostFilterSegurancaObjeto setData ::', setData);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('fetchPostFilterSegurancaObjeto data :: ', data);
                // return false;
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    console.log('fetchPostFilterSegurancaObjeto dbResponse :: ', dbResponse);
                    // 
                    setListaSegurancaObjeto(dbResponse);
                    setPagination('list');
                    //
                } else {
                    setMessage({
                        show: false,
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

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');
            console.log('parametros :: ', parametros);
            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    await fetchPostFilterSegurancaObjeto(setFiltro);

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
                {typeof AppJson !== "undefined" ? (
                    <div>

                        <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                            <table className="table table-striped">
                                <thead className="border border-2 border-dark border-start-0 border-end-0">
                                    <tr>
                                        <th scope="col">AÇÃO</th>
                                        <th scope="col">MÓDULO</th>
                                        <th scope="col">MÉTODO/AÇÃO</th>
                                        <th scope="col">OBJETO</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    {isLoading && (
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
                                    {listaSegurancaObjeto.map((var_list_api_coluna, index_var_list_api) => (
                                        <tr key={index_var_list_api}>
                                            <td>
                                                {var_list_api_coluna.seg_permitido === "Y" ? (
                                                    <div>
                                                        <button
                                                            type="button"
                                                            className="btn btn-success btn-sm me-1"
                                                            onClick={() => fetchSegurançaObjetoAtualizar(base_url, api_post_seguranca_objeto_atualizar, var_list_api_coluna.id, 'Y')}
                                                            disabled
                                                        >
                                                            <i className="bi bi-hand-thumbs-up"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            className="btn btn-outline-danger btn-sm ms-1"
                                                            onClick={() => fetchSegurançaObjetoAtualizar(base_url, api_post_seguranca_objeto_atualizar, var_list_api_coluna.id, 'N')}
                                                        >
                                                            <i className="bi bi-hand-thumbs-down"></i>
                                                        </button>
                                                    </div>
                                                ) : (
                                                    <div>
                                                        <button
                                                            type="button"
                                                            className="btn btn-outline-success btn-sm me-1"
                                                            onClick={() => fetchSegurançaObjetoAtualizar(base_url, api_post_seguranca_objeto_atualizar, var_list_api_coluna.id, 'Y')}
                                                        >
                                                            <i className="bi bi-hand-thumbs-up"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            className="btn btn-danger btn-sm ms-1"
                                                            onClick={() => fetchSegurançaObjetoAtualizar(base_url, api_post_seguranca_objeto_atualizar, var_list_api_coluna.id, 'N')}
                                                            disabled
                                                        >
                                                            <i className="bi bi-hand-thumbs-down"></i>
                                                        </button>
                                                    </div>
                                                )}
                                            </td>
                                            <td>{var_list_api_coluna.seg_modulo || ''}</td>
                                            <td>{var_list_api_coluna.seg_metodo_acao || ''}</td>
                                            <td>{var_list_api_coluna.seg_objeto || ''}</td>
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
                        </div>
                        <p>Indice:</p>
                        col = coluna; <br />
                        btn = botão; <br />
                        cmp = campo; <br />

                        <AppJson
                            parametros={parametros}
                            dbResponse={listaSegurancaObjeto}
                        />

                    </div>
                ) : (
                    <div>
                        <p className="text-danger">AppJson não lacançado.</p>
                    </div>
                )}

            </div>
        );
    };
</script>