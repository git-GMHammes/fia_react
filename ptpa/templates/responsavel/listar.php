<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
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
    'api_get_responsavel' => 'index.php/fia/ptpa/responsavel/api/exibir',
    'api_post_filter_responsaveis' => 'index.php/fia/ptpa/responsavel/api/filtrar',
    'api_get_sexo' => 'index.php/fia/ptpa/sexobiologico/api/exibir',
    'api_get_genero' => 'index.php/fia/ptpa/genero/api/exibir',
    'api_get_unidade' => 'index.php/fia/ptpa/unidade/api/exibir',
    'api_get_municipio' => 'index.php/fia/ptpa/municipio/api/exibir',
    'api_get_profissao' => 'index.php/fia/ptpa/profissao/api/exibir',
    'api_get_perfil' => 'index.php/fia/ptpa/perfil/api/exibir',
);
?>

<div class="app_listar_responsavel" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListarResponsavel = () => {

        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar_responsavel').getAttribute('data-result'));

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const request_scheme = parametros.request_scheme;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const token_csrf = parametros.token_csrf;
        const base_url = parametros.base_url;
        const getURI = parametros.getURI;
        const title = parametros.title;

        //Base Lista Responsavel
        const api_get_responsavel = parametros.api_get_responsavel;
        const base_paginator = base_url + parametros.base_paginator;
        const getVar_page = parametros.getVar_page;
        const page = parametros.page;

        //Base Formulário Modal
        const api_post_filter_responsaveis = parametros.api_post_filter_responsaveis;
        const api_get_municipio = parametros.api_get_municipio;
        const api_get_profissao = parametros.api_get_profissao;
        const api_get_unidade = parametros.api_get_unidade;
        const api_get_perfil = parametros.api_get_perfil;
        const api_get_genero = parametros.api_get_genero;
        const api_get_sexo = parametros.api_get_sexo;

        // Variáveis da API
        const [responsaveis, setResponsaveis] = React.useState([]);
        const [paginacaoResponsaveis, setPaginacaoResponsaveis] = React.useState([]);

        // Variáveis do Modal
        const [profissoes, setProfissoes] = React.useState([]);
        const [municipios, setMunicipios] = React.useState([]);
        const [unidades, setUnidades] = React.useState([]);
        const [generos, setGeneros] = React.useState([]);
        const [perfis, setPerfis] = React.useState([]);
        const [sexos, setSexos] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);

        // Declare todos os campos do formulário aqui
        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            id: null,
            Nome: null,
            CPF: null,
            Nascimento: null,
            RG: null,
            ExpedidorRG: null,
            ExpedicaoRG: null,
            NomeMae: null,
            Etnia: null,
            SexoId: null,
            SexoBiologico: null,
            GeneroIdentidadeId: null,
            GeneroIdentidadeDescricao: null,
            TelefoneMovel: null,
            TelefoneFixo: null,
            TelefoneRecado: null,
            Email: null,
            Endereco: null,
            Bairro: null,
            UF: null,
            Escolaridade: null,
            NomeUnidade: null,

            MunicipioId: null,
            MunicipioUnidade: null,
            AcessoCadastroID: null,
            AcessoId: null,
            AcessoDescricao: null,
            ProntuarioId: null,
            NMatricula: null,
            Certidao: null,
            NumRegistro: null,
            Folha: null,
            Livro: null,
            Circunscricao: null,
            Zona: null,
            UFRegistro: null,
            TipoEscola: null,
            TurnoEscolarAdolesc: null,
            NomeEscola: null,
            DataCadastramento: null,
            DataTermUnid: null,
            DataInicioUnid: null,
            CodProfissao: null,
            EnderecoUnidade: null,
            AcessoCreatedAt: null,
            AcessoUpdatedAt: null,
            PerfilId: null,
            PerfilDescricao: null,
        });

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;
            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função que gerencia atualizações do MODAL
        const handleOpenModal = (responsavel) => {
            setFormData(responsavel);
        }

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
            // fetch(`${base_url}${api_post_filter_prossionais}/${apiIdentifier}${getVar_page}`, {
            fetch(`${base_url}${api_post_filter_responsaveis}${getVar_page}`, {
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
                        setResponsaveis(data.result.dbResponse);
                        setPagination(true);
                    }
                })
                .catch((error) => {
                    setError('Erro ao encviar Filtro: ' + error.message);
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
                    await fetchSexos();
                    await fetchGeneros();
                    await fetchUnidades();
                    await fetchMunicipios();
                    await fetchResponsaveis();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter os Sexos
        const fetchResponsaveis = async () => {
            try {
                const response = await fetch(base_url + api_get_responsavel + getVar_page);
                const data = await response.json();
                console.log('Responsaveis: ', data);
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setResponsaveis(data.result.dbResponse);
                    setPagination(true);
                    setPaginacaoResponsaveis(data.result.linksArray);
                }
            } catch (error) {
                setError('Erro ao carregar Responsavel: ' + error.message);
            }

        };

        // Fetch para obter os Sexos
        const fetchSexos = async () => {
            try {
                const response = await fetch(base_url + api_get_sexo);
                const data = await response.json();
                console.log('Sexo: ', data);
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setSexos(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Sexos: ' + error.message);
            }
        };

        // Fetch para obter os Gêneros
        const fetchGeneros = async () => {
            try {
                const response = await fetch(base_url + api_get_genero);
                const data = await response.json();
                console.log('Genero: ', data);
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setGeneros(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Gêneros: ' + error.message);
            }
        };

        // Fetch para obter as Unidades
        const fetchUnidades = async () => {
            try {
                const response = await fetch(base_url + api_get_unidade);
                const data = await response.json();
                console.log('Unidades: ', data);
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setUnidades(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Unidades: ' + error.message);
            }
        };

        // Fetch para obter os Municípios
        const fetchMunicipios = async () => {
            try {
                const response = await fetch(base_url + api_get_municipio);
                const data = await response.json();
                console.log('Municipio: ', data);
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setMunicipios(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Municípios: ' + error.message);
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

        if (isLoading) {
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
                    <table className="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-responsavel" className="form-control form-control-sm" style={formControlStyle} type="text" name="unidade_id" placeholder="Unidade" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    UNIDADE
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-responsavel" className="form-control form-control-sm" style={formControlStyle} type="text" placeholder="Nome" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    NOME
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-responsavel" className="form-control form-control-sm" style={formControlStyle} type="text" placeholder="CPF" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    CPF
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-responsavel" className="form-control form-control-sm" style={formControlStyle} type="text" placeholder="E-mail" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    E-mail
                                </th>
                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <form action="">
                                            <input data-api="filtro-responsavel" className="form-control form-control-sm" style={formControlStyle} type="text" placeholder="Telefone móvel" aria-label=".form-control-sm example" />
                                        </form>
                                    </div>
                                    TEL MÓVEL
                                </th>

                                <th scope="col" className="text-nowrap">
                                    <div className="collapse mb-4" style={formGroupStyle} id="collapseExample">
                                        <button className="btn" onClick={() => submitAllForms('filtro-prontuario')} type="submit" style={{ width: '100%', fontSize: '0.8rem', padding: '0.375rem 0.75rem', borderRadius: '0.25rem', boxSizing: 'border-box' }}>Filtrar</button>
                                    </div>
                                    EDITAR
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {responsaveis.map((responsaveis_value, index) => (
                                <tr key={index} className="col-12 col-sm-3 mb-5">
                                    <td>{responsaveis_value.NomeUnidade}</td>
                                    <td>{responsaveis_value.Nome}</td>
                                    <td>{responsaveis_value.CPF}</td>
                                    <td>{responsaveis_value.Email}</td>
                                    <td>{responsaveis_value.TelefoneMovel}</td>
                                    <td>
                                        {/* Botão para acionar o modal */}
                                        <button type="button" className="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target={`#staticBackdropResponsavel${index}`} onClick={() => handleOpenModal(responsaveis_value)}>
                                            <i className="bi bi-pencil-square" />
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
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </tfoot>
                    </table>

                    {/* Modais para cada responsavel */}
                    {responsaveis.map((responsavel, index) => (
                        <div key={index} className="modal fade" id={`staticBackdropResponsavel${index}`} data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby={`staticBackdropResponsavelLabel${index}`} aria-hidden="true">
                            {/* modal-fullscreen / modal-x1 */}
                            <div className="modal-dialog modal-xl">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5 className="modal-title" id={`staticBackdropResponsavelLabel${index}`}>Detalhes do Responsável</h5>
                                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div className="modal-body">
                                        <div>
                                            {responsavel.Nome}
                                        </div>
                                        {/* Fomulário de Responsáveis */}
                                        <form action={`${base_url}index.php/fia/ptpa/responsavel/api/cadastrar${getVar_page}`} method="post" className="row was-validated m-2">
                                            <div className="row">
                                                <div className="col-12 col-sm-12">
                                                    <div className="card mb-4">
                                                        <div className="card-body">
                                                            <div className="row">
                                                                <div className="col-12 col-sm-4 mb-3">

                                                                    <input type="hidden" name="id" className="form-control" id="id" value={formData.id || ''} required />
                                                                    <input type="hidden" name="acesso_id" className="form-control" id="acesso_id" value="2" required />
                                                                    <input type="hidden" name="perfil_id" className="form-control" id="perfil_id" value="6" required />
                                                                    <input type="hidden" name="token_csrf" className="form-control" id="token_csrf" value={token_csrf} required />


                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Nome" style={formLabelStyle} className="form-label">Nome</label>
                                                                        <input data-api="dados-responsavel" type="text" name="Nome" value={formData.Nome || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="Nome" required />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="CPF" style={formLabelStyle} className="form-label">CPF</label>
                                                                        <input data-api="dados-responsavel" type="text" name="CPF" value={formData.CPF || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="CPF" required />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Nascimento" style={formLabelStyle}>Data de Nascimento<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-responsavel" type="date" name="Nascimento" value={formData.Nascimento || ''} onChange={handleChange} className="form-control" style={formControlStyle} id="Nascimento" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div className="row">
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="RG" style={formLabelStyle}>RG<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-responsavel" type="text" name="RG" value={formData.RG || ''} onChange={handleChange} className="form-control" style={formControlStyle} id="RG" />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="ExpedidorRG" style={formLabelStyle}>Expedidor do RG<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-responsavel" type="text" value={formData.ExpedidorRG || ''} onChange={handleChange} name="ExpedidorRG" className="form-control" style={formControlStyle} id="ExpedidorRG" />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="ExpedicaoRG" style={formLabelStyle}>Expedição do RG<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-responsavel" type="date" value={formData.ExpedicaoRG || ''} onChange={handleChange} name="ExpedicaoRG" className="form-control" style={formControlStyle} id="ExpedicaoRG" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div className="row">
                                                                <div className="col-12 col-sm-12 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="NomeMae" style={formLabelStyle} className="form-label">Nome da mãe</label>
                                                                        <input data-api="dados-responsavel" type="text" name="NomeMae" value={formData.NomeMae || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="NomeMae" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="col-12 col-sm-12">
                                                    <div className="card mb-4">
                                                        <div className="card-body">
                                                            <div className="row">
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Etnia" style={formLabelStyle}>Etnia<strong style={requiredField}>*</strong></label>
                                                                        <select data-api="dados-responsavel" className="form-select" value={formData.Etnia || ''} onChange={handleChange} style={formControlStyle} name="Etnia" id="Etnia" required aria-label="Default select 1">
                                                                            <option value="">Seleção Nula</option>
                                                                            <option value="Pardo">Pardo</option>
                                                                            <option value="Branco">Branco</option>
                                                                            <option value="Preto">Preto</option>
                                                                            <option value="Indígena">Indígena</option>
                                                                            <option value="Amarelo">Amarelo</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="SexoId" style={formLabelStyle} className="form-label">Sexo<strong style={requiredField}>*</strong></label>
                                                                        <select data-api="dados-responsavel" name="SexoId" value={formData.SexoId || ''} onChange={handleChange} style={formControlStyle} className="form-select" id="SexoId" aria-label="Default select 2">
                                                                            <option value="">Seleção Nula</option>
                                                                            {sexos.map(sexo_select => (
                                                                                <option key={sexo_select.id} value={sexo_select.id}>
                                                                                    {sexo_select.sexo_biologico}
                                                                                </option>
                                                                            ))}
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="GeneroIdentidadeId" style={formLabelStyle} className="form-label">Gênero<strong style={requiredField}>*</strong></label>
                                                                        <select data-api="dados-responsavel" name="GeneroIdentidadeId" value={formData.GeneroIdentidadeId || ''} onChange={handleChange} style={formControlStyle} className="form-select" id="GeneroIdentidadeId" aria-label="Default select 3" >
                                                                            <option value="">Seleção Nula</option>
                                                                            {generos.map(genero_select => (
                                                                                <option key={genero_select.id} value={genero_select.id}>
                                                                                    {genero_select.genero}
                                                                                </option>
                                                                            ))}
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="col-12 col-sm-12">
                                                    <div className="card mb-4">
                                                        <div className="card-body">
                                                            <div className="row">
                                                                <div className="col-12 col-sm-3 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="TelefoneMovel" style={formLabelStyle} className="form-label">Telefone Móvel<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-responsavel" type="text" name="TelefoneMovel" value={formData.TelefoneMovel || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="TelefoneMovel" required />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-3 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="TelefoneFixo" style={formLabelStyle} className="form-label">Telefone Fixo</label>
                                                                        <input data-api="dados-responsavel" type="text" name="TelefoneFixo" value={formData.TelefoneFixo || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="TelefoneFixo" />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-3 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="TelefoneRecado" style={formLabelStyle} className="form-label">Telefone Recado</label>
                                                                        <input data-api="dados-responsavel" type="text" name="TelefoneRecado" value={formData.TelefoneRecado || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="TelefoneRecado" />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-3 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Email" style={formLabelStyle} className="form-label">E-mail<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-responsavel" type="email" name="Email" value={formData.Email || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="Email" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div className="row">
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Endereco" style={formLabelStyle} className="form-label">Endereço</label>
                                                                        <input data-api="dados-responsavel" type="text" name="Endereco" value={formData.Endereco || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="Endereco" />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Bairro" style={formLabelStyle} className="form-label">Bairro</label>
                                                                        <input data-api="dados-responsavel" type="text" name="Bairro" value={formData.Bairro || ''} onChange={handleChange} style={formControlStyle} className="form-control" id="Bairro" />
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="UF" style={formLabelStyle} className="form-label">UF</label>
                                                                        <select data-api="dados-responsavel" name="UF" value={formData.UF || ''} onChange={handleChange} style={formControlStyle} className="form-select" id="UF" aria-label="Default select" >
                                                                            <option value="">Seleção Nula</option>
                                                                            <option value={`RJ`}>RJ - Rio de Janeiro</option>
                                                                            <option value={`SP`}>SP - São Paulo</option>
                                                                            <option value={`AC`}>AC - Acre</option>
                                                                            <option value={`AL`}>AL - Alagoas</option>
                                                                            <option value={`AP`}>AP - Amapá</option>
                                                                            <option value={`AM`}>AM - Amazonas</option>
                                                                            <option value={`BA`}>BA - Bahia</option>
                                                                            <option value={`CE`}>CE - Ceará</option>
                                                                            <option value={`DF`}>DF - Distrito Federal</option>
                                                                            <option value={`ES`}>ES - Espírito Santo</option>
                                                                            <option value={`GO`}>GO - Goiás</option>
                                                                            <option value={`MA`}>MA - Maranhão</option>
                                                                            <option value={`MT`}>MT - Mato Grosso</option>
                                                                            <option value={`MS`}>MS - Mato Grosso do Sul</option>
                                                                            <option value={`MG`}>MG - Minas Gerais</option>
                                                                            <option value={`PA`}>PA - Pará</option>
                                                                            <option value={`PB`}>PB - Paraíba</option>
                                                                            <option value={`PR`}>PR - Paraná</option>
                                                                            <option value={`PE`}>PE - Pernambuco</option>
                                                                            <option value={`PI`}>PI - Piauí</option>
                                                                            <option value={`RN`}>RN - Rio Grande do Norte</option>
                                                                            <option value={`RS`}>RS - Rio Grande do Sul</option>
                                                                            <option value={`RO`}>RO - Rondônia</option>
                                                                            <option value={`RR`}>RR - Roraima</option>
                                                                            <option value={`SC`}>SC - Santa Catarina</option>
                                                                            <option value={`SE`}>SE - Sergipe</option>
                                                                            <option value={`TO`}>TO - Tocantins</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="col-12 col-sm-12">
                                                    <div className="card mb-4">
                                                        <div className="card-body">
                                                            <div className="row">
                                                                <div className="col-12 col-sm-4 md-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="Escolaridade" style={formLabelStyle} className="form-label">Escolaridade<strong style={requiredField}>*</strong></label>
                                                                        <select data-api="dados-responsavel" name="Escolaridade" className="form-select" value={formData.Escolaridade || ''} onChange={handleChange} style={formControlStyle} id="Escolaridade" aria-label="Default select" >
                                                                            <option value="">Seleção Nula</option>
                                                                            <option value={`6º Ano Ensino Fundamental`}>6º Ano Ensino Fundamental</option>
                                                                            <option value={`7º Ano Ensino Fundamental`}>7º Ano Ensino Fundamental</option>
                                                                            <option value={`8º Ano Ensino Fundamental`}>8º Ano Ensino Fundamental</option>
                                                                            <option value={`9º Ano Ensino Fundamental`}>9º Ano Ensino Fundamental</option>
                                                                            <option disabled>──────────</option>
                                                                            <option value={`1º Ano do Ensino Médio`}>1º Ano do Ensino Médio</option>
                                                                            <option value={`2º Ano do Ensino Médio`}>2º Ano do Ensino Médio</option>
                                                                            <option value={`3º Ano do Ensino Médio`}>3º Ano do Ensino Médio</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="NomeUnidade" style={formLabelStyle} className="form-label">Unidade<strong style={requiredField}>*</strong></label>
                                                                        <select data-api="dados-responsavel" name="NomeUnidade" className="form-select" value={formData.NomeUnidade || ''} onChange={handleChange} style={formControlStyle} id="NomeUnidade" aria-label="Default select" >
                                                                            <option value="">Seleção Nula</option>
                                                                            {unidades.map(unidade_select => (
                                                                                <option key={unidade_select.id} value={unidade_select.Nome}>
                                                                                    {unidade_select.Nome}
                                                                                </option>
                                                                            ))}
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div className="col-12 col-sm-4 mb-3">
                                                                    <div style={formGroupStyle}>
                                                                        <label htmlFor="DataCadastramento" style={formLabelStyle}>Data de cadastramento<strong style={requiredField}>*</strong></label>
                                                                        <input data-api="dados-responsavel" type="date" name="DataCadastramento" value={formData.DataCadastramento || ''} onChange={handleChange} className="form-control" style={formControlStyle} id="DataCadastramento" required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="row">
                                                <div className="col-12 col-sm-12">
                                                    <button className="btn btn-outline-primary mb-5" type="submit">Enviar</button>
                                                </div>
                                            </div>
                                        </form>
                                        {/* formulário Responsavel */}

                                    </div>
                                    <div className="modal-footer">
                                        <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}

                    <nav aria-label="Responsáveis">
                        <ul className="pagination">
                            {pagination == true && paginacaoResponsaveis.map((paginacao_responsaveis_value, index_page) => {
                                // Converte ambos para string para garantir uma comparação correta
                                const isActive = paginacao_responsaveis_value.text.trim() === String(page);
                                return (
                                    <li key={index_page} className={`page-item ${isActive ? 'active' : ''} ${paginacao_responsaveis_value.disabled ? 'disabled' : ''}`}>
                                        <a
                                            className="page-link"
                                            href={paginacao_responsaveis_value.href}
                                            tabIndex={paginacao_responsaveis_value.disabled ? '-1' : '0'}
                                            aria-disabled={paginacao_responsaveis_value.disabled ? 'true' : 'false'}>{paginacao_responsaveis_value.text.trim()}
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
    //ReactDOM.render(<AppListarResponsavel />, document.querySelector('.app_listar_responsavel'));

    const rootElement = document.querySelector('.app_listar_responsavel');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListarResponsavel />);
</script>
<?php
$parametros_backend = array();
?>