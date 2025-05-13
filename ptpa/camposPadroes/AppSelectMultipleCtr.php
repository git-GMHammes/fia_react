<script type="text/babel">
    const AppSelectMultipleCtr = (
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
        const [selectedIds, setSelectedIds] = React.useState([]);
        const debounceTimeout = React.useRef(null);

        // Field Attributes src\app\Views\fia\ptpa\camposPadroes\AppSelectMultipleCtr.php
        const attributeOrigemForm = fieldAttributes.origemForm || '';
        const base_url = parametros.base_url || '';
        const labelField = fieldAttributes.labelField || 'AppTextLabel';
        const nameField = fieldAttributes.nameField || 'AppTextName';
        const errorMessage = fieldAttributes.errorMessage || '';

        const attributeFieldKey = fieldAttributes.attributeFieldKey || [];
        const attributeFieldName = fieldAttributes.attributeFieldName || [];
        const attributeRequired = fieldAttributes.attributeRequired || false;
        const attributeDisabled = fieldAttributes.attributeDisabled || false;
        // console.log('attributeRequired: ', nameField, attributeRequired);

        // Attributes of APIs
        const api_get = fieldAttributes.api_get || 'api/get';
        const api_post = fieldAttributes.api_post || 'api/post';
        const api_filter = fieldAttributes.api_filter || 'api/filter';
        const api_add = fieldAttributes.api_add || 'api/add';
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
            const { name, value, type, checked } = event.target;

            // Para checkboxes de seleção
            if (type === 'checkbox') {
                const itemId = event.target.getAttribute('data-id');
                setSelectedIds(prev => {
                    if (checked) {
                        return [...prev, itemId];
                    } else {
                        return prev.filter(id => id !== itemId);
                    }
                });
                return;
            }

            // Para o campo de filtro
            if (name === 'filtroSelect') {
                // console.log('handleChange - filtroSelect:', value);

                if (value.length <= 1) {
                    setObjetoMapKey(objetoArrayKey);
                    setSetFilter((prev) => ({
                        ...prev,
                        filtroSelect: null,
                    }));
                    if (debounceTimeout.current) {
                        clearTimeout(debounceTimeout.current);
                    }
                    fetchPost();
                    return;
                }

                setSetFilter((prev) => ({
                    ...prev,
                    filtroSelect: value,
                }));

                setFormData((prev) => ({
                    ...prev,
                    [name]: value,
                }));

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
            // console.log('---------------------------------');
            // console.log('C:/laragon/www/fiaptpa/src/app/Views/fia/ptpa/camposPadroes/AppSelectMultipleCtr.php');
            // console.log('fetchPost... ');
            if (custonApiPostObjeto === 'api/post') {
                return false;
            } else {
                setObjetoMapKey([{ key: '', value: 'carregando...' }]);
            }
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log('fetchPost - url: ', url);
            const SetData = { setFormData };
            //
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
                // 
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('fetchPost - dbResponse: ', dbResponse);
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
                        message: 'Não foram encontradas unidades cadastradas'
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
            if (custonApiPostObjeto === 'api/filter') {
                return false;
            } else {
                setObjetoMapKey([{ key: '', value: 'carregando...' }]);
            }
            let message = errorMessage === '' ? `Não foram encontrados(as) ${labelField} cadastrados(as)` : errorMessage;
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            // console.log("url :: ", url);
            const setData = setFilter;
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

        // fetcAdd
        const fetchAdd = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_add, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;

            let setData = {};

            if (custonApiPostObjeto.toLowerCase().includes('genero')) {
                setData = { genero: formData.campoAdd };
                // console.log('Cadastrando em tabela de gênero:', setData);
            }
            else if (custonApiPostObjeto.toLowerCase().includes('perfil')) {
                setData = { perfil: formData.campoAdd };
                // console.log('Cadastrando em tabela de perfil:', setData);
            }
            else {
                setData = formData;
                // console.log("Não foi cadastrada uma tabela relacionada a esse endpoint utilizado:", custonApiPostObjeto);
                return false;
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('fetchAdd - data: ', data);

                if (data.result && data.result.dbResponse && data.result.dbResponse.affectedRows > 0) {
                    await fetchPost();
                    // console.log('fetchAdd - data: ', data.result.dbResponse);
                    return true;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Erro ao realizar o cadastro'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        // Adicione este useEffect para processar valores iniciais do formData
        React.useEffect(() => {
            // Verifica se existe valor inicial no formData para este campo
            if (formData[nameField] && typeof formData[nameField] === 'string') {
                // Converte a string separada por vírgulas em array de IDs
                const initialIds = formData[nameField].split(',').map(id => id.trim());

                // Filtra IDs vazios e atualiza o estado de seleção
                const validIds = initialIds.filter(id => id !== '');

                if (validIds.length > 0) {
                    setSelectedIds(validIds);

                    // Atualiza também o label inicial baseado nos IDs
                    if (validIds.length === 1) {
                        // Encontra o item correspondente ao ID
                        const selectedItem = objetoMapKey.find(item => item.key === validIds[0]);
                        if (selectedItem) {
                            setSelectedLabel(selectedItem.value);
                        }
                    } else if (validIds.length > 1) {
                        setSelectedLabel(`${validIds.length} opções selecionadas`);
                    }
                }
            }
        }, [formData[nameField], objetoMapKey, nameField]);

        // Novo efeito para atualizar o label quando selectedIds muda
        React.useEffect(() => {
            if (selectedIds.length === 0) {
                setSelectedLabel('Selecione uma opção');
            } else if (selectedIds.length === 1) {
                const selectedItem = objetoMapKey.find(item => item.key === selectedIds[0]);
                setSelectedLabel(selectedItem ? selectedItem.value : 'Selecione uma opção');
            } else {
                setSelectedLabel(`${selectedIds.length} opções selecionadas`);
            }

            // Atualizar formData quando selectedIds mudar
            setFormData(prev => ({
                ...prev,
                [nameField]: selectedIds.join(', ')
            }));
        }, [selectedIds, objetoMapKey, nameField, setFormData]);

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

        // Renderização MODIFICADA para usar checkboxes
        return (
            <div>
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
                            className="btn btn-sm dropdown text-start border border-0"
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
                                    {(attributeRequired) && (
                                        <i className="bi bi-exclamation-circle text-danger"></i>
                                    )}
                                </div>
                                <div className="p-1 bd-highlight">
                                    <i className="bi bi-caret-down-fill ps-1" />
                                </div>
                            </div>
                        </button>
                        <div className="dropdown-menu w-100">
                            {(
                                api_get !== 'api/get' &&
                                api_post !== 'api/post' &&
                                api_filter !== 'api/filter'
                            ) && (
                                    <div className="m-1 p-1 border border rounded">
                                        <input
                                            type="text"
                                            data-api={`filtro-buscarselect`}
                                            className="form-control form-control-sm border border-1 border-info"
                                            style={formControlStyle}
                                            id={`filtroSelect`}
                                            name={`filtroSelect`}
                                            placeholder="Digite para filtrar..."
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                            required={attributeRequired}
                                            disabled={attributeDisabled}
                                        />
                                    </div>
                                )}
                            <div className="m-1 p-2 w-auto border rounded" style={{ maxHeight: '300px', overflowY: 'auto' }}>
                                <div className="d-flex flex-column w-100">
                                    {objetoMapKey.map((item, index) => (
                                        <div key={`${item.key || ''}-${index}`} className="mb-2 w-100">
                                            <input
                                                type="checkbox"
                                                className="btn-check"
                                                id={`btn-check-${nameField}-${item.key || index}`}
                                                name={nameField}
                                                value={item.key}
                                                data-id={item.key}
                                                checked={selectedIds.includes(item.key)}
                                                onChange={handleChange}
                                                disabled={attributeDisabled}
                                                autoComplete="off"
                                            />
                                            <label
                                                className={`btn w-100 ${selectedIds.includes(item.key) ? 'btn-primary' : 'btn-outline-primary'} text-start`}
                                                htmlFor={`btn-check-${nameField}-${item.key || index}`}
                                                title={item.value}
                                            >
                                                {item.value}
                                            </label>
                                        </div>
                                    ))}
                                    {/* Campo para adicionar novo item */}
                                    <div className="mt-1 ms-0 me-0 mb-0">
                                        <div className="input-group">
                                            <button
                                                className="btn btn-sm btn-success"
                                                type="button"
                                                onClick={() => {
                                                    if (formData.campoAdd && formData.campoAdd.trim() !== '') {
                                                        fetchAdd(formData);
                                                        // Limpar o campo após adicionar
                                                        setFormData((prev) => ({
                                                            ...prev,
                                                            campoAdd: ''
                                                        }));
                                                    }
                                                }}
                                            >
                                                <i className="bi bi-plus-lg"></i>
                                            </button>
                                            <input
                                                type="text"
                                                className="form-control form-control-sm border border-1 border-success"
                                                placeholder="Novo"
                                                id="campoAdd"
                                                name="campoAdd"
                                                onChange={(e) => {
                                                    setFormData((prev) => ({
                                                        ...prev,
                                                        campoAdd: e.target.value
                                                    }));
                                                }}
                                                value={formData.campoAdd || ''}
                                            />
                                            <button
                                                className="btn btn-sm btn-success"
                                                type="button"
                                                onClick={() => {
                                                    if (formData.campoAdd && formData.campoAdd.trim() !== '') {
                                                        fetchAdd(formData);
                                                        // Limpar o campo após adicionar
                                                        setFormData((prev) => ({
                                                            ...prev,
                                                            campoAdd: ''
                                                        }));
                                                    }
                                                }}
                                            >
                                                <i className="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {objetoMapKey.length === 0 && (
                                    <div className="text-center text-muted">Nenhuma opção disponível</div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
                {/* Mensagem do AppSelectMultipleCtr */}
                <AppMessageCard
                    parametros={message}
                    modalId="modal_listar_conteudo_adolescente"
                />
            </div>
        );
    };
</script>