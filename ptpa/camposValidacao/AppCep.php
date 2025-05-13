<script type="text/babel">
    const AppCep = (
        {
            formData = {},
            setFormData = {},
            parametros = {}
        }
    ) => {

        // BASE 
        const getURI = parametros.getURI || [];
        const origemForm = parametros.origemForm || '';
        const viacep = 'https://viacep.com.br/ws/';
        const opencep = 'https://opencep.com/v1/';

        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // UTIL 
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Função para adicionar a máscara de CEP
        const applyMaskCEP = (cep) => {
            cep = cep.replace(/\D/g, '');
            cep = cep.replace(/^(\d{5})(\d)/, '$1-$2');
            return cep;
        };

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            const formattedValue = applyMaskCEP(value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange para garantir que o modal não seja exibido ao mudar o valor
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            const formattedValue = applyMaskCEP(value);

            setFormData((prev) => ({
                ...prev,
                [name]: formattedValue
            }));
        };

        // Função handleBlur para garantir que o modal não seja exibido ao sair do campo
        const handleBlur = (event) => {
            const { name, value } = event.target;

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            if (name === 'CEP') {
                // Se o campo estiver vazio, não faz nada
                if (value.trim() === '') {
                    return true;
                }
                const formattedValue = value.replace(/\D/g, '');

                // Verifica se o CEP pertence ao estado do Rio de Janeiro
                if (/^2[0-8][0-9]{3}[0-9]{3}$/.test(formattedValue)) {
                    fetchViaCep(formattedValue);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'O CEP informado não pertence ao estado do Rio de Janeiro.',
                    });
                    console.error('O CEP informado não pertence ao estado do Rio de Janeiro.');
                }
            }
        };

        // Função para buscar dados do ViaCEP
        const fetchViaCep = async (setCep) => {
            const url = `${viacep}/${setCep}/json`;
            try {

                const response = await fetch(url);

                if (response.status === 200) {
                    const data = await response.json();
                    console.log('ViaCEP Data:', data);

                    if (checkWordInArray(getURI, 'unidade')) {
                        // Construindo o endereço completo
                        const endereco = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                        console.log('Endereço Completo:', endereco);
                        setFormData((prev) => ({
                            ...prev,
                            unidades_cep: data.cep,
                            unidades_endereco: endereco
                        }));
                    }

                    if (checkWordInArray(getURI, 'adolescente')) {

                        const endereco = `${data.logradouro}, ${data.bairro}`;

                        setFormData((prev) => ({
                            ...prev,
                            CEP: data.cep,
                            Endereco: endereco,
                            Municipio: data.localidade
                        }));
                    }

                    // Salvando na variável interna
                } else if (response.status === 400) {
                    console.error('ViaCEP Error 400: Bad Request');
                    setFormData((prev) => ({
                        ...prev,
                        unidades_endereco: ''
                    }));
                } else {
                    console.error(`ViaCEP Error ${response.status}: ${response.statusText}`);
                    setFormData((prev) => ({
                        ...prev,
                        unidades_endereco: ''
                    }));
                }
                return true;
            } catch (error) {
                console.error('Error fetching ViaCEP data:', error);
                setFormData((prev) => ({
                    ...prev,
                    unidades_endereco: ''
                }));
                return false;
            }
        };

        // Função para buscar dados do OpenCEP
        const fetchOpenCep = async (set_cep) => {
            const url = `${$opencep}/${set_cep}`;
            try {
                const response = await fetch(opencep);
                if (!response.ok) {
                    throw new Error(`OpenCEP fetch failed: ${response.statusText}`);
                }
                const data = await response.json();
                console.log('OpenCEP Data:', data);
            } catch (error) {
                console.error('Error fetching OpenCEP data:', error);
            }
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

        const requiredField = {
            color: '#FF0000',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };
        const renderCEPAppend = () => {
            return (
                <div>
                    <div className="btn-group">
                        <button
                            className="btn btn-sm"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <div className="d-flex justify-content-end">
                                CEP
                                <i className="bi bi-search ms-1"></i>
                            </div>
                        </button>
                        <div className="dropdown-menu p-2">
                            <div className="d-flex justify-content-between">
                                {checkWordInArray(getURI, 'consultar') ? (
                                    <div className="p-2">
                                        {formData.CEP}
                                    </div>
                                ) : (
                                    <input
                                        data-api={`filtro-${origemForm}`}
                                        type="text"
                                        id="CEP"
                                        name="CEP"
                                        value={formData.CEP || ''}
                                        maxLength="10"
                                        onFocus={handleFocus}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        style={formControlStyle}
                                        className="form-control form-control-sm"
                                        disabled={checkWordInArray(getURI, 'alocar') ? true : false}
                                    />
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            );
        };

        const renderCEPStyle = () => {
            return (
                <div>
                    <div style={formGroupStyle}>
                        <label
                            htmlFor="CEP"
                            style={formLabelStyle}
                            className="form-label"
                        >
                            CEP
                            {checkWordInArray(getURI, 'consultar') ? null : <strong style={requiredField}>*</strong>}
                        </label>
                        <div className="d-flex justify-content-between">
                            <div className='w-100'>
                                {checkWordInArray(getURI, 'consultar') ? (
                                    <div className='p-2'>
                                        {formData.CEP ? (
                                            <div>
                                                {formData.CEP}
                                            </div>
                                        ) : (
                                            <div className='text-muted'>
                                                ...
                                            </div>
                                        )}
                                    </div>
                                ) : (
                                    <input data-api={`filtro-${origemForm}`}
                                        type="text"
                                        id="CEP"
                                        name="CEP"
                                        value={formData.CEP || ''}
                                        onFocus={handleFocus}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        style={formControlStyle}
                                        className="form-control form-control-sm"
                                        required
                                    />
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            );
        };

        return (
            <div>
                {checkWordInArray(getURI, 'unidade') ? renderCEPAppend() : renderCEPStyle()}

                <AppMessageCard parametros={message} modalId="modal_cep" />
            </div>
        );
    };
</script>
<div>

</div>