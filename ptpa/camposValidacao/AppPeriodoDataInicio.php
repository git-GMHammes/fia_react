<script type="text/babel">
    const AppPeriodoDataInicio = (
        {
            formData = {},
            setFormData = () => { },
            parametros = {},
            salvar = () => { },
            setSalvar = () => { }
        }
    ) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];
        // // console.log('getURI', getURI);

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        // Definindo mensagens do Sistema
        const [tabNav, setTabNav] = React.useState('form');
        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        const [dateLimits, setDateLimits] = React.useState({ min: '', max: '' });

        const handleButtonClick = () => {
            // Força o calendário a abrir ao clicar no botão
            dateInputRef.current.focus();
        };

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            // console.log('handleFocus name :: ', name);
            // console.log('handleFocus value :: ', value);

            if (name === 'periodo_data_inicio') {
                setFormData((prev) => ({
                    ...prev,
                    periodo_data_inicio: value
                }));
            }
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            // console.log('handleChange name :: ', name);
            // console.log('handleChange value :: ', value);

            setSalvar(true);

            if (name === 'periodo_data_inicio') {
                setFormData((prev) => ({
                    ...prev,
                    periodo_data_inicio: value
                }));
            }

        };

        const handleBlur = (event) => {
            const { name, value } = event.target;

            // console.log('handleBlur name :: ', name);
            // console.log('handleBlur value :: ', value);

            if (name === 'periodo_data_inicio' && value === '') {
                return true;
            }

            if (name === 'periodo_data_inicio') {

                setFormData((prev) => ({
                    ...prev,
                    periodo_data_inicio: value
                }));

                if (parseInt(formData.periodo_numero, 10) === 1) {
                    // console.log('Primeiro semestre: 01/01/AAAA - 30/06/AAAA');
                    if (parseInt(formData.periodo_numero, 10) === 1) {
                        const inicioSemestre1 = new Date(`${formData.periodo_ano}-01-01T00:00:00`);
                        const fimSemestre1 = new Date(`${formData.periodo_ano}-06-30T23:59:59`);
                        const dataInicio = new Date(value + "T00:00:00");

                        // console.log('inicioSemestre1 :: ', inicioSemestre1);
                        // console.log('fimSemestre1 :: ', fimSemestre1);
                        // console.log('dataInicio :: ', dataInicio);

                        if (dataInicio < inicioSemestre1 || dataInicio > fimSemestre1) {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: 'O campo inicio do período deve estar dentro do 1º semestre.',
                            });
                        }
                    }

                } else if (parseInt(formData.periodo_numero, 10) === 2) {
                    // console.log('Segundo semestre: 01/07/AAAA - 31/12/AAAA');
                    const inicioSemestre2 = new Date(`${formData.periodo_ano}-07-01T00:00:00`);
                    const fimSemestre2 = new Date(`${formData.periodo_ano}-12-31T23:59:59`);
                    const dataInicio = new Date(value + "T00:00:00");

                    if (dataInicio < inicioSemestre2 || dataInicio > fimSemestre2) {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'O campo inicio do período deve estar dentro do 2º semestre. Verifique se o Campo Ano informado esta correto',
                        });

                        setFormData((prev) => ({ ...prev, periodo_data_inicio: null }));
                    }

                } else {
                    // Caso todas as validações passem, limpa mensagens de erro
                    setMessage({ show: false, type: null, message: null });
                    setFormData((prev) => ({
                        ...prev,
                        periodo_data_inicio: value,
                    }));
                }

                // // console.log('formData.periodo_data_inicio !== "") &&); :: ', formData.periodo_data_inicio !== "")
                // // console.log('formData.periodo_data_termino !== "") &&); :: ', formData.periodo_data_termino !== "")
                // // console.log('formData.periodo_data_inicio > formData.periodo_data_termino :: ', formData.periodo_data_inicio > formData.periodo_data_termino);

                if (
                    (formData.periodo_data_inicio !== "") &&
                    (formData.periodo_data_termino !== "") &&
                    (formData.periodo_data_inicio > formData.periodo_data_termino)
                ) {
                    // Verifica se periodo_data_termino é menor que periodo_data_termino
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'O Campo Termino do Período não deve ser menor do que o Campo Início do período.'
                    });
                    setFormData((prev) => ({
                        ...prev,
                        periodo_data_inicio: '',
                        periodo_data_termino: ''
                    }));
                }

            }
        };

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
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
                        htmlFor="periodo_data_inicio"
                        style={formLabelStyle}
                        className="form-label">Inicio do Período
                    </label>
                    {(checkWordInArray(getURI, 'consultar')) ? (
                        <div className='p-2'>
                            {formData.periodo_data_inicio}
                        </div>
                    ) : (
                        <div>
                            <input
                                data-api={`filtro-${origemForm}`}
                                type="date"
                                id="periodo_data_inicio"
                                name="periodo_data_inicio"
                                value={formData.periodo_data_inicio || ''}
                                min={dateLimits.min}
                                max={dateLimits.max}
                                className="form-control"
                                onFocus={(e) => {
                                    e.target.showPicker();
                                    handleFocus(e);
                                }}
                                onChange={handleChange}
                                onBlur={handleBlur}
                                style={formControlStyle}
                                required
                                onKeyDown={(e) => e.preventDefault()}
                                onKeyPress={(e) => e.preventDefault()}
                            />
                        </div>
                    )
                    }
                </div>
                {/*message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}

                <AppMessageCard
                    parametros={message}
                    modalId="modal_data_inicio"
                />
            </div>
        );

    };
</script>