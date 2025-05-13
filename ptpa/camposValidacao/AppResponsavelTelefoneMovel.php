<script type="text/babel">
    const AppResponsavelTelefoneMovel = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];
        const base_url = parametros.base_url || '';

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        const [ativarCampo, setAtivarCampo] = React.useState(true);

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

        // Estado para mensagens e validação
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função para adicionar a máscara de Telefone
        const applyMaskTelefone = (telefone) => {
            telefone = telefone.replace(/\D/g, ''); // Remove tudo que não é número
            telefone = telefone.replace(/^(\d{2})(\d)/, '($1)$2'); // Adiciona parênteses ao DDD
            telefone = telefone.replace(/(\d{5})(\d)/, '$1-$2'); // Adiciona o hífen após os primeiros 5 números
            return telefone;
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

            // Aplica a máscara se for o campo Telefone
            let maskedValue = value;
            if (name === 'Responsavel_TelefoneMovel') {
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

            if (name === 'Responsavel_TelefoneMovel') {
                if (!isValidTelefone(value)) {
                    if (lastInteraction === 'mouse') {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'Telefone inválido. Por favor, insira um número de telefone. Ex (21) 9 0000-0000.'
                        });
                    }

                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                } else {
                    console.log('Telefone Responsável OK');
                }
            }
        };

        // Validação de Telefone: permite apenas números, (), . e -
        const isValidTelefone = (telefone) => {
            const telefoneSemMascara = telefone.replace(/[^\d]/g, ''); // Remove caracteres não numéricos

            // Verifica se o telefone tem 11 dígitos e não é uma sequência de números repetidos
            if (telefoneSemMascara.length !== 11 || /^(\d)\1+$/.test(telefoneSemMascara)) return false;

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
                        htmlFor="Responsavel_TelefoneMovel"
                        style={formLabelStyle}
                        className="form-label">Celular
                        {!(checkWordInArray(getURI, 'consultar')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {(checkWordInArray(getURI, 'consultar')) ? (
                        <div className='p-2'>
                            {formData.Responsavel_TelefoneMovel}
                        </div>
                    ) : (
                        <input data-api={`filtro-${parametros.origemForm || ''}`}
                            type="text"
                            id="Responsavel_TelefoneMovel"
                            name="Responsavel_TelefoneMovel"
                            value={formData.Responsavel_TelefoneMovel || ''}
                            maxLength="14"
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
                <AppMessageCard parametros={message} modalId="modal_tel_model" />
            </div>
        );
    };
</script>