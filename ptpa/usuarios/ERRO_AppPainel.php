<?php

$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');
# 
$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'token_csrf' => $token_csrf,
    'atualizar_id' => $atualizar_id,
    'api_get_seguranca' => 'index.php/fia/ptpa/usuario/api/seguranca',
    'api_post_seguranca_atualizar' => 'index.php/fia/ptpa/seguranca/api/atualizar',
    'api_post_seguranca_objeto_atualizar' => 'index.php/fia/ptpa/segurancaobjeto/api/atualizar',
    'api_post_seguranca_cadastrar' => 'index.php/fia/ptpa/seguranca/api/cadastrar',
    'api_get_perfil' => 'index.php/fia/ptpa/perfil/api/filtrar',
    'api_get_cargo' => 'index.php/fia/ptpa/cargofuncao/api/filtrar',
    'api_post_seguranca_filter' => 'index.php/fia/ptpa/seguranca/api/filtrar',
    'api_post_seguranca_filter_objeto' => 'index.php/fia/ptpa/segurancaobjeto/api/filtrar',
    'api_post_cadastrar_profissional' => 'index.php/fia/ptpa/profissional/api/cadastrar',
    'api_post_atualizar_profissional' => 'index.php/fia/ptpa/profissional/api/atualizar',
    'api_post_filtrar_menu' => 'index.php/fia/ptpa/menu/api/filtrar',
);
#
$parametros_backend['api_get_atualizar_profissional'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/profissional/api/exibir' . $atualizar_id) : ('fia/ptpa/profissional/api/exibir/erro');
#
?>

<div class="app_painel" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppPainel = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_painel').getAttribute('data-result'));
        parametros.origemForm = 'profissional'

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const getVar_page = parametros.getVar_page;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf;

        // Base API/POST
        const api_post_seguranca_filter = parametros.api_post_seguranca_filter;
        const api_post_seguranca_filter_objeto = parametros.api_post_seguranca_filter_objeto;
        const api_get_seguranca = parametros.api_get_seguranca || '';
        const api_post_seguranca_objeto_atualizar = parametros.api_post_seguranca_objeto_atualizar || '';
        const api_post_seguranca_atualizar = parametros.api_post_seguranca_atualizar || '';
        const api_post_seguranca_cadastrar = parametros.api_post_seguranca_cadastrar || '';
        const api_get_perfil = parametros.api_get_perfil || '';
        const api_get_cargo = parametros.api_get_cargo || '';
        const api_post_filtrar_menu = parametros.api_post_filtrar_menu || filtrar';

        const api_post_cadastrar_profissional = parametros.api_post_cadastrar_profissional || '';
        const api_post_atualizar_profissional = parametros.api_post_atualizar_profissional || '';

        // Variáveis para APIs Select
        const [listaSeguranca, setListaSeguranca] = React.useState([]);
        const [listaSegurancaObjeto, setListaSegurancaObjeto] = React.useState([]);
        const [listaMenu, setListaMenu] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);
        const [activeTab, setActiveTab] = React.useState('menu');

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
        // Adicione este estado para controlar qual modal está ativo
        const [activeModalId, setActiveModalId] = React.useState(null);
        // Seleção TAB
        const handleTabClick = (tab) => {
            setActiveTab(tab);
        };

        // Função para lidar com o clique do botão
        const handleModalClick = (id) => {
            setActiveModalId(id);
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;
            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Se o campo for o campo1, faz a validação
            if (name === 'campo1') {
                console.log('Informar o que é para fazer.');
            }
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

        const submitAllForms = async (filtro) => {
            // 
            console.log('submitAllForms...');
            const setData = formData;
            console.log('setData :: ', setData);
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
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    await fetchSeguranca();
                    await fetchGetMenu();
                    // await fetchPostFilterSegurancaObjeto();

                } catch (error) {
                    console.error('Erro ao carregar dados:', error);

                } finally {
                    setIsLoading(false);
                    console.log('Termino React.useEffect');

                }
            };

            loadData();

        }, []);

        // Função handleBlur simplificada
        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Se o campo for o campo1, faz a validação
            if (name === 'campo1') {
                console.log('Informar o que é para fazer.');
            }

            setFormData((prev) => ({
                ...prev,
                [name]: ''
            }));

        };


        // setListaMenu(dbResponse);


        // Requisição GET Comum
        const fetchSeguranca = async (custonBaseURL = base_url, custonApiGet = api_get_seguranca, customPage = getVar_page) => {
            const url = `${custonBaseURL}${custonApiGet}${customPage}&limit=100`;
            console.log('fetchSeguranca URL:', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                console.log("fetchSeguranca data:: ", data);

                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    AppPainel
                    setIsLoading(false);
                    setListaSeguranca(data.result.dbResponse);
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
                    fetchPostFilterSeguranca();
                }

                return dataReturn.result.dbResponse || [];

            } catch (error) {
                console.error('Erro na requisição POST:', error.message);
                return [];
            }
        };

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
        const fetchPostFilterSeguranca = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_seguranca_filter, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage + '&limit=100';
            console.log('fetchPostFilterSeguranca URL:', url);
            const setData = formData;
            console.log('fetchPostFilterSeguranca setData:', setData);
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
                    setListaSeguranca(dbResponse);
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
                        &nbsp;
                    </div>
                    <div className="col-12 col-sm-4">
                        &nbsp;
                    </div>
                </div>
                <div className="mt-4 ms-2 me-2">
                    <ul className="nav nav-tabs">
                        <li className="nav-item">
                            <a
                                className={`nav-link ${activeTab === 'menu' ? 'active' : ''}`}
                                onClick={() => handleTabClick('menu')}
                                href="#"
                            >
                                Menu
                            </a>
                        </li>
                        <li className="nav-item">
                            <a
                                className={`nav-link ${activeTab === 'seguranca' ? 'active' : ''}`}
                                onClick={() => handleTabClick('seguranca')}
                                href="#"
                            >
                                Segurança
                            </a>
                        </li>
                        <li className="nav-item">
                            <a
                                className={`nav-link ${activeTab === 'ajuda' ? 'active' : ''}`}
                                onClick={() => handleTabClick('ajuda')}
                                href="#"
                            >
                                Ajuda
                            </a>
                        </li>
                    </ul>

                    <div className="tab-content border border-top-0 p-2">
                        {activeTab === 'menu' && (
                            <div className="tab-pane active">
                                <AppMenu
                                    parametros={parametros}
                                    isLoading
                                    setIsLoading={setIsLoading}
                                    listaMenu={listaMenu}
                                    setListaMenu={setListaMenu}
                                />
                            </div>
                        )}
                        {activeTab === 'seguranca' && (
                            <div className="tab-pane active">
                                <AppSeguranca
                                    parametros={parametros}
                                    fetchSeguranca={fetchSeguranca}
                                    fetchSegurançaAtualizar={fetchSegurançaAtualizar}
                                    formData={formData}
                                    setFormData={setFormData}
                                    verticalBarStyle={verticalBarStyle}
                                    formGroupStyle={formGroupStyle}
                                    formControlStyle={formControlStyle}
                                    handleModalClick={handleModalClick}
                                    handleChange={handleChange}
                                    submitAllForms={submitAllForms}
                                    handleBlur={handleBlur}
                                    setTabNav={setTabNav}
                                    showAlert={showAlert}
                                    setShowAlert={setShowAlert}
                                    alertType={alertType}
                                    setAlertType={setAlertType}
                                    alertMessage={alertMessage}
                                    setAlertMessage={setAlertMessage}
                                    message={message}
                                    setMessage={setMessage}
                                    error={error}
                                    setError={setError}
                                    isLoading={isLoading}
                                    setIsLoading={setIsLoading}
                                    pagination={pagination}
                                    setPagination={setPagination}
                                    paginacaoLista={paginacaoLista}
                                    setPaginacaoLista={setPaginacaoLista}
                                    activeTab={activeTab}
                                    setActiveTab={setActiveTab}
                                    listaSeguranca={listaSeguranca}
                                    setListaSeguranca={setListaSeguranca}
                                    listaSegurancaObjeto={listaSegurancaObjeto}
                                    setListaSegurancaObjeto={setListaSegurancaObjeto}
                                    listaMenu={listaMenu}
                                    setListaMenu={setListaMenu}
                                    activeModalId={activeModalId}
                                    setActiveModalId={setActiveModalId}
                                />
                            </div>
                        )}
                        {activeTab === 'ajuda' && (
                            <div className="tab-pane active">
                                ... Ajuda
                            </div>
                        )}
                    </div>
                </div>
            </div>
        );
    };
</script>