<script type="text/babel">
    const AppRG = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';
        const base_url = parametros.base_url || '';
        const getURI = parametros.getURI || [];

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        const [lastInteraction, setLastInteraction] = React.useState(null);

        // Adiciona listeners para detectar a última interação
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


        // Definindo mensagens do Sistema
        const [tabNav, setTabNav] = React.useState('form');
        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        const applyRgMask = (value) => {
            // Remove todos os caracteres que não são dígitos
            const numericValue = value.replace(/\D/g, "");

            // Aplica o padrão de RG: 12.345.678-9
            return numericValue
                .replace(/^(\d{2})(\d)/, "$1.$2")
                .replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
                .replace(/^(\d{2})\.(\d{3})\.(\d{3})(\d)/, "$1.$2.$3-$4");
        };

        const handleKeyDown = (event) => {
            if (event.key === 'Tab') {
                event.preventDefault(); // Impede a navegação por TAB neste campo
                console.log('TAB desativado neste campo.');
            }
        };

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });

            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            console.log('handleFocus/message.show: ', message.show);
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            const maskedValue = applyRgMask(value);
            // Remove caracteres inválidos conforme a validação de RG

            //console.log('name handleChange (RG): ', name);
            //console.log('value handleChange (RG): ', applyRgMask(value));

            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue
            }));
        };

        // Função handleBlur para limpar o campo RG se for inválido
        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Verifica se é o campo RG e faz a validação
            if (name === 'RG') {
                if (!isValidRG(value)) {
                    if (lastInteraction === 'mouse') {
                        // Função para exibir o alerta (success, danger, warning, info)
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'RG inválido ou ausente.'
                        });
                    }
                    // Limpa o campo se o RG for inválido
                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                    console.log('RG Inválido: campo limpo');
                } else {
                    console.log('RG OK');
                }
            }
        };

        // Validação de RG: aceita apenas o formato 12.345.678-9
        const isValidRG = (rg) => {
            const rgRegex = /^\d{2}\.\d{3}\.\d{3}-\d{1}$/; // Valida o formato do RG
            return rgRegex.test(rg);
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

        return (
            <div>
                <div style={formGroupStyle}>
                    <label
                        htmlFor="RG"
                        style={formLabelStyle}
                        className="form-label">RG
                        {!(checkWordInArray(getURI, 'consultar')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {(checkWordInArray(getURI, 'consultar')) ? (
                        <div className='p-2'>
                            {formData.RG}
                        </div>
                    ) : (
                        <input data-api={`filtro-${origemForm}`}
                            type="text"
                            className="form-control form-control-sm"
                            style={formControlStyle}
                            id="RG"
                            name="RG"
                            value={formData.RG || ''}
                            onChange={handleChange}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                            onKeyDown={handleKeyDown}
                            maxLength="12"
                        />
                    )}
                </div>
                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}
                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message}
                    modalId="modal_rg"
                />

            </div>
        );
    };
</script>