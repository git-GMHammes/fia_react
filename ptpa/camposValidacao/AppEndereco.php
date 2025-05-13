<script type="text/babel">
    const AppEndereco = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];

        // Variáveis uteis
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Definindo mensagens do Sistema
        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('handleChange: ', name);
            console.log('handleChange: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Validação de unidades_endereco: Aceita apenas letras, números, espaços, vírgulas e pontos
        const isValidEndercoUnidade = (unidades_endereco) => {
            // Verifica se o endereco tem mais de 4 letras
            if (unidades_endereco.length < 4 || unidades_endereco === '') {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O endereço deve conter mais de 4 letras.'
                });
                return false;
            }

            // Permite letras (A-Z, a-z), números, espaços, vírgulas e pontos
            const regex = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s,./-]*$/;
            return regex.test(unidades_endereco);
        };

        const handleChange = (event) => {
            const { name, value } = event.target;

            // Filtra apenas letras, números, espaços e caracteres especiais permitidos (-, ., ,)
            const filteredValue = value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ0-9\s,.-]/g, '');

            if (filteredValue !== value && message.show === false) {
                // Exibe mensagem se houver caracteres inválidos
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Endereço inválido. Por favor, insira um endereço contendo apenas letras e números.'
                });
            }

            setFormData((prev) => ({
                ...prev,
                [name]: filteredValue
            }));
        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            if (value === '') {
                return true;
            }

            // Verifica se é o campo unidades_endereco e faz a validação
            if (name === 'unidades_endereco') {
                if (!isValidEndercoUnidade(value) && message.show === false) {

                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                    console.log('unidades_endereco Inválido: campo limpo');
                } else {
                    console.log('unidades_endereco OK');
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
                    htmlFor="unidades_endereco"
                    style={formLabelStyle}
                    className="form-label"
                >
                    {checkWordInArray(getURI, 'adolescente') && checkWordInArray(getURI, 'cadastrar')
                        ? 'Endereço'
                        : 'Endereço (Unidade)'}
                    {checkWordInArray(getURI, 'consultar') ? null : <strong style={requiredField}>*</strong>}
                </label>
                    <div className="d-flex justify-content-between">
                        <div className='w-100'>
                            {checkWordInArray(getURI, 'consultar') ? (
                                <div className='p-2'>
                                    {formData.unidades_endereco ? (
                                        <div>
                                            {formData.unidades_endereco}
                                        </div>
                                    ) : (
                                        <div className='text-muted'>
                                            ...
                                        </div>
                                    )}
                                </div>
                            ) : (
                                <input data-api={`filtro-${origemForm}`}
                                    type="text"
                                    id="unidades_endereco"
                                    name="unidades_endereco"
                                    value={formData.unidades_endereco || ''}
                                    onFocus={handleFocus}
                                    onChange={handleChange}
                                    onBlur={handleBlur}
                                    style={formControlStyle}
                                    className="form-control form-control-sm"
                                    required
                                />
                            )}
                        </div>
                        {
                            (
                                (
                                    !checkWordInArray(getURI, 'alocarfuncionario') &&
                                    !checkWordInArray(getURI, 'consultar')
                                ) &&
                                
                                (
                                    !checkWordInArray(getURI, 'adolescente') 
                                )
                                
                            ) && (
                                <div>
                                    <AppCep
                                        formData={formData}
                                        setFormData={setFormData}
                                        parametros={parametros}
                                    />
                                </div>
                            )} 
                    </div>
                </div>
                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}
                {/* Exibe o componente de alerta */}
                <AppMessageCard parametros={message} modalId="modal_unidade_endereco" />
            </div>
        );
    };
</script>