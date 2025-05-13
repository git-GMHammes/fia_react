<script type="text/babel">
    const AppNomeEscola = ({
        parametros = {},
        formData = {},
        setFormData = () => { },
    }) => {
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

        const [ativarCampo, setAtivarCampo] = React.useState(true);
        const [tabNav, setTabNav] = React.useState('form');
        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null,
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
            setAtivarCampo(true);
            //console.log(`Focus event triggered for: ${name}, value: ${value}`);

            //console.log('handleFocus: ', name);
            //console.log('handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));

            //console.log('handleFocus/message.show: ', message.show);
        };

        const handleChange = (event) => {
            const { name, value } = event.target;
            //console.log(`Change event: ${name}, value: ${value}`);
            // Permite o valor vazio e impede números no nome
            if (value === '' || /^[^\d]+$/.test(value)) {
                setFormData((prev) => ({
                    ...prev,
                    [name]: value,
                }));
            } else {
                setAtivarCampo(false);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O Campo Nome do Endereço aceita apenas letras.'
                });
            }
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;
            console.log(`Blur event triggered for: ${name}, value: ${value}`);
            if (name === 'NomeEscola') {
                if (!isValidName(value)) {
                    if (lastInteraction === 'mouse') {
                        //console.log('Nome inválido, exibindo modal.');
                        // Função para exibir o alerta (success, danger, warning, info)
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'Nome da escola é obrigatório.'
                        });
                    }
                    // Limpa o campo se o NomeEscola for inválido
                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                    //console.log('NomeEscola Inválido: campo limpo');
                } else {
                    //console.log('NomeEscola OK');
                }
            }
        };

        // Validação: Impede números
        const isValidName = (value) => {
            return value.trim() !== '' && /^[^\d]+$/.test(value); // Verifica se não está vazio e não contém números
        };

        React.useEffect(() => {

            const timer = setTimeout(() => {
                setAtivarCampo(true);
            }, 2000);

            return () => clearTimeout(timer);
        }, [formData.NomeEscola]);

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
                        htmlFor="NomeEscola"
                        style={formLabelStyle}
                        className="form-label">Nome da Escola
                        {!(checkWordInArray(getURI, 'consultar')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {(checkWordInArray(getURI, 'consultar')) ? (
                        <div className="p-2">
                            {formData.NomeEscola}
                        </div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            type="text"
                            id="NomeEscola"
                            name="NomeEscola"
                            value={formData.NomeEscola || ''}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                            onChange={handleChange}
                            onKeyDown={handleKeyDown}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            readOnly={!ativarCampo}
                            required
                        />
                    )}
                </div>

                <AppMessageCard parametros={message} modalId="modal_nome_escola" />

            </div>
        );
    };
</script>