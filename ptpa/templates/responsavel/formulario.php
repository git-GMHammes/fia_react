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
    'base_url' => base_url(),
    'token_csrf' => $token_csrf,
    'api_get_sexo' => 'index.php/fia/ptpa/sexobiologico/api/exibir',
    'api_get_genero' => 'index.php/fia/ptpa/genero/api/exibir',
    'api_get_unidade' => 'index.php/fia/ptpa/unidade/api/exibir',
    'api_get_municipio' => 'index.php/fia/ptpa/municipio/api/exibir',
    'api_get_profissao' => 'index.php/fia/ptpa/profissao/api/exibir',
    'api_get_perfil' => 'index.php/fia/ptpa/perfil/api/exibir',
);
$parametros_backend['api_get_atualizar_responsavel'] = ($atualizar_id !== 'erro') ? ('fia/ptpa/responsavel/api/exibir' . $atualizar_id) : ('fia/ptpa/responsavel/api/exibir/erro');
?>

<div class="app_form_responsavel" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">

    const AppFormResponsavel = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_form_responsavel').getAttribute('data-result'));

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const title = parametros.title;
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf;

        // Base Cadastro Responsavel
        const api_get_atualizar_responsavel = parametros.api_get_atualizar_responsavel;
        const api_get_profissao = parametros.api_get_profissao;
        const api_get_municipio = parametros.api_get_municipio;
        const api_get_unidade = parametros.api_get_unidade;
        const api_get_perfil = parametros.api_get_perfil;
        const api_get_genero = parametros.api_get_genero;
        const api_get_sexo = parametros.api_get_sexo;

        // Declare todas as Listas, NO PLURAL de APIs aqui:
        const [sexos, setSexos] = React.useState([]);
        const [generos, setGeneros] = React.useState([]);
        const [unidades, setUnidades] = React.useState([]);
        const [municipios, setMunicipios] = React.useState([]);
        const [profissoes, setProfissoes] = React.useState([]);
        const [perfis, setPerfis] = React.useState([]);

        // Variáveis Úteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);

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

        const isValidCPF = (cpf) => {
            // Remove caracteres não numéricos
            cpf = cpf.replace(/[^\d]+/g, '');

            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

            let soma = 0, resto;
            for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(9, 10))) return false;

            soma = 0;
            for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(10, 11))) return false;

            return true;
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;
            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            // Se o campo for o CPF, faz a validação
            if (name === 'cpf') {
                const cpfInput = event.target;
                if (!isValidCPF(value)) {
                    // Adiciona a classe de erro do Bootstrap se o CPF for inválido
                    cpfInput.classList.add('is-invalid');
                    setError('CPF inválido');
                } else {
                    // Remove a classe de erro se o CPF for válido
                    cpfInput.classList.remove('is-invalid');
                    setError(null);
                }
            }

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

        };

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
                    await fetchProfissoes();
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
                const response = await fetch(base_url + api_get_atualizar_responsavel);
                const data = await response.json();
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    console.log('Responsavel: ', data);
                    setFormData(data.result.dbResponse[0]);
                }
            } catch (error) {
                setError('Erro ao carregar Responsaveis: ' + error.message);
            }
        };

        // Fetch para obter os Sexos
        const fetchSexos = async () => {
            try {
                const response = await fetch(base_url + api_get_sexo);
                const data = await response.json();
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Sexo: ', data);
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
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Genero: ', data);
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
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Unidades: ', data);
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
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Municipio: ', data);
                    setMunicipios(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar municípios: ' + error.message);
            }
        };

        // Fetch para obter os Profissoes
        const fetchProfissoes = async () => {
            try {
                const response = await fetch(base_url + api_get_profissao);
                const data = await response.json();
                if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Profissões: ', data);
                    setProfissoes(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Profissões: ' + error.message);
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

        return (
            <div>
                {debugMyPrint ? (
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                            Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ) : null}
                <div className="row mb-1">
                    <div className="col-12 mb-1">
                        <div className="d-flex align-items-center">
                            <div className="ms-3" style={verticalBarStyle}></div>
                            <h2 className="myBold">{title}</h2>
                        </div>
                    </div>
                </div>

                {/* Fomulário de Responsáveis */}
                <form action={base_url + 'index.php/fia/ptpa/responsavel/api/cadastrar'} method="post" className="row was-validated m-2">
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
                                                <input data-api="dados-responsavel" type="text" id="Nome" name="Nome" value={formData.Nome || ''} onChange={handleChange} style={formControlStyle} className="form-control" required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="CPF" style={formLabelStyle} className="form-label">CPF</label>
                                                <input data-api="dados-responsavel" type="text" id="CPF" name="CPF" value={formData.CPF || ''} onChange={handleChange} className={`form-control ${formData.CPF && !isValidCPF(formData.CPF) ? 'is-invalid' : ''}`} style={formControlStyle} required />
                                            </div>
                                            {formData.CPF && !isValidCPF(formData.CPF) && (
                                                <span style={{ color: 'red', fontSize: '12px' }}>
                                                    CPF inválido
                                                </span>
                                            )}
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="Nascimento" style={formLabelStyle}>Data de Nascimento<strong style={requiredField}>*</strong></label>
                                                <input data-api="dados-responsavel" type="date" id="Nascimento" name="Nascimento" value={formData.Nascimento || ''} onChange={handleChange} className="form-control" style={formControlStyle} />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="RG" style={formLabelStyle}>RG<strong style={requiredField}>*</strong></label>
                                                <input data-api="dados-responsavel" type="text" id="RG" name="RG" value={formData.RG || ''} onChange={(e) => setFormData({ ...formData, RG: e.target.value })} className={`form-control ${/\D/.test(formData.RG) ? 'is-invalid' : ''}`} style={formControlStyle} />
                                            </div>
                                            {formData.RG && /\D/.test(formData.RG) && (
                                                <span style={{ color: 'red', fontSize: '12px' }}>
                                                    RG inválido
                                                </span>
                                            )}
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="ExpedidorRG" style={formLabelStyle}>Expedidor do RG<strong style={requiredField}>*</strong></label>
                                                <input data-api="dados-responsavel" type="text" id="ExpedidorRG" name="ExpedidorRG" value={formData.ExpedidorRG || ''} onChange={handleChange} className="form-control" style={formControlStyle} />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="ExpedicaoRG" style={formLabelStyle}>Expedição do RG<strong style={requiredField}>*</strong></label>
                                                <input data-api="dados-responsavel" type="date" id="ExpedicaoRG" name="ExpedicaoRG" value={formData.ExpedicaoRG || ''} onChange={handleChange} className="form-control" style={formControlStyle} />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-12 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="NomeMae" style={formLabelStyle} className="form-label">Nome da mãe</label>
                                                <input data-api="dados-responsavel" type="text" id="NomeMae" name="NomeMae" value={formData.NomeMae || ''} onChange={handleChange} style={formControlStyle} className="form-control" />
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
                                                <select data-api="dados-responsavel" id="Etnia" name="Etnia" className="form-select" value={formData.Etnia || ''} onChange={handleChange} style={formControlStyle} required aria-label="Default select 1">
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
                                                <select data-api="dados-responsavel" id="SexoId" name="SexoId" value={formData.SexoId || ''} onChange={handleChange} style={formControlStyle} className="form-select" aria-label="Default select 2">
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
                                                <label htmlFor="GeneroIdentidadeeId" style={formLabelStyle} className="form-label">Gênero<strong style={requiredField}>*</strong></label>
                                                <select data-api="dados-responsavel" id="GeneroIdentidadeeId" name="GeneroIdentidadeeId" value={formData.GeneroIdentidadeeId || ''} onChange={handleChange} style={formControlStyle} className="form-select" aria-label="Default select 3" >
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
                                                <input data-api="dados-responsavel" type="text" id="TelefoneMovel" name="TelefoneMovel" value={formData.TelefoneMovel || ''} onChange={handleChange} style={formControlStyle} className="form-control" required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-3 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="TelefoneFixo" style={formLabelStyle} className="form-label">Telefone Fixo</label>
                                                <input data-api="dados-responsavel" type="text" id="TelefoneFixo" name="TelefoneFixo" value={formData.TelefoneFixo || ''} onChange={handleChange} style={formControlStyle} className="form-control" />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-3 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="TelefoneRecado" style={formLabelStyle} className="form-label">Telefone Recado</label>
                                                <input data-api="dados-responsavel" type="text" id="TelefoneRecado" name="TelefoneRecado" value={formData.TelefoneRecado || ''} onChange={handleChange} style={formControlStyle} className="form-control" />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-3 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="Email" style={formLabelStyle} className="form-label">E-mail<strong style={requiredField}>*</strong></label>
                                                <input data-api="dados-responsavel" type="email" id="Email" name="Email" value={formData.Email || ''} onChange={handleChange} style={formControlStyle} className="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="Endereco" style={formLabelStyle} className="form-label">Endereço</label>
                                                <input data-api="dados-responsavel" type="text" id="Endereco" name="Endereco" value={formData.Endereco || ''} onChange={handleChange} style={formControlStyle} className="form-control" />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="Bairro" style={formLabelStyle} className="form-label">Bairro</label>
                                                <input data-api="dados-responsavel" type="text" id="Bairro" name="Bairro" value={formData.Bairro || ''} onChange={handleChange} style={formControlStyle} className="form-control" />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="UF" style={formLabelStyle} className="form-label">UF</label>
                                                <select data-api="dados-responsavel" id="UF" name="UF" value={formData.UF || ''} onChange={handleChange} style={formControlStyle} className="form-select" aria-label="Default select" >
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
                                                <select data-api="dados-responsavel" id="Escolaridade" name="Escolaridade" className="form-select" value={formData.Escolaridade || ''} onChange={handleChange} style={formControlStyle} aria-label="Default select" >
                                                    <option value="">Seleção Nula</option>
                                                    <option value={`6º Ano Ensino Fundamental`}>6º Ano Ensino Fundamental</option>
                                                    <option value={`7º Ano Ensino Fundamental`}>7º Ano Ensino Fundamental</option>
                                                    <option value={`8º Ano Ensino Fundamental`}>8º Ano Ensino Fundamental</option>
                                                    <option value={`9º Ano Ensino Fundamental`}>9º Ano Ensino Fundamental</option>
                                                    <option disabled>──────────</option>
                                                    <option value={`1º Ano do Ensino Médio`}>1º Ano do Ensino Médio</option>
                                                    <option value={`2º Ano do Ensino Médio`}>2º Ano do Ensino Médio</option>
                                                    <option value={`3º Ano do Ensino Médio`}>3º Ano do Ensino Médio</option>
                                                    <option disabled>──────────</option>
                                                    <option value={`Superior Completo`}>Superior Completo</option>
                                                    <option value={`Superior Incompleto`}>Superior Incompleto</option>
                                                    <option value={`Cursando`}>Cursando</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="NomeUnidade" style={formLabelStyle} className="form-label">Unidade<strong style={requiredField}>*</strong></label>
                                                <select data-api="dados-responsavel" id="NomeUnidade" name="NomeUnidade" className="form-select" value={formData.NomeUnidade || ''} onChange={handleChange} style={formControlStyle} aria-label="Default select" >
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
                                                <input data-api="dados-responsavel" type="date" id="DataCadastramento" name="DataCadastramento" value={formData.DataCadastramento || ''} onChange={handleChange} className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-12 col-sm-12">
                            <button
                                className="btn btn-outline-success mb-5"
                                type="submit"
                            >
                                Enviar
                            </button>
                        </div>
                    </div>
                </form>
                {/* formulário Responsavel */}

            </div>
        );
    };
    //ReactDOM.render(<AppFormResponsavel />, document.querySelector('.app_form_responsavel'));

    const rootElement = document.querySelector('.app_form_responsavel');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppFormResponsavel />);
</script>
<?php
$parametros_backend = array();
?>