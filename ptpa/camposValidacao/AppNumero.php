<script type="text/babel">
    const AppNumero = ({
        formData = {},
        setFormData = () => { },
        parametros = {}
    }) => {
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const origemForm = parametros.origemForm || '';
        const base_url = parametros.base_url || '';
        const getURI = parametros.getURI || [];

        const checkWordInArray = (array, word) => array.includes(word);

        // Estado para mensagens
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null,
        });

        // Máscara para o número de matrícula
        const applyMaskNumero = (matricula) => {
            return matricula.replace(/\\D/g, '');// Permite apenas números e limita a 9 dígitos
        };

        // Opção 1: renomear o parâmetro
        const isValidNumero = (matriculaInput = 0) => {
            const matricula = String(matriculaInput).replace(/\D/g, '');
            return matricula.trim().length > 0;
        };

        const handleFocus = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        const handleChange = (event) => {
            const { name, value } = event.target;
            const maskedValue = applyMaskNumero(value);

            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue,
            }));
        };

        const handleBlur = async (event) => {
            const { name, value } = event.target;

            if (!isValidNumero(value)) {
                console.log('Número inválida.');
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Número da residência é obrigatório.',
                });
                setFormData((prev) => ({
                    ...prev,
                    [name]: '', // Limpa o campo se for inválido
                }));
                return;
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
                        htmlFor="Numero"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Número
                        {!(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc') ? (
                        <div className="p-2">{formData.Numero}</div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            id="Numero"
                            name="Numero"
                            value={formData.Numero || ''}
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            disabled={checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')}
                            required
                        />
                    )}

                    <AppMessageCard parametros={message} modalId="modal_numero" />
                </div>
            </div>

        );
    };
</script>