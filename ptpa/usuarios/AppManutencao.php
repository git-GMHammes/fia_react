<script type="text/babel">
    const AppManutencao = (
        {
            parametros = {},
        }
    ) => {

        // API do ambiente
        const base_url = parametros.base_url || '';
        const api_post_perfil_filter = parametros.api_post_perfil_filter || '';
        const api_post_perfil_deletar = parametros.api_post_perfil_deletar || '';
        const api_post_perfil_cadastrar = parametros.api_post_perfil_cadastrar || '';
        const api_post_cargo_filter = parametros.api_post_cargo_filter || '';
        const api_post_cargo_cadastrar = parametros.api_post_cargo_cadastrar || '';
        const api_post_cargo_deletar = parametros.api_post_cargo_deletar || '';
        const api_post_gerar_seguranca = parametros.api_post_gerar_seguranca || '';
        const api_post_excluir_seguranca = parametros.api_post_excluir_seguranca || '';
        const getVar_page = parametros.getVar_page || '';
        const debugMyPrint = parametros.debugMyPrint || false;
        const token_csrf = parametros.token_csrf || '';

        // Variáveis de Estado
        const [modalData, setModalData] = React.useState(null);
        const [submitselectPermission, setSubmitselectPermission] = React.useState(false);
        const [submitPermission, setSubmitPermission] = React.useState(false);
        const [isLoading, setIsLoading] = React.useState(true);
        const [isLoadingPage, setIsLoadingPage] = React.useState(false);
        const [listPerfil, setListPerfil] = React.useState([]);
        const [listCargoFuncao, setListCargoFuncao] = React.useState([]);
        const [pagination, setPagination] = React.useState([]);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            id: null,
            json: 1,
            permitido: 'N',
            //
            token_csrf: token_csrf,
            submit_perfil: null,
            submit_cargo_funcao: null,
            form_on: 'Y',
            // 
            select_perfil: null,
            select_cargo_funcao: null
        });

        // Função para manipular os dados do modal
        const handleModalData = (data) => {
            setModalData(data);
        };

        // Adicione esta função ao seu componente
        const closeModal = (modal_id) => {
            const modalElement = document.getElementById(modal_id);
            if (modalElement) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        };

        // Opção 1: Criar um novo objeto com apenas o token_csrf
        const resetForm = () => {
            setFormData({
                token_csrf: formData.token_csrf,
                id: null,
                permitido: 'N',
                submit_perfil: null,
                submit_cargo_funcao: null,
                form_on: null,
                select_perfil: null,
                select_cargo_funcao: null
            });
        };

        const submitAllForms = async (apiIdentifier) => {
            console.log('submitAllForms :: ', apiIdentifier);
            console.log('formData :: ', formData);
            console.log('submitPermission :: ', submitPermission);
            let cadastro = false;
            if (
                apiIdentifier === 'filtro-api-submit-perfil'
                && submitPermission
            ) {
                console.log('filtro-api-submit-perfil');
                console.log('api_post_perfil_cadastrar :: : ', api_post_perfil_cadastrar);
                let cadastro = await fetchPostSubmtPerfil(formData);
                if (cadastro) {
                    fetchPostFilterPerfil();
                    resetForm();
                }
                return false;
            }
            if (
                apiIdentifier === 'filtro-api-submit-cargo'
                && submitPermission
            ) {
                console.log('filtro-api-submit-cargo');
                console.log('api_post_cargo_cadastrar :: : ', api_post_cargo_cadastrar);
                let cadastro = await fetchPostSubmtCargoFuncao(formData);
                if (cadastro) {
                    await fetchPostFilterCargoFuncao();
                    resetForm();
                }
                return false;
            }
            if (
                apiIdentifier === 'filtro-api-segurana-aplicar'
                && submitselectPermission
            ) {
                console.log('filtro-api-segurana-aplicar...');
                console.log('api_post_gerar_seguranca :: : ', api_post_gerar_seguranca);
                let cadastro = fetchPostGerarSeguranca(formData);
                if (cadastro) {
                    fetchPostFilterPerfil();
                    fetchPostFilterCargoFuncao();
                    resetForm();
                }
                return false;
            }
            if (
                apiIdentifier === 'filtro-api-segurana-remover'
                && submitselectPermission
            ) {
                console.log('filtro-api-segurana-remover');
                console.log('api_post_excluir_seguranca :: : ', api_post_excluir_seguranca);
                let cadastro = fetchPostRemoveSeguranca();
                if (cadastro) {
                    fetchPostFilterPerfil();
                    fetchPostFilterCargoFuncao();
                    resetForm();
                }
                return false;
            }
            return false;
        };

        // Função handleFocus para receber foco
        const handleFocus = (event) => {

            const { name, value } = event.target;

            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança de campo
            if (name === 'variavel_001') {
                console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
        };

        // Função handleChange simplificada
        const handleChange = (event) => {

            const { name, type, value, checked } = event.target;

            console.log('handleChange: ', name);
            console.log('handleChange: ', value);

            if (type === 'checkbox') {
                const checkValue = checked ? 'Y' : 'N';
                console.log('handleChange checkbox: ', checkValue);

                setFormData((prev) => ({
                    ...prev,
                    [name]: checkValue
                }));
            } else {
                console.log('handleChange normal: ', value);

                setFormData((prev) => ({
                    ...prev,
                    [name]: value
                }));
            }

            setMessage({ show: false, type: null, message: null });
        };

        // Função que executa após a retirada do foco
        const handleBlur = (event) => {
            const { name, value } = event.target;

            console.log('name handleBlur: ', name);
            console.log('value handleBlur: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança é no campo 'variavel_001'
            if (name === 'variavel_001') {
                console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
            setMessage({ show: false, type: null, message: null });
        }

        // POST Padrão 
        const fetchPostSubmtPerfil = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_perfil_cadastrar, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            const setData = formData;
            setIsLoadingPage(true);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                setIsLoadingPage(false);
                if (data.result && data.result.affectedRows > 0) {
                    console.log('Perfil cadastrado com sucesso.');
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Perfil cadastrado com sucesso.'
                    });
                    return true
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas objeto cadastradas'
                    });
                    setIsLoading(false);
                    return false;
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        // POST Padrão 
        const fetchPostSubmtCargoFuncao = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_cargo_cadastrar, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            const setData = formData;
            setIsLoadingPage(true);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                if (data.result && data.result.affectedRows > 0) {
                    console.log('Cargo/Função cadastrado com sucesso.');
                    await fetchPostFilterCargoFuncao();
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Cargo/Função cadastrado com sucesso.'
                    });
                    setIsLoadingPage(false);
                    return true
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas objeto cadastradas'
                    });
                    setIsLoading(false);
                    return false;
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        // POST Padrão 
        const fetchPostFilterPerfil = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_perfil_filter, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
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
                setIsLoadingPage(false);
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // 
                    setListPerfil(dbResponse);
                    setPagination('list');
                } else {
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        // POST Padrão 
        const fetchPostDeletarPerfil = async (id = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_perfil_deletar, customPage = getVar_page) => {
            // console.log('fetchPost...');

            const url = custonBaseURL + custonApiPostObjeto + customPage;
            console.log('fetchPost :: ', url);
            closeModal('detalheModalExclusaoPerfil');
            setIsLoadingPage(true);
            const setData = {
                id: id
            };

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                console.log('data ::', data);
                if (data.result && data.result.affectedRows > 0) {
                    setIsLoading(false);
                    await fetchPostFilterPerfil();
                    setIsLoadingPage(false);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Perfil Excluido com sucesso'
                    });
                    return true;
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

        // POST Padrão 
        const fetchPostDeletarCargoFuncao = async (id = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_cargo_deletar, customPage = getVar_page) => {
            // console.log('fetchPost...');

            const url = custonBaseURL + custonApiPostObjeto + customPage;
            console.log('fetchPost :: ', url);
            closeModal('detalheModalExclusaoCargoFuncao');
            setIsLoadingPage(true);
            const setData = {
                id: id
            };

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                console.log('data ::', data);
                if (data.result && data.result.affectedRows > 0) {
                    setIsLoading(false);
                    await fetchPostFilterCargoFuncao();
                    setIsLoadingPage(false);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Cargo Função Excluido com sucesso'
                    });
                    return true;
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

        // POST Padrão 
        const fetchPostFilterCargoFuncao = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_cargo_filter, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
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
                setIsLoadingPage(false);
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // 
                    setListCargoFuncao(dbResponse);
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

        // POST Padrão 
        const fetchPostGerarSeguranca = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_gerar_seguranca, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            const setData = formData;
            setIsLoadingPage(true);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('data ::', data);
                if (data.result && data.result.affectedRows > 0) {
                    setIsLoadingPage(false);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Segurança gerada com sucesso.'
                    });
                    console.log('Segurança cadastrada com sucesso.');
                    return true;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Regra de Segurança já cadastrada. Você pode excluír ou tentar outra combinação.'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        // POST Padrão 
        const fetchPostRemoveSeguranca = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_excluir_seguranca, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            const setData = formData;
            setIsLoadingPage(true);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                setIsLoadingPage(false);
                if (data.result && data.result.affectedRows > 0) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Segurança excluída com sucesso.'
                    });
                    console.log('Segurança excluída com sucesso.');
                    return true;
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

        // Carregar Dados Iniciais
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            if (!debugMyPrint) {
                console.log('debugMyPrint: ', debugMyPrint);
            }
            setTimeout(() => {
                setIsLoading(false);
            }, 1000);

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    fetchPostFilterPerfil();
                    fetchPostFilterCargoFuncao();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);

                } finally {
                    setIsLoading(false);
                }
            };

            loadData();

        }, []);

        // Verifica se os campos estão preenchidos
        React.useEffect(() => {
            if (
                formData.select_perfil !== null
                && formData.select_cargo_funcao !== null
            ) {
                setSubmitselectPermission(true);
            }
            if (
                formData.submit_perfil !== null
            ) {
                setSubmitPermission(true);
            }
            if (
                formData.submit_cargo_funcao !== null
            ) {
                setSubmitPermission(true);
            }
        }, [formData, submitPermission, submitselectPermission]);

        // Renderização do modal (com ID fixo)
        const renderModalExclusaoPerfil = () => {
            return (
                <div className="modal fade" id="detalheModalExclusaoPerfil" data-bs-backdrop="static" tabIndex="-1" aria-labelledby="exampleModalPerfilLabel" aria-hidden="true">
                    <div className="modal-dialog modal-dialog-centered">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title" id="exampleModalPerfilLabel">
                                    {modalData ? modalData.perfil : ''}
                                </h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                Tem certeza que deseja realizar uma exclusão lojica ao perfil?<br />
                                Isso irá afetar a todos os funcionários com esse perfil.<br />
                                <p className="m-2">Chave do Perfil: {modalData ? modalData.id : ''}</p>
                            </div>
                            <div className="modal-footer">
                                <button
                                    type="button"
                                    className="btn btn-danger"
                                    onClick={() => {
                                        fetchPostDeletarPerfil(modalData.id);
                                    }}
                                >
                                    Excluir
                                </button>
                                <button type="button" className="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            );
        };

        // Renderização do modal (com ID fixo)
        const renderModalExclusaoCargoFuncao = () => {
            return (
                <div className="modal fade" id="detalheModalExclusaoCargoFuncao" data-bs-backdrop="static" tabIndex="-1" aria-labelledby="exampleModalCargoFuncaoLabel" aria-hidden="true">
                    <div className="modal-dialog modal-dialog-centered">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title" id="exampleModalCargoFuncaoLabel">
                                    {modalData ? modalData.cargo_funcao : ''}
                                </h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div className="modal-body">
                                Tem certeza que deseja realizar uma exclusão lojica do Cargo/Função?<br />
                                Isso irá afetar a todos os funcionários com esse Cargo/Função.<br />
                                <p className="m-2">Chave do Perfil: {modalData ? modalData.id : ''}</p>
                            </div>

                            <div className="modal-footer">
                                <button
                                    type="button"
                                    className="btn btn-danger"
                                    onClick={() => {
                                        fetchPostDeletarCargoFuncao(modalData.id);
                                    }}
                                >
                                    Excluir
                                </button>
                                <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            );
        };

        return (
            <div>
                <div className="row g-2 mb-2">
                    <div className="col-12 col-sm-6">

                        <div className="card border border-start-0 border-end-0 border-top-0 border-bottom-0">
                            <div className="card-body">
                                <h5 className="card-title m-0 p-0">Perfil</h5>
                                <div className="form-text text-muted fst-italic m-0 p-0 mb-4">Exibir</div>

                                <form className="was-validated" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-api-submit-perfil`);
                                }}>

                                    <div className="input-group">
                                        <input
                                            aria-label="Dollar amount (with dot and two decimal places)"
                                            type="text"
                                            className="form-control border-end-0"
                                            id="submit_perfil"
                                            name="submit_perfil"
                                            value={formData.submit_perfil ?? ''}
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                        />
                                        <button className="btn btn-outline-secondary border border-secondary m-0 p-0 ps-3 pe-3" type="submit">
                                            <i className="bi bi-floppy"></i>
                                        </button>
                                    </div>

                                </form>
                                <div className="table-responsive overflow-y-auto" style={{ height: '300px' }}>
                                    <table className="table table-striped">
                                        <thead className="border border-2 border-dark border-start-0 border-end-0">
                                            <tr>
                                                <th scope="col">#ID</th>
                                                <th scope="col">#DESCRIÇÃO</th>
                                                <th scope="col">
                                                    <div className="d-flex justify-content-center">
                                                        #EXCLUIR
                                                    </div>
                                                </th>
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

                                            {listPerfil.map((var_list_api_coluna, index_var_list_api) => (
                                                <tr key={index_var_list_api}>
                                                    <td>{var_list_api_coluna.id || ''}</td>
                                                    <td>{var_list_api_coluna.perfil || ''}</td>
                                                    <td>
                                                        <div className="d-flex justify-content-center">
                                                            {/* Botão do Modal */}
                                                            <button
                                                                type="button"
                                                                className="btn btn-outline-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#detalheModalExclusaoPerfil"
                                                                onClick={() => handleModalData(var_list_api_coluna)}
                                                            >
                                                                <i className="bi bi-trash"></i>
                                                            </button>
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
                                            </tr>
                                        </tfoot>
                                    </table>

                                    {/* Modal (renderizado uma única vez, fora da tabela) */}
                                    {renderModalExclusaoPerfil()}

                                </div>
                            </div>
                        </div>

                    </div>
                    <div className="col-12 col-sm-6">

                        <div className="card border border-start-0 border-end-0 border-top-0 border-bottom-0">
                            <div className="card-body">
                                <h5 className="card-title m-0 p-0">Cargo/Função</h5>
                                <div className="form-text text-muted fst-italic m-0 p-0 mb-4">Exibir</div>
                                <form className="was-validated" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-api-submit-cargo`);
                                }}>
                                    <div className="input-group">

                                        <input
                                            type="text"
                                            className="form-control border-end-0"
                                            aria-label="Dollar amount (with dot and two decimal places)"
                                            id="submit_cargo_funcao"
                                            name="submit_cargo_funcao"
                                            value={formData.submit_cargo_funcao ?? ''}
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                        />
                                        <button className="btn btn-outline-secondary border border-secondary m-0 p-0 ps-3 pe-3" type="submit">
                                            <i className="bi bi-floppy"></i>
                                        </button>

                                    </div>
                                </form>
                                <div className="table-responsive overflow-y-auto" style={{ height: '300px' }}>
                                    <table className="table table-striped">
                                        <thead className="border border-2 border-dark border-start-0 border-end-0">
                                            <tr>
                                                <th scope="col">#ID</th>
                                                <th scope="col">#DESCRIÇÃO</th>
                                                <th scope="col">
                                                    <div className="d-flex justify-content-center">
                                                        #EXCLUIR
                                                    </div>
                                                </th>
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
                                            {listCargoFuncao.map((var_list_api_coluna, index_var_list_api) => (
                                                <tr key={index_var_list_api}>
                                                    <td>{var_list_api_coluna.id || ''}</td>
                                                    <td>{var_list_api_coluna.cargo_funcao || ''}</td>
                                                    <td>
                                                    <div className="d-flex justify-content-center">
                                                            {/* Botão do Modal */}
                                                            <button
                                                                type="button"
                                                                className="btn btn-outline-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#detalheModalExclusaoCargoFuncao"
                                                                onClick={() => handleModalData(var_list_api_coluna)}
                                                            >
                                                                <i className="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ))}

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                                    {/* Modal (renderizado uma única vez, fora da tabela) */}
                                    {renderModalExclusaoCargoFuncao()}

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div className="row p-2">
                    <div className="col-12 col-sm-6">
                        <h5 className="card-title m-0 p-0">Aplicar Combinação</h5>
                        <div className="form-text text-muted fst-italic m-0 p-0 mb-4">Adicionar Regra de Segurança</div>
                        <div className="row g-2">
                            <div className="col-12 col-sm-2">
                                <div className="border border-success rounded w-100 pt-1 ps-2">
                                    <form className="was-validated pb-1" onSubmit={(e) => {
                                        e.preventDefault();
                                        submitAllForms(`filtro-${origemForm}`);
                                    }}>
                                        <div className="form-check form-switch">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                id="permitido"
                                                name="permitido"
                                                value={formData.permitido ?? 'Y'}
                                                onFocus={handleFocus}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                checked={formData.permitido === 'Y'}
                                            />
                                            <label className="form-check-label" htmlFor="flexSwitchCheckChecked">Permitido</label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">

                                <form className="was-validated" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-api-segurana-aplicar`);
                                }}>
                                    <div className="input-group">
                                        <select
                                            className="form-select"
                                            aria-label=".form-select-sm perfil"
                                            id="select_perfil"
                                            name="select_perfil"
                                            value={formData.select_perfil ?? ''}
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                        >
                                            <option value="">Perfil</option>
                                            {listPerfil.map((var_list_perfil_select, index_var_perfil_select) => (
                                                <option
                                                    key={index_var_perfil_select}
                                                    value={var_list_perfil_select.id ?? 'valor'}
                                                >
                                                    {var_list_perfil_select.perfil ?? 'Nada'}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                </form>

                            </div>
                            <div className="col-12 col-sm-6">

                                <form className="was-validated" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-api-segurana-aplicar`);
                                }}>
                                    <div className="input-group">
                                        <select
                                            className="form-select border-end-0"
                                            aria-label=".form-select-sm example"
                                            id="select_cargo_funcao"
                                            name="select_cargo_funcao"
                                            value={(formData.select_cargo_funcao === null) ? '' : formData.select_cargo_funcao}
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                        >
                                            <option value="">Cargo</option>
                                            {listCargoFuncao.map((var_list_cargo_select, index_var_cargo_select) => (
                                                <option
                                                    key={index_var_cargo_select}
                                                    value={var_list_cargo_select.id ?? 'valor'}
                                                >
                                                    {var_list_cargo_select.cargo_funcao ?? 'Nada'}
                                                </option>
                                            ))}
                                        </select>
                                        <button className="btn btn-outline-secondary border border-secondary m-0 p-0 ps-3 pe-3" type="submit">
                                            <i className="bi bi-floppy"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {/* Remover Combinação */}
                    <div className="col-12 col-sm-6">
                        <h5 className="card-title m-0 p-0">Remover Combinação</h5>
                        <div className="form-text text-muted fst-italic m-0 p-0 mb-4">Excluir Regra de Segurança</div>
                        <div className="row g-2">
                            <div className="col-12 col-sm-6">
                                <form className="was-validated" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-api-segurana-remover`);
                                }}>
                                    <select
                                        className="form-select"
                                        aria-label=".form-select-sm example"
                                        id="select_perfil"
                                        name="select_perfil"
                                        value={formData.select_perfil ?? ''}
                                        onFocus={handleFocus}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                    >
                                        <option value="">Perfil</option>
                                        {listPerfil.map((var_list_perfil_select, index_var_perfil_select) => (
                                            <option
                                                key={index_var_perfil_select}
                                                value={var_list_perfil_select.id ?? 'valor'}
                                            >
                                                {var_list_perfil_select.perfil ?? 'Nada'}
                                            </option>
                                        ))}
                                    </select>
                                </form>

                            </div>
                            <div className="col-12 col-sm-6">

                                <form className="was-validated" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-api-segurana-remover`);
                                }}>
                                    <div className="input-group">
                                        <select
                                            className="form-select border-end-0"
                                            aria-label=".form-select-sm example"
                                            id="select_cargo_funcao"
                                            name="select_cargo_funcao"
                                            value={formData.select_cargo_funcao ?? ''}
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                        >
                                            <option value="">Cargo</option>
                                            {listCargoFuncao.map((var_list_cargo_select, index_var_cargo_select) => (
                                                <option
                                                    key={index_var_cargo_select}
                                                    value={var_list_cargo_select.id ?? 'valor'}
                                                >
                                                    {var_list_cargo_select.cargo_funcao ?? 'Nada'}
                                                </option>
                                            ))}
                                        </select>
                                        <button className="btn btn-outline-secondary border border-secondary m-0 p-0 ps-3 pe-3" type="submit">
                                            <i className="bi bi-floppy"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                {
                    typeof AppMessageCard !== "undefined" ? (
                        <div>

                            <div>
                                <AppMessageCard
                                    parametros={message}
                                    modalId={`modal_app_manutencao`}
                                />
                            </div>

                        </div>
                    ) : (
                        <div>
                            <p className="text-danger">AppMessageCard não lacançado.</p>
                        </div>
                    )
                }
                {
                    typeof AppJson !== "undefined" ? (
                        <div>
                            <AppJson
                                parametros={parametros}
                                dbResponse={formData}
                            />
                            <AppJson
                                parametros={parametros}
                                dbResponse={listCargoFuncao}
                            />
                        </div>
                    ) : (
                        <div>
                            <p className="text-danger">AppJson não lacançado.</p>
                        </div>
                    )
                }
            </div >
        );
    };
</script>