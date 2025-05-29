<script type="text/babel">
    const AppEmail = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Variáveis recebidas do backend
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const [lastInteraction, setLastInteraction] = React.useState(null);

        React.useEffect(() => {
            const handleKeyDown = () => setLastInteraction('keyboard');
            const handleMouseDown = () => setLastInteraction('mouse');

            window.addEventListener('keydown', handleKeyDown);
            window.addEventListener('mousedown', handleMouseDown);

            return () => {
                window.removeEventListener('keydown', handleKeyDown);
                window.removeEventListener('mousedown', handleMouseDown);
            };
        }, []);

        // Estado para mensagens e validação
        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        const handleKeyDown = (event) => {
            if (event.key === 'Tab') {
                event.preventDefault(); // Impede a navegação por TAB neste campo
                console.log('TAB desativado neste campo.');
            }
        };

        const handleFocus = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });

            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange para atualização dos campos
        const handleChange = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleBlur para validação ao perder o foco
        const handleBlur = (event) => {
            const { name, value } = event.target;

            console.log('handleBlur: ', name);
            console.log('handleBlur: ', value);
            if (name === 'Email') {
                if (
                    value.length > 0 &&
                    !isValidEmail(value)
                ) {
                    if (lastInteraction === 'mouse') {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'Email inválido. Por favor, insira um Email válido.'
                        });
                    }

                    // Limpa o campo se o Email for inválido
                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                    console.log('Email Inválido: campo limpo');
                } else {
                    console.log('Email OK');
                }
            }
        };

        // Validação de E-mail
        const isValidEmail = (Email) => {
            if (Email.length > 0) {
                const regexEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,6}$/;
                return regexEmail.test(Email);
            }
        };

        // Estilos de formatação
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

        return (
            <div>
                <div style={formGroupStyle}>
                    <label
                        htmlFor="Email"
                        style={formLabelStyle}
                        className="form-label">E-mail
                        {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                    </label>
                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                        <div className='p-2'>
                            {formData.Email}
                        </div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            type="email"
                            id="Email"
                            name="Email"
                            value={formData.Email || ''}
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            onKeyDown={handleKeyDown}
                            className="form-control form-control-sm"
                            disabled={(checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')) ? true : false}
                            required
                        />
                    )}
                </div>

                {/* message.show && (
                <span style={/*{ color: 'red', fontSize: '12px' }}>
                    {message.message}
                </span>
            )*/}

                {/* Exibe o componente de alerta */}
                <AppMessageCard parametros={message} modalId="modal_email" />
            </div>
        );
    };

</script>