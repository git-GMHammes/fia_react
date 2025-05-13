<script type="text/babel">
    const AppCircunscricao = ({
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

        // Máscara para o campo Circunscrição
        const applyMaskCircunscricao = (value) => {
            return value.replace(/\D/g, '').slice(0, 15); // Permite apenas números e limita a 15 dígitos
        };

        // Validação do campo Circunscrição
        const isValidCircunscricao = (circunscricao) => {
            return circunscricao.length >= 9 && circunscricao.length <= 15; // Verifica se está entre 9 e 15 dígitos
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
                    message: 'O Campo Circunscrição aceita apenas numeros.'
                });
                return false;
            }
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Valida o valor ao perder o foco
            if (!isValidCircunscricao(value) && ativarCampo) {
                console.log('Circunscrição inválida.');
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Circunscrição inválida. Deve conter entre 9 e 15 números.',
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
                        htmlFor="Circunscricao"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Circunscrição
                        {!(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc') ? (
                        <div className="p-2">{formData.Circunscricao}</div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            id="Circunscricao"
                            name="Circunscricao"
                            value={formData.Circunscricao || ''}
                            maxLength="15"
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            required
                        />
                    )}

                    <AppMessageCard parametros={message} modalId="modal_circunscricao"/>

                </div>
            </div>
        );
    };
</script>
