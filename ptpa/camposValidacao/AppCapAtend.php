<script type="text/babel">
    const AppCapAtend = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const base_url = parametros.base_url || '';
        const getURI = parametros.getURI || [];
        const origemForm = parametros.origemForm || '';

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Definindo mensagens do Sistema
        const [debounceTimeout, setDebounceTimeout] = React.useState(null); // Guardar o timeout
        const [defineAtualizar, setDefineAtualizar] = React.useState(false);
        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função para validar Capacidade Atendimento
        const isValidCapacity = (unidades_cap_atendimento) => {
            unidades_cap_atendimento = unidades_cap_atendimento ? String(unidades_cap_atendimento).replace(/\D/g, '') : '0';
            const numericValue = Number(unidades_cap_atendimento);

            if (numericValue > 0) {
                return true;
            } else if (numericValue === 0 || numericValue < 0 || numericValue === ' ') {
                return false;
            }

            // Verifica se o CAP contém apenas números e difentes de 0
            const regex = /^[^a-zA-Z]*(?<!\d)0(?!\d)?[^a-zA-Z]*$/; // Remove tudo que não for dígito
            return regex.test(unidades_cap_atendimento)
        };

        // Função handleFocus no focu
        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange com timeout
        const handleChange = (event) => {
            const { name, value } = event.target;

            // Mostra imediatamente o valor atual no campo
            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));

            // Remove qualquer timeout anterior
            if (debounceTimeout) {
                clearTimeout(debounceTimeout);
            }

            // Define um novo timeout para processar a lógica após o usuário parar de digitar
            const timeout = setTimeout(() => {
                // Remove caracteres não numéricos
                const filteredValue = value.replace(/\D/g, '');

                // Verifica se o valor é válido
                if (filteredValue === '' || Number(filteredValue) <= 0) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Capacidade de Atendimento inválida. Por favor, insira um valor numérico e positivo.',
                    });
                    setFormData((prev) => ({
                        ...prev,
                        [name]: null,
                    }));
                } else {
                    setMessage({ show: false, type: null, message: null });
                    setFormData((prev) => ({
                        ...prev,
                        [name]: filteredValue,
                    }));
                }
            }, 300); // Timeout de 300ms após o usuário parar de digitar

            setDebounceTimeout(timeout); // Armazena o timeout atual
        };

        // Função handleBlur para limpar o campo Capacidade de Atendimento se for inválido
        const handleBlur = (event) => {
            const { name, value } = event.target;

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
                    <label
                        htmlFor="unidades_cap_atendimento"
                        style={formLabelStyle}>Capacidade de Atendimento
                        {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                    </label>
                    {checkWordInArray(getURI, 'consultar') ? (
                        <div className='p-2'>
                            {formData.unidades_cap_atendimento ? (
                                <div>
                                    {formData.unidades_cap_atendimento}
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
                            id="unidades_cap_atendimento"
                            name="unidades_cap_atendimento"
                            value={formData.unidades_cap_atendimento || ''}
                            onChange={handleChange}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
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
                <AppMessageCard parametros={message} modalId="modal_cap_atend" />

            </div>
        );
    };
</script>