<?php
$token_csrf = (session()->get('token_csrf')) ? (session()->get('token_csrf')) : ('erro');
$atualizar_id = isset($result['atualizar_id']) ? ($result['atualizar_id']) : ('erro');

$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'result' => isset($result) ? ($result) : (array()),
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
    'atualizar_id' => $atualizar_id,
    'getVar_page' => isset($metadata['getVar_page']) ? ('?page=' . $metadata['getVar_page']) : ('?page=' . '1'),
    'server_port' => $_SERVER['SERVER_PORT'],
    'token_csrf' => $token_csrf,
    'DEBUG_MY_PRINT' => false,
    'api_filter_unidades' => 'index.php/fia/ptpa/unidade/api/filtrar',
    // 'api_get_objeto' => 'index.php/api/projeto/objeto/api/exibir',
    // 'api_update_objeto' => 'index.php/api/projeto/objeto/api/filtrar',
);

$parametros_backend['api_update_objeto'] = ($atualizar_id !== 'erro') ? ('projeto/sub/projeto/api/exibir/' . $atualizar_id) : ('projeto/sub/projeto/api/exibir/erro');
$parametros_backend['base_paginator'] = implode('/', $parametros_backend['getURI']);
?>

<div class="app_exemple_cep" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppTesteCEP = (
        {
            // parametros = {}
        }
    ) => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_exemple_cep').getAttribute('data-result'));
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const request_scheme = parametros.request_scheme || 'http';
        const server_name = parametros.server_name || 'localhost';
        const token_csrf = parametros.token_csrf || 'erro';
        const server_port = parametros.server_port || '80';
        const base_url = parametros.base_url || 'http://localhost';

        // Variáveis de estado
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const [apiobjeto, setApiobjeto] = React.useState([]);

        // Litas api_filter_unidades
        const api_get_objeto = parametros.api_get_objeto || '';
        const api_filter_unidades = parametros.api_filter_unidades || '';
        const base_paginator = base_url;
        const getVar_page = parametros.getVar_page || '?page=1';
        const [apiUrlList, setApiUrlList] = React.useState([]);

        // Variáveis Uteis
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [paginacaoLista, setPaginacaoLista] = React.useState([]);
        const [dataLoading, setDataLoading] = React.useState(true);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Estados para parâmetros do backend
        const [backendParams, setBackendParams] = React.useState({
            getURI: [],
            debugMyPrint: false,
            request_scheme: 'http',
            server_name: 'localhost',
            token_csrf: 'erro',
            server_port: '80',
            base_url: 'http://localhost',
            api_filter_unidades: ''
        });

        // Estados para o componente
        const [units, setUnits] = React.useState([]);
        const [originalUnits, setOriginalUnits] = React.useState([]);

        // Função para calcular a diferença entre CEPs
        const calculateCepDistance = (cep1, cep2) => {
            const cleanCep1 = cep1.replace(/\D/g, '');
            const cleanCep2 = cep2.replace(/\D/g, '');
            return Math.abs(parseInt(cleanCep1) - parseInt(cleanCep2));
        };

        // Função para ordenar unidades por proximidade do CEP
        const orderUnitsByCepProximity = (cepReference) => {
            if (!cepReference) return;

            const orderedUnits = [...units].sort((a, b) => {
                const distanceA = calculateCepDistance(a.unidades_cep, cepReference);
                const distanceB = calculateCepDistance(b.unidades_cep, cepReference);
                return distanceA - distanceB;
            });

            setUnits(orderedUnits);
        };

        // Função para aplicar máscara de CEP
        const applyMaskCEP = (value) => {
            return value
                .replace(/\D/g, '') // Remove tudo o que não é dígito
                .replace(/(\d{5})(\d)/, '$1-$2') // Coloca hífen entre o 5º e o 6º dígitos
                .substring(0, 9); // Limita o tamanho
        };

        // Função handleFocus
        const handleFocus = (event) => {
            const { name, value } = event.target;
            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            if (name === 'cep') {
                setUnits(originalUnits);
            }

            setFormData(prev => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange
        const handleChange = (event) => {
            const { name, value } = event.target;
            console.log('handleChange: ', name);
            console.log('handleChange: ', value);

            let processedValue = value;
            if (name === 'cep') {
                processedValue = applyMaskCEP(value);
            }

            setFormData(prev => ({
                ...prev,
                [name]: processedValue
            }));

            setMessage({ show: false, type: null, message: null });
        };

        // Função handleBlur
        const handleBlur = (event) => {
            const { name, value } = event.target;
            console.log('name handleBlur: ', name);
            console.log('value handleBlur: ', value);

            setFormData(prev => ({
                ...prev,
                [name]: value
            }));

            if (name === 'cep') {
                orderUnitsByCepProximity(value);
            }

            setMessage({ show: false, type: null, message: null });
        };

        // FormData state
        const [formData, setFormData] = React.useState({
            cep: '',
            unidade: '',
        });

        // POST fetchPostUnidade
        const fetchPostUnidade = async (custonBaseURL = base_url, custonApiPostObjeto = api_filter_unidades, customPage = '?limit=90000') => {
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
                // 
                const data = await response.json();
                // 
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    console.log('dbResponse ::', dbResponse);
                    // 
                    setUnits(dbResponse);
                    setOriginalUnits(dbResponse);
                    return true;
                    //
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

        // React.useEffect
        React.useEffect(() => {

            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    await fetchPostUnidade();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);

                } finally {
                    setIsLoading(false);

                }
            };

            loadData();

        }, []);

        // Efeito para carregar dados após ter os parâmetros
        React.useEffect(() => {
            if (backendParams.api_filter_unidades) {
                fetchPostUnidade();
            }
        }, [backendParams.api_filter_unidades]);

        return (
            <div>
                <div className="space-y-4">
                    {/* Input CEP */}
                    <div className="flex flex-col space-y-2">
                        <label htmlFor="cep" className="text-sm font-medium">CEP</label>
                        <input
                            type="text"
                            id="cep"
                            name="cep"
                            className="border rounded p-2"
                            value={formData.cep}
                            onChange={handleChange}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                            placeholder="Digite o CEP"
                        />
                    </div>

                    {/* Select Unidades */}
                    <div className="flex flex-col space-y-2">
                        <label htmlFor="unit" className="text-sm font-medium">Unidade</label>
                        <select
                            id="unit"
                            name="unit"
                            className="border rounded p-2"
                            value={formData.unidade}
                            onChange={handleChange}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                        >
                            <option value="">Selecione uma unidade</option>
                            {units.map((unit) => (
                                <option key={unit.id} value={unit.id}>
                                    {unit.unidades_nome} - {unit.unidades_cep}
                                </option>
                            ))}
                        </select>
                    </div>

                    {/* Loading state */}
                    {isLoading && (
                        <div className="text-center py-4">
                            Carregando...
                        </div>
                    )}
                </div>

            </div>
        )

    };
    const rootElement = document.querySelector('.app_exemple_cep');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppTesteCEP />);
    // ReactDOM.render(<AppExemple />, document.querySelector('.app_exemple_cep'));
</script>
<?php
$parametros_backend = array();
?>