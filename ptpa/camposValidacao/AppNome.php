<script type="text/babel">
    const AppNome = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {
        // console.log('src/app/Views/fia/ptpa/camposValidacao/AppNome.php');
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];
        
        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Definindo mensagens do Sistema
        // const [tabNav, setTabNav] = React.useState('form');
        // const [showAlert, setShowAlert] = React.useState(false);
        // const [alertType, setAlertType] = React.useState('');
        // const [alertMessage, setAlertMessage] = React.useState('');

        // Estado para mensagens e validação
        // const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

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

        const isValidNome = (Nome) => {
            if(Nome === ''){
                return true;
            }
            // Verifica se o nome tem mais de 3 letras
            if (Nome.length < 3) return false;
            return true;
        };

        // Função handleChange com remoção de números, caracteres especiais e espaços duplicados
        const handleChange = (event) => {
            const { name, value } = event.target;

            const lastChar = value.slice(-1); // Pega o último caractere
            if (!isNaN(parseInt(lastChar)) && message.show === false) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Nome completo é obrigatório e deve conter apenas letras e espaços.'
                });
            } else if (name === 'Nome') {
                const clearedValue = value
                    .replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s']/g, '')
                    .replace(/\s{2,}/g, ' ')
                    .replace(/'{2,}/g, "'"); // remove apóstrofo duplicado

                setFormData((prev) => ({
                    ...prev,
                    [name]: clearedValue
                }));
            } else {
                setMessage({ show: false, type: null, message: null });
            }

        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            if (name === 'Nome' && value === '') {
                return true;
            }

            if (!isValidNome(value) && message.show === false) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Nome inválido. Por favor, insira um nome contendo apenas letras.'
                });

                setFormData((prev) => ({
                    ...prev,
                    [name]: ''
                }));
            } else {
                setMessage({ show: false, type: null, message: null });
            }

            console.log('handleChange/handleBlur.show: ', message.show);

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

        // console.log('src/app/Views/fia/ptpa/camposValidacao/AppNome.php');

        return (
            <div>
                <div style={formGroupStyle}>
                    <label
                        htmlFor="Nome"
                        style={formLabelStyle}
                        className="form-label">Nome Completo
                        {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                        </label>
                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                        <div className='p-2'>
                            {formData.Nome}
                        </div>
                    ) : (
                        <input data-api={`filtro-${origemForm}`}
                            type="text"
                            id="Nome"
                            name="Nome"
                            value={formData.Nome || ''}
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            disabled={(checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')) ? true : false}
                            required
                        />
                    )}
                </div>
                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message}
                    modalId="modal_nome"
                />

                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}
            </div>
        );

    };
</script>