<script type="text/babel">
    const AppTelefoneMovel = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';

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

        const [ativarCampo, setAtivarCampo] = React.useState(true);

        // Função para adicionar a máscara de Telefone
        const applyMaskTelefone = (telefone) => {
            telefone = telefone.replace(/\D/g, ''); // Remove tudo que não é número
            telefone = telefone.replace(/^(\d{2})(\d)/, '($1)$2'); // Adiciona parênteses ao DDD
            telefone = telefone.replace(/(\d{5})(\d)/, '$1-$2'); // Adiciona o hífen após os primeiros 5 números
            return telefone;
        };

        // Validação de Telefone: permite apenas números, (), . e -
        const isValidTelefone = (telefone) => {
            const telefoneSemMascara = telefone.replace(/[^\d]/g, ''); // Remove caracteres não numéricos

            // Verifica se o telefone tem 11 dígitos e não é uma sequência de números repetidos
            if (telefoneSemMascara.length !== 11 || /^(\d)\1+$/.test(telefoneSemMascara)) return false;

            return true;
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

            // Aplica a máscara se for o campo Telefone
            let maskedValue = value;
            if (name === 'TelefoneMovel') {
                maskedValue = applyMaskTelefone(value);
            }

            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue
            }));
        };

        // Função handleBlur para limpar o campo Telefone se for inválido
        const handleBlur = (event) => {
            const { name, value } = event.target;

            if (name === 'TelefoneMovel') {
                if (!isValidTelefone(value)) {
                    // Função para exibir o alerta (success, danger, warning, info)
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Telefone inválido. Por favor, insira um número de telefone. Ex (21) 9 0000-0000'
                    });

                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                }
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

        return (
            <div>
                <div style={formGroupStyle}>
                    <label htmlFor="TelefoneMovel" style={formLabelStyle} className="form-label">#Telefone Movel<strong style={requiredField}>*</strong></label>
                    <input data-api={`filtro-${parametros.origemForm || ''}`}
                        type="text"
                        id="TelefoneMovel"
                        name="TelefoneMovel"
                        value={formData.TelefoneMovel || ''}
                        maxLength="14"
                        onChange={handleChange}
                        onFocus={handleFocus}
                        onBlur={handleBlur}
                        style={formControlStyle}
                        className="form-control"
                        readOnly={!ativarCampo}
                        required
                    />
                </div>
                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}

                {/* Exibe o componente de alerta */}
                <AppMessageCard parametros={message} modalId="modal_tel_movel" />
            </div>
        );
    };
</script>