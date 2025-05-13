<script type="text/babel">
    const AppRegistro = ({
        formData = {},
        setFormData = () => { },
        parametros = {}
    }) => {

        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];
        const base_url = parametros.base_url || '';

        const checkWordInArray = (array, word) => array.includes(word);
        const [ativarCampo, setAtivarCampo] = React.useState(true);

        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null,
        });

        // Validação de Nº Registro
        const isValidRegistro = (value) => {
            return value.length > 5;
        };

        const handleFocus = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });
            setAtivarCampo(true);

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
                    message: 'O Campo Nº Registro aceita apenas numeros.'
                });
                return false;
            }
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Valida o valor ao perder o foco
            if (!isValidRegistro(value) && ativarCampo) {
                console.log('Registro inválido.');
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Nº Registro inválido. Deve conter entre 6 e 9 números.',
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
                        htmlFor="NumRegistro"
                        style={formLabelStyle}
                        className="form-label">Nº Registro
                        {!(checkWordInArray(getURI, 'consultar')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {checkWordInArray(getURI, 'consultar') ? (
                        <div className="p-2">
                            {formData.NumRegistro}
                        </div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            id="NumRegistro"
                            name="NumRegistro"
                            value={formData.NumRegistro || ''}
                            maxLength="9"
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            readOnly={!ativarCampo}
                            required
                        />
                    )}

                    <AppMessageCard parametros={message} modalId="modal_registro" />

                </div>
            </div>
        );
    };
</script>