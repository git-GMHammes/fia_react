<script type="text/babel">
    const AppCpf = ({
        formData = {},
        setFormData = () => { },
        parametros = {},
        camposObrigatorios = {}
    }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const origemForm = parametros.origemForm || '';
        const base_url = parametros.base_url || '';
        const getURI = parametros.getURI || [];
        // console.log("getURI :: ", getURI);

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Duplicado
        const [cpfDuplicado, setCpfDuplicado] = React.useState(false);
        const [cpfDeclarado, setCpfDeclarado] = React.useState('');
        const [ativarCampo, setAtivarCampo] = React.useState(true);

        // Estado para mensagens e validação
        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        
        // Função para adicionar a máscara de CPF
        const applyMaskCPF = (cpf) => {
            if (cpf === '' || /^\d+$/.test(cpf)) {
                cpf = cpf.replace(/\D/g, '');
                cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                return cpf;
            } else {
                setAtivarCampo(false);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'O Campo CPF aceita apenas numeros.'
                });
                return false;
            }
        };
        
        // Validação de CPF
        const isValidCPF = (cpf) => {
            // Remove caracteres não numéricos
            cpf = cpf.replace(/[^\d]+/g, '');
            
            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
            
            let soma = 0, resto;
            for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(9, 10))) return false;
            
            soma = 0;
            for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(10, 11))) return false;
            
            return true;
        };
        
        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            setMessage({ show: false, type: null, message: null });
            setAtivarCampo(true);

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            if (name === 'CPF' && value !== '') {
                setCpfDuplicado(false);
                setCpfDeclarado(value);
            }

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            // Aplica a máscara se for o campo CPF
            let maskedValue = value.replace(/\D/g, '');
            if (name === 'CPF') {
                maskedValue = applyMaskCPF(value);
            }

            console.log('name handleChange (CPF): ', name);
            console.log('value handleChange (CPF): ', maskedValue);

            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue
            }));
        };

        const handleBlur = async (event) => {
            const { name, value } = event.target;

            if (value === '') {
                return true;
            }

            if (!isValidCPF(value)) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'CPF inválido. Por favor, insira um CPF válido.'
                });

                setFormData((prev) => ({
                    ...prev,
                    [name]: ''
                }));
                return true;
            }

            let cpfData = { [name]: value };

            try {
                // Aguarda o resultado de fetchCadastro
                const isDuplicado = await fetchCadastro(cpfData);
                setCpfDuplicado(isDuplicado);

                if (
                    value !== cpfDeclarado &&
                    isDuplicado &&
                    checkWordInArray(getURI, 'profissional') &&
                    checkWordInArray(getURI, 'atualizar')
                ) {
                    console.log('CPF já cadastrado.');
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'CPF já cadastrado.'
                    });
                    return true;
                }

                if (
                    isDuplicado &&
                    (checkWordInArray(getURI, 'profissional') &&
                        checkWordInArray(getURI, 'atualizar') ? false : true
                    )
                ) {
                    console.log('CPF já cadastrado.');
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'CPF já cadastrado.'
                    });
                }
            } catch (error) {
                console.error('Erro no fetchCadastro:', error);
            }

        };

        // Fetch para obter os Cadastros
        const fetchCadastro = async (cpfData) => {

            try {
                // console.log("parametros :: ", parametros);

                const response = await fetch(base_url + 'index.php/fia/ptpa/cadGeral/api/filtrar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(cpfData)
                });
                const data = await response.json();

                console.log('data: ', data);

                if (
                    data.result &&
                    data.result.dbResponse &&
                    data.result.dbResponse.length > 0
                ) {

                    // Limpa o campo se o CPF for inválido
                    setFormData((prev) => ({
                        ...prev,
                        ['CPF']: checkWordInArray(getURI, 'profissional') && checkWordInArray(getURI, 'atualizar') ? cpfData.CPF : ''
                    }));

                    setCpfDuplicado(true);
                    console.log('CPF já cadastrado.');
                    return true;
                    
                } else {
                    
                    setCpfDuplicado(false);
                    console.log('CPF não cadastrado.');
                    return false;
                }

            } catch (error) {
                // Função para exibir o alerta (success, danger, warning, info)
                if (message.show === false) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Erro ao carregar Funcionários: ' + error.message
                    });
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
                        htmlFor="CPF"
                        style={formLabelStyle}
                        className="form-label">CPF
                        {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (camposObrigatorios.CPF ? <strong style={requiredField}>*</strong> : null)}
                    </label>
                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                        <div className='p-2'>
                            {formData.CPF}
                        </div>
                    ) : (
                        <input data-api={`filtro-${origemForm}`}
                            type="text"
                            id="CPF"
                            name="CPF"
                            value={formData.CPF || ''}
                            maxLength="14"
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            disabled={(checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')) ? true : false}
                            readOnly={!ativarCampo}
                            required={!camposObrigatorios.CPF}
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
                    modalId="modal_cpf"
                />

            </div>
        );
    };
</script>