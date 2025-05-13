<script type="text/babel">
    const AppUnidadeNome = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

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

        // Validação de unidade_nome: Mais de 4 letras e apenas letras
        const isValidNome = (unidade_nome) => {
            // Verifica se o nome tem mais de 4 letras
            if (unidade_nome.length < 4) {
                return false;
            }
            // Verifica se o nome contém apenas letras (A-Z, a-z) e espaços
            const regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
            return regex.test(unidade_nome);
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

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleBlur simplificada
        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Verifica se é o campo unidade_nome e faz a validação
            if (name === 'unidade_nome') {
                if (!isValidNome(value)) {

                    // Função para exibir o alerta (success, danger, warning, info)
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Alerta de Validação de unidade_nome Ativa'
                    });

                    // Limpa o campo se o unidade_nome for inválido
                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                    console.log('unidade_nome Inválido: campo limpo');
                } else {
                    console.log('unidade_nome OK');
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
                    <label htmlFor="unidade_nome" style={formLabelStyle} className="form-label">Nome (Unidade)<strong style={requiredField}>*</strong></label>
                    <input data-api={`filtro-${origemForm}`}
                        type="text"
                        id="unidade_nome"
                        name="unidade_nome"
                        value={formData.unidade_nome || ''}
                        onChange={handleChange}
                        onFocus={handleFocus}
                        onBlur={handleBlur}
                        style={formControlStyle}
                        className="form-control form-control-sm"
                        required
                    />
                </div>
                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}
                {/* Exibe o componente de alerta */}
                <AppMessageCard parametros={message} modalId="modal_unidade_nome" />

            </div>
        );
    };
</script>