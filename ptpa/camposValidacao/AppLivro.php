<script type="text/babel">
    const AppLivro = ({
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

        // Máscara para o campo Livro
        const applyMaskLivro = (value) => {
            return value.replace(/\D/g, '').slice(0, 5); // Permite apenas números e limita a 5 dígitos
        };

        // Validação do campo Livro
        const isValidLivro = (livro) => {
            return livro.length >= 4 && livro.length <= 5; // Verifica se está entre 4 e 5 dígitos
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
            setAtivarCampo(true);

            // Aplica a máscara ao valor digitado
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
                    message: 'O Campo Livro aceita apenas numeros.'
                });
                return false;
            }
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            // Valida o valor ao perder o foco
            if (!isValidLivro(value) && ativarCampo) {
                console.log('Livro inválido.');
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Livro inválido. Deve conter entre 4 e 5 números.',
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
                        htmlFor="Livro"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Livro
                        {!(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc') ? (
                        <div className="p-2">{formData.Livro}</div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            id="Livro"
                            name="Livro"
                            value={formData.Livro || ''}
                            maxLength="5"
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            required
                        />
                    )}

                    <AppMessageCard parametros={message} modalId="modal_livro" />

                </div>
            </div>
        );
    };
</script>