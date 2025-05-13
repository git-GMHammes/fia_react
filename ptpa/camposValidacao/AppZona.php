<script type="text/babel">
    const AppZona = ({
        formData = {},
        setFormData = () => { },
        parametros = {}
    }) => {
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];

        const checkWordInArray = (array, word) => array.includes(word);
        const [ativarCampo, setAtivarCampo] = React.useState(true);

        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null,
        });

        // Máscara para o campo Zona
        const applyMaskZona = (value) => {
            return value.replace(/\D/g, '').slice(0, 3); // Permite apenas números e limita a 3 dígitos
        };

        // Validação do campo Zona
        const isValidZona = (zona) => {
            return zona.length === 2; // Verifica se o tamanho é exatamente 3 dígitos
        };

        const handleFocus = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });
            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));
        };

        const handleChange = (event) => {
            const { name, value } = event.target;

            if (value === '' || /^\d+$/.test(value)) {
                setFormData((prev) => ({
                    ...prev,
                    [name]: value,
                }));
            } else {
                setAtivarCampo(false);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O Campo Zona aceita apenas numeros.'
                });
                return false;
            }
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Valida o valor ao perder o foco
            if (!isValidZona(value) && ativarCampo) {
                console.log('Zona inválida.');
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Zona inválida. Deve conter exatamente 2 números.',
                });
                setFormData((prev) => ({
                    ...prev,
                    [name]: '', // Limpa o campo se for inválido
                }));
            } else {
                setMessage({
                    show: false,
                    type: null,
                    message: null,
                });
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
                    <label
                        htmlFor="Zona"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Zona
                        {!(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc') ? (
                        <div className="p-2">{formData.Zona}</div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            id="Zona"
                            name="Zona"
                            value={formData.Zona || ''}
                            maxLength="3"
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            readOnly={!ativarCampo}
                            required
                        />
                    )}

                    <AppMessageCard parametros={message} modalId="modal_zona" />

                </div>
            </div>
        );
    };
</script>