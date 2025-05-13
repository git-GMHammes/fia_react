<script type="text/babel">
    const AppCapAtendFiltrar = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Parâmetros do backend
        const origemForm = parametros.origemForm || '';

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Definindo mensagens do Sistema
        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [message, setMessage] = React.useState(false);

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

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            //console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            // Verifica se o valor contém apenas dígitos
            if (/[^0-9]/.test(value)) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: "O Campo Capacidade de Atendimento deve receber somente números"
                });
            }

            const filteredValue = value.replace(/\D/g, '');

            setFormData((prev) => ({
                ...prev,
                [name]: filteredValue
            }));
        };

        // Função handleBlur para limpar o campo Capacidade de Atendimento se for inválido
        const handleBlur = (event) => {
            const { name, value } = event.target;

            if (name === 'unidades_cap_atendimento') {
                if (!isValidCapacity(value) && message.show === false) {
                    // Exibe mensagem de alerta para caracteres inválidos
                    setMessage({
                        show: false,
                        type: 'light',
                        message: 'Capacidade de Atendimento inválida. Por favor, insira um valor numérico e não negativo.'
                    });

                    // Limpa o campo se o unidades_endereco for inválido
                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                } else {
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
            color: 'gray',
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
                <label
                    htmlFor="unidades_cap_atendimento"
                    style={formLabelStyle}
                    className="form-label">Capacidade
                </label>
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
                />

            </div>
        );
    };
</script>