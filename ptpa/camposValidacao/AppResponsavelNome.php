<script type="text/babel">
    const AppResponsavelNome = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

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
        const [ativarCampo, setAtivarCampo] = React.useState(true);
        const [tabNav, setTabNav] = React.useState('form');
        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
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

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });
            setAtivarCampo(true);

            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            if (value === '' || /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/.test(value)) {
                setFormData((prev) => ({
                    ...prev,
                    [name]: value,
                }));
            } else {
                setAtivarCampo(false);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O Campo Nome do Responsável aceita apenas letras.'
                });
            }
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Verifica se é o campo Nome e faz a validação
            if (name === 'Responsavel_Nome') {
                if (!isValidNome(value)) {
                    if (lastInteraction === 'mouse') {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'Nome completo é obrigatório e deve conter apenas letras e espaços.'
                        });
                    }

                    // Limpa o campo se o Nome for inválido
                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                    console.log('Nome Inválido: campo limpo');
                } else {
                    console.log('Nome OK');
                }
            }
        };

        // Validação de Nome: Mais de 4 letras e apenas letras
        const isValidNome = (Nome) => {
            // Verifica se o nome tem mais de 4 letras
            if (Nome.length < 4) {
                return false;
            }
            // Verifica se o nome contém apenas letras (A-Z, a-z) e espaços
            const regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;
            return regex.test(Nome);
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
                        htmlFor="Responsavel_Nome"
                        style={formLabelStyle}
                        className="form-label">Nome
                        {!(checkWordInArray(getURI, 'consultar')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {(checkWordInArray(getURI, 'consultar')) ? (
                        <div className='p-2'>
                            {formData.Responsavel_Nome}
                        </div>
                    ) : (
                        <input data-api={`filtro-${origemForm}`}
                            type="text"
                            id="Responsavel_Nome"
                            name="Responsavel_Nome"
                            value={formData.Responsavel_Nome || ''}
                            onChange={handleChange}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                            onKeyDown={handleKeyDown}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            readOnly={!ativarCampo}
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
                <AppMessageCard parametros={message} modalId="modal_resp_nome" />

            </div>
        );
    };
</script>