<script type="text/babel">
    const AppPeriodoAno = (
        {
            formData = {},
            setFormData = () => { },
            parametros = {}, 
            salvar = () => { },
            setSalvar = () => { }
        }
    ) => {

        // Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const waitOneSecond = () => new Promise((resolve) => setTimeout(resolve, 1000));

        // Estado para mensagens do sistema
        const [error, setError] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Ano atual e limite máximo
        const anoAtual = new Date().getFullYear();
        const anoLimite = anoAtual + 5;

        // Função para validar o ano
        const isValidAno = (ano) => {
            return /^\d{4}$/.test(ano) && ano >= anoAtual && ano <= anoLimite;
        };

        // Função handleFocus no focu
        const handleFocus = (event) => {
            const { name, value } = event.target;

            // console.log('name handleFocus: ', name);
            // console.log('value handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange para restringir apenas números e remover letras e caracteres especiais
        const handleChange = async (event) => {
            const { name, value } = event.target;

            console.log('name handleChange: ', name);
            console.log('value handleChange: ', value);

            setSalvar(true);

            // Impede novas execuções se a mensagem já estiver sendo exibida
            if (message.show) {
                console.log('Mensagem já exibida. Ignorando novas entradas...');
                return;
            }

            // Verifica se há caracteres inválidos no valor original
            const hasInvalidChars = /[^0-9]/.test(value);

            if (hasInvalidChars) {
                // Exibe a mensagem de erro e aguarda 1 segundo antes de permitir novas entradas
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O Campo Ano deve conter apenas números.',
                });
                setError('O Campo Ano deve conter apenas números.');

                // Aguarda 1 segundo para evitar novas execuções
                await waitOneSecond();

                // Remove a mensagem após o tempo de espera
                setMessage({
                    show: false,
                    type: null,
                    message: null,
                });

                return; // Interrompe a execução para evitar atualização do estado com valores inválidos
            }

            // Filtra o valor para permitir apenas números
            const filteredValue = value.replace(/[^0-9]/g, '');

            // Atualiza o estado com o valor filtrado
            setFormData((prev) => ({
                ...prev,
                [name]: filteredValue,
                ano: filteredValue,
            }));
            setError('');
        };

        // Função handleBlur para validação do ano
        const handleBlur = (event) => {
            const { name, value } = event.target;

            if (name === "periodo_ano" && value === "") {
                return true;
            }

            // console.log('name handleFocus: ', name);
            // console.log('value handleFocus: ', value);

            // Permite apenas números no campo
            const filteredValue = value.replace(/[^0-9]/g, '');

            // Ano atual e limites
            const anoAtual = new Date().getFullYear();
            const limiteFuturo = anoAtual + 4;
            const limitePassado = anoAtual - 1;

            // Controle para verificar se alguma validação foi acionada
            let validationTriggered = false;

            // Validações
            if (filteredValue.length !== 4 && message.show === false) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O Campo Ano deve ter 4 dígitos.',
                });
                setError('O Campo Ano deve ter 4 dígitos.');
                setFormData((prev) => ({ ...prev, [name]: null }));
                validationTriggered = true;
            } else if (parseInt(filteredValue, 10) < limitePassado && message.show === false) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O Ano informado está em um passado não permitido.',
                });
                setError('O Ano informado está em um passado não permitido.');
                setFormData((prev) => ({ ...prev, [name]: null }));
                validationTriggered = true;
            } else if (parseInt(filteredValue, 10) > limiteFuturo && message.show === false) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O Ano informado não deve estar em um futuro tão distante.',
                });
                setError('O Ano informado não deve estar em um futuro tão distante.');
                setFormData((prev) => ({ ...prev, [name]: null }));
                validationTriggered = true;
            } else if (!validationTriggered) {
                setMessage({ show: false, type: null, message: null });
                setFormData((prev) => ({ ...prev, [name]: filteredValue }));
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
                    <label htmlFor="periodo_ano"
                        style={formLabelStyle}
                        className="form-label">Ano
                        {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                    </label>
                    {checkWordInArray(getURI, 'consultar') ? (
                        <div className='p-2'>
                            {formData.periodo_ano}
                        </div>
                    ) : (
                        <input
                            className={`form-control form-control-sm ${error ? 'is-invalid' : formData.periodo_ano ? 'is-valid' : ''}`}
                            data-api={`filtro-${origemForm}`}
                            type="text"
                            id="periodo_ano"
                            name="periodo_ano"
                            value={formData.periodo_ano || ''}
                            onChange={handleChange}
                            onFocus={handleFocus}
                            onBlur={handleBlur}
                            style={formControlStyle}
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
                <AppMessageCard
                    parametros={message}
                    modalId="modal_ano"
                />
            </div>
        );
    };
</script>