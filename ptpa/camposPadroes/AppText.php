<script type="text/babel">
    const AppText = ({
        submitAllForms,
        parametros = {},
        formData = {},
        setFormData = () => { },
        fieldAttributes = {}
    }) => {

        const getURI = parametros.getURI || [];
        const base_url = parametros.base_url || '';
        const ufCertidao = parametros.ufCertidao || [];
        const municipioCertidao = parametros.municipioCertidao || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const [InitialValue, setInitialValue] = React.useState('');
        // console.log('ufCertidao :: ', ufCertidao);
        // console.log('municipioCertidao :: ', municipioCertidao);
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
        const [attributeRequired, setAttributeRequired] = React.useState(false);
        const [attributeReadOnly, setAttributeReadOnly] = React.useState(false);
        const [attributeDisabled, setAttributeDisabled] = React.useState(false);
        const attributeMask = fieldAttributes.attributeMask || false;

        const [constMinlength, setConstMinlength] = React.useState(attributeMinlength);
        const [constMaxlength, setConstMaxlength] = React.useState(attributeMaxlength);

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

        const [certidaoNova, setCertidaoNova] = React.useState({
            cert_nova_p_01_06: '',
            cert_nova_p_07: '',
            cert_nova_p_08_15: '',
            cert_nova_p_16_17: '',
            cert_nova_p_18_21: '',
            cert_nova_p_22_23: '',
            cert_nova_p_24_26: '',
            cert_nova_p_27_32: '',
        });

        {/* Data Registro (AAAAMMDD) / IBGE UF / IBGE Municipio */ }
        const [validP0815NovaDataRegistro, setValidP0815NovaDataRegistro] = React.useState(false);
        const [validP1617NovaUfIbge, setValidP1617NovaUfIbge] = React.useState(false);
        const [validP1821NovaMunicipioIbge, setValidP1821NovaMunicipioIbge] = React.useState(false);

        const [certidaoAntiga, setCertidaoAntiga] = React.useState({
            cert_antiga_p_01_06: '',
            cert_antiga_p_07_08: '',
            cert_antiga_p_09_12: '',
            cert_antiga_p_13: '',
            cert_antiga_p_14_18: '',
            cert_antiga_p_19_21: '',
            cert_antiga_p_22_28: '',
            cert_antiga_p_29_30: '',
            cert_antiga_p_31_32: '',
        });

        {/* IBGE UF / Ano Registro (1900 > ) / Tipo Certidão 1 */ }
        const [validP0708AntigaUfIbge, setValidP0708AntigaUfIbge] = React.useState(false);
        const [validP0912AntigaAnoRegistro, setValidP0912AntigaAnoRegistro] = React.useState(false);
        const [validP13AntigaTipoCertidao, setValidP13AntigaTipoCertidao] = React.useState(false);

        const atualizarCertidaoNova = (input = '') => ({
            cert_nova_p_01_06: input.length >= 6 ? input.slice(0, 6) : '',
            cert_nova_p_07: input.length >= 7 ? input.slice(6, 7) : '',
            cert_nova_p_08_15: input.length >= 15 ? input.slice(7, 15) : '',
            cert_nova_p_16_17: input.length >= 17 ? input.slice(15, 17) : '',
            cert_nova_p_18_21: input.length >= 21 ? input.slice(17, 21) : '',
            cert_nova_p_22_23: input.length >= 23 ? input.slice(21, 23) : '',
            cert_nova_p_24_26: input.length >= 26 ? input.slice(23, 26) : '',
            cert_nova_p_27_32: input.length >= 32 ? input.slice(26, 32) : '',
        });

        const atualizarCertidaoAntiga = (input = '') => ({
            cert_antiga_p_01_06: input.length >= 6 ? input.slice(0, 6) : '',
            cert_antiga_p_07_08: input.length >= 8 ? input.slice(6, 8) : '',
            cert_antiga_p_09_12: input.length >= 12 ? input.slice(8, 12) : '',
            cert_antiga_p_13: input.length >= 13 ? input.slice(12, 13) : '',
            cert_antiga_p_14_18: input.length >= 18 ? input.slice(13, 18) : '',
            cert_antiga_p_19_21: input.length >= 21 ? input.slice(18, 21) : '',
            cert_antiga_p_22_28: input.length >= 28 ? input.slice(21, 28) : '',
            cert_antiga_p_29_30: input.length >= 30 ? input.slice(28, 30) : '',
            cert_antiga_p_31_32: input.length >= 32 ? input.slice(30, 32) : '',
        });

        const [tipoCertidao, setTipoCertidao] = React.useState('certIndefinida');

        React.useEffect(() => {
            const timer1 = setInterval(() => {
                setAttributeDisabled(false);
            }, 1000);
            // const timer2 = setInterval(() => {
            // setError('');
            // }, 1000);

            return () => {
                clearInterval(timer1);
                // clearInterval(timer2);
            };
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
                setError(message);

                setTimeout(() => {
                    setError('');
                }, 4000);

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
                // console.log('message :: ', message);
                setAttributeDisabled(true);
                setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                setMsgError(message);
                setModalMessage({
                    show: true,
                    type: 'light',
                    message: message
                });
                setError(message);

                setTimeout(() => {
                    setError('');
                }, 4000);
            }

            return cleanedInput;
        };

        {/* APENAS NÚMEROS, PONTO e HÍFEN */ }
        const cleanInputMask = (input) => {

            // Verifica se o último caractere digitado é inválido
            const lastChar = input.slice(-1);

            // Apenas números, ponto e hífen
            const lastCharIsInvalid = !/^[0-9.\-]$/.test(lastChar) && lastChar !== '';

            // Remove caracteres inválidos (agora permite só números, ponto e hífen)
            let cleanedInput = input.replace(/[^0-9.\-]/g, '');

            // Se o último caractere for inválido, mostra mensagem
            if (lastCharIsInvalid) {
                // console.log('ERRO - APENAS LETRAS, NÚMEROS, PONTO E HÍFEN');
                let message = errorMessage === '' ? `O Campo ${labelField} aceita apenas, números, ponto (.) e hífen (-)` : errorMessage;
                setError(message);
                setAttributeDisabled(true);
                setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                setMsgError(message);
                setModalMessage({
                    show: true,
                    type: 'light',
                    message: message
                });

                setTimeout(() => {
                    setError('');
                }, 4000);
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
                setError(message);
                setAttributeDisabled(true);
                setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                setMsgError(message);
                setModalMessage({
                    show: true,
                    type: 'light',
                    message: message
                });

                setTimeout(() => {
                    setError('');
                }, 4000);
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
                setError(message);

                setTimeout(() => {
                    setError('');
                }, 4000);
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
        const applyMaskCertidaoNova = (certidao) => {
            // Remover caracteres não numéricos
            // let cleaned = certidao.replace(/\D/g, '');
            let cleaned = cleanInputOnlyNumber(certidao);

            // Limitar a 32 dígitos
            cleaned = cleaned.substring(0, 32);

            // Array com as posições onde adicionar cada separador
            const positions = [6, 7, 15, 17, 21, 23, 26, 32];
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

        const applyMaskCertidaoAntiga = (certidao) => {
            // Remover caracteres não numéricos
            // let cleaned = certidao.replace(/\D/g, '');
            let cleaned = cleanInputOnlyNumber(certidao);

            // Limitar a 32 dígitos
            cleaned = cleaned.substring(0, 32);

            // Array com as posições onde adicionar cada separador
            const positions = [6, 8, 12, 13, 18, 21, 28, 30, 32];
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

        {/* CERTIDÃO (ANTIGO/NOVO) - DECREPTO*/ }
        const certidaoAntigoNovo = (nCertidao) => {
            // console.log('Iniciando análise do número:', nCertidao)
            if (typeof nCertidao !== 'string' || nCertidao.length !== 32) {
                // console.log('Tamanho incorreto:', nCertidao.length)
                return 'erro'
            }
            let antigo = true
            let novo = true

            let anoAtual = new Date().getFullYear()

            // Modelo Novo
            // console.log('-----Modelo Novo-----')
            let p16 = nCertidao.substring(0, 6)
            // console.log('Novo [1–6] Código da Serventia:', p16, /^\d{6}$/.test(p16) ? 'certo' : 'errado')
            if (!/^\d{6}$/.test(p16)) novo = false

            let p7 = nCertidao.substring(6, 7)
            // console.log('Novo [7] Dígito verificador:', p7, /^\d$/.test(p7) ? 'certo' : 'errado')
            if (!/^\d$/.test(p7)) novo = false

            let p815 = nCertidao.substring(7, 15)
            let anoNovo = Number(p815.substring(0, 4))
            let mesNovo = Number(p815.substring(4, 6))
            let diaNovo = Number(p815.substring(6, 8))
            let dataValidaNovo = false

            if (/^\d{8}$/.test(p815)) {
                let data = new Date(anoNovo, mesNovo - 1, diaNovo)
                dataValidaNovo = data.getFullYear() === anoNovo && data.getMonth() === mesNovo - 1 && data.getDate() === diaNovo
                if (anoNovo < 2000 || anoNovo > anoAtual) dataValidaNovo = false
            }
            // console.log('Novo [8–15] Data do Registro (AAAAMMDD):', p815, dataValidaNovo ? 'certo' : 'errado')
            if (!dataValidaNovo) novo = false

            let p1617 = nCertidao.substring(15, 17)
            // console.log('Novo [16–17] UF (código IBGE):', p1617, /^\d{2}$/.test(p1617) ? 'certo' : 'errado')
            if (!/^\d{2}$/.test(p1617)) novo = false

            let p1821 = nCertidao.substring(17, 21)
            // console.log('Novo [18–21] Município (código IBGE):', p1821, /^\d{4}$/.test(p1821) ? 'certo' : 'errado')
            if (!/^\d{4}$/.test(p1821)) novo = false

            let p2223 = nCertidao.substring(21, 23)
            // console.log('Novo [22–23] Livro:', p2223, /^\d{2}$/.test(p2223) ? 'certo' : 'errado')
            if (!/^\d{2}$/.test(p2223)) novo = false

            let p2426 = nCertidao.substring(23, 26)
            // console.log('Novo [24–26] Folha:', p2426, /^\d{3}$/.test(p2426) ? 'certo' : 'errado')
            if (!/^\d{3}$/.test(p2426)) novo = false

            let p2732 = nCertidao.substring(26, 32)
            // console.log('Novo [27–32] Termo de Registro:', p2732, /^\d{6}$/.test(p2732) ? 'certo' : 'errado')
            if (!/^\d{6}$/.test(p2732)) novo = false

            // Modelo Antigo
            // console.log('-----Modelo Antigo-----')
            let a16 = nCertidao.substring(0, 6)
            // console.log('Antigo [1–6] Zona/Cartório:', a16, /^\d{6}$/.test(a16) ? 'certo' : 'errado')
            if (!/^\d{6}$/.test(a16)) antigo = false

            let a78 = nCertidao.substring(6, 8)
            // console.log('Antigo [7–8] UF:', a78, /^\d{2}$/.test(a78) ? 'certo' : 'errado')
            if (!/^\d{2}$/.test(a78)) antigo = false

            let a912 = nCertidao.substring(8, 12)
            let anoAntigo = parseInt(a912)
            let anoAntigoValido = /^\d{4}$/.test(a912) && anoAntigo >= 1900 && anoAntigo <= anoAtual
            // console.log('Antigo [9–12] Ano de registro:', a912, anoAntigoValido ? 'certo' : 'errado')
            if (!anoAntigoValido) antigo = false

            let a13 = nCertidao.substring(12, 13)
            // console.log('Antigo [13] Tipo de certidão:', a13, /^[1-9]$/.test(a13) ? 'certo' : 'errado')
            if (!/^[1-9]$/.test(a13)) antigo = false

            let a1418 = nCertidao.substring(13, 18)
            // console.log('Antigo [14–18] Livro:', a1418, /^\d{5}$/.test(a1418) ? 'certo' : 'errado')
            if (!/^\d{5}$/.test(a1418)) antigo = false

            let a1921 = nCertidao.substring(18, 21)
            // console.log('Antigo [19–21] Folha:', a1921, /^\d{3}$/.test(a1921) ? 'certo' : 'errado')
            if (!/^\d{3}$/.test(a1921)) antigo = false

            let a2228 = nCertidao.substring(21, 28)
            // console.log('Antigo [22–28] Termo de Registro:', a2228, /^\d{7}$/.test(a2228) ? 'certo' : 'errado')
            if (!/^\d{7}$/.test(a2228)) antigo = false

            let a2930 = nCertidao.substring(28, 30)
            let dvOk = a2930 === '' || /^\d{2}$/.test(a2930)
            // console.log('Antigo [29–30] DV (Dígito Verificador):', a2930, dvOk ? 'certo' : 'errado')
            if (!(a2930 === '' || /^\d{2}$/.test(a2930))) antigo = false

            let a3132 = nCertidao.substring(30, 32)
            // console.log('Antigo [31–32] Reservado:', a3132, 'ignorado')

            if (novo && !antigo) return 'novo'
            if (antigo && !novo) return 'antigo'
            return 'erro'
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
            // console.log('fetchViaCep :: ', setCep);
            const url = `${viacep}/${setCep}/json`;
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`OpenCEP fetch failed: ${response.statusText}`);
                }
                const data = await response.json();
                // console.log('fetchViaCep Data:', data);
                return true;
            } catch (error) {
                // console.log('Error fetching ViaCEP data:', error);
                return false;
            }
        };

        // Função para validar pelo OpenCEP
        const fetchOpenCep = async (set_cep) => {
            // console.log('fetchOpenCep :: ', set_cep);
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
            setFormData((prev) => ({ ...prev, [name]: value }));
            // console.log('src/app/Views/fia/ptpa/camposPadroes/AppText.php')
            // console.log('handleFocus: ', name, value);
            {/* CPF */ }
            if (name === 'CPF' && value !== '') {
                // console.log('handleFocus / CPF: ', name, value);
                setInitialValue(value);
                return true;
            }
            if (name === 'Certidao') {
                setConstMaxlength(32);
            }

            if (name === 'Certidao' && value !== '') {
                // console.log('handleFocus / Certidao: ', name, value);
                const certCleanInput = cleanInputOnlyNumber(value);
                setInitialValue(certCleanInput);
                setFormData((prev) => ({
                    ...prev,
                    [name]: certCleanInput,
                }));
                // console.log(' --- --- --- --- --- ');
                // console.log('name :: ', name);
                // console.log('certCleanInput :: ', certCleanInput);
                return true;
            }

        };

        // handleChange
        const handleChange = (event) => {
            const { name, value } = event.target;
            // console.log('name, value :: ', name, value);
            if (name === 'Certidao') {
                // console.log('--- --- --- --- --- ---');
                // console.log('handleChange / Certidao: ', value.length);
                const certCleanInput = cleanInputOnlyNumber(value);
                const limitedValue = certCleanInput.slice(0, 32);
                setFormData((prev) => ({ ...prev, [name]: limitedValue }));
            }

            // Fecha qualquer modal aberto antes de fazer a validação
            setError('');
            if (!value) {
                setFormData((prev) => ({ ...prev, [name]: '' }));
                setMsgError(false);
                return;
            };

            // Trata ExpedidorRG
            if (name === 'ExpedidorRG') {
                // console.log(`name === 'ExpedidorRG'`);
                const cleanedValue = cleanInputOnlyLetter(value);
                setFormData((prev) => ({
                    ...prev,
                    [name]: cleanedValue,
                }));
                setError('');
                return;
            }

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
                    // console.log('ERROR :: ', error);
                    const maskedValueInteiro = cleanInputOnlyNumber(value)
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueInteiro,
                    }));
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

                    // console.log(' --- --- --- ---');
                    // console.log('DEBUG - Certidão');
                    // console.log('labelColor :: ', labelColor);
                    // console.log('name :: ', name);
                    if (
                        labelColor === 'gray'
                        && name === 'Certidao'
                    ) {
                        const maskedValueProc = cleanInputMask(value);
                        setFormData((prev) => ({
                            ...prev,
                            [name]: maskedValueProc,
                        }));
                        break;
                    }

                    // console.log('case - handleFocus \'Certidão\'');
                    const maskedValueCert = cleanInputOnlyNumber(value);
                    setFormData((prev) => ({
                        ...prev,
                        [name]: maskedValueCert,
                    }));

                    setCertidaoNova(atualizarCertidaoNova(maskedValueCert));
                    setCertidaoAntiga(atualizarCertidaoAntiga(maskedValueCert));
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
            // console.log("attributeMask :: ", attributeMask);
            // console.log("constMinlength :: ", constMinlength);
            let message = errorMessage === '' ? `Por favor, informe um ${attributeMask} válido.` : errorMessage;
            let isValid = true;
            let dataColuna = {};

            // console.log('------------');
            // console.log('errorMessage :: ', errorMessage);

            if (
                labelColor !== 'gray'
                && name !== 'Certidao'
            ) {
                const countValue = value;
                if (
                    countValue.length > 0 &&
                    countValue.length < constMinlength
                ) {
                    // console.log('------------------------');
                    // console.log('countValue.length < constMinlength');

                    if (errorMessage !== '') {
                        message = errorMessage;
                    } else {
                        message = `O Campo ${labelField} devem ter entre ${constMinlength} e ${constMaxlength} caracteres`;
                    }

                    setAttributeDisabled(true);
                    setModalId(`modal_form_${gerarContagemAleatoria(6)}`);
                    setMsgError(message);
                    setModalMessage({
                        show: true,
                        type: 'light',
                        message: message
                    });
                    setError(message);
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
                        setError(message);
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
                            setError(message);
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
                            setError(message);
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
                            setError(message);
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
                            setError(message);
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
                        setError(message);
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
                    setConstMaxlength(40);
                    const cleanedValue = cleanInputOnlyNumber(value);
                    setCertidaoNova(atualizarCertidaoNova(cleanedValue));
                    setCertidaoAntiga(atualizarCertidaoAntiga(cleanedValue));

                    setTimeout(() => {
                        if (tipoCertidao === 'certNova') {
                            // console.log("tipoCertidao === 'certNova'");
                            setFormData((prev) => ({
                                ...prev,
                                Certidao: applyMaskCertidaoNova(cleanedValue),
                                constTipoCertidao: 'certNova'
                            }));
                        } else if (tipoCertidao === 'certAntiga') {
                            // console.log("tipoCertidao === 'certAntiga'");
                            setFormData((prev) => ({
                                ...prev,
                                Certidao: applyMaskCertidaoAntiga(cleanedValue),
                                constTipoCertidao: 'certAntiga'
                            }));
                        } else if (tipoCertidao === 'certIndefinida') {
                            // console.log("tipoCertidao === 'certIndefinida'");
                            setFormData((prev) => ({
                                ...prev,
                                Certidao: applyMaskCertidaoAntiga(cleanedValue),
                                constTipoCertidao: 'certIndefinida'
                            }));
                        }
                    }, 100);

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

                    if (
                        cleanedValue.length === 0 || cleanedValue.length === 32
                    ) {
                        // console.log('------------------------');
                        // console.log('Certidao');
                        // console.log('cleanedValue.length === 32');

                        break;

                    } else {
                        setModalId(`modal_form_cpf_duplicado${gerarContagemAleatoria(6)}`);
                        setAttributeDisabled(true);
                        setModalMessage({
                            show: true,
                            type: 'light',
                            message: `O Campo ${labelField} deve conter exatamente 32 dígitos.`
                        });

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
                            setError(message);
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
                            setError(message);
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
            // console.log('fetchGetCEP cleanedCEP ::', cleanedCEP);
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
                    setError('CEP inválido');
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
                    setError(msg);
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

        const funcValidNova0815 = function (dataFormData = '') {
            if (!dataFormData || dataFormData.length !== 10) return false;

            // Extrai ano, mês e dia como number
            var ano = parseInt(dataFormData.slice(0, 4), 10);
            var mes = parseInt(dataFormData.slice(5, 7), 10);
            var dia = parseInt(dataFormData.slice(8, 10), 10);

            // O Date usa mês 0-11, por isso mes-1
            var dataObj = new Date(ano, mes - 1, dia);

            // Verifica se a data bate certinho
            return (
                dataObj.getFullYear() === ano &&
                (dataObj.getMonth() + 1) === mes &&
                dataObj.getDate() === dia
            );
        };

        const funcValidAntiga0912 = function (valor = '') {
            // Esperado: valor com 4 dígitos representando o ano
            if (!valor || valor.length !== 4) return false;
            var ano = parseInt(valor, 10);
            var anoAtual = new Date().getFullYear();
            return ano >= 1900 && ano <= anoAtual;
        };
        {/* VALIDA CERTIDAO */ }
        React.useEffect(() => {
            // --- Validação da data nova ---
            const valorNova = certidaoNova.cert_nova_p_08_15;
            if (valorNova && valorNova.length === 8) {
                const ano = valorNova.slice(0, 4);
                const mes = valorNova.slice(4, 6);
                const dia = valorNova.slice(6, 8);
                var dataFormatada = ano + '-' + mes + '-' + dia;
                const dataValida = funcValidNova0815(dataFormatada);
                setValidP0815NovaDataRegistro(dataValida);
                // (Opcional) console.log
            }

            // --- Valida IBGE UF Nova ---
            const novoIbgeUF = certidaoNova.cert_nova_p_16_17;
            if (novoIbgeUF !== '') {
                const validaUfNovo = checkWordInArray(ufCertidao, novoIbgeUF);
                setValidP1617NovaUfIbge(validaUfNovo);
            }

            // --- Valida IBGE Municipio Nova ---
            const novoIbgeMunicipio = certidaoNova.cert_nova_p_18_21
            if (novoIbgeMunicipio !== '') {
                const validaMunicipioNovo = checkWordInArray(municipioCertidao, novoIbgeMunicipio);
                // console.log('validaMunicipioNovo :: ', validaMunicipioNovo);
                setValidP1821NovaMunicipioIbge(validaMunicipioNovo);
            }

            // --- Valida IBGE UF Antiga ---
            const antigaIbgeUF = certidaoAntiga.cert_antiga_p_07_08;
            if (antigaIbgeUF !== '') {
                const validaUfAntiga = checkWordInArray(ufCertidao, antigaIbgeUF);
                setValidP0708AntigaUfIbge(validaUfAntiga);
            }

            // --- Ano Registro do ano antiga ---
            const anoRegistroAntiga = certidaoAntiga.cert_antiga_p_09_12;
            const anoRegistroValido = funcValidAntiga0912(anoRegistroAntiga);
            setValidP0912AntigaAnoRegistro(anoRegistroValido);

            // --- Validação do anoRegistro antiga ---
            const TipoCertidaoAntiga = certidaoAntiga.cert_antiga_p_13;
            const tipoCertidaoValida = TipoCertidaoAntiga === '1';
            // console.log('tipoCertidaoValida : ', tipoCertidaoValida);
            setValidP13AntigaTipoCertidao(tipoCertidaoValida);

        }, [
            certidaoNova.cert_nova_p_08_15,
            certidaoNova.cert_nova_p_16_17,
            certidaoNova.cert_nova_p_18_21,
            certidaoAntiga.cert_antiga_p_07_08,
            certidaoAntiga.cert_antiga_p_09_12,
            certidaoAntiga.cert_antiga_p_13
        ]);
        {/* PREENCHE CERTIDAO */ }
        React.useEffect(() => {

            console.log('--- --- --- ---');
            if (
                validP0708AntigaUfIbge
                && validP0912AntigaAnoRegistro
                && validP13AntigaTipoCertidao
            ) {

                setTimeout(() => {
                    // console.log('setTimeout -- 500');
                    setTipoCertidao('certAntiga');
                }, 500);

                setFormData((prev) => ({
                    ...prev,
                    constTipoCertidao: 'certAntiga',
                    NumRegistro: certidaoAntiga.cert_antiga_p_22_28,
                    Zona: certidaoAntiga.cert_antiga_p_01_06,
                    Folha: certidaoAntiga.cert_antiga_p_19_21,
                    Livro: certidaoAntiga.cert_antiga_p_14_18,
                    Circunscricao: '',
                }));

            } else if (
                validP0815NovaDataRegistro
                && validP1617NovaUfIbge
                && validP1821NovaMunicipioIbge
            ) {
                setTimeout(() => {
                    setTipoCertidao('certNova');
                    // console.log('setTimeout -- 501');
                }, 501);
                setFormData((prev) => ({
                    ...prev,
                    constTipoCertidao: 'certNova',
                    NumRegistro: certidaoNova.cert_nova_p_27_32,
                    Zona: certidaoNova.cert_nova_p_16_17,
                    Folha: certidaoNova.cert_nova_p_24_26,
                    Livro: certidaoNova.cert_nova_p_22_23,
                    Circunscricao: certidaoNova.cert_nova_p_18_21,
                }));

            } else {
                setTimeout(() => {
                    setTipoCertidao('certIndefinida');
                }, 502);
            }

        }, [
            validP0708AntigaUfIbge,
            validP0912AntigaAnoRegistro,
            validP13AntigaTipoCertidao,
            certidaoAntiga.cert_antiga_p_14_18,
            certidaoAntiga.cert_antiga_p_19_21,
            certidaoAntiga.cert_antiga_p_22_28,
            validP0815NovaDataRegistro,
            validP1617NovaUfIbge,
            validP1821NovaMunicipioIbge,
            certidaoNova.cert_nova_p_22_23,
            certidaoNova.cert_nova_p_24_26,
            certidaoNova.cert_nova_p_27_32
        ]);
        {/* attributeRequired, attributeReadOnly, attributeDisabled */ }
        React.useEffect(() => {
            console.log('fieldAttributes.attributeRequired :: ', fieldAttributes.attributeRequired);
            console.log('fieldAttributes.attributeReadOnly :: ', fieldAttributes.attributeReadOnly);
            console.log('fieldAttributes.attributeDisabled :: ', fieldAttributes.attributeDisabled);

            if (fieldAttributes.attributeReadOnly === 'Y') {
                setAttributeReadOnly(true);
            } else {
                setAttributeReadOnly(false);
            }
        }, [
            fieldAttributes.attributeReadOnly,
        ]);
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
                {/* && nameField === 'NumRegistro' */}
                {(false) && (
                    <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                        -> {JSON.stringify(attributeReadOnly, null, 2)}
                    </pre>
                )}
                <div style={formGroupStyle}>
                    <label
                        htmlFor={nameField}
                        style={formLabelStyle}
                        className="form-label"
                    >
                        {labelField}
                        {(attributeRequired) && (
                            <strong style={requiredField}> *</strong>
                        )}
                    </label>
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="text"
                        className={`form-control form-control-sm ${error ? 'is-invalid' : formData[nameField] ? 'is-valid' : ''}`}
                        style={formControlStyle}
                        id={nameField}
                        name={nameField}
                        value={formData[nameField] || ''}
                        placeholder={attributePlaceholder}
                        minLength={constMinlength}
                        maxLength={constMaxlength}
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
                    {/* CERTIDÃO NOVA */}
                    {(debugMyPrint && certidaoNova.cert_nova_p_01_06.length === 6) && (
                        <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                            Certidao Nova: {certidaoNova.cert_nova_p_01_06.length === 6 ? JSON.stringify(certidaoNova, null, 2) : ''}
                        </pre>
                    )}
                    {(debugMyPrint && validP0815NovaDataRegistro) && (
                        <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                            Valida Nova [08-15] Data Registro: {validP0815NovaDataRegistro ? JSON.stringify(validP0815NovaDataRegistro, null, 2) : ''}
                        </pre>
                    )}
                    {(debugMyPrint && validP1617NovaUfIbge) && (
                        <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                            Valida Nova [16-17] Uf IBGE: {validP1617NovaUfIbge ? JSON.stringify(validP1617NovaUfIbge, null, 2) : ''}
                        </pre>
                    )}
                    {(debugMyPrint && validP1821NovaMunicipioIbge) && (
                        <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                            Valida Nova [18-21] Municipio IBGE: {validP1821NovaMunicipioIbge ? JSON.stringify(validP1821NovaMunicipioIbge, null, 2) : ''}
                        </pre>
                    )}

                    {/* CERTIDÃO ANTIGA */}
                    {(debugMyPrint && certidaoAntiga.cert_antiga_p_01_06.length === 6) && (
                        <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                            Certidao Antiga: {certidaoAntiga.cert_antiga_p_01_06.length === 6 ? JSON.stringify(certidaoAntiga, null, 2) : ''}
                        </pre>
                    )}
                    {(debugMyPrint && validP0708AntigaUfIbge) && (
                        <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                            Valid Antiga [07-08] Uf IBGE: {validP0708AntigaUfIbge ? JSON.stringify(validP0708AntigaUfIbge, null, 2) : ''}
                        </pre>
                    )}
                    {(debugMyPrint && validP0912AntigaAnoRegistro) && (
                        <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                            Valida Antiga [09-12] Ano Registro: {validP0912AntigaAnoRegistro ? JSON.stringify(validP0912AntigaAnoRegistro, null, 2) : ''}
                        </pre>
                    )}
                    {(debugMyPrint && validP13AntigaTipoCertidao) && (
                        <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                            Valida Antiga [13] Tipo Certidao: {validP13AntigaTipoCertidao ? JSON.stringify(validP13AntigaTipoCertidao, null, 2) : ''}
                        </pre>
                    )}
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