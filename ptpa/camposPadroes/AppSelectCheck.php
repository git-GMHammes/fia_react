<script type="text/babel">
    const AppSelectCheck = (
        {
            parametros = {},
            formData = {},
            setFormData = () => { },
            fieldAttributes = {},
        }
    ) => {
        // Variáveis
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const objetoArrayKey = fieldAttributes.objetoArrayKey || [];
        const [objetoMapKey, setObjetoMapKey] = React.useState([]);
        const [isLoading, setIsLoading] = React.useState(true);
        const [progress, setProgress] = React.useState(0);
        const [showFilterSelect, setShowFilterSelect] = React.useState(true);
        const [applyFilters, setApplyFilters] = React.useState({
            filtroSelect: null,
        });
        const [selectedLabel, setSelectedLabel] = React.useState('Escolha uma opção');
        const [listSelect, setListSelect] = React.useState([]);
        const [selectedIds, setSelectedIds] = React.useState([]);
        const [choice, setChoice] = React.useState(false);
        const [other, setOther] = React.useState('');
        const debounceTimeout = React.useRef(null);

        // Field Attributes
        const attributeOrigemForm = fieldAttributes.origemForm || '';
        const base_url = parametros.base_url || '';
        const labelField = fieldAttributes.labelField || 'AppTextLabel';
        const nameField = fieldAttributes.nameField || 'AppTextName';
        const btnCollor = fieldAttributes.btnCollor || '';
        const btnOutline = fieldAttributes.btnOutline || false;
        const btnSize = fieldAttributes.btnSize || '';
        const btnRounded = fieldAttributes.btnRounded || '';

        const attributeFieldValue = fieldAttributes.attributeFieldValue || 'id'; //ttributeFieldValue
        const attributeFieldLabel = fieldAttributes.attributeFieldLabel || 'value'; // ttributeFieldLabel
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

        const handleFocus = (event) => {
            const { name, value } = event.target;
            // console.log('-------------------------');
            // console.log('handleFocus');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            // console.log('name :: ', name);
            // console.log('value :: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        const handleChange = (event) => {
            // Adicione "type" na desestruturação para resolver o erro
            const { name, value, type, checked } = event.target;
            console.log('-------------------------');
            console.log('handleChange');
            console.log('-------------------------');
            console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            console.log('name :: ', name);
            console.log('value :: ', value);
            console.log('type :: ', type);
            console.log('checked :: ', checked);
            // 
            const cleanedValue = removeSpacesAroundCommas(value);
            if (type === 'checkbox') {
                setSelectedIds(prev => checked
                    ? [...prev, value]
                    : prev.filter(id => id !== value)
                );
                return;
            }

            if (type === 'text') {
                setFormData((prev) => ({
                    ...prev,
                    [name]: cleanedValue
                }));
                return;
            }

            setMessage({ show: false, type: null, message: null });

        };

        const handleBlur = (event) => {
            const { name, value } = event.target;
            // console.log('-------------------------');
            // console.log('handleBlur');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            // console.log('name :: ', name);
            // console.log('value :: ', value);

            // setFormData((prev) => ({
            // ...prev,
            // [name]: selectedIds.join(','),
            // }));

            setMessage({ show: false, type: null, message: null });
        };

        const updateSelectedLabel = (selectedValues) => {
            if (!selectedValues || selectedValues.length === 0) {
                setSelectedLabel('Escolha uma opção');
                return;
            }

            // Se há apenas um item selecionado
            if (selectedValues.length === 1) {
                const item = listSelect.find(item => item.id.toString() === selectedValues[0].toString());
                if (item) {
                    setSelectedLabel(item.value);
                } else {
                    setSelectedLabel(selectedValues[0]); // Se for um valor "Outro"
                }
                return;
            }

            // Se há múltiplos itens selecionados
            setSelectedLabel(`${selectedValues.length} itens selecionados`);
        };

        const removeSpacesAroundCommas = (value) => {
            // Primeiro remove espaços antes das vírgulas
            let cleaned = value.replace(/\s+,/g, ',');
            // Depois remove espaços depois das vírgulas
            cleaned = cleaned.replace(/,\s+/g, ',');
            // Remove vírgulas duplicadas ou mais
            cleaned = cleaned.replace(/,+/g, ',');
            return cleaned;
        };


        const stringToArray = (value) => {
            if (!value) return [];
            return Array.isArray(value) ? value : value.split(',').map((item) => item.trim());
        };

        const arrayToString = (array) => {
            if (!array) return '';
            return Array.isArray(array) ? array.join(',') : array;
        };

        const makeSelectedLabel = () => {
            if (!formData[nameField] || formData[nameField] === '') {
                return 'Escolha uma opção';
            }

            const selectedValues = stringToArray(formData[nameField]);
            return getSelectedLabel(selectedValues);
        };

        const buildSelectedLabel = (id) => {
            // Se não temos uma lista de seleção válida
            if (!Array.isArray(listSelect)) {
                console.error('A variável global listSelect não é um array:', listSelect);
                return 'Erro: A variável global listSelect não é um array.';
            }

            // Se o id for uma string com valores separados por vírgula
            if (typeof id === 'string' && id.includes(',')) {
                const ids = stringToArray(id);
                if (ids.length > 1) {
                    return `${ids.length} itens selecionados`;
                } else if (ids.length === 1) {
                    const foundItem = listSelect.find(item => item.id.toString() === ids[0].toString());
                    if (foundItem) {
                        return foundItem.value;
                    }
                }
            }

            // Comportamento original para um único id
            const foundItem = listSelect.find(item => item.id.toString() === id.toString());
            if (foundItem) {
                return foundItem.value;
            } else {
                return 'Escolha uma opção';
            }
        };

        const removeFilter = (parameter) => {
            // console.log('-----------------');
            // console.log('removeFilter');
            // console.log('parameter :: ', parameter);
            if (parameter !== null && !isNaN(parameter)) {
                setShowFilterSelect(false);
            } else {
                setShowFilterSelect(true);
            }
        }

        const getSelectedLabel = (selectedValues) => {
            if (!selectedValues || selectedValues.length === 0) {
                return 'Escolha uma opção';
            }

            // Se há apenas um item selecionado
            if (selectedValues.length === 1) {
                const item = listSelect.find(item => item.id.toString() === selectedValues[0].toString());
                if (item) {
                    return item.value;
                } else {
                    return selectedValues[0]; // Se for um valor "Outro"
                }
            }

            // Se há múltiplos itens selecionados
            return `${selectedValues.length} itens selecionados`;
        };

        const getSelectedLabelText = (values) => {
            if (!values || values.length === 0) {
                return 'Escolha uma opção';
            }

            if (values.length === 1) {
                // Se for um valor numérico, busca na lista
                if (!isNaN(values[0])) {
                    const foundItem = listSelect.find(item => item.id.toString() === values[0].toString());
                    if (foundItem) {
                        return foundItem.value;
                    }
                }
                // Se não for numérico ou não for encontrado na lista, retorna o próprio valor
                return values[0];
            }

            // Múltiplos itens
            return `${values.length} itens selecionados`;
        };

        const renderLoading = () => {
            return (
                <div
                    className="progress"
                    role="progressbar"
                    aria-label="Example with label"
                    aria-valuenow={progress}
                    aria-valuemin="0"
                    aria-valuemax="100"
                >
                    <div
                        className="progress-bar"
                        style={{
                            width: `${progress}%`,
                            transition: 'width 0.1s linear', // Para suavizar a animação
                        }}
                    >
                        {`${Math.round(progress)}%`}
                    </div>
                </div>
            );
        };

        const setMappedResponse = (parameter) => {
            // console.log('-------------------------');
            // console.log('setMappedResponse');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            // console.log('parameter:', parameter);

            // Validação para verificar se é um array
            if (!Array.isArray(parameter)) {
                console.error('O parâmetro fornecido não é um array:', parameter);
                return;
            }

            // Mapeando os valuees com base nas variáveis globais
            const mappedResponse = parameter.map((item) => {
                return {
                    id: item[attributeFieldValue],
                    value: item[attributeFieldLabel],
                };
            });

            // console.log('mappedResponse:', mappedResponse);
            setListSelect(mappedResponse);
            addOuther(mappedResponse)
        };

        const addOuther = (parameter) => {
            let addDataStack = [];
            // console.log('-------------------------');
            // console.log('addOuth');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            // console.log('parameter :: ', parameter);
            // console.log('attributeFieldValue :: ', attributeFieldValue);
            // console.log('attributeFieldValue :: ', attributeFieldValue);
            // console.log('formData :: ', formData);
            // console.log(`formData[nameField] - ${nameField}:: `, formData[nameField]);
            if (formData[nameField] && formData[nameField] !== null && !isNaN(formData[nameField])) {
                // console.log('-------------------------');
                // console.log('#formData[nameField] - Existe');
                // console.log('#formData[nameField] - Diferente de null');
                // console.log('#formData[nameField] - Diferente de NaN');
                setChoice(false);
                return;
            }
            const getOuther = formData[nameField] || '';
            // console.log('getOuther :: ', getOuther);
            parameter.map((item, index) => {
                addDataStack.push(item[attributeFieldValue]);
            });

            if (getOuther && !checkWordInArray(addDataStack, getOuther)) {
                // console.log('-------------------------');
                // console.log('getOuther :: Existe');
                // console.log('getOuther :: Encontrado no FormData');
                debounceTimeout.current = setTimeout(() => {
                    // Criando um novo objeto com a chave dinâmica
                    const newItem = {};
                    newItem['id'] = getOuther;
                    // Adicionar também o attributeFieldLabel para exibir corretamente
                    newItem['value'] = getOuther;

                    // Criar uma nova cópia do array para não modificar o original
                    const updatedList = [...parameter, newItem];
                    // console.log('updatedList :: ', updatedList);
                    setListSelect(updatedList);
                    setOther('Outro');
                }, 300);
            } else {
                // console.log('-------------------------');
                // console.log('getOuther :: Não Existe');
                // console.log('getOuther :: Não Encontrado no FormData');
                debounceTimeout.current = setTimeout(() => {
                    setOther('Outro');
                    setChoice(false);
                }, 300);
            }
            // console.log(`formData[${nameField}] :: `, getOuther);
            return parameter;
        }

        const handleOther = (parameter) => {
            // console.log('-------------------------');
            // console.log('handleOther');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            setChoice(parameter);
        }

        {/* FETCH POST */ }
        const fetchPOST = async (custonBaseURL = base_url, custonApiPostObjeto = api_post, customPage = '') => {
            setIsLoading(true);
            // console.log('-------------------------');
            // console.log('fetchPost');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log('url :: ', url);
            const setData = formData;
            // console.log('setData :: ', setData);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('data :: ', data);
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('dbResponse :: ', dbResponse);
                    setIsLoading(false);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Falha ao enviar dados.'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        {/* FETCH GET */ }
        const fetchGET = async (custonBaseURL = base_url, custonApiGetObjeto = api_get, customPage = '?page =1&limit=90000') => {
            setIsLoading(true);
            setShowFilterSelect(false);
            // console.log('-------------------------');
            // console.log('fetchGet');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            const url = custonBaseURL + custonApiGetObjeto + customPage;
            // console.log('url :: ', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('attributeFieldValue :: ', attributeFieldValue);
                    // console.log('attributeFieldLabel :: ', attributeFieldLabel);
                    // console.log('dbResponse :: ', dbResponse);
                    await setMappedResponse(dbResponse);
                    setIsLoading(false);
                    removeFilter(formData[nameField]);
                    makeSelectedLabel();
                    return dbResponse;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Erro ao carregar Dados.'
                    });
                    setIsLoading(false);
                    setShowFilterSelect(false);
                    return false;
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Unidades: ' + error.message
                });
                return false;
            }
        };

        {/* FETCH FILTER */ }
        const fetchFilter = async (formFilter, custonBaseURL = base_url, custonApiPostObjeto = api_filter, customPage = '?page =1&limit=90000') => {
            setIsLoading(true);
            // console.log('-------------------------');
            // console.log('fetchFilter');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log('url :: ', url);
            const setData = formFilter;
            // console.log('setData :: ', setData);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('data :: ', data);
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('attributeFieldValue :: ', attributeFieldValue);
                    // console.log('attributeFieldLabel :: ', attributeFieldLabel);
                    // console.log('dbResponse :: ', dbResponse);
                    setMappedResponse(dbResponse);
                    setListSelect(dbResponse);
                    setIsLoading(false);
                    return dbResponse;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontrados dados.'
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

        const renderModalHelperSelect1 = () => {
            return (
                <div
                    className="modal fade"
                    id="staticHelpSelect1"
                    data-bs-backdrop="static"
                    data-bs-keyboard="false"
                    tabIndex={-1}
                    aria-labelledby="staticHelpSelect1Label"
                    aria-hidden="true"
                    style={{ zIndex: 1060 }} // Z-index mais alto para garantir que fique acima do backdrop
                >
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <div className="d-flex justify-content-between">
                                    <h5 className="modal-title">
                                        <i className="bi bi-question-circle"></i> Ajuda com Filtros
                                    </h5>
                                </div>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div className="modal-body">
                                <p className="mb-3">
                                    O campo de seleção possui funcionalidades para facilitar sua busca:
                                </p>
                                <ul className="mb-3">
                                    <li>
                                        <span className="fw-semibold text-primary">Não é necessário clicar no ícone da lupa</span> à direita do campo.<br />
                                        Basta começar a digitar e os resultados serão filtrados automaticamente de acordo com as letras ou palavras inseridas.
                                    </li>
                                    <li className="mt-2">
                                        <span className="fw-semibold text-success">Ao digitar</span>, o campo <span className="text-decoration-underline">carrega</span> uma nova lista de opções, mostrando apenas os itens que correspondem ao que você digitou.
                                    </li>
                                    <li className="mt-2">
                                        <span className="fw-semibold text-secondary">O ícone de texto à esquerda</span> serve para <span className="text-decoration-underline">limpar o filtro</span>.<br />
                                        Clique nele para restaurar o select ao seu estado inicial e ver todas as opções novamente.
                                    </li>
                                </ul>
                                <div className="alert alert-info p-2 mb-0">
                                    <span className="fw-semibold">Dica:</span> Use palavras-chave para encontrar rapidamente o que procura!
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            );
        };

        const renderModalHelperSelect2 = () => {
            return (
                <div
                    className="modal fade"
                    id="staticHelpSelect2"
                    data-bs-backdrop="static"
                    data-bs-keyboard="false"
                    tabIndex={-1}
                    aria-labelledby="staticHelpSelect2Label"
                    aria-hidden="true"
                    style={{ zIndex: 1060 }} // Z-index mais alto para garantir que fique acima do backdrop
                >
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <div className="d-flex justify-content-between">
                                    <h5 className="modal-title">
                                        <i className="bi bi-question-circle"></i> Ajuda com Adição de Outros
                                    </h5>
                                </div>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div className="modal-body">
                                <p className="mb-3">
                                    O campo de seleção possui funcionalidades para facilitar sua Adição de Outros:
                                </p>
                                <ul className="mb-3">
                                    <li>
                                        <span className="fw-semibold text-primary">Não é necessário clicar no ícone da Salvar</span> à direita do campo.<br />
                                        Basta começar a digitar e sua adição será automaticamente informada ao formulário.
                                    </li>
                                    <li className="mt-2">
                                        <span className="fw-semibold text-success">O ícone de texto à esquerda</span> serve para <span className="text-decoration-underline">Sair do campo</span>.<br />
                                        Clique nele para restaurar o select ao seu estado inicial e ver todas as opções novamente.
                                    </li>
                                </ul>
                                <div className="alert alert-info p-2 mb-0">
                                    <span className="fw-semibold">Dica:</span> Use somente palavras que tenham significado ao seu contexto!
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            );
        };

        const loadData = async () => {
            try {
                await fetchGET();
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
            } finally {
                setTimeout(() => {
                    // console.log('-------------------------');
                    // console.log('finally');
                    setIsLoading(false);
                }, 300);
            }
        };

        {/* REACT PRINCIPAL */ }
        React.useEffect(() => {
            // console.log('-------------------------');
            // console.log('useEffect');
            // console.log('-------------------------');
            // console.log('src/ app/ Views/ fia/ ptpa/ camposPadroes/ AppSelectCheck.php');
            // console.log('attributeFieldLabel :: ', attributeFieldLabel);
            // console.log('attributeFieldValue :: ', attributeFieldValue);
            if (
                api_get === 'api/get' &&
                api_post === 'api/post' &&
                api_filter === 'api/filter'
            ) {
                setListSelect(objetoArrayKey);
                setIsLoading(false);
                setShowFilterSelect(false);
                return;
            }

            loadData();

        }, []);

        {/* REACT - LOADING */ }
        React.useEffect(() => {
            let animationFrame;
            const startAnimation = () => {
                const startTime = performance.now();
                const animate = (time) => {
                    const elapsed = time - startTime;
                    const newProgress = Math.min((elapsed / 10000) * 100, 100); // 10 segundos totais
                    setProgress(newProgress);
                    if (newProgress < 100) {
                        animationFrame = requestAnimationFrame(animate);
                    } else {
                        setProgress(0); // Reinicia o ciclo
                        startAnimation(); // Chama novamente a função para reiniciar
                    }
                };
                animationFrame = requestAnimationFrame(animate);
            };
            startAnimation();
            return () => cancelAnimationFrame(animationFrame); // Cleanup da animação
        }, []);

        {/* REACT formData[nameField], listSelect */ }
        React.useEffect(() => {
            // console.log('-------------------------');
            // console.log('useEffect');
            // console.log('listSelect :: ', listSelect);
            if (listSelect && listSelect.length > 0) {
                if (listSelect && listSelect.length > 0) {
                    const values = stringToArray(formData[nameField] || '');
                    setSelectedLabel(getSelectedLabelText(values));
                }
            }
        }, [formData[nameField], listSelect]);

        {/* REACT 004 */ }
        React.useEffect(() => {
            if (formData[nameField]) {
                setSelectedIds(formData[nameField].split(',').map(id => id.trim()));
            } else {
                setSelectedIds([]);
            }
            // Só rode na montagem e se trocar o campo
            // eslint-disable-next-line react-hooks/exhaustive-deps
        }, [nameField]);

        {/* REACT 005 */ }
        React.useEffect(() => {
            setFormData(prev => ({
                ...prev,
                [nameField]: selectedIds.join(','),
            }));
            // eslint-disable-next-line react-hooks/exhaustive-deps
        }, [selectedIds, nameField]);

        return (
            <div className={`b-0 p-0`}>
                {/* label */}
                {/* primary */}
                {/* secondary */}
                {/* success */}
                {/* info */}
                {/* warning */}
                {/* danger */}
                {/* light */}
                {/* dark */}
                {/* Modal renderizado no final do componente */}
                {renderModalHelperSelect1()}
                {renderModalHelperSelect2()}
                {/* btn-group */}
                <div className="btn-group w-100 b-0 p-0">
                    <button
                        className={`btn btn${btnOutline ? '-outline' : ''}-${btnCollor} ${btnSize ? `btn-${btnSize}` : ''} dropdown text-start ${btnRounded ? `rounded-${btnRounded}` : ''} b-0 p-0`}
                        type="button"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                    >
                        <div className="d-flex justify-content-between b-0 p-0">
                            <div className="ms-2">
                                {selectedLabel}
                            </div>
                            <div>
                                {(attributeRequired) ? (
                                    <i class={`bi bi-exclamation-circle text-danger me-2`} style={{ fontSize: "1.18rem" }}></i>
                                ) : (
                                    <i class="bi bi-check-lg text-success me-2" style={{ fontSize: "1.18rem" }}></i>
                                )}
                                <i className="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </button>
                    <div className={`dropdown-menu w-100 p-1`}>
                        {(showFilterSelect) && (
                            <div className="input-group mb-1">
                                <span
                                    className="input-group-text"
                                    onClick={() => fetchGET()}
                                >
                                    <i className="bi bi-file-text"></i>
                                </span>
                                <input
                                    type="text"
                                    className="form-control form-control-sm"
                                    name={`filtroSelect`}
                                    id={`filtroSelect`}
                                    value={formData.filtroSelect || applyFilters.filtroSelect || ''}
                                    onFocus={handleFocus}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                    placeholder="Digite para buscar"
                                    aria-label="Amount (to the nearest search)"
                                />
                                <span
                                    className="input-group-text"
                                    data-bs-toggle="modal" data-bs-target="#staticHelpSelect1"
                                >
                                    <i className="bi bi-search"></i>
                                </span>
                            </div>
                        )}

                        {(isLoading) && (
                            <div className="mt-2 mb-2">{renderLoading()}</div>
                        )}

                        <div className="overflow-scroll">
                            <div style={{ maxHeight: "300px", overflowX: "hidden" }}>
                                {(listSelect && !isLoading) && (
                                    listSelect.map((item, index) => (
                                        <div key={index}>
                                            <input
                                                type="checkbox"
                                                className="btn-check"
                                                name={nameField}
                                                id={`${nameField}${index}`}
                                                value={item.id}
                                                onFocus={handleFocus}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                autoComplete="off"
                                                checked={selectedIds.includes(item.id.toString())}
                                            />
                                            <label
                                                className="btn w-100 text-start"
                                                htmlFor={`${nameField}${index}`}
                                            >
                                                {item.value}
                                            </label>
                                        </div>
                                    ))
                                )}

                                {(!choice && !isLoading) && (
                                    <button
                                        type="button"
                                        className="btn w-100 text-start"
                                        onClick={() => handleOther(true)}
                                    >
                                        {other}
                                    </button>
                                )}
                            </div>
                        </div>

                        {(choice) && (
                            <div className="input-group">
                                <span
                                    className="input-group-text"
                                    onClick={() => handleOther(false)}
                                >
                                    <i className="bi bi-file-text"></i>
                                </span>
                                {/* TEXT OUTRO*/}
                                <input
                                    type="text"
                                    className="form-control form-control-sm"
                                    name={`${nameField}`}
                                    id={`${nameField}`}
                                    value={formData[nameField] || ''}
                                    onFocus={handleFocus}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                    aria-label="Amount (to the floppy)"
                                />
                                <span
                                    className="input-group-text"
                                    data-bs-toggle="modal" data-bs-target="#staticHelpSelect2"
                                >
                                    <i className="bi bi-floppy"></i>
                                </span>
                            </div>
                        )}
                    </div>
                </div>
                {/* btn-group */}

            </div>
        );
    };
    // const rootElement = document.querySelector('.app_select_radio');
    // const root = ReactDOM.createRoot(rootElement);
    // root.render(<AppSelectCheck />);
    // ReactDOM.render(<AppSelectCheck />, document.querySelector('.app_select_radio'));
</script>