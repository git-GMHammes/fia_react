<script type="text/babel">
    const BKPSelectBtnRadio = (
        {
            submitAllForms,
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
        const [setFilter, setSetFilter] = React.useState({
            filtroSelect: null,
        });
        const [selectedLabel, setSelectedLabel] = React.useState('Selecione uma opção');
        const debounceTimeout = React.useRef(null);

        // Field Attributes src\app\Views\fia\ptpa\camposPadroes\BKPSelectBtnRadio.php
        const attributeOrigemForm = fieldAttributes.origemForm || '';
        const base_url = parametros.base_url || '';
        const labelField = fieldAttributes.labelField || 'AppTextLabel';
        const nameField = fieldAttributes.nameField || 'AppTextName';
        const errorMessage = fieldAttributes.errorMessage || '';
        const [selectedIds, setSelectedIds] = React.useState(() => {
            // Verifica se o formData possui o campo nameField e se ele tem valor
            return formData[nameField] ? [formData[nameField].toString()] : [];
        });

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

        // Função handleChange simplificada
        const handleChange = (event) => {
            // console.log("handleChange...");
            const { name, value } = event.target;

            // Lógica para os radio buttons (diferente de filtroSelect)
            if (name !== 'filtroSelect') {
                // Para radio buttons, sempre substituímos o array com um único valor
                setSelectedIds([value]);

                // Encontrar o item correspondente para atualizar o label
                const selectedItem = objetoMapKey.find(item => item.key === value);
                if (selectedItem && selectedItem[attributeFieldName[1]]) {
                    setSelectedLabel(selectedItem[attributeFieldName[1]]);
                }

                return;
            }

            // Resto da lógica para o filtroSelect
            if (name === 'filtroSelect') {
                // console.log('handleChange - filtroSelect:', value);

                // Se o comprimento do valor for 1 ou menos, redefine o estado
                if (value.length <= 1) {
                    setObjetoMapKey(objetoArrayKey);
                    setSetFilter((prev) => ({
                        ...prev,
                        filtroSelect: null,
                    }));
                    // Cancela qualquer debounce ativo
                    if (debounceTimeout.current) {
                        clearTimeout(debounceTimeout.current);
                    }
                    fetchPost();
                    return;
                }

                // Atualiza o estado setFilter
                setSetFilter((prev) => ({
                    ...prev,
                    filtroSelect: value,
                }));

                setFormData((prev) => ({
                    ...prev,
                    [name]: value,
                }));

                // Adiciona debounce para chamar fetchFilter
                if (debounceTimeout.current) {
                    clearTimeout(debounceTimeout.current);
                }
                debounceTimeout.current = setTimeout(() => {
                    fetchFilter();
                }, 300);
            }

        };

        // Função que executa após a retirada do foco
        const handleBlur = (event) => {
            const { name } = event.target;

            // console.log('name handleBlur: ', name);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => {
                const updatedFormData = {
                    ...prev,
                    [name]: selectedIds.join(', '),
                };
                // console.log('formData atualizado no handleBlur:', updatedFormData);
                return updatedFormData;
            });
        };

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
            // console.log('-------------------------------');
            // console.log('src/app/Views/fia/ptpa/camposPadroes/BKPSelectBtnRadio.php');
            if (custonApiPostObjeto === 'api/post') {
                return false;
            } else {
                setObjetoMapKey([{ key: '', value: 'carregando...' }]);
            }
            let message = errorMessage === '' ? `Não foram encontrados(as) ${labelField} cadastrados(as)` : errorMessage;
            // console.log('fetchPost - getVar_page: ', getVar_page);
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log('fetchPost - url: ', url);
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
                    // console.log('fetchPost - data.result.dbResponse: ', data.result.dbResponse);
                    const dbResponse = data.result.dbResponse;
                    //
                    // console.log('attributeFieldKey :: ', attributeFieldKey);
                    // console.log('attributeFieldName :: ', attributeFieldName);
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
                        message: message
                    });
                    setIsLoading(false);
                    setObjetoMapKey([]);
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
            let message = errorMessage === '' ? `Não foram encontrados(as) ${labelField} cadastrados(as)` : errorMessage;
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log('fetchFilter - url: ', url);
            const setData = setFilter;
            // console.log("setData :: ", setData);

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
                        message: message
                    });
                    setIsLoading(false);
                    setObjetoMapKey([]);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        {/* React.useEffect 1 */ }
        React.useEffect(() => {
            // Verifica se há algum ID selecionado no formData
            if (formData[nameField]) {
                const id = formData[nameField].toString();
                setSelectedIds([id]);

                // Buscar o item correspondente no objetoMapKey para definir o label
                const item = objetoMapKey.find(item => item.key === id);
                if (item && item[attributeFieldName[1]]) {
                    setSelectedLabel(item[attributeFieldName[1]]);
                }
            }
        }, []);

        {/* React.useEffect 2 */ }
        React.useEffect(() => {
            // Verifica se há algum ID selecionado no formData
            // console.log('-----------------------');
            // console.log('React.useEffect - formData: ', formData);
            if (formData[nameField]) {
                const id = formData[nameField].toString();
                setSelectedIds([id]);

                // Buscar o item correspondente no objetoMapKey para definir o label
                const item = objetoMapKey.find(item => item.key === id);
                if (item && item[attributeFieldName[1]]) {
                    setSelectedLabel(item[attributeFieldName[1]]);
                }
            }
        }, [formData]);

        {/* React.useEffect 3 */ }
        React.useEffect(() => {
            // console.log('--------------------------');
            // console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                // console.log('labelField :: ', labelField);
                // console.log('nameField :: ', nameField);

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

        {/* React.useEffect 4 */ }
        React.useEffect(() => {
            if (selectedIds.length > 0 && JSON.stringify(formData[nameField]) !== JSON.stringify(selectedIds[0])) {
                setFormData(prev => ({
                    ...prev,
                    [nameField]: selectedIds[0], // Usa apenas o primeiro ID
                }));
            }
        }, [selectedIds]);

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
                        htmlFor="dynamicSelect"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        {labelField}
                        {attributeRequired && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    <div className="btn-group w-100">
                        <button
                            className="btn btn-sm dropdown text-start"
                            type="button"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                        >
                            <div className="d-flex bd-highlight">
                                <div className="p-1 flex-grow-1 bd-highlight">
                                    {selectedLabel}
                                </div>
                                <div className="p-1 bd-highlight">
                                    {attributeRequired && (
                                        <i className="bi bi-exclamation-circle text-danger"></i>
                                    )}
                                </div>
                                <div className="p-1 bd-highlight">
                                    <i className="bi bi-caret-down-fill ps-1" />
                                </div>
                            </div>
                        </button>
                        <ul className="dropdown-menu w-100">
                            {!api_get === "api/get" &&
                                !api_post === "api/post" &&
                                !api_filter === "api/filter" && (
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
                                            required={false}
                                        />
                                    </div>
                                )}
                            <div className="m-1 p-2 w-auto border rounded">
                                {objetoMapKey.map((item, index) => (
                                    <li key={`${item.key || ''}${index}`} className="toggle-container">
                                        <input
                                            type="radio"
                                            className="btn-check"
                                            id={`${nameField}-${item.key || index}`}
                                            name={nameField}
                                            value={item.key || ''}
                                            autoComplete="off"
                                            checked={selectedIds.length > 0 && selectedIds[0].toString() === (item.key || '').toString()}
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                            required={attributeRequired}
                                            disabled={attributeDisabled}
                                        />
                                        <label
                                            className={`btn btn-outline btn-sm text-start mb-2 w-100 ${selectedIds.length > 0 && selectedIds[0] === item.key ? "active" : ""}`}
                                            htmlFor={`${nameField}-${item.key || index}`}
                                            aria-pressed={selectedIds.length > 0 && selectedIds[0] === item.key}
                                        >
                                            {(item[attributeFieldName[1]] || "Nome Indisponível")}
                                        </label>
                                    </li>
                                ))}
                            </div>

                        </ul>
                    </div>
                </div>
            </div>
        );
    };
</script>