<script type="text/babel">
    const AppComplemento = ({
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
        const applyMaskMatricula = (matricula) => {
            return matricula.replace(/\D/g, '').slice(0, 9); // Permite apenas números e limita a 9 dígitos
        };

        // Validação de matrícula
        const isValidMatricula = (matricula = 0) => {
            const plainMatricula = matricula.replace(/\D/g, ''); // Remove qualquer caractere que não seja número
            return plainMatricula.length === 9; // Valida se tem exatamente 9 dígitos
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
            const maskedValue = applyMaskMatricula(value);

            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue,
            }));
        };

        const handleBlur = async (event) => {
            const { name, value } = event.target;

            if (!isValidMatricula(value)) {
                console.log('Matrícula inválida.');
                setMessage({
                    show: false,
                    type: 'danger',
                    message: 'Matrícula inválida. Por favor, insira exatamente 9 números.',
                });
                setFormData((prev) => ({
                    ...prev,
                    [name]: '', // Limpa o campo se for inválido
                }));
                return;
            }

            try {
                const isDuplicado = await fetchCadastro({ [name]: value });
                if (isDuplicado) {
                    console.log('Matrícula já cadastrada.');
                    setMessage({
                        show: false,
                        type: 'danger',
                        message: 'Matrícula já cadastrada no sistema.',
                    });
                }
            } catch (error) {
                console.error('Erro ao verificar duplicidade:', error);
            }
        };

        // Fetch para verificar duplicidade
        const fetchCadastro = async (matriculaData) => {
            try {
                const response = await fetch(`${base_url}fia/ptpa/cadGeral/api/filtrar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(matriculaData),
                });
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    return false; // Matrícula duplicada
                }
                return false; // Matrícula não encontrada
            } catch (error) {
                console.error('Erro no fetchCadastro:', error);
                return false;
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
                        htmlFor="Matricula"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Complemento
                        {!(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc') ? (
                        <div className="p-2">{formData.Matricula}</div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            id="Matricula"
                            name="Matricula"
                            value={formData.Matricula || ''}
                            maxLength="9"
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            disabled={checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')}
                            required
                        />
                    )}

                    <AppMessageCard parametros={message} modalId="modal_matricula"/>
                </div>
            </div>
            
        );
    };
</script>
