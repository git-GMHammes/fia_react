<script type="text/babel">
    const AppSelectBtnCheck = (
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
        const [onEscolaridade, setOnEscolaridade] = React.useState(true);
        const objetoArrayKey = fieldAttributes.objetoArrayKey || [];
        const [objetoMapKey, setObjetoMapKey] = React.useState([]);
        const [isLoading, setIsLoading] = React.useState(true);
        const [selectedIds, setSelectedIds] = React.useState(
            formData.GeneroIdentidadeId
                ? formData.GeneroIdentidadeId.split(",").map((id) => id.trim())
                : []
        );

        const [previousGeneroIdentidadeId, setPreviousGeneroIdentidadeId] = React.useState("");
        const [selectedLabel, setSelectedLabel] = React.useState('Selecione uma ou mais opções');
        const debounceTimeout = React.useRef(null);
        const debounceUpdate = React.useRef(null);

        // Field Attributes
        const attributeOrigemForm = fieldAttributes.origemForm || '';
        const base_url = parametros.base_url || '';
        const labelField = fieldAttributes.labelField || 'AppTextLabel';
        const nameField = fieldAttributes.nameField || 'AppTextName';
        const errorMessage = fieldAttributes.errorMessage || '';

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

        // Simula carregamento de dados
        React.useEffect(() => {
            if (api_get === 'api/get' && api_post === 'api/post' && api_filter === 'api/filter') {
                setObjetoMapKey(objetoArrayKey);
            }
        }, [objetoArrayKey]);

        // Função handleFocus para receber foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            //  console.log('handleFocus: ', name);
            //  console.log('handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange simplificada
        const handleChange = async (event) => {
            // console.log("handleChange...");

            const { name, value, checked, type } = event.target;

            if (type === "checkbox") {
                // Atualiza o estado local selectedIds primeiro
                const updatedSelectedIds = checked
                    ? [...selectedIds, value] // Adiciona o ID selecionado
                    : selectedIds.filter((id) => id !== value); // Remove o ID desmarcado

                setSelectedIds(updatedSelectedIds);

                // Atualiza o formData após a atualização de selectedIds, fora do ciclo de renderização
                setTimeout(() => {
                    // Atualiza o rótulo com as opções selecionadas
                    const selectedOptions = objetoMapKey
                        .filter((item) => updatedSelectedIds.includes(item.key))
                        .map((item) => item.value);

                    setSelectedLabel(
                        selectedOptions.length > 0
                            ? selectedOptions.join(', ') // Lista de seleções separada por vírgulas
                            : 'Selecione uma opção'
                    );

                    setFormData((prev) => ({
                        ...prev,
                        [name]: updatedSelectedIds.join(", "),
                    }));
                }, 0);

                return;
            }

            // Lógica para o campo filtroSelect
            if (name === 'filtroSelect') {
                // console.log('handleChange - filtroSelect:', value);

                // Se o comprimento do valor for 1 ou menos, redefine o estado
                if (value.length <= 1) {
                    //  console.log('value.length <= 1, redefinindo...');
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
                    //  console.log('Chamando fetchFilter com:', value);
                    fetchFilter();
                }, 300);
            }

            // Se o usuário selecionar "Outro", exibir input de texto
            if (name === nameField && value === 'Outro') {
                setOnEscolaridade(false);
            }

            // Se o campo for um input de texto, permitir edição corretamente
            setFormData((prevData) => ({
                ...prevData,
                [name]: value || "", // Garante que o campo pode ser apagado
            }));

        };

        // Função que executa após a retirada do foco
        const handleBlur = (event) => {
            const { name } = event.target;
            setMessage({ show: false, type: null, message: null });
            if (formData[name] !== selectedIds.join(', ')) {
                setFormData((prev) => ({
                    ...prev,
                    [name]: selectedIds.join(', '),
                }));
            }
        };

        // POST Padrão 
        const fetchPost = async (custonBaseURL = base_url, custonApiPostObjeto = api_post, customPage = getVar_page) => {
            // console.log('-------------------------------------');
            if (custonApiPostObjeto === 'api/post') {
                return false;
            } else {
                setObjetoMapKey([{ key: '', value: 'carregando...' }]);
            }
            let message = errorMessage === '' ? `Não foram encontrados(as) ${labelField} cadastrados(as)` : errorMessage;
            //  console.log('fetchPost - getVar_page: ', getVar_page);
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log('fetchPost - url: ', url);
            const SetData = { setFormData };
            //  console.log('fetchPost - SetData: ', SetData);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(SetData),
                });
                // console.log('fetchPost - response :: ', response);
                // Verificação de erros HTTP
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                const data = await response.json();
                //  console.log('fetchPost - data: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    //  console.log('fetchPost - data.result.dbResponse: ', data.result.dbResponse);
                    const dbResponse = data.result.dbResponse;
                    // 
                    const mappedResponse = dbResponse.map((item) => ({
                        [attributeFieldKey[1]]: item[attributeFieldKey[0]], // Mapeia a chave
                        [attributeFieldName[1]]: item[attributeFieldName[0]], // Mapeia o valor
                    }));
                    //  console.log('fetchFilter - mappedResponse: ', mappedResponse);
                    setObjetoMapKey(mappedResponse);
                    //
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: message
                    });
                    setMsgError(message);
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
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log('----------------------------------------------');
            // console.log('fetchFilter - url: ', url);
            let message = errorMessage === '' ? `Não foram encontrados(as) ${labelField} cadastrados(as)` : errorMessage;
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
                //  console.log("response :: ", response);
                const data = await response.json();
                //  console.log("data :: ", data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    //  console.log('fetchFilter - dbResponse: ', dbResponse);
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
                    setMsgError(message);
                    setIsLoading(false);
                    setObjetoMapKey([]);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // React.useEffect
        React.useEffect(() => {
            //  console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                //  console.log('loadData iniciando...');

                try {
                    await fetchPost();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };
            //  console.log(formData);
            loadData();
        }, []);

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
                {(onEscolaridade) ? (
                    <div style={formGroupStyle}>
                        <label
                            htmlFor="dynamicSelect"
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
                                className="btn dropdown text-start"
                                type="button"
                                data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"
                                aria-expanded="false"
                                style={{ height: "34px", padding: "4px 8px"}}
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
                            <ul className="dropdown-menu w-100">
                                {(
                                    !api_get === 'api/get' &&
                                    !api_post === 'api/post' &&
                                    !api_filter === 'api/filter'
                                ) && (
                                        <div className="m-1 p-1 border border rounded">
                                            <input
                                                type="text"
                                                data-api={`filtro-buscarselect`}
                                                className="form-control"
                                                style={formControlStyle}
                                                id={`filtroSelect`}
                                                name={`filtroSelect`}
                                                onFocus={handleFocus}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                required={attributeRequired}
                                            />
                                        </div>
                                    )}
                                <div className="m-1 p-2 w-auto border rounded">
                                    {objetoMapKey.map((item, index) => (
                                        <li key={`${item.key}${index}`} className="toggle-container">
                                            <input
                                                type="checkbox"
                                                className="btn-check"
                                                id={`${nameField}-${item.key}`}
                                                name={nameField}
                                                value={item.key}
                                                autoComplete="off"
                                                checked={selectedIds.includes(item.key)}
                                                onFocus={handleFocus}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                required={attributeRequired}
                                                disabled={attributeDisabled}
                                            />
                                            <label
                                                className={`btn btn-outline btn-sm text-start w-100 ${selectedIds.includes(item.key) ? "active" : ""}`}
                                                htmlFor={`${nameField}-${item.key}`}
                                                aria-pressed={selectedIds.includes(item.key)}
                                            >
                                                {item[attributeFieldName[1]] || "Nome Indisponível"}
                                            </label>
                                        </li>
                                    ))}
                                    <li key="Outro" className="toggle-container">
                                        <input
                                            type="checkbox"
                                            className="btn-check"
                                            id={`${nameField}-Outro`}
                                            name={nameField}
                                            value="Outro"
                                            autoComplete="off"
                                            checked={selectedIds.includes("Outro")}
                                            onFocus={handleFocus}
                                            onChange={(e) => {
                                                handleChange(e);
                                                if (e.target.value === "Outro") {
                                                    setOnEscolaridade(false);
                                                }
                                            }}
                                            onBlur={handleBlur}
                                            required={attributeRequired}
                                        />
                                        <label
                                            className={`btn btn-outline btn-sm text-start w-100 ${selectedIds.includes("Outro") ? "active" : ""}`}
                                            htmlFor={`${nameField}-Outro`}
                                            aria-pressed={selectedIds.includes("Outro")}
                                        >
                                            Outro:
                                        </label>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </div>
                ) : (
                    <div style={formGroupStyle}>
                        <label htmlFor="dynamicInput" style={formLabelStyle} className="form-label">
                            Especifique {labelField}
                            <strong
                                style={{ ...requiredField, cursor: 'pointer' }}
                                onClick={() => setOnEscolaridade(true)}
                            >
                                *
                            </strong>
                        </label>
                        <input
                            type="text"
                            id="dynamicInput"
                            name={nameField}
                            value={formData[nameField] || ''}
                            onChange={handleChange}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            required={attributeRequired}
                        />
                    </div>
                )}
            </div>
        );
    };
</script>