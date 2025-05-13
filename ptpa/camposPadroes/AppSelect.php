<script type="text/babel">
    const AppSelect = (
        {
            parametros = {},
            formData = {},
            setFormData = () => { },
            fieldAttributes = {},
        }
    ) => {
        // Variáveis recebidas do Backend
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const objetoArrayKey = fieldAttributes.objetoArrayKey || [];
        const [objetoMapKey, setObjetoMapKey] = React.useState([]);
        const [isLoading, setIsLoading] = React.useState(true);
        const [applyFilters, setApplyFilters] = React.useState({
            filtroSelect: null,
        });
        const [selectedLabel, setSelectedLabel] = React.useState('Selecione uma opção');
        const debounceTimeout = React.useRef(null);

        // Field Attributes
        const attributeOrigemForm = fieldAttributes.origemForm || '';
        const base_url = parametros.base_url || '';
        const labelField = fieldAttributes.labelField || 'AppTextLabel';
        const nameField = fieldAttributes.nameField || 'AppTextName';

        const attributeFieldKey = fieldAttributes.attributeFieldKey || [];
        const attributeFieldName = fieldAttributes.attributeFieldName || [];
        const attributeRequired = fieldAttributes.attributeRequired || false;
        const attributeDisabled = fieldAttributes.attributeDisabled || false;

        // Attributes of APIs
        const api_get = fieldAttributes.api_get || 'api/get';
        const api_post = fieldAttributes.api_post || 'api/post';
        const api_filter = fieldAttributes.api_filter || 'api/filter';
        const getVar_page = '?page=1&limit=90000';

        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
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
        };

        const handleChange = (event) => {
            const { name, value } = event.target;

            // console.log('name === nameField: ', name);
            // console.log('name === nameField: ', nameField);

            // Atualiza o estado do formulário
            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));

            // Reseta mensagens de feedback
            setMessage({ show: false, type: null, message: null });

            // Atualiza o filtro dinamicamente, se aplicável
            if (name === 'filtroSelect') {
                setApplyFilters((prev) => ({
                    ...prev,
                    [name]: value,
                }));

                clearTimeout(debounceTimeout.current);
                debounceTimeout.current = setTimeout(() => {
                    fetchFilter();
                }, 500);
            }

            // Atualiza o rótulo do botão dropdown
            if (name === nameField) {
                const selectedOption = objetoMapKey.find(item => item.key === value);
                setSelectedLabel(selectedOption ? `${selectedOption.value.slice(0, calcularSlice(window.innerWidth))}` : 'Selecione uma opção');
            }
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

            setMessage({ show: false, type: null, message: null });
        };

        // Adicione este useEffect para monitorar o valor inicial e mudanças no formData
        React.useEffect(() => {
            if (formData[nameField]) {
                const selectedOption = objetoMapKey.find(item => item.key === formData[nameField]);
                if (selectedOption) {
                    setSelectedLabel(selectedOption.value.slice(0, calcularSlice(window.innerWidth)));
                }
            }
        }, [formData[nameField], objetoMapKey]); // Dependências do useEffect

        // React.useEffect
        React.useEffect(() => {
            // console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                // console.log('loadData iniciando...');

                try {
                    await fetchPost();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };
            // console.log(formData);
            loadData();
        }, []);

        // Simula carregamento de dados
        React.useEffect(() => {
            if (
                api_get === 'api/get' &&
                api_post === 'api/post' &&
                api_filter === 'api/filter'
            ) {
                setObjetoMapKey(objetoArrayKey);
            }

        }, [objetoArrayKey]);

        // POST Padrão 
        const fetchPost = async (custonBaseURL = base_url, custonApiPostObjeto = api_post, customPage = getVar_page) => {
            // console.log('fetchPost... ');
            // console.log('src/app/Views/fia/ptpa/camposPadroes/AppSelect.php');
            if (
                api_get === 'api/get' &&
                api_post === 'api/post' &&
                api_filter === 'api/filter'
            ) {
                // console.log('return false...');
                return false;
            }
            // console.log('fetchPost - getVar_page: ', getVar_page);
            const url = `${custonBaseURL}${custonApiPostObjeto}${customPage}`;
            // console.log('fetchPost - url: ', url);
            // return false;
            const SetData = { setFormData };
            // console.log('fetchPost - SetData: ', SetData);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(SetData),
                });
                // Verificação de erros HTTP
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                const data = await response.json();
                // console.log('fetchPost - data: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('fetchPost - dbResponse :: ', dbResponse);
                    // 
                    const mappedResponse = dbResponse.map((item) => ({
                        [attributeFieldKey[1]]: item[attributeFieldKey[0]], // Mapeia a chave
                        [attributeFieldName[1]]: item[attributeFieldName[0]], // Mapeia o valor
                    }));
                    // console.log('fetchFilter - mappedResponse: ', mappedResponse);
                    setObjetoMapKey(mappedResponse);
                    //
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Filtro sem sucesso'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // Filtro Padrão
        const fetchFilter = async (custonBaseURL = base_url, custonApiPostObjeto = api_filter, customPage = getVar_page) => {
            // console.log('fetchFilter... ');
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log("url :: ", url);
            const setData = applyFilters;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                // console.log("response :: ", response);
                const data = await response.json();
                // console.log("data :: ", data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('fetchFilter - dbResponse: ', dbResponse);
                    // 
                    const mappedResponse = dbResponse.map((item) => ({
                        [attributeFieldKey[1]]: item[attributeFieldKey[0]], // Mapeia a chave
                        [attributeFieldName[1]]: item[attributeFieldName[0]], // Mapeia o valor
                    }));
                    // console.log('fetchFilter - mappedResponse: ', mappedResponse);
                    setObjetoMapKey(mappedResponse);
                    //
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Filtro sem sucesso'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        const calcularSlice = (larguraTela) => {
            if (larguraTela < 413 && larguraTela > 361) return 25;
            if (larguraTela < 361) return 18;
            return 100;
        };

        // Style 
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

        const requiredField = {
            color: '#FF0000',
        };

        const fontErro = {
            fontSize: '0.7em',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                <div style={formGroupStyle}>
                    <label
                        htmlFor={nameField}
                        style={formLabelStyle}
                        className="form-label"
                    >
                        {labelField}
                        {(attributeRequired) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    <div className="btn-group w-100">
                        <button
                            className="form-control text-start w-100"
                            type="button"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                            style={{ height: "34px", padding: "4px 8px", border: "none", boxShadow: "none"}}
                        >
                            <div className="d-flex justify-content-between align-items-center">
                                <span className="flex-grow-1">{selectedLabel}</span>
                                {(attributeRequired && formData[nameField] === null) && (
                                    <i className="bi bi-exclamation-circle text-danger me-2 p-0" style={{ textShadow: "-0.5px -0.5px 0.5px red" }}></i>
                                )}
                                {(formData[nameField] !== null) && (
                                    <i className="bi bi-check-lg text-success me-2 p-0" style={{ textShadow: "-0.5px -0.5px 0.5px green" }}></i>
                                )}
                                <i className="bi bi-chevron-down m-0 p-0" />
                            </div>
                        </button>
                        <div className="dropdown-menu w-100">
                            {(
                                !api_get === 'api/get' &&
                                !api_post === 'api/post' &&
                                !api_filter === 'api/filter'
                            ) && (
                                    <div className="m-1 p-1 border border rounded">
                                        <input
                                            type="text"
                                            data-api={`filtro-buscarselect`}
                                            className="form-control form-control-sm"
                                            style={formControlStyle}
                                            id={`filtroSelect`}
                                            name={`filtroSelect`}
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                            required={attributeRequired}
                                            disabled={attributeDisabled}
                                        />
                                    </div>
                                )}
                            <div className="m-1 p-2 w-auto border rounded">
                                                        
                                <select
                                    data-api={`filtro-${attributeOrigemForm}`}
                                    className="form-select form-select-sm"
                                    style={formControlStyle}
                                    id={nameField}
                                    name={nameField}
                                    value={formData[nameField] || ''}
                                    size={10}
                                    required={attributeRequired}
                                    disabled={attributeDisabled}
                                    onFocus={handleFocus}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                >
                                    <option value="" disabled>
                                        Selecione uma opção
                                    </option>
                                    {/* MAP SELECT */}
                                    {objetoMapKey.map((item, index) => (
                                        <option key={item.key || index}
                                            value={item.key}
                                            title={item.value}
                                        >
                                            {item.value}
                                            {/*item.value.length > 30 ? `${item.value.slice(0, 30)}...` : item.value*/}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    };
</script>