<script type="text/babel">
    const AppPeriodo = (
        {
            formData = {},
            setFormData = () => { },
            parametros = {},
            salvar = () => { },
            setSalvar = () => { }
        }
    ) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];
        // console.log('getURI', getURI);

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

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

        const [selecaoPeriodos, setSelecaoPeriodos] = React.useState([
            {
                periodo_numero: '1',
                descricao: '1º Semestre'
            },
            {
                periodo_numero: '2',
                descricao: '2º Semestre'
            }
        ]);

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            // console.log('handleChange: ', name);
            // console.log('handleChange: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('handleChange: ', name);
            console.log('handleChange: ', value);

            setSalvar(true);

            setMessage({ show: false, type: null, message: null, });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleBlur para limpar o campo Telefone se for inválido
        const handleBlur = (event) => {
            const { name, value } = event.target;

            // console.log('handleBlur: ', name);
            // console.log('handleBlur: ', value);

            // Atualiza o formData normalmente
            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
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
                    <label htmlFor="periodo_numero"
                        style={formLabelStyle}
                        className="form-label">Período
                        {checkWordInArray(getURI, 'consultar') ? (null) : (<strong disabled={checkWordInArray(getURI, 'alocar') ? true : false} style={requiredField}>*</strong>)}
                    </label>
                    {checkWordInArray(getURI, 'consultar') ? (
                        <div className='p-2'>
                            {formData.periodo_numero}
                        </div>
                    ) : (
                        <select
                            data-api={`filtro-${origemForm}`}
                            id="periodo_numero"
                            name="periodo_numero"
                            value={formData.periodo_numero || ''}
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-select form-select-sm"
                            aria-label="Default select"
                            required
                        >
                            <option value="">Seleção Nula</option>
                            {selecaoPeriodos.map(periodo_select => (
                                <option key={periodo_select.periodo_numero} value={periodo_select.periodo_numero}>
                                    {periodo_select.descricao}
                                </option>
                            ))}
                        </select>
                    )}
                </div>
                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}

                {/* Exibe o componente de alerta */}
                <AppMessageCard parametros={message} modalId="modal_periodo" />
            </div>
        );
    };
</script>