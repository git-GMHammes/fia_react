<script type="text/babel">
    const AppDate = ({
        submitAllForms,
        parametros = {},
        formData = {},
        setFormData = () => { },
        fieldAttributes = {}
    }) => {
        // Script que aceita parâmetros, formulário de dados, função de configuração de dados e atributos de campo
        // console.log('AppDate:', parametros, formData, setFormData, fieldAttributes);

        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const origemForm = fieldAttributes.attributeOrigemForm || 'attributeOrigemForm';
        const getURI = parametros.getURI || [];
        const labelField = fieldAttributes.labelField || '';
        const nameField = fieldAttributes.nameField || 'AppTextName';
        const labelColor = fieldAttributes.labelColor || 'black';
        const attributeMin = fieldAttributes.attributeMin || '';
        const attributeMax = fieldAttributes.attributeMax || '';
        const attributeRequired = fieldAttributes.attributeRequired || false;
        const attributeReadOnly = fieldAttributes.attributeReadOnly || false;
        const attributeDisabled = fieldAttributes.attributeDisabled || false;
        const attributeMask = fieldAttributes.attributeMask || false;

        // Estado para mensagens e validação
        const [avisoCampo, setAvisoCampo] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função para gerar uma string aleatória com letras maiúsculas e números
        const gerarContagemAleatoria = (comprimento) => {
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Letras maiúsculas e números
            let resultado = '';
            for (let i = 0; i < comprimento; i++) {
                resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
            return resultado;
        };

        const [msgError, setMsgError] = React.useState(false);
        const [error, setError] = React.useState('');
        const [valid, setValid] = React.useState(true);
        const cleanInput = (value) => value.replace(/\D/g, '');
        const [dateLimits, setDateLimits] = React.useState({ min: '', max: '' });

        const maxDate = (() => {
            switch (attributeMax) {
                //Profisional
                case 'Profissional':
                    // Para "Profissional", limite até hoje
                    return new Date().toISOString().split('T')[0];
                    break;

                default:
                    break;
            }
        })();

        // Função handleFocus para receber foco
        const handleFocus = (event) => {
            const { name, value } = event.target;
            console.log('handleFocus: ', name, value);
            setMessage({ show: false, type: null, message: null });
        };

        // handleChange
        const handleChange = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });

            if (!value) {
                setFormData((prev) => ({ ...prev, [name]: '' }));
                setMsgError(false);
                return;
            };

            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));

            setMessage({ show: false, type: null, message: null });
        };

        const handleBlur = async (event) => {
            const { name, value } = event.target;
            console.log('handleBlurAppDate: ', name, value);
            setMessage({ show: false, type: null, message: null });

            switch (attributeMask) {

                //Adolescente
                case 'Adolescente':
                    // Calcula a data atual                    
                    const dataMinima = new Date();
                    dataMinima.setFullYear(dataMinima.getFullYear() - 18);

                    const dataMaxima = new Date();
                    dataMaxima.setFullYear(dataMaxima.getFullYear() - 15);

                    const calcularIdade = (Nascimento) => {
                        const hoje = new Date();
                        const nascimento = new Date(Nascimento);
                        let idade = hoje.getFullYear() - nascimento.getFullYear();
                        const mesAtual = hoje.getMonth();
                        const diaAtual = hoje.getDate();

                        // Ajusta a idade caso o mês ou dia atual seja antes do mês/dia de nascimento
                        if (mesAtual < nascimento.getMonth() || (mesAtual === nascimento.getMonth() && diaAtual < nascimento.getDate())) {
                            idade--;
                        }

                        return idade;
                    };

                    setMessage({ show: false, type: null, message: null });

                    if (name === 'Nascimento' && value) {
                        const idade = calcularIdade(value);

                        // Valida se a idade está dentro do intervalo permitido
                        if (idade < 14 || idade > 18) {
                            // alert("Data de nascimento inválida ou fora da faixa etária permitida.");
                            setFormData((prev) => ({
                                ...prev,
                                [name]: '' // Reseta o campo se a idade não estiver no intervalo
                            }));
                        } else {
                            console.log(`Idade válida: ${idade} anos.`);
                        }
                    }

                    setFormData((prev) => ({
                        ...prev,
                        [name]: value,
                    }));


                    if (name === 'Nascimento') {
                        const dataSelecionada = new Date(value);
                        const min = new Date(dataMinima);
                        const max = new Date(dataMaxima);

                        // Verifica se a data está dentro do intervalo permitido
                        if (dataSelecionada < min || dataSelecionada > max) {
                            setFormData((prev) => ({
                                ...prev,
                                [name]: '',
                            }));
                            setMessage({
                                show: true,
                                type: 'light',
                                message: 'Data de nascimento inválida ou fora da faixa etária permitida.',
                            });
                        } else {
                            setMessage({ show: false, type: null, message: null });
                        }
                    }

                    break;

                //Filtro-Unidades
                case 'Filtro-Unidades':

                    // Validações para o campo unidades_data_cadastramento_fim
                    if (name === 'unidades_data_cadastramento_fim') {
                        const dataInicio = formData.unidades_data_cadastramento_inicio;

                        // Verifica se a data de início está preenchida
                        if (new Date(dataInicio) > new Date(value)) {
                            // Verifica se a data de início não é maior que a data de fim
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A data de início não pode ser maior do que a data de fim."
                            });
                        }
                    }

                    // Validações para o campo unidades_data_cadastramento_inicio
                    if (name === 'unidades_data_cadastramento_inicio') {
                        const dataFim = formData.unidades_data_cadastramento_fim;

                        // Verifica se a data de fim está preenchida e se a data de início não é maior
                        if (dataFim && new Date(value) > new Date(dataFim)) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A data de início não pode ser maior do que a data de fim."
                            });
                        }
                    }

                    break;

                //Período
                case 'Periodo':
                    if (name === 'periodo_data_inicio') {
                        const dataInicio = new Date(value + "T00:00:00");
                        const inicioSemestre1 = new Date(`${formData.periodo_ano}-01-01T00:00:00`);
                        const fimSemestre1 = new Date(`${formData.periodo_ano}-06-30T23:59:59`);
                        const inicioSemestre2 = new Date(`${formData.periodo_ano}-07-01T00:00:00`);
                        const fimSemestre2 = new Date(`${formData.periodo_ano}-12-31T23:59:59`);

                        if (parseInt(formData.periodo_numero, 10) === 1) {
                            if (dataInicio < inicioSemestre1 || dataInicio > fimSemestre1) {
                                // Limpa o campo antes de exibir a mensagem
                                await setFormData((prev) => ({
                                    ...prev,
                                    periodo_data_inicio: "",
                                }));

                                setMessage({
                                    show: true,
                                    type: 'light',
                                    message: 'O campo início do período deve estar dentro do 1º semestre. Verifique se o Campo Ano informado está correto.',
                                });
                            }
                        } else if (parseInt(formData.periodo_numero, 10) === 2) {
                            if (dataInicio < inicioSemestre2 || dataInicio > fimSemestre2) {
                                await setFormData((prev) => ({
                                    ...prev,
                                    periodo_data_inicio: "",
                                }));

                                setMessage({
                                    show: true,
                                    type: 'light',
                                    message: 'O campo início do período deve estar dentro do 2º semestre. Verifique se o Campo Ano informado está correto.',
                                });
                            }
                        }
                    } else if (name === 'periodo_data_termino') {
                        const dataTermino = new Date(value + "T00:00:00");
                        const inicioSemestre1 = new Date(`${formData.periodo_ano}-01-01T00:00:00`);
                        const fimSemestre1 = new Date(`${formData.periodo_ano}-06-30T23:59:59`);
                        const inicioSemestre2 = new Date(`${formData.periodo_ano}-07-01T00:00:00`);
                        const fimSemestre2 = new Date(`${formData.periodo_ano}-12-31T23:59:59`);

                        if (parseInt(formData.periodo_numero, 10) === 1) {
                            if (dataTermino < inicioSemestre1 || dataTermino > fimSemestre1) {
                                await setFormData((prev) => ({
                                    ...prev,
                                    periodo_data_termino: '',
                                }));

                                setMessage({
                                    show: true,
                                    type: 'light',
                                    message: 'O campo término do período deve estar dentro do 1º semestre. Verifique se o Campo Ano informado está correto.',
                                });
                            }
                        } else if (parseInt(formData.periodo_numero, 10) === 2) {
                            if (dataTermino < inicioSemestre2 || dataTermino > fimSemestre2) {
                                await setFormData((prev) => ({
                                    ...prev,
                                    periodo_data_termino: '',
                                }));

                                setMessage({
                                    show: true,
                                    type: 'light',
                                    message: 'O campo término do período deve estar dentro do 2º semestre. Verifique se o Campo Ano informado está correto.',
                                });
                            }
                        }

                        if (
                            // Validação adicional para garantir que término não seja menor que início
                            name === 'periodo_data_termino',
                            formData.periodo_data_inicio !== "" &&
                            formData.periodo_data_termino !== "" &&
                            formData.periodo_data_inicio > formData.periodo_data_termino
                        ) {
                            await setFormData((prev) => ({
                                ...prev,
                                periodo_data_inicio: "",
                                periodo_data_termino: "",
                            }));

                            setMessage({
                                show: true,
                                type: 'light',
                                message: 'O Campo Término do Período não deve ser menor do que o Campo Início do período.',
                            });
                        }
                    }
                    break;


                //Filtro de Período    
                case 'Filtro-Periodo':

                    if (name === 'periodo_data_inicio' || name === 'periodo_data_termino') {

                        // Função para validar se a data é válida
                        const isDateValid = (date) => {
                            const parsedDate = new Date(date);
                            return parsedDate instanceof Date && !isNaN(parsedDate);
                        };

                        // Ignorar se o campo estiver vazio
                        if (value === null || value.trim() === '') return;


                        // Função para validar ano bissexto
                        const isValidLeapYearDate = (date) => {
                            const [year, month, day] = date.split('-').map(Number);
                            const isLeapYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
                            return month === 2 && day === 29 ? isLeapYear : isDateValid(date);
                        };

                        const dataInicio = formData.periodo_data_inicio;
                        const dataFim = formData.periodo_data_termino;

                        // Validações para data de início e fim
                        if (name === 'periodo_data_inicio' && value) {
                            // console.log(periodo_data_inicio);
                            const [year] = value.split('-').map(Number);
                            if (year < 1999) {
                                setMessage({
                                    show: true,
                                    type: 'light',
                                    message: "Você está pesquisando uma data anterior ao Decreto Estadual nº 25.162, de 01/01/1999"
                                });
                                setFormData((prev) => ({
                                    ...prev,
                                    periodo_data_inicio: ''
                                }));
                                return;
                            }
                        }

                        // Validação de data inválida
                        if (
                            !isValidLeapYearDate(value) &&
                            (
                                name === 'periodo_data_inicio'
                            )) {
                            console.log('name handleBlur: ', name);
                            console.log('value handleBlur: ', value);
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A data de início deve ser válida"
                            });

                            setFormData((prev) => ({
                                ...prev,
                                periodo_data_inicio: null,
                            }));
                            return;
                        }

                        // Validação de data inválida
                        if (
                            !isValidLeapYearDate(value) &&
                            (
                                name === 'periodo_data_termino'
                            )) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A data de término deve ser válida"
                            });
                            setFormData((prev) => ({
                                ...prev,
                                periodo_data_termino: ''
                            }));
                            return;
                        }

                        // Verifica se a data de início é maior que a data de fim
                        if (dataInicio && dataFim && new Date(dataInicio) > new Date(dataFim)) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "O campo de início não deve ser maior ou igual à data de término"
                            });
                            setFormData((prev) => ({
                                ...prev,
                                [name]: ''
                            }));
                            return;
                        }
                    }
                    break;

                // Profissional
                case 'Profissional':
                    if (name === 'DataAdmissao' && value === '') {
                        return true;
                    }

                    // console.log('Entrou no case Profissional');
                    const dataAtual = new Date();
                    dataAtual.setHours(0, 0, 0, 0); // Ajusta para data atual sem horas para comparação precisa

                    // Converte as datas apenas se os campos tiverem valor
                    const dataAdmissao = formData.DataAdmissao ? new Date(formData.DataAdmissao) : null;
                    const dataDemissao = formData.DataDemissao ? new Date(formData.DataDemissao) : null;
                    const dataSelecionada = value ? new Date(value) : null;

                    let errorMessage = '';

                    // Se o campo estiver vazio, exibe uma mensagem abaixo do campo e redefine o valor
                    if (!value && name === 'DataAdmissao' && message.show === false) {

                        setMessage({
                            show: true,
                            type: 'light',
                            message: "Informe uma data para admissão do funcionário."
                        });

                        setFormData((prev) => ({
                            ...prev,
                            [name]: ''
                        }));
                    } else if (name === 'DataAdmissao' && message.show === false) {
                        const limiteMinimo = new Date(dataAtual);
                        limiteMinimo.setFullYear(limiteMinimo.getFullYear() - 34);

                        // Verifica se a data de admissão é menor que 34 anos a partir da data atual
                        if (dataAdmissao && dataAdmissao < limiteMinimo && message.show === false) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A Data deve ser maior do que 34 anos a partir da data atual."
                            });
                            setFormData((prev) => ({
                                ...prev,
                                [name]: ''
                            }));
                        } else if (dataAdmissao && dataAdmissao > dataAtual && message.show === false) {
                            // Verifica se a data de admissão é superior à data atual
                            errorMessage = 'Data de Admissão inválida. Por favor, insira uma data válida não superior à data atual.';

                            setMessage({
                                show: true,
                                type: 'light',
                                message: errorMessage
                            });

                            // Zera o valor do campo de data de admissão
                            setFormData((prev) => ({
                                ...prev,
                                DataAdmissao: ''
                            }));
                        }
                    } else if (name === 'DataDemissao' && message.show === false) {
                        if (dataDemissao && dataDemissao < dataAdmissao && message.show === false) {
                            // Verifica se a data de demissão é anterior à data de admissão
                            setMessage({
                                show: true,
                                type: 'light',
                                message: 'Data de Demissão inválida. Por favor, insira uma data válida superior à Data de Admissão e à data atual.'
                            });

                            // Zera o valor do campo de data de demissão
                            setFormData((prev) => ({
                                ...prev,
                                DataDemissao: ''
                            }));
                        } else if (dataDemissao && dataDemissao > dataAtual && message.show === false) {
                            // Verifica se a data de demissão é superior à data atual
                            errorMessage = 'A Data de Demissão não pode ser superior a hoje';

                            setMessage({
                                show: true,
                                type: 'light',
                                message: errorMessage
                            });

                            // Zera o valor do campo de data de demissão
                            setFormData((prev) => ({
                                ...prev,
                                DataDemissao: ''
                            }));
                        }
                    } else {
                        setFormData((prev) => ({
                            ...prev,
                            [name]: value
                        }));
                        setMessage({ show: false, type: 'light', message: errorMessage });
                    }
                    break;

                //Filtro de Profissional
                case 'Filtro-Profissional':

                    // Função para validar se a data é válida
                    const isDateValid = (date) => {
                        const parsedDate = new Date(date);
                        return parsedDate instanceof Date && !isNaN(parsedDate);
                    };

                    // Ignorar se o campo estiver vazio
                    if (value === null || value.trim() === '') return;


                    // Função para validar ano bissexto
                    const isValidLeapYearDate = (date) => {
                        const [year, month, day] = date.split('-').map(Number);
                        const isLeapYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
                        return month === 2 && day === 29 ? isLeapYear : isDateValid(date);
                    };

                    const currentDate = new Date().toISOString().split('T')[0];
                    const dataInicio = formData.DataAdmissao;
                    const dataFim = formData.DataDemissao;

                    // Validações para data de início e fim
                    if (name === 'DataAdmissao' && value) {
                        // console.log(DataAdmissao);
                        const [year] = value.split('-').map(Number);
                        if (year < 1999) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "Você está pesquisando uma data anterior ao Decreto Estadual nº 25.162, de 01/01/1999"
                            });
                            setFormData((prev) => ({
                                ...prev,
                                DataAdmissao: ''
                            }));
                            return;
                        }
                        if (value > currentDate) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A data de Admissão não pode ser superior à data de hoje"
                            });
                            setFormData((prev) => ({
                                ...prev,
                                DataAdmissao: ''
                            }));
                            return;
                        }
                    }
                    if (name === 'DataDemissao' && value) {
                        // console.log(DataDemissao);
                        if (value > currentDate) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A data de Demissão não pode ser superior à data de hoje"
                            });
                            setFormData((prev) => ({
                                ...prev,
                                DataDemissao: ''
                            }));
                            return;
                        }
                    }

                    // Validação de data inválida
                    if (
                        !isValidLeapYearDate(value) &&
                        (
                            name === 'DataAdmissao'
                        )) {
                        console.log('name handleBlur: ', name);
                        console.log('value handleBlur: ', value);
                        setMessage({
                            show: true,
                            type: 'light',
                            message: "A data de Admissão deve ser válida"
                        });

                        setFormData((prev) => ({
                            ...prev,
                            DataAdmissao: null,
                        }));
                        return;
                    }

                    // Validação de data inválida
                    if (
                        !isValidLeapYearDate(value) &&
                        (
                            name === 'DataDemissao'
                        )) {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: "A data de Demissão deve ser válida"
                        });
                        setFormData((prev) => ({
                            ...prev,
                            DataDemissao: ''
                        }));
                        return;
                    }

                    // Verifica se a data de início é maior que a data de fim
                    if (dataInicio && dataFim && new Date(dataInicio) > new Date(dataFim)) {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: "O campo de Admissão não deve ser maior ou igual à data de Demissão"
                        });
                        setFormData((prev) => ({
                            ...prev,
                            [name]: ''
                        }));
                        return;
                    }

                    break;

                //Filtro de ALocar Funcionário
                case 'Filtro-ALocarFuncionário':
                    if (name === 'periodo_data_inicio' || name === 'periodo_data_termino') {

                        // Função para validar se a data é válida
                        const isDateValid = (date) => {
                            const parsedDate = new Date(date);
                            return parsedDate instanceof Date && !isNaN(parsedDate);
                        };

                        // Ignorar se o campo estiver vazio
                        if (value === null || value.trim() === '') return;


                        // Função para validar ano bissexto
                        const isValidLeapYearDate = (date) => {
                            const [year, month, day] = date.split('-').map(Number);
                            const isLeapYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
                            return month === 2 && day === 29 ? isLeapYear : isDateValid(date);
                        };

                        const dataInicio = formData.periodo_data_inicio;
                        const dataFim = formData.periodo_data_termino;

                        // Validações para data de início e fim
                        if (name === 'periodo_data_inicio' && value) {
                            // console.log(periodo_data_inicio);
                            const [year] = value.split('-').map(Number);
                            if (year < 1999) {
                                setMessage({
                                    show: true,
                                    type: 'light',
                                    message: "Você está pesquisando uma data anterior ao Decreto Estadual nº 25.162, de 01/01/1999"
                                });
                                setFormData((prev) => ({
                                    ...prev,
                                    periodo_data_inicio: ''
                                }));
                                return;
                            }
                        }

                        // Validação de data inválida
                        if (
                            !isValidLeapYearDate(value) &&
                            (
                                name === 'periodo_data_inicio'
                            )) {
                            console.log('name handleBlur: ', name);
                            console.log('value handleBlur: ', value);
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A data de início deve ser válida"
                            });

                            setFormData((prev) => ({
                                ...prev,
                                periodo_data_inicio: null,
                            }));
                            return;
                        }

                        // Validação de data inválida
                        if (
                            !isValidLeapYearDate(value) &&
                            (
                                name === 'periodo_data_termino'
                            )) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "A data de término deve ser válida"
                            });
                            setFormData((prev) => ({
                                ...prev,
                                periodo_data_termino: ''
                            }));
                            return;
                        }

                        // Verifica se a data de início é maior que a data de fim
                        if (dataInicio && dataFim && new Date(dataInicio) > new Date(dataFim)) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: "O campo de início não deve ser maior ou igual à data de Demissão"
                            });
                            setFormData((prev) => ({
                                ...prev,
                                [name]: ''
                            }));
                            return;
                        }
                    }

                    break;
                default:
                    break;

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
            color: labelColor,
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
            <>
                {checkWordInArray(getURI, 'unidade') ? (
                    <>
                        <label
                            htmlFor={nameField}
                            style={formLabelStyle}
                            className="form-label"
                        >
                            {labelField}
                            {checkWordInArray(getURI, 'unidade') ? (

                                null
                            ) : (
                                (attributeRequired) && (
                                    <strong style={requiredField}>*</strong>
                                )
                            )}
                        </label>
                        <input
                            data-api={`filtro-${origemForm}`}
                            type="date"
                            // className={`form-control ${error ? 'is-invalid' : formData[nameField] ? 'is-valid' : ''}`}
                            className="form-control form-control-sm"
                            style={formControlStyle}
                            id={nameField}
                            name={nameField}
                            value={formData[nameField] || ''}
                            max={`${maxDate}`}
                            required={attributeRequired}
                            readOnly={attributeReadOnly}
                            disabled={attributeDisabled}
                            onKeyDown={(e) => e.preventDefault()} // Previne a digitação
                            onKeyPress={(e) => e.preventDefault()} // Previne a digitação
                            onFocus={(e) => {
                                e.target.showPicker();
                                handleFocus(e);
                            }}
                            onChange={handleChange}
                            onBlur={handleBlur}
                        />
                    </>
                ) : (
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
                            type="date"
                            // className={`form-control ${error ? 'is-invalid' : formData[nameField] ? 'is-valid' : ''}`}
                            className="form-control form-control-sm"
                            style={formControlStyle}
                            id={nameField}
                            name={nameField}
                            value={formData[nameField] || ''}
                            max={`${maxDate}`}
                            required={attributeRequired}
                            readOnly={attributeReadOnly}
                            disabled={attributeDisabled}
                            onKeyDown={(e) => e.preventDefault()} // Previne a digitação
                            onKeyPress={(e) => e.preventDefault()} // Previne a digitação
                            onFocus={(e) => {
                                e.target.showPicker();
                                handleFocus(e);
                            }}
                            onChange={handleChange}
                            onBlur={handleBlur}
                        />
                    </div>
                )}
                {/* Exibe o componente de alerta */}
                {typeof AppMessageCard !== "undefined" ? (
                    <div>
                        <AppMessageCard
                            parametros={message} modalId={`modal_date${nameField}`}
                        />
                    </div>
                ) : (
                    <div>
                        <p className="text-light">AppMessageCard não existe.</p>
                    </div>
                )}
            </>
        );
    };
</script>