<script type="text/babel">
    const AppCertidaoNascimento = ({
        formData = {},
        setFormData = () => { },
        parametros = {}
    }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const origemForm = parametros.origemForm || '';
        const base_url = parametros.base_url || '';
        const getURI = parametros.getURI || [];

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        const [ativarCampo, setAtivarCampo] = React.useState(true);

        // Duplicado
        const [certidaoDuplicado, setCertidaoDuplicado] = React.useState(false);
        const [certidaoDeclarado, setCertidaoDeclarado] = React.useState('');
        // Largura
        const [width, setWidth] = React.useState(window.innerWidth);
        const [labelFieldMatCertNasc, setLabelFieldMatCertNasc] = React.useState('Matrícula da Certidão de Nasciment');

        // Estado para mensagens e validação
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Validação de Certidão de Nascimento
        const isValidCertidao = (value) => {
            const onlyNumbers = value.replace(/\D/g, ''); // Remove tudo que não for dígito
            return onlyNumbers.length === 32; // Verifica se contém exatamente 32 números
        };

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            setMessage({ show: false, type: null, message: null });
            setAtivarCampo(true);

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            if (name === 'Certidao' && value !== '') {
                setCertidaoDuplicado(false);
                setCertidaoDeclarado(value);
            }

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange para lidar com entrada do usuário
        const handleChange = (event) => {
            const { name, value } = event.target;
            // Remove tudo que não for número
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
                    message: 'O Campo Matrícula da Certidão de Nascimento aceita apenas numeros.'
                });
            }
        };

        // Função handleBlur para validação ao perder o foco
        const handleBlur = async (event) => {
            const { name, value } = event.target;

            if (value === '') {
                return true;
            }

            if (!isValidCertidao(value)) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Certidão de Nascimento inválida. Certifique-se de que contém exatamente 32 números.'
                });

                setFormData((prev) => ({
                    ...prev,
                    [name]: ''
                }));
                return true;
            }

            let certidaoData = { [name]: value };

            try {
                // Aguarda o resultado de fetchCadastro
                const isDuplicado = await fetchCadastro(certidaoData);
                setCertidaoDuplicado(isDuplicado);

                if (
                    value !== certidaoDeclarado &&
                    isDuplicado &&
                    checkWordInArray(getURI, 'profissional') &&
                    checkWordInArray(getURI, 'atualizar')
                ) {
                    console.log('Certidão já cadastrada.');
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Certidão já cadastrada.'
                    });
                    return true;
                }

                if (
                    isDuplicado &&
                    (checkWordInArray(getURI, 'profissional') &&
                        checkWordInArray(getURI, 'atualizar') ? false : true
                    )
                ) {
                    console.log('Certidão já cadastrada.');
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Certidão já cadastrada.'
                    });
                }
            } catch (error) {
                console.error('Erro no fetchCadastro:', error);
            }
        };

        // Fetch para obter os Cadastros
        const fetchCadastro = async (certidaoData) => {

            try {
                // console.log("parametros :: ", parametros);

                const response = await fetch(base_url + 'index.php/fia/ptpa/cadGeral/api/filtrar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(certidaoData)
                });
                const data = await response.json();

                console.log('data: ', data);

                if (
                    data.result &&
                    data.result.dbResponse &&
                    data.result.dbResponse.length > 0
                ) {

                    // Limpa o campo se o Certidao for inválido
                    setFormData((prev) => ({
                        ...prev,
                        ['Certidao']: checkWordInArray(getURI, 'profissional') && checkWordInArray(getURI, 'atualizar') ? certidaoData.Certidao : ''
                    }));

                    setCertidaoDuplicado(true);
                    return true;

                } else {

                    setCertidaoDuplicado(false);
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


        // useEffect para atualizar o estado da largura da tela
        React.useEffect(() => {
            const handleResize = () => {
                setWidth(window.innerWidth);
            };

            window.addEventListener('resize', handleResize);

            return () => {
                window.removeEventListener('resize', handleResize);
            };
        }, []);

        React.useEffect(() => {
            if (width < 1500) {
                setLabelFieldMatCertNasc('Certidão');
            } else {
                setLabelFieldMatCertNasc('Matrícula da Certidão de Nasciment');
            }
        }, [width]);

        return (
            <div>
                <div style={formGroupStyle}>
                    <label
                        htmlFor="Certidao"
                        style={formLabelStyle}
                        className="form-label">{`${labelFieldMatCertNasc}`}
                        {!(checkWordInArray(getURI, 'consultar')) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    {(checkWordInArray(getURI, 'consultar')) ? (
                        <div className='p-2'>
                            {formData.Certidao}
                        </div>
                    ) : (
                        <input
                            type="text"
                            id="Certidao"
                            name="Certidao"
                            value={formData.Certidao || ''}
                            maxLength="32"
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            readOnly={!ativarCampo}
                            required
                        />
                    )}
                </div>
                {/* Exibe o componente de alerta */}
                <AppMessageCard parametros={message} modalId="modal_certidao" />
            </div>
        );
    };
</script>