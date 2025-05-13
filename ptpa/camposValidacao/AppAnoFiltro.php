<script type="text/babel">
    const AppAnoFiltro = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {
        // Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';

        // Estado para mensagens do sistema
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Ano atual e limites de 10 anos no passado e 10 anos no futuro
        const anoAtual = new Date().getFullYear();
        const anoLimiteFuturo = anoAtual + 10;
        const anoLimitePassado = anoAtual - 10;

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

        // Função para validar o ano
        const isValidAno = (ano) => {
            return /^\d{4}$/.test(ano) && ano >= anoLimitePassado && ano <= anoLimiteFuturo;
        };

        // Função handleChange para restringir apenas números e remover letras e caracteres especiais
        const handleChange = (event) => {
            const { name, value } = event.target;
            const filteredValue = value.replace(/[^0-9]/g, ''); // Permite apenas números

            setFormData((prev) => ({
                ...prev,
                [name]: filteredValue
            }));
        };

        // Função handleBlur para validação do ano
        const handleBlur = (event) => {
            const { name, value } = event.target;
            const ano = parseInt(value, 10);

            if (!value && message.show === false) {
                setFormData((prev) => ({
                    ...prev,
                    [name]: ""
                }));
                return;
            }else if (ano < anoLimitePassado && message.show === false) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O filtro possui um limite de 10 anos no passado'
                });
                setFormData((prev) => ({
                    ...prev,
                    [name]: ""
                }));
            } else if (ano > anoLimiteFuturo && message.show === false) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O filtro possui um limite de 10 anos no futuro'
                });
                setFormData((prev) => ({
                    ...prev,
                    [name]: ""
                }));
            } else {
                setMessage({ show: false, type: null, message: null });
            }
        };

        // Estilos personalizados
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

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                <label
                    htmlFor="ano"
                    style={formLabelStyle}
                    className="form-label">Ano
                </label>
                <input
                    data-api={`filtro-${origemForm}`}
                    type="text"
                    id="ano"
                    name="ano"
                    value={formData.ano || ''}
                    maxLength="4"
                    onChange={handleChange}
                    onFocus={handleFocus}
                    onBlur={handleBlur}
                    style={formControlStyle}
                    className="form-control form-control-sm"
                    aria-label=".form-control-sm example"
                />
                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}

                {/* Exibe o componente de alerta */}
                <AppMessageCard parametros={message} modalId="modal_ano_filtro" />
            </div>
        );
    };
</script>