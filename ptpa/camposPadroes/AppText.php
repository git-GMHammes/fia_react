<script type="text/babel">
    const AppText = ({
        submitAllForms,
        parametros = {},
        formData = {},
        setFormData = () => { },
        fieldAttributes = {}
    }) => {

        // Script que aceita parâmetros, formulário de dados, função de configuração de dados e atributos de campo

        // CEP, CPF, Telefone, Processo

        // console.log('AppText:', parametros, formData, setFormData, fieldAttributes);

        // console.log('src/app/Views/fia/ptpa/camposPadroes/AppText.php')

        const getURI = parametros.getURI || [];
        const base_url = parametros.base_url || '';
        const [InitialValue, setInitialValue] = React.useState('');

        const labelField = fieldAttributes.labelField || 'AppTextLabel';
        const nameField = fieldAttributes.nameField || 'AppTextName';
        const labelColor = fieldAttributes.labelColor || 'gray';
        const errorMessage = fieldAttributes.errorMessage || '';
        const origemForm = fieldAttributes.attributeOrigemForm || 'AppTextOrigemForm';
        const attributePlaceholder = fieldAttributes.attributePlaceholder || '';
        const attributeMinlength = fieldAttributes.attributeMinlength || 1;
        const attributeMaxlength = fieldAttributes.attributeMaxlength || 2;
        const attributePattern = fieldAttributes.attributePattern || '';
        const attributeAutocomplete = fieldAttributes.attributeAutocomplete || 'off';
        const attributeRequired = fieldAttributes.attributeRequired || false;
        const attributeReadOnly = fieldAttributes.attributeReadOnly || false;
        const [attributeDisabled, setAttributeDisabled] = React.useState(fieldAttributes.attributeDisabled || false);
        const attributeMask = fieldAttributes.attributeMask || false;

        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Função para gerar uma string aleatória com letras maiúsculas e números
        const gerarContagemAleatoria = (comprimento) => {
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Letras maiúsculas e números
            let resultado = '';
            for (let i = 0; i < comprimento; i++) {
                resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
            return resultado;
        };

        const viacep = 'https://viacep.com.br/ws/';
        const opencep = 'https://opencep.com/v1/';
        const [cpfDuplicado, setCpfDuplicado] = React.useState(false);
        const [msgError, setMsgError] = React.useState(false);
        const [error, setError] = React.useState('');
        const [valid, setValid] = React.useState(true);
        // const cleanInputOnlyNumber = (value) => value.replace(/\D/g, '');
        const [modalId, setModalId] = React.useState(`modal_form_${gerarContagemAleatoria(3)}`);
        const [modalmessage, setModalMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        const [internalDisabled, setInternalDisabled] = React.useState(false);

        React.useEffect(() => {
            const timer = setInterval(() => {
                setAttributeDisabled(false);
            }, 1000);

            return () => clearInterval(timer);
        }, [attributeDisabled]);

        const finalDisabled = typeof attributeDisabled !== 'undefined'
            ? attributeDisabled
            : internalDisabled;

        {/* APENAS NÚMEROS */ }
        const cleanInputOnlyNumber = (input) => {
            // Verifica se o último caractere digitado é inválido
            const lastChar = input.slice(-1);
            const lastCharIsInvalid = !/^[0-9.-]$/.test(lastChar) && lastChar !== '';

            // Remove caracteres inválidos
            let cleanedInput = input.replace(/[^0-9]/g, '');

            // Se o último caractere for inválido, mostra mensagem
            if (lastCharIsInvalid) {
                // console.log('ERRO - APENAS NÚMEROS');
                let message = errorMessage === '' ? `O Campo ${labelField} aceita apenas números` : errorMessage;
                setAttributeDisabled(true);
                setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                setMsgError(message);
                setModalMessage({
                    show: true,
                    type: 'light',
                    message: message
                });
            }

            return cleanedInput;
        };

        {/* APENAS LETRAS e ESPAÇOS */ }
        const cleanInputOnlyLetter = (input) => {
            // Verifica se o último caractere digitado é inválido
            const lastChar = input.slice(-1);
            const lastCharIsInvalid = !/^[A-Za-zÀ-ÿ\s]$/.test(lastChar) && lastChar !== '';

            // Remove caracteres inválidos
            let cleanedInput = input.replace(/[^A-Za-zÀ-ÿ\s]/g, '');

            // Se o último caractere for inválido, mostra mensagem
            if (lastCharIsInvalid) {
                // console.log('ERRO - APENAS LETRAS e ESPAÇOS');
                let message = errorMessage === '' ? `O Campo ${labelField} aceita apenas letras` : errorMessage;
                setAttributeDisabled(true);
                setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                setMsgError(message);
                setModalMessage({
                    show: true,
                    type: 'light',
                    message: message
                });
            }

            return cleanedInput;
        };

        {/* APENAS LETRAS e NUMEROS */ }
        const cleanInputOnlyLetterNumber = (input) => {
            // Verifica se o último caractere digitado é inválido
            const lastChar = input.slice(-1);
            const lastCharIsInvalid = !/^[a-zA-Z0-9\s]$/.test(lastChar) && lastChar !== '';

            // Remove caracteres inválidos
            let cleanedInput = input.replace(/[^a-zA-Z0-9\s]/g, '');

            // Se o último caractere for inválido, mostra mensagem
            if (lastCharIsInvalid) {
                // console.log('ERRO - APENAS LETRAS e NUMEROS');
                let message = errorMessage === '' ? `O Campo ${labelField} aceita apenas letras e números` : errorMessage;
                setAttributeDisabled(true);
                setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                setMsgError(message);
                setModalMessage({
                    show: true,
                    type: 'light',
                    message: message
                });
            }

            return cleanedInput;
        };

        {/* PERMITE APENAS LETRAS, NUMEROS, PONTO E VIRGULA */ }
        const cleanInput = (input) => {
            // Verifica se o último caractere digitado é inválido
            const lastChar = input.slice(-1);
            const lastCharIsInvalid = !/^[a-zA-Z0-9\s.,]$/.test(lastChar) && lastChar !== '';

            // Remove caracteres inválidos
            let cleanedInput = input.replace(/[^a-zA-Z0-9\s.,]/g, '');

            // Se o último caractere for inválido, mostra mensagem
            if (lastCharIsInvalid) {
                // console.log('ERRO - PERMITE APENAS LETRAS, NUMEROS, PONTO E VIRGULA');
                let message = errorMessage === '' ? `O Campo ${labelField} aceita apenas letras, números, pontos e vírgulas` : errorMessage;
                setAttributeDisabled(true);
                setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                setMsgError(message);
                setModalMessage({
                    show: true,
                    type: 'light',
                    message: message
                });
            }

            return cleanedInput;
        };

        {/* Mascara para RG */ }
        function applyMaskRG(value) {
            let onlyNums = value.replace(/\D/g, '').slice(0, 10);
            let result = '';
            if (onlyNums.length > 0) {
                result = '-' + onlyNums.slice(-1);
                if (onlyNums.length > 1) result = '.' + onlyNums.slice(-4, -1) + result;
                if (onlyNums.length > 4) result = '.' + onlyNums.slice(-7, -4) + result;
                if (onlyNums.length > 7) result = onlyNums.slice(0, -7) + result;
            }
            return result.replace(/^\./, '');
        }

        // Máscara CPF
        const applyMaskCPF = (cpf) => {
            cpf = cleanInputOnlyNumber(cpf)
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            return cpf;
        };

        // Validação CPF
        const isValidCPF = (cpf) => {
            cpf = cleanInputOnlyNumber(cpf);
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
            return resto === parseInt(cpf.substring(10, 11));
        };

        {/* Máscara Certidão */ }
        {/* SSSSSS.AA.BB.AAAA.T.LLLLL.FFF.TTTTTTT-DD */ }
        const applyMaskCertidao = (certidao) => {
            // Remover caracteres não numéricos
            // let cleaned = certidao.replace(/\D/g, '');
            let cleaned = cleanInputOnlyNumber(certidao);

            // Limitar a 32 dígitos
            cleaned = cleaned.substring(0, 32);

            // Array com as posições onde adicionar cada separador
            const positions = [6, 8, 10, 14, 15, 20, 23, 30];
            const separators = ['.', '.', '.', '.', '.', '.', '.', '-'];

            let resultado = '';
            let lastPos = 0;

            // Percorrer cada posição e adicionar o separador correspondente
            for (let i = 0; i < positions.length; i++) {
                const pos = positions[i];

                // Só adiciona se tivermos dígitos suficientes
                if (cleaned.length > lastPos) {
                    // Adiciona os dígitos até esta posição
                    resultado += cleaned.substring(lastPos, Math.min(pos, cleaned.length));

                    // Adiciona o separador se não for o último grupo ou se tivermos mais dígitos
                    if (cleaned.length > pos) {
                        resultado += separators[i];
                    }

                    // Atualiza a última posição processada
                    lastPos = pos;
                } else {
                    break;
                }
            }

            // Adiciona qualquer dígito restante (após a última posição)
            if (cleaned.length > lastPos) {
                resultado += cleaned.substring(lastPos);
            }

            return resultado;
        }

        // Máscara Telefone
        const applyMaskTelefone = (telefone) => {
            telefone = cleanInputOnlyNumber(telefone);
            if (telefone.length === 11) {
                telefone = telefone.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})$/, '($1)$2$3-$4');
            } else if (telefone.length === 10) {
                telefone = telefone.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1)$2-$3');
            }
            return telefone;
        };

        // Máscara CEP
        const applyMaskCEP = (cep) => cleanInputOnlyNumber(cep).replace(/^(\d{5})(\d)/, '$1-$2');

        // Função para validar pelo ViaCEP
        const fetchViaCep = async (setCep) => {
            const url = `${viacep}/${setCep}/json`;
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`OpenCEP fetch failed: ${response.statusText}`);
                }
                const data = await response.json();
                // console.log('OpenCEP Data:', data);
                return true;
            } catch (error) {
                // console.log('Error fetching ViaCEP data:', error);
                return false;
            }
        };

        // Função para validar pelo OpenCEP
        const fetchOpenCep = async (set_cep) => {
            const url = `${opencep}/${set_cep}`;
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`OpenCEP fetch failed: ${response.statusText}`);
                }
                const data = await response.json();
                // console.log('OpenCEP Data:', data);
                return true;
            } catch (error) {
                // console.log('Error fetching OpenCEP data:', error);
                return false;
            }
        };

        // Máscara Processo
        const applyMaskProcesso = (processo) => cleanInputOnlyNumber(processo)
            .replace(/^(\d{7})(\d{2})(\d{4})(\d{1})(\d{2})(\d{4})$/, '$1-$2.$3.$4.$5.$6');

        const isValidTextFilter = (value) => {
            // Verifica se o texto contém caracteres inválidos
            const containsInvalidChars = /[^a-zA-ZÀ-ÖØ-öø-ÿçÇ ]$/u.test(value);

            if (labelColor === 'gray') {
                // Se for cinza, não mostra mensagem, mas remove caractere
                return { shouldShowMessage: false, isValid: !containsInvalidChars };
            } else {
                // Se não for cinza, mostra mensagem e remove caractere
                return { shouldShowMessage: true, isValid: !containsInvalidChars };
            }
        };

        // Função para verificar se o valor é válido para campo numérico
        const isValidNumberFilter = (value) => {
            // Verifica se o texto contém caracteres inválidos para números
            const containsInvalidChars = /[^0-9 ]$/.test(value);

            if (labelColor === 'gray') {
                // Se for cinza, não mostra mensagem, mas remove caractere
                return { shouldShowMessage: false, isValid: !containsInvalidChars };
            } else {
                // Se não for cinza, mostra mensagem e remove caractere
                return { shouldShowMessage: true, isValid: !containsInvalidChars };
            }
        };

        // Função handleFocus para receber foco
        const handleFocus = (event) => {
            const { name, value } = event.target;
            // console.log('src/app/Views/fia/ptpa/camposPadroes/AppText.php')
            // console.log('handleFocus: ', name, value);
            {/* CPF */ }
            if (name == 'CPF' && value !== '') {
                // console.log('handleFocus / CPF: ', name, value);
                setInitialValue(value);
            }

            if (name == 'Certidao' && value !== '') {
                // console.log('handleFocus / Certidao: ', name, value);
                setInitialValue(value);
            }

            setFormData((prev) => ({ ...prev, [name]: value }));
        };

        // handleChange
        const handleChange = (event) => {
            const { name, value } = event.target;
            // console.log('name, value :: ', name, value);

            // Fecha qualquer modal aberto antes de fazer a validação
            if (!value) {
                setFormData((prev) => ({ ...prev, [name]: '' }));
                setMsgError(false);
                return;
            };

            // Trata Nome
            if (name === 'Nome') {
                const cleanedValue = cleanInputOnlyLetter(value);
                setFormData((prev) => ({
                    ...prev,
                    [name]: cleanedValue,
                }));
                return;
            }

            // Trata Nome da escola
            if (name === 'NomeEscola') {
                const cleanedValue = cleanInputOnlyLetter(value);
                setFormData((prev) => ({
                    ...prev,
                    [name]: cleanedValue,
                }));
                return;
            }

            // Trata Logradouro
            if (name === 'Logradouro') {
                const cleanedValue = cleanInput(value);
                setFormData((prev) => ({
                    ...prev,
                    [name]: cleanedValue,
                }));
                return;
            }

            // Trata Complemento
            if (name === 'Complemento') {
                const cleanedValue = cleanInput(value);
                setFormData((prev) => ({
                    ...prev,
                    [name]: cleanedValue,
                }));
                return;
            }

            // Verifica se o valor realmente mudou antes de atualizar o state
            if (formData[name] === value) return;

            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));

            // console.log("valid :: ", valid);

            switch (attributePattern) {
                case 'Inteiro':
                    const maskedValueInteiro = cleanInputOnlyNumber(value)
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueInteiro,
                    }));
                    // console.log('Inteiro:', value);
                    break;

                case 'Caracter':
                    const maskedValueChar = cleanInputOnlyLetter(value)
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueChar,
                    }));
                    // console.log('Caracter:', value);
                    break;

                case 'Senha':
                    setFormData((prev) => ({
                        ...prev,
                        [name]: value,
                    }));
                    // console.log('Senha:', value);
                    break;

                default:
                    break;
            };

            switch (attributeMask) {
                case 'RG':
                    const maskedValueRG = applyMaskRG(value);
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueRG,
                    }));
                    // console.log('Telefone:', value);
                    break;

                case 'Telefone':
                    const maskedValueTel = applyMaskTelefone(value);
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueTel,
                    }));
                    // console.log('Telefone:', value);
                    break;

                case 'CPF':
                    const maskedValueCPF = applyMaskCPF(value);
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueCPF,
                    }));
                    // console.log('CPF:', value);
                    break;

                case 'CEP':
                    const maskedValueCEP = applyMaskCEP(value);
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueCEP,
                    }));
                    // console.log('CEP:', value);
                    break;

                case 'Processo':
                    const maskedValueProc = applyMaskProcesso(value);
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueProc,
                    }));
                    // console.log('Processo:', value);
                    break;

                case 'Certidao':
                    // Somente preenche os campos se tiver o comprimento adequado
                    // console.log('PASSOU AQUI');

                    const maskedValueCert = applyMaskCertidao(value);
                    // console.log("maskedValue :: ", maskedValueCert);
                    const cleanedValue = cleanInputOnlyNumber(value);

                    if (cleanedValue.length >= 32) {
                        // console.log('Aqui tem 32')
                        // Extraindo os valores conforme as posições especificadas
                        const serventiaCode = cleanedValue.substring(0, 6); // posições 1-6
                        const acervoCode = cleanedValue.substring(6, 8);    // posições 7-8
                        const rcpnCode = cleanedValue.substring(8, 10);     // posições 9-10
                        const anoRegistro = cleanedValue.substring(10, 14); // posições 11-14
                        const tipoLivro = cleanedValue.substring(14, 15);   // posição 15
                        const numeroLivro = cleanedValue.substring(15, 20); // posições 16-20
                        const numeroFolha = cleanedValue.substring(20, 23); // posições 21-23
                        const numeroTermo = cleanedValue.substring(23, 30); // posições 24-30
                        const digitoVerificador = cleanedValue.substring(30, 32); // posições 31-32

                        // console.log('serventiaCode :: ', serventiaCode);
                        // console.log('acervoCode :: ', acervoCode);
                        // console.log('rcpnCode :: ', rcpnCode);
                        // console.log('anoRegistro :: ', anoRegistro);
                        // console.log('tipoLivro :: ', tipoLivro);
                        // console.log('numeroLivro :: ', numeroLivro);
                        // console.log('numeroFolha :: ', numeroFolha);
                        // console.log('numeroTermo :: ', numeroTermo);
                        // console.log('digitoVerificador :: ', digitoVerificador);

                        // Atualiza o formData com os campos específicos
                        setFormData((prev) => ({
                            ...prev,
                            [name]: maskedValueCert,
                            NumRegistro: numeroTermo,        // Número do termo (deve ser esse)
                            Folha: numeroFolha,              // Número da folha
                            Livro: numeroLivro,              // Número do livro
                            Circunscricao: serventiaCode,    // Código da Serventia (Circunscrição)
                            Zona: acervoCode,                // Código do acervo
                            // Se quiser adicionar mais campos:
                            // TipoLivro: tipoLivro,
                            // AnoRegistro: anoRegistro,
                            // CodigoRCPN: rcpnCode,
                            // DigitoVerificador: digitoVerificador
                        }));
                    } else {
                        setFormData((prev) => ({
                            ...prev,
                            [name]: maskedValueCert,
                        }));
                    }
                    break;

                default:
                    break;
            }
        };

        const handleBlur = async (event) => {
            // setModalMessage({ show: false, type: 'light', message: '' });
            const { name, value } = event.target;
            // console.log("-------------------------");
            // console.log("handleBlur");
            // console.log("-------------------------");
            // console.log("name :: ", name);
            // console.log("value :: ", value);
            // console.log("attributeMinlength :: ", attributeMinlength);
            let message = errorMessage === '' ? `Por favor, informe um ${attributeMask} válido.` : errorMessage;
            let isValid = true;
            let dataColuna = {};

            // console.log('------------');
            // console.log('errorMessage :: ', errorMessage);

            if (labelColor !== 'gray') {
                const countValue = value;
                if (
                    countValue.length > 0 &&
                    countValue.length < attributeMinlength
                ) {
                    // console.log('------------------------');
                    // console.log('countValue.length < attributeMinlength');

                    if (errorMessage !== '') {
                        message = errorMessage;
                    } else {
                        message = `O Campo ${labelField} devem ter entre ${attributeMinlength} e ${attributeMaxlength} caracteres`;
                    }

                    setAttributeDisabled(true);
                    setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                    setMsgError(message);
                    setModalMessage({
                        show: true,
                        type: 'light',
                        message: message
                    });
                }
            }

            switch (attributeMask) {
                // Telefone
                case 'Telefone':
                    isValid = cleanInputOnlyNumber(value).length >= 10;
                    break;

                    {/* CPF */ }
                case 'CPF':
                    // console.log('attributeMask :: ', attributeMask);
                    if (labelColor === 'gray') {
                        // console.log('------------------------');
                        // console.log('labelColor === gray');
                        break;
                    }
                    isValid = isValidCPF(value);
                    // console.log('HOP-562');
                    if (
                        value.length > 0 &&
                        !isValid &&
                        labelColor !== 'gray'
                    ) {
                        // console.log('------------------------');
                        // console.log(`!isValid &&`);
                        // console.log(`labelColor !== 'gray'`);
                        setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                        let message = errorMessage === '' ? `Campo ${labelField} inválido` : errorMessage;
                        setAttributeDisabled(true);
                        setMsgError(message);
                        setModalMessage({
                            show: true,
                            type: 'light',
                            message: message
                        });
                        setFormData((prev) => ({
                            ...prev,
                            CPF: '',
                        }));
                        break;
                    }
                    {/* CPF DUPLICADO */ }
                    dataColuna = (name === 'CPF') ? ({ CPF: value }) : ('');
                    const isDuplicadoCPF = await fetchCadastro(dataColuna);
                    // console.log('isDuplicadoCPF :: ', isDuplicadoCPF);
                    // console.log('InitialValue :: ', InitialValue);
                    // console.log('value :: ', value);
                    // console.log('labelColor :: ', labelColor);
                    // console.log('HOP-1');

                    if (
                        isDuplicadoCPF &&
                        name === 'CPF' &&
                        labelColor !== 'gray' &&
                        InitialValue !== value
                    ) {
                        // console.log('------------------------');
                        // console.log(`isDuplicadoCPF &&`);
                        // console.log(`name :: `, name);
                        // console.log(`labelColor !== 'gray' &&`);
                        // console.log(`InitialValue !== value`);
                        setModalId(`modal_form_cpf_duplicado${gerarContagemAleatoria(6)}`);
                        let message = errorMessage === '' ? `Campo ${labelField} Duplicado` : errorMessage;
                        // console.log('HOP-2');
                        if (
                            checkWordInArray(getURI, 'cadastrar') &&
                            labelColor !== 'gray' ||
                            checkWordInArray(getURI, 'drupal') &&
                            labelColor !== 'gray'
                        ) {
                            setAttributeDisabled(true);
                            setMsgError(message);
                            setModalMessage({
                                show: true,
                                type: 'light',
                                message: message
                            });
                            setFormData((prev) => ({
                                ...prev,
                                CPF: '',
                            }));
                            break;
                        }
                        // console.log('HOP-3');

                        if (
                            checkWordInArray(getURI, 'atualizar') &&
                            value !== InitialValue &&
                            labelColor !== 'gray'
                        ) {
                            // console.log('------------------------');
                            // console.log(`checkWordInArray(getURI, 'atualizar')`);
                            // console.log(`value !== InitialValue`);
                            // console.log(`labelColor !== 'gray'`);
                            setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                            setAttributeDisabled(true);
                            setMsgError(message);
                            setModalMessage({
                                show: true,
                                type: 'light',
                                message: message
                            });
                            setFormData((prev) => ({
                                ...prev,
                                CPF: '',
                            }));
                            break;
                        }
                    }

                    if (
                        formData['CPF'] !== "" &&
                        formData['Responsavel_CPF'] !== "" &&
                        formData['CPF'] === formData['Responsavel_CPF'] &&
                        labelColor !== 'gray' &&
                        InitialValue !== value
                    ) {
                        let message = errorMessage === '' ? `Campo ${labelField} do Adolescente é igual ao Campo CPF do Responsável` : errorMessage;
                        if (
                            checkWordInArray(getURI, 'cadastrar') &&
                            labelColor !== 'gray' ||
                            checkWordInArray(getURI, 'drupal') &&
                            labelColor !== 'gray'
                        ) {
                            setAttributeDisabled(true);
                            setMsgError(message);
                            setModalMessage({
                                show: true,
                                type: 'light',
                                message: message
                            });
                            setFormData((prev) => ({
                                ...prev,
                                CPF: '',
                                Responsavel_CPF: '',
                            }));
                            break;
                        }

                        if (
                            checkWordInArray(getURI, 'atualizar') &&
                            value !== InitialValue &&
                            labelColor !== 'gray'
                        ) {
                            setAttributeDisabled(true);
                            setMsgError(message);
                            setModalMessage({
                                show: true,
                                type: 'light',
                                message: message
                            });
                            setFormData((prev) => ({
                                ...prev,
                                CPF: '',
                                Responsavel_CPF: '',
                            }));
                            break;
                        }
                    }
                    // console.log('HOP-FIM');
                    break;

                // CEP
                case 'CEP':
                    const cleanedCEP = cleanInputOnlyNumber(value);
                    // console.log("name, value :: ", name, value);
                    // console.log("name, value :: ", cleanedCEP);
                    // console.log("cleanedCEP.length :: ", cleanedCEP.length);
                    if (cleanedCEP.length !== 8 && cleanedCEP.length > 0) {
                        // Mostra mensagem de erro para CEPs incompletos
                        setModalId(`modal_form_${gerarContagemAleatoria(4)}`);
                        isValid = false;
                        let message = `O Campo ${labelField} deve ter 8 Números`;
                        // console.log('------------------------');
                        // console.log('message :: ', message);
                        setModalMessage({
                            show: true,
                            type: 'light',
                            message: message
                        });
                        console.error(message);
                    } else if (cleanedCEP.length === 8) {
                        try {
                            await fetchGetCEP(cleanedCEP);
                        } catch (error) {
                            isValid = false;
                        }
                    }
                    break;

                // Processo
                case 'Processo':
                    isValid = /^\d{7}-\d{2}\.\d{4}\.\d{1}\.\d{2}\.\d{4}$/.test(value);
                    break;

                // Certidao
                case 'Certidao':

                    if (
                        labelColor === 'gray' ||
                        InitialValue == value

                    ) {
                        // console.log('------------------------');
                        // console.log('Certidao');
                        // console.log('value :: ', value);
                        // console.log('InitialValue :: ', InitialValue);
                        break;
                    }
                    {/* CERTIDAO DUPLICADA */ }
                    dataColuna = (name === 'Certidao') ? ({ Certidao: value }) : ('');
                    const isDuplicadoCertidao = await fetchCadastro(dataColuna);
                    // console.log('------------------------');
                    // console.log('Certidao');
                    // console.log('isDuplicadoCertidao :: ', isDuplicadoCertidao);
                    // console.log('InitialValue :: ', InitialValue);
                    // console.log('value :: ', value);

                    if (isDuplicadoCertidao && name === 'Certidao' && labelColor !== 'gray' && InitialValue !== value) {
                        let message = errorMessage === '' ? `Campo ${labelField} Duplicado` : errorMessage;
                        if (
                            checkWordInArray(getURI, 'cadastrar') &&
                            labelColor !== 'gray' ||
                            checkWordInArray(getURI, 'drupal') &&
                            labelColor !== 'gray'
                        ) {
                            setModalId(`modal_form_cpf_duplicado${gerarContagemAleatoria(6)}`);
                            setAttributeDisabled(true);
                            setMsgError(message);
                            setModalMessage({
                                show: true,
                                type: 'light',
                                message: message
                            });
                            setFormData((prev) => ({
                                ...prev,
                                Certidao: '',
                                NumRegistro: '',
                                Folha: '',
                                Livro: '',
                                Circunscricao: '',
                                Zona: '',
                            }));
                            break;
                        }

                        if (
                            checkWordInArray(getURI, 'atualizar') &&
                            value !== InitialValue &&
                            labelColor !== 'gray'
                        ) {
                            setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                            setAttributeDisabled(true);
                            setMsgError(message);
                            setModalMessage({
                                show: true,
                                type: 'light',
                                message: message
                            });
                            setFormData((prev) => ({
                                ...prev,
                                Certidao: '',
                            }));
                            break;
                        }
                    }
                    break;

                default:
                    break;
            }

            // console.log('isValid(194) ::', isValid);

            if (isValid) {
                // console.log(`Campo(if) ${attributeMask}`);
                setMsgError(false);
            } else {
                // console.log(`Campo(else) ${attributeMask}`);
                setMsgError(message);
            }
        };

        // Fetch para obter os Cadastros
        const fetchCadastro = async (dataColuna) => {
            // console.log('dataColuna :: ', dataColuna)
            {/* BUSCA CADASTRO */ }
            let coluna = '';
            let inputCPF = '';
            let inputCertidao = '';

            // Verificar se o objeto data contém a propriedade CPF ou Certidao
            if (
                dataColuna.CPF !== undefined &&
                dataColuna.CPF !== null &&
                dataColuna.CPF.length > 0 &&
                /^\d{3}\.\d{3}\.\d{3}-\d{2}$/.test(dataColuna.CPF)
            ) {
                // Lógica para busca por CPF
                coluna = 'CPF';
                // console.log("Buscando por CPF:", dataColuna.CPF);
                // Continuar com a lógica específica para CPF...
            } else if (dataColuna.Certidao !== undefined) {
                // Lógica para busca por Certidão
                coluna = 'Certidao';
                // console.log("Buscando por Certidão:", dataColuna.Certidao);
                // Continuar com a lógica específica para Certidão...
            } else {
                // Caso nenhum dos dois seja fornecido
                // console.log("Tipo de dado não identificado");
                return null; // ou throw new Error("Tipo de dado inválido");
            }

            try {
                // console.log("parametros :: ", parametros);

                const response = await fetch(base_url + 'index.php/fia/ptpa/cadGeral/api/filtrar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataColuna)
                });
                const data = await response.json();

                // console.log('data: ', data);

                if (
                    coluna === 'CPF' &&
                    data.result &&
                    data.result.dbResponse &&
                    data.result.dbResponse.length > 0
                ) {
                    // Se estiver atualizando e o CPF for o mesmo que já estava no registro
                    if (InitialValue === dataColuna.CPF) {
                        // Não faz nada, mantém o mesmo CPF
                        return true;
                    }

                    // Verifica se está na página de atualização de adolescente ou profissional
                    if (
                        checkWordInArray(getURI, 'atualizar') &&
                        (checkWordInArray(getURI, 'adolescente') || checkWordInArray(getURI, 'profissional'))
                    ) {
                        inputCPF = data.CPF;
                    } else {
                        // Se for cadastro ou outro tipo de atualização, o CPF não pode ser duplicado
                        inputCPF = '';
                    }

                    setFormData((prev) => ({
                        ...prev,
                        ['CPF']: inputCPF
                    }));
                    return true;
                }


            } catch (error) {
                // Função para exibir o alerta (success, danger, warning, info)
                // console.log(error.message);
            }
        };

        // Fetch para GET
        const fetchGetCEP = async (cleanedCEP) => {
            const url = `${viacep}${cleanedCEP}/json`;
            // console.log('fetchGetCEP URL ::', url);
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.erro) {
                    setModalId(`modal_form_${gerarContagemAleatoria(3)}`);
                    setMsgError('CEP inválido');
                    setModalMessage({
                        show: true,
                        type: 'light',
                        message: 'CEP inválido'
                    });
                    return false;
                }
                if (data.uf && data.localidade && data.uf !== 'RJ') {
                    setModalId(`modal_form_${gerarContagemAleatoria(3)}`);
                    const msg = `A Localidade ${data.localidade}, ${data.uf}, não participa do projeto FIA/PTPA. Um CEP do Estado do Rio de Janeiro deve ser informado.`;
                    setMsgError(msg);
                    setModalMessage({
                        show: true,
                        type: 'light',
                        message: msg
                    });
                    return false;
                }

                setFormData((prev) => ({
                    ...prev,
                    CEP: data.cep,
                    Logradouro: data.logradouro,
                    Bairro: data.bairro,
                    Municipio: data.localidade,
                    Estado: data.estado,
                    UF: data.uf,
                    DDD: data.ddd,
                    GIA: data.gia,
                    IBGE: data.ibge,
                    Regiao: data.regiao,
                    SIAFI: data.siafi,
                    checkMunicipio: false,
                    dropMunicipio: false,
                }));
                // console.log('CEP fetchGetCEP ::', data);
                return false;
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // 
                    setApiUrlList(dbResponse);
                    setPagination('list');
                    //
                    setFormData((prev) => ({
                        ...prev,
                        ...dbResponse
                    }));
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas objeto cadastradas'
                    });
                    setIsLoading(false);
                }

                if (data.result && Array.isArray(data.result.dbResponse) && data.result.linksArray.length > 0) {
                    setPaginacaoLista(data.result.linksArray);
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Unidades: ' + error.message
                });
            }
        };

        // Style 
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
            color: labelColor
        };

        const requiredField = {
            color: '#FF0000',
        };

        const fontErro = {
            fontSize: '0.7em',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                <div style={formGroupStyle}>
                    <label
                        htmlFor={nameField}
                        style={formLabelStyle}
                        className="form-label"
                    >
                        {labelField}
                        {(attributeRequired) && (
                            <strong style={requiredField}>*</strong>
                        )}
                    </label>
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="text"
                        className={`form-control form-control-sm ${error ? 'is-invalid' : formData[name] ? 'is-valid' : ''}`}
                        style={formControlStyle}
                        id={nameField}
                        name={nameField}
                        value={formData[nameField] || ''}
                        placeholder={attributePlaceholder}
                        minLength={attributeMinlength}
                        maxLength={attributeMaxlength}
                        autoComplete={attributeAutocomplete}
                        required={attributeRequired}
                        disabled={attributeDisabled}
                        readOnly={attributeReadOnly}
                        list={`${name}-options`}
                        onFocus={handleFocus}
                        onChange={handleChange}
                        onBlur={handleBlur}
                    />
                    <datalist id={`${name}-options`}>
                        <option value=""></option>
                    </datalist>
                </div>
                {(
                    msgError &&
                    !checkWordInArray(getURI, 'fia') &&
                    !checkWordInArray(getURI, 'ptpa')
                ) && (
                        <div className="fw-light text-danger" style={fontErro}>
                            {errorMessage && (
                                <div>
                                    {msgError}
                                </div>
                            )}
                        </div>
                    )}

                {
                    typeof AppMessageCard !== "undefined" ? (
                        <div>
                            <AppMessageCard
                                parametros={modalmessage}
                                modalId={modalId}
                            />
                        </div>
                    ) : (
                        <div>
                            <p className="text-danger">AppMessageCard não lacançado.</p>
                        </div>
                    )}
            </div>
        );
    };

</script>