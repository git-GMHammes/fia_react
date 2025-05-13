<?php
$parametros_backend = array(
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'result' => isset($result) ? ($result) : (array()),
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'api_get_value_profissional' => 'index.php/fia/ptpa/profissional/api/exibir/20',
    'api_get_sexo' => 'index.php/fia/ptpa/sexobiologico/api/exibir',
    'api_get_genero' => 'index.php/fia/ptpa/generoidentidade/api/exibir',
    'api_get_responsavel' => 'index.php/fia/ptpa/responsavel/api/exibir',
    'api_get_unidade' => 'index.php/fia/ptpa/unidade/api/exibir',
    'api_get_municipio' => 'index.php/fia/ptpa/municipio/api/exibir',
);
?>

<div class="app_form_perfil" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const { useState, useEffect } = React;
    // 
    const AppFormPerfil = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_form_perfil').getAttribute('data-result'));
        
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        const api_get_value_profissional = parametros.api_get_value_profissional;
        const api_get_profissao = parametros.api_get_profissao;
        const api_get_municipio = parametros.api_get_municipio;
        const api_get_unidade = parametros.api_get_unidade;
        const api_get_perfil = parametros.api_get_perfil;
        const api_get_genero = parametros.api_get_genero;
        const api_get_sexo = parametros.api_get_sexo;
        console.log('base_url', base_url);
        
        // Declare todas as Listas, NO PLURAL de APIs aqui:
        const [profissoes, setProfissoes] = React.useState([]);
        const [municipios, setMunicipios] = React.useState([]);
        const [sexos, setSexos] = useState([]);
        const [generos, setGeneros] = useState([]);
        const [unidades, setUnidades] = useState([]);
        const [citys, setCitys] = useState([]);
        const [responsaveis, setResponsáveis] = useState([]);

        // Decalre Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            id : null,
            acesso_id : null,
            municipio_id : null,
            perfil_id : null,
            sexo_biologico_id : null,
            genero_identidade_id : null,
            profissao_id : null,
            unidade_id : null,
            
            Nome : null,
            CPF : null,
            Nascimento : null,
            RG : null,
            ExpedicaoRG : null,
            ExpedidorRG : null,
            NomeMae : null,

            Etnia : null,

            TelefoneMovel : null,
            TelefoneFixo : null,
            TelefoneRecado : null,
            Email : null,
            Endereco : null,
            Bairro : null,
            UF : null,
            
            programa_fia: null,

            DataCadastramento : null,
            DataInicioUnid : null,
            DataTermUnid : null
        });
        
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);

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
            fontSize: '1.rem',
            borderColor: '#fff',
        };

        const requiredField = {
            color: '#FF0000',
        };
        
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchMunicipios();
                    await fetchUnidades();
                    await fetchUserData();
                    await fetchGeneros();
                    await fetchSexos();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Fetch para obter os Sexo
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
            
            // Fetch para obter os Generos
            const fetchGeneros = async () => {
                try {
                    const response = await fetch(base_url + api_get_genero);
                    const data = await response.json();
                    console.log('Genero: ', data);
                    if (data.result.dbResponse && data.result.dbResponse.length > 0) {
                        setGeneros(data.result.dbResponse);
                    }
                } catch (error) {
                    setError('Erro ao carregar Generos: ' + error.message);
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
                    setError('Erro ao carregar municípios: ' + error.message);
                }
            };

            // Fetch para obter os dados do Profissional
            const fetchUserData = async () => {
                try {
                    const response = await fetch(base_url + api_get_value_profissional);
                    const data = await response.json();
                    console.log('Profissional: ', data);
                    if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                        const user = data.result.dbResponse[0];
                        setFormData({
                            nome: user.Nome,
                            sexo: user.SexoBiologico,
                            municipio: user.MunicipioUnidade,
                        });
                    }
                } catch (error) {
                    setError('Erro ao buscar dados: ' + error.message);
                }
            };

        // Lidar com a mudanças (Handle Change)
        const handleChange = (event) => {
            const { name, value } = event.target;
            setFormData(prevState => ({
                ...prevState,
                [name]: value
            }));
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
                <div className="row mb-1">
                    <div className="col-12 mb-1">
                        <div className="d-flex align-items-center">
                            <div className="ms-3" style={verticalBarStyle}></div>
                            <h2 className="myBold">CADASTRAR PERFIL</h2>
                        </div>
                    </div>
                </div>
                <form action={base_url+'index.php/fia/ptpa/adolescente/api/cadastrar'} method="post" className="row was-validated m-2">
                    <div className="row">
                        <div className="col-12 col-sm-12">
                            <div className="card mb-4">
                                <div className="card-body">
                                    <div className="row">
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="NomeProfissional" style={formLabelStyle}>Nome<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="NomeProfissional" name="NomeProfissional" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="CPFFUncionario" style={formLabelStyle}>CPF<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="CPFFUncionario" name="CPFFUncionario" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="Nascimento" style={formLabelStyle}>Data de nascimento<strong style={requiredField}>*</strong></label>
                                                <input type="date" id="Nascimento" name="Nascimento" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="RG" style={formLabelStyle}>RG<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="RG" name="RG" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="ExpedidorRG" style={formLabelStyle}>Expedidor do RG<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="ExpedidorRG" name="ExpedidorRG" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="ExpedicaoRG" style={formLabelStyle}>Expedição do RG<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="ExpedicaoRG" name="ExpedicaoRG" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-12 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="NomeMae" style={formLabelStyle}>Nome da mãe<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="NomeMae" name="NomeMae" className="form-control" style={formControlStyle} required />
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
                                                <input type="text" id="Etnia" name="Etnia" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="sexo_biologico_id" style={formLabelStyle} className="form-label">Sexo<strong style={requiredField}>*</strong></label>
                                                <select id="sexo_biologico_id" name="sexo_biologico_id" className="form-select" style={formControlStyle}aria-label="Default select 0" required>
                                                    <option value="">Seleção Nula</option>
                                                    {sexos.map(sexo_select => (
                                                        <option key={sexo_select.id} value={sexo_select.sexo_biologico}>
                                                            {sexo_select.sexo_biologico}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="genero_identidade_id" style={formLabelStyle} className="form-label">Gênero<strong style={requiredField}>*</strong></label>
                                                <select id="genero_identidade_id" name="genero_identidade_id" className="form-select" style={formControlStyle} aria-label="Default select 0" required>
                                                    <option value="">Seleção Nula</option>
                                                    {generos.map(genero_select => (
                                                        <option key={genero_select.id} value={genero_select.genero}>
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
                                                <label htmlFor="TelefoneMovel" style={formLabelStyle}>Telefone Móvel<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="TelefoneMovel" name="TelefoneMovel" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-3 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="TelefoneFixo" style={formLabelStyle}>Telefone Fixo<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="TelefoneFixo" name="TelefoneFixo" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-3 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="TelefoneRecado" style={formLabelStyle}>Telefone Recado<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="TelefoneRecado" name="TelefoneRecado" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-3 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="Email" style={formLabelStyle}>E-mail<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="Email" name="Email" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="Endereco" style={formLabelStyle}>Endereço<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="Endereco" name="Endereco" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="Bairro" style={formLabelStyle}>Bairro<strong style={requiredField}>*</strong></label>
                                                <input type="text" id="Bairro" name="Bairro" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                        <div style={formGroupStyle}>
                                            <label htmlFor="UFRegistro" style={formLabelStyle} className="form-label">UF<strong style={requiredField}>*</strong></label>
                                                <select id="UFRegistro" name="UFRegistro" className="form-select" style={formControlStyle} aria-label="Default select 4" required>
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
                                        <div className="col-12 col-sm-4 mb-3">
                                            <label htmlFor="programa_fia" className="form-label">Programa FIA</label>
                                            <div className="form-check">
                                                <input type="radio" id="programa_fia_ptpa" name="programa_fia" className="form-check-input" required />
                                                <label className="form-check-label" htmlFor="validationFormCheck3">PTPA</label>
                                            </div>
                                            <div className="form-check">
                                                <input type="radio" id="programa_fia_par" name="programa_fia" className="form-check-input" required />
                                                <label className="form-check-label" htmlFor="validationFormCheck4">PAR</label>
                                            </div>
                                            <div className="form-check">
                                                <input type="radio" id="programa_fia_sos" name="programa_fia" className="form-check-input" required />
                                                <label className="form-check-label" htmlFor="validationFormCheck5">SOS Criança e Adolescente</label>
                                            </div>
                                            <div className="form-check">
                                                <input type="radio" id="programa_fia_naca" name="programa_fia" className="form-check-input" required />
                                                <label className="form-check-label" htmlFor="validationFormCheck6">NACA</label>
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="CodProfissao" style={formLabelStyle} className="form-label">Cargo/Função<strong style={requiredField}>*</strong></label>
                                                <select id="CodProfissao" name="CodProfissao" className="form-select" style={formControlStyle} aria-label="Default select 0" required>
                                                    <option value="">Seleção Nula</option>
                                                    <option value={1}>Coordenador</option>
                                                    <option value={2}>Assistente administrativo</option>
                                                    <option value={3}>Psicólogo</option>
                                                    <option value={4}>Assistente social</option>
                                                    {profissoes.map(profissao_select => (
                                                        <option key={profissao_select.id} value={profissao_select.Descricao}>
                                                            {profissao_select.Descricao}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="unidade_id" style={formLabelStyle} className="form-label">Unidade<strong style={requiredField}>*</strong></label>
                                                <select id="unidade_id" name="unidade_id" className="form-select" style={formControlStyle} aria-label="Default select 1" required >
                                                    <option value="">Seleção Nula</option>
                                                    {unidades.map(unidade_select => (
                                                        <option key={unidade_select.id} value={unidade_select.Nome}>
                                                            {unidade_select.Nome}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="DataCadastramento" style={formLabelStyle}>Data de cadastramento<strong style={requiredField}>*</strong></label>
                                                <input type="date" id="DataCadastramento" name="DataCadastramento" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="DataInicioUnid" style={formLabelStyle}>Admissão<strong style={requiredField}>*</strong></label>
                                                <input type="date" id="DataInicioUnid" name="DataInicioUnid" className="form-control" style={formControlStyle} required />
                                            </div>
                                        </div>
                                        <div className="col-12 col-sm-4 mb-3">
                                            <div style={formGroupStyle}>
                                                <label htmlFor="DataTermUnid" style={formLabelStyle}>Demissão<strong style={requiredField}>*</strong></label>
                                                <input type="date" id="DataTermUnid" name="DataTermUnid" className="form-control" style={formControlStyle} required />
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
            </div>
        );
    };
    //ReactDOM.render(<AppFormPerfil />, document.querySelector('.app_form_perfil'));

    const rootElement = document.querySelector('.app_form_perfil');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppFormPerfil />);
</script>
<?php
$parametros_backend = array();
?>