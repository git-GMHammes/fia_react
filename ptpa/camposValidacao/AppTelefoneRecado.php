<script type="text/babel">
    const AppTelefoneRecado = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Estado para mensagens e validação
        const [error, setError] = React.useState('');
        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = () => {
            setMessage((prev) => ({
                ...prev,
                show: false
            }));
        };

        // Função para adicionar a máscara de Telefone, permitindo espaço após o DDD
        const applyMaskTelefone = (telefone) => {
            telefone = telefone.replace(/\D/g, ''); // Remove tudo que não é número

            if (telefone.length === 11) { // Celular: (21)9NNNN-NNNN
                telefone = telefone.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})$/, '($1)$2$3-$4');
            } else if (telefone.length === 10) { // Fixo: (21)NNNN-NNNN
                telefone = telefone.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1)$2-$3');
            }
            
            return telefone;
        };

        // Validação de Telefone
        const isValidTelefone = (telefone) => {

            const telefoneSemMascara = telefone.replace(/\D/g, '');
            return (telefoneSemMascara.length === 10 || telefoneSemMascara.length === 11) &&
                !/^(\d)\1+$/.test(telefoneSemMascara);
        };

        // Função handleChange
        const handleChange = (event) => {
            const { name, value } = event.target;
            let maskedValue = value;
            if (name === 'TelefoneRecado') {
                maskedValue = applyMaskTelefone(value);
            }
            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue
            }));
            setError('');
        };

        // Função handleBlur
        const handleBlur = (event) => {
            const { name, value } = event.target;
            
            if (value === '') {
                return true;
            }

            if (name === 'TelefoneRecado') {
                if (!isValidTelefone(value)) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Telefone inválido. Por favor, insira um telefone válido.'
                    });
                    setError('Telefone inválido. Por favor, insira um telefone válido.');

                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                }
                else{
                    setMessage({ show: false, type: null, message: null });
                }
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
                        htmlFor="TelefoneRecado"
                        style={formLabelStyle}
                        className="form-label">Telefone
                        {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                    </label>
                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                        <div className='p-2'>
                            {formData.TelefoneRecado}
                        </div>
                    ) : (
                        <input data-api={`filtro-${origemForm}`}
                            className={`form-control form-control-sm ${error ? 'is-invalid' : formData.TelefoneRecado ? 'is-valid' : ''}`}
                            type="text"
                            id="TelefoneRecado"
                            name="TelefoneRecado"
                            value={formData.TelefoneRecado || ''}
                            maxLength="14"
                            onChange={handleChange}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            disabled={(checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')) ? true : false}
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
                <AppMessageCard parametros={message} modalId="modal_telefone_recado" />
            </div>
        );
    };
</script>