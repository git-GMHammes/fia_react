<script type="text/babel">
    const AppResponsavelCPF = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

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

        // Função para adicionar a máscara de CPF
        const applyMaskCPF = (Responsavel_CPF) => {
            let cpf = Responsavel_CPF.replace(/\D/g, '');
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            return cpf;
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

            // Aplica a máscara se for o campo CPF
            let maskedValue = value;
            if (name === 'Responsavel_CPF') {
                maskedValue = applyMaskCPF(value);
            }

            console.log('name handleChange (Responsavel_CPF): ', name);
            console.log('value handleChange (Responsavel_CPF): ', maskedValue);

            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue
            }));
        };

        // Função handleBlur para limpar o campo CPF se for inválido
        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Verifica se é o campo CPF e faz a validação
            if (name === 'Responsavel_CPF') {
                if (!isValidCPF(value)) {
                    if (lastInteraction === 'mouse') {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'CPF inválido ou já cadastrado no sistema.'
                        });
                    }

                    // Limpa o campo se o CPF for inválido
                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                    console.log('CPF Inválido: campo limpo');
                } else {
                    console.log('CPF OK');
                }
            }
        };

        // Validação de CPF
        const isValidCPF = (Responsavel_CPF) => {
            // Remove caracteres não numéricos
            let cpf = Responsavel_CPF.replace(/[^\d]+/g, '');

            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

            let soma = 0, resto;
            for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(9, 10))) return false;

            soma = 0;
            for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(10, 11))) return false;

            return true;
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
                        htmlFor="Responsavel_CPF"
                        style={formLabelStyle}
                        className="form-label">CPF
                        {!(checkWordInArray(getURI, 'consultar')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {(checkWordInArray(getURI, 'consultar')) ? (
                        <div className='p-2'>
                            {formData.Responsavel_CPF}
                        </div>
                    ) : (
                        <input data-api={`filtro-${origemForm}`}
                            type="text"
                            id="Responsavel_CPF"
                            name="Responsavel_CPF"
                            value={formData.Responsavel_CPF || ''}
                            maxLength="14"
                            onChange={handleChange}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                            onKeyDown={handleKeyDown}
                            style={formControlStyle}
                            className="form-control form-control-sm"
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
                <AppMessageCard parametros={message} modalId="modal_responsavel_cpf" />

            </div>
        );
    };
</script>