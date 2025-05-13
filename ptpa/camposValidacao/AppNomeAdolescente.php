<script type="text/babel">
    const AppNomeAdolescente = (
        {
            parametros = {},
            formData = {},
            setFormData = () => { },
        }
    ) => {
        // Variáveis recebidas do Backend
        const base_url = parametros.base_url || 'erro';
        const api_get_adolescente = parametros.api_get_adolescente || 'erro';
        const getVar_page = parametros.getVar_page || 'erro';
        const origemForm = parametros.origemForm || 'erro';

        // Variáveis de estado
        const [adolescentes, setAdolescentes] = React.useState([]);
        const [isLoading, setIsLoading] = React.useState(true);
        const debounceTimeout = React.useRef(null);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleFocus para receber foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('handleChange: ', name);
            console.log('handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança é no campo 'adolescente_nome'
            if (name === 'adolescente_nome') {
                console.log('adolescente_nome :: ', value);
                const [id, nome] = value.split('/');
                setFormData((prev) => ({
                    ...prev,
                    adolescente_id: id,
                    adolescente_nome: nome
                }));
            }

            if (name === 'pesquisa_nome_ado') {
                // Clear previous timeout
                setFormData((prev) => ({
                    ...prev,
                    adolescente_nome: pesquisa_nome_ado
                }));

                if (debounceTimeout.current) {
                    clearTimeout(debounceTimeout.current);
                }

                // Set new timeout
                debounceTimeout.current = setTimeout(() => {
                    console.log('fetchFiltrarAdolscente');
                    fetchFiltrarAdolescente();
                }, 100);
            }

            setMessage({ show: false, type: null, message: null });
        };

        // Função handleBlur para perder foco
        const handleBlur = (event) => {
            const { name, value } = event.target;

            console.log('handleBlur: ', name);
            console.log('handleBlur: ', value);

            fetchAdolescente();

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // POST fetchAdolescente 
        const fetchAdolescente = async (custonBaseURL = base_url, custonApiPostAdolescente = api_get_adolescente, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostAdolescente + customPage + "&limit=90000";
            console.log('fetchAdolescente :: ', url);
            const setData = { 'PerfilId': '2'};
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('data(fetchAdolescente) :: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // 
                    setAdolescentes(dbResponse);
                    //
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontrados Adolescentes cadastrados'
                    });
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // POST pesquisa Adolescentes
        const fetchFiltrarAdolescente = async (custonBaseURL = base_url, custonApiPostObjeto = api_get_adolescente, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            const setData = { 'Nome': formData.pesquisa_nome_ado };

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                    // body: '{"Nome": "Naruto"}',
                });
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // 
                    setAdolescentes(dbResponse);
                    //
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas unidades cadastradas'
                    });
                    setDataLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    await fetchAdolescente();

                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    console.log('loadData finalizado...');
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Styles
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

        return (
            <div>
                <div style={formGroupStyle}>
                    <label
                        htmlFor="adolescente_id"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Adolescente<strong style={requiredField}>*</strong>
                    </label>
                    {(isLoading) ? (
                        <div>
                            <div>&nbsp;</div>
                            <AppLoading
                                parametros={{ tipoLoading: 'progress', carregando: true }}
                            />
                        </div>
                    ) : (
                        <div className="btn-group w-100">
                            <button
                                className="btn btn-sm d-flex justify-content-between align-items-center w-100"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <span>
                                    {typeof formData.adolescente_nome === 'string' ? formData.adolescente_nome : 'Seleção Nula'}
                                </span>
                                <i className="bi bi-chevron-down"></i>
                            </button>
                            <div className="dropdown-menu w-100 overflow-auto" style={{ height: '380px' }}>
                                <input
                                    data-api={`filtro-filtro-adolescente`}
                                    type="text"
                                    id="pesquisa_nome_ado"
                                    name="pesquisa_nome_ado"
                                    value={formData.pesquisa_nome_ado || ''}
                                    onFocus={handleFocus}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                    className="form-control"
                                    style={formControlStyle}
                                />
                                <input
                                    data-api={`filtro-${origemForm}`}
                                    type="hidden"
                                    id="adolescente_id"
                                    name="adolescente_id"
                                    value={formData.adolescente_id || ''}
                                    onFocus={handleFocus}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                />
                                {adolescentes.map((adolescente_select) => (
                                    <div key={adolescente_select.id}>
                                        <input
                                            data-api={`filtro-${origemForm}`}
                                            type="radio"
                                            style={formControlStyle}
                                            className="btn-check"
                                            name="adolescente_nome"
                                            id={`adolescente-${adolescente_select.id}`}
                                            value={`${adolescente_select.id}/${adolescente_select.Nome}`}
                                            onFocus={handleFocus}
                                            onChange={handleChange}
                                            onBlur={handleBlur}
                                            autoComplete="off"
                                        />
                                        <label
                                            className="btn btn-outline w-100 pe-3 m-1 text-start"
                                            htmlFor={`adolescente-${adolescente_select.id}`}
                                        >
                                            {adolescente_select.Nome}
                                        </label>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        );
    };

</script>