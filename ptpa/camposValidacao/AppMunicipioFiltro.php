<script type="text/babel">
    const AppMunicipioFiltro = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const origemForm = parametros.origemForm || '';

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Estado para mensagens e validação
        // const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [uuidMessage, setUuidMessage] = React.useState(0);
        const generateUniqueId = () => `modal_${Date.now()}_${Math.random().toString(36).substring(2, 8)}`;
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Validação de municipio_nome: Mais de 4 letras e apenas letras
        const isValidNomeUnidade = (municipio_nome) => {
            // Verifica se o nome tem mais de 4 letras
            if (municipio_nome.length < 4 || municipio_nome === '') {
                return false;
            }
            // Verifica se o nome contém apenas letras (A-Z, a-z) e espaços, sem números ou caracteres especiais
            const regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
            return regex.test(municipio_nome);
        };

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            setMessage({ show: false, type: null, message: null });

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange atualizada para impedir números e caracteres especiais
        const handleChange = (event) => {
            const { name, value } = event.target;

            if (!isNaN(parseInt(value.slice(-1))) && message.show === false) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O campo Nome não pode receber números.',
                    id: generateUniqueId(),
                });

                setFormData((prev) => ({
                    ...prev,
                    [name]: ''
                }));
            }

            const filteredValue = value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s]/g, '')
                .replace(/\s{2,}/g, ' ');
            setFormData((prev) => ({
                ...prev,
                [name]: filteredValue
            }));
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value.trim(),
            }));
            
        };
        const colorFiltro = checkWordInArray(getURI, 'exibir') ? 'gray' : 'black';

        React.useEffect(() => {
            console.log('uuidMessage atualizado:', uuidMessage);
        }, [uuidMessage]);

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
            color: colorFiltro,
        };

        const requiredField = {
            color: '#FF0000',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                <div style={formGroupStyle}>
                    {checkWordInArray(getURI, 'exibir') ? (
                        <label
                            htmlFor="municipio_nome"
                            style={formLabelStyle} className="form-label">Municipio
                        </label>

                    ) : (
                        <label
                            htmlFor="municipio_nome"
                            style={formLabelStyle} className="form-label">Municipio
                            {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                        </label>
                    )}
                    {checkWordInArray(getURI, 'consultar') ? (
                        <div className='p-2'>
                            {formData.municipio_nome}
                        </div>
                    ) : (
                        <input data-api={`filtro-${origemForm}`}
                            type="text"
                            id="municipio_nome"
                            name="municipio_nome"
                            value={formData.municipio_nome || ''}
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                        />
                    )}
                </div>

                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}

                <AppMessageCard
                    parametros={message}
                    modalId="modal_nome_municipio_filtro"
                />
            </div>
        );
    };
</script>