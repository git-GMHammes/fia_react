<script type="text/babel">
    const AppRota = ({
        parametros = {}
    }) => {

        // APIs
        const base_url = parametros.base_url || '';
        const token_csrf = parametros.token_csrf || '';
        const getVar_page = parametros.getVar_page || '1';
        const api_get_seguranca = parametros.api_get_seguranca || '';
        const api_post_seguranca_filter = parametros.api_post_seguranca_filter || '';
        const api_post_seguranca_atualizar = parametros.api_post_seguranca_atualizar || '';

        // Variáveis
        const [activeModalId, setActiveModalId] = React.useState(null);
        const [isLoadingPage, setIsLoadingPage] = React.useState(false);
        const [isLoading, setIsLoading] = React.useState(true);
        const [listApi, setListApi] = React.useState([]);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função para lidar com o clique do botão
        const handleModalClick = (id) => {
            setActiveModalId(id);
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            //
            token_csrf: '123',
            json: '1',
            //
            campo1: null,
            campo2: null,
            campo3: null,
            id: null,
            pf_id: null,
            pf_perfil: null,
            cf_id: null,
            cf_cargo_funcao: null,
            seg_modulo: null,
            seg_metodo_acao: null,
            seg_permitido: null,
            seg_projeto: null,
            seg_sub_projeto: null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
            cf_form_on: null,
            cf_created_at: null,
            cf_updated_at: null,
            cf_deleted_at: null,
            pf_form_on: null,
            pf_created_at: null,
            pf_updated_at: null,
            pf_deleted_at: null

        });


        // Função handleFocus para receber foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            // console.log('handleFocus: ', name);
            // console.log('handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança de campo
            if (name === 'variavel_001') {
                // console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            // console.log('handleChange: ', name);
            // console.log('handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança é no campo 'variavel_001'
            if (name === 'variavel_001') {
                // console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
            setMessage({ show: false, type: null, message: null });
        };

        // Função que executa após a retirada do foco
        const handleBlur = (event) => {
            const { name, value } = event.target;

            // console.log('name handleBlur: ', name);
            // console.log('value handleBlur: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança é no campo 'variavel_001'
            if (name === 'variavel_001') {
                // console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
            setMessage({ show: false, type: null, message: null });
        }

        const submitAllForms = async (filtro) => {
            // 
            // console.log('submitAllForms...');
            const setData = formData;
            // console.log('setData :: ', setData);
            let data = '';
            let dbResponse = [];
            let response = '';
            // console.log('Dados a serem enviados:', setData);

            if (filtro === `filtro-seguranca`) {
                // Convertendo os dados do setPost em JSON
                fetchPostFilterSeguranca(setData);
            }
        };

        // React.useEffect
        React.useEffect(() => {
            // console.log('React.useEffect - Carregar Dados Iniciais');
            // console.log('parametros :: ', parametros);
            // Função para carregar todos os dados necessários
            const loadData = async () => {
                // console.log('loadData iniciando...');

                try {
                    await fetchPostSeguranca();

                } catch (error) {
                    console.error('Erro ao carregar dados:', error);

                } finally {
                    setIsLoading(false);
                    // console.log('Termino React.useEffect');

                }
            };

            loadData();

        }, []);

        const fetchPostSeguranca = async (custonBaseURL = base_url, custonApiGet = api_get_seguranca, customPage = getVar_page) => {
            const url = `${custonBaseURL}${custonApiGet}${customPage}&limit=100`;
            // console.log('fetchSeguranca URL:', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                // console.log("fetchSeguranca data:: ", data);

                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    AppPainel
                    setIsLoading(false);
                    setListApi(data.result.dbResponse);
                    setPagination(true);
                }

                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);
                }

            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Unidades: ' + error.message
                });
                console.error('Erro ao carregar Profissionais: ' + error.message);
            }
        };
        
        const fetchSegurançaAtualizar = async (custonBaseURL = base_url, custonGet = api_post_seguranca_atualizar, get_id = 'erro', get_permitido = 'N') => {
            const url = `${custonBaseURL}${custonGet}`;
            // console.log('fetchSeguranca URL:', url);
            setIsLoadingPage(true);

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
                    await fetchPostFilterSeguranca(formData);
                    setIsLoadingPage(false);
                }

                return dataReturn.result.dbResponse || [];

            } catch (error) {
                console.error('Erro na requisição POST:', error.message);
                return [];
            }
        };

        const fetchPostFilterSeguranca = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_seguranca_filter, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage + '&limit=100';
            // console.log('fetchPostFilterSeguranca URL:', url);
            const setData = formData;
            // console.log('fetchPostFilterSeguranca setData:', setData);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // 
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
                if (data.result && data.result.linksArray && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

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
                    <div className="d-flex justify-content-end">
                        <div className="me-2">
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-seguranca`);
                            }}>
                                <input
                                    className="btn btn-secondary"
                                    style={formControlStyle}
                                    type="submit"
                                    value="Filtrar"
                                />
                            </form>
                        </div>
                    </div>
                    {/* toggle filtros */}
                    <nav className="navbar navbar-expand-lg" style={formControlStyle}>
                        <div className="container-fluid">
                            <button className="navbar-toggler bg-info" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
                                <i className="bi bi-filter" />
                            </button>
                            <div className="collapse navbar-collapse" id="navbarSupportedContent2">
                                <ul className="d-flex justify-content-around navbar-nav me-auto mb-2 mb-lg-0 w-100">
                                    <li className="nav-item flex-grow-1 mx-2">
                                        <div style={formGroupStyle}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-seguranca`);
                                            }}>
                                                <input
                                                    data-api="filtro-seguranca"
                                                    type="text"
                                                    name="pf_perfil"
                                                    value={formData.pf_perfil || ''}
                                                    onChange={handleChange}
                                                    placeholder="Perfil"
                                                    className="form-control form-control-sm"
                                                    style={formControlStyle}
                                                />
                                            </form>
                                        </div>
                                    </li>

                                    <li className="nav-item flex-grow-1 mx-2">
                                        <div style={formGroupStyle}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-seguranca`);
                                            }}>
                                                <input
                                                    data-api="filtro-seguranca"
                                                    type="text"
                                                    name="cf_cargo_funcao"
                                                    value={formData.cf_cargo_funcao || ''}
                                                    onChange={handleChange}
                                                    placeholder="Cargo Função"
                                                    className="form-control form-control-sm"
                                                    style={formControlStyle}
                                                />
                                            </form>
                                        </div>
                                    </li>

                                    <li className="nav-item flex-grow-1 mx-2">
                                        <div style={formGroupStyle}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-seguranca`);
                                            }}>
                                                <input
                                                    data-api="filtro-seguranca"
                                                    type="text"
                                                    name="seg_modulo"
                                                    value={formData.seg_modulo || ''}
                                                    onChange={handleChange}
                                                    placeholder="Módulo"
                                                    className="form-control form-control-sm"
                                                    style={formControlStyle}
                                                />
                                            </form>
                                        </div>
                                    </li>

                                    <li className="nav-item flex-grow-1 mx-2">
                                        <div style={formGroupStyle}>
                                            <form onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-seguranca`);
                                            }}>
                                                <input
                                                    data-api="filtro-seguranca"
                                                    type="text"
                                                    name="seg_metodo_acao"
                                                    value={formData.seg_metodo_acao || ''}
                                                    onChange={handleChange}
                                                    placeholder="Método"
                                                    className="form-control form-control-sm"
                                                    style={formControlStyle}
                                                />
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>

                <div>
                    {/* Divisor */}
                    <hr style={{ borderColor: 'gray', borderWidth: '1px' }} />
                    {/* Tabela */}
                    <div className="table-responsive ms-2 me-2 ps-2 pe-2">
                        <table className="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">PERMISSÃO</th>
                                    <th scope="col">#</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Perfil</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">CARGO/FUNÇÃO</th>
                                    <th scope="col">MÓDULO</th>
                                    <th scope="col">MÉTODO</th>
                                </tr>
                            </thead>
                            <tbody>
                                {isLoading && (
                                    <tr>
                                        <td colSpan="9">
                                            <div className="m-5">
                                                <AppLoading parametros={{
                                                    tipoLoading: "progress",
                                                    carregando: isLoading
                                                }} />
                                            </div>
                                        </td>
                                    </tr>
                                )}

                                {listApi.map((seguranca_coluna, index_lista_ps) => (
                                    <tr key={index_lista_ps}>
                                        <td>
                                            {/* Positivo/Negativo */}
                                            {seguranca_coluna.seg_permitido === "Y" ? (
                                                <div>
                                                    <button
                                                        type="button"
                                                        className="btn btn-success btn-sm me-1"
                                                        onClick={() => fetchSegurançaAtualizar(base_url, api_post_seguranca_atualizar, seguranca_coluna.id, 'Y')}
                                                        disabled
                                                    >
                                                        <i className="bi bi-hand-thumbs-up"></i>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        className="btn btn-outline-danger btn-sm ms-1"
                                                        onClick={() => fetchSegurançaAtualizar(base_url, api_post_seguranca_atualizar, seguranca_coluna.id, 'N')}
                                                    >
                                                        <i className="bi bi-hand-thumbs-down"></i>
                                                    </button>
                                                </div>
                                            ) : (
                                                <div>
                                                    <button
                                                        type="button"
                                                        className="btn btn-outline-success btn-sm me-1"
                                                        onClick={() => fetchSegurançaAtualizar(base_url, api_post_seguranca_atualizar, seguranca_coluna.id, 'Y')}
                                                    >
                                                        <i className="bi bi-hand-thumbs-up"></i>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        className="btn btn-danger btn-sm ms-1"
                                                        onClick={() => fetchSegurançaAtualizar(base_url, api_post_seguranca_atualizar, seguranca_coluna.id, 'N')}
                                                        disabled
                                                    >
                                                        <i className="bi bi-hand-thumbs-down"></i>
                                                    </button>
                                                </div>
                                            )}
                                        </td>
                                        <td>
                                            {/* Button trigger modal */}
                                            <button
                                                type="button"
                                                className="btn btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target={`#modalDefineObjetosTela_${index_lista_ps}`}
                                                onClick={() => handleModalClick(index_lista_ps)}
                                            >
                                                Objetos da Tela
                                            </button>

                                            {/* Modal */}
                                            <div
                                                className="modal fade"
                                                id={`modalDefineObjetosTela_${index_lista_ps}`}
                                                data-bs-backdrop="static"
                                                data-bs-keyboard="false"
                                                tabIndex="-1"
                                                aria-hidden="true"
                                            >
                                                <div className="modal-dialog modal-dialog-centered modal-xl">
                                                    <div className="modal-content">
                                                        <div className="modal-header">
                                                            <h5 className="modal-title">
                                                                <div className="fs-5">
                                                                    Objetos da Tela
                                                                </div>
                                                            </h5>
                                                            <button
                                                                type="button"
                                                                className="btn-close"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close"
                                                                onClick={() => setActiveModalId(null)}
                                                            ></button>
                                                        </div>
                                                        <div className="modal-body">

                                                            {activeModalId === index_lista_ps && (
                                                                <div>Carregar objetos da Pagina</div>
                                                            )}

                                                        </div>
                                                        <div className="modal-footer">
                                                            <button
                                                                type="button"
                                                                className="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal"
                                                                onClick={() => setActiveModalId(null)}
                                                            >
                                                                Fechar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{seguranca_coluna.pf_id}</td>
                                        <td>{seguranca_coluna.pf_perfil}</td>
                                        <td>{seguranca_coluna.cf_id}</td>
                                        <td>{seguranca_coluna.cf_cargo_funcao}</td>
                                        <td>{seguranca_coluna.seg_modulo}</td>
                                        <td>{seguranca_coluna.seg_metodo_acao}</td>
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
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {/* Paginação */}
                    {pagination && (
                        <div>
                            <nav aria-label="Page navigation example">
                                <ul className="pagination">
                                    {/*
                                                {paginacaoLista.map((paginacao_value, index) => (
                                                <li key={index} className={`page-item ${paginacao_value.active ? 'active' : ''}`}>
                                                    <button
                                                        className="page-link"
                                                        onClick={() => fetchSeguranca(base_url, api_get_seguranca, paginacao_value.href)}
                                                    >
                                                        {paginacao_value.text.trim()}
                                                    </button>
                                                </li>
                                            ))}
                                        */}

                                </ul>
                            </nav>
                        </div>
                    )}

                    {/* Modais para cada profissional */}
                    <AppMessageCard
                        parametros={message}
                        modalId="modal_painel"
                    />

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
        )
    }
</script>