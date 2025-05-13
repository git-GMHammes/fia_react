<script type="text/babel">
    const AppPeriodoDataFim = (
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
        // console.log('getURI', getURI);

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

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            // console.log('handleFocus: ', name);
            // console.log('handleFocus: ', value);

            if (name === 'periodo_data_termino') {
                setFormData((prev) => ({
                    ...prev,
                    periodo_data_termino: value
                }));
            }

        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            // console.log('handleChange: ', name);
            // console.log('handleChange: ', value);

            setSalvar(true);

            if (name === 'periodo_data_termino') {
                setFormData((prev) => ({
                    ...prev,
                    periodo_data_termino: value
                }));
            }
        };

        // Função handleBlur para limpar o campo Telefone se for inválido
        const handleBlur = (event) => {
            const { name, value } = event.target;

            // console.log('handleBlur: ', name);
            // console.log('handleBlur: ', value);

            if (name === 'periodo_data_termino' && value === '') {
                return true;
            }

            if (name === 'periodo_data_termino') {

                const dataInicio = new Date(formData.periodo_data_termino);
                const dataTermino = new Date(value);
                if (parseInt(formData.periodo_numero, 10) === 1) {
                    // console.log('Primeiro semestre: 01/01/AAAA - 30/06/AAAA');
                    const inicioSemestre1 = new Date(`${formData.periodo_ano}-01-01T00:00:00`);
                    const fimSemestre1 = new Date(`${formData.periodo_ano}-06-30T23:59:59`);
                    // console.log('inicioSemestre1', inicioSemestre1);
                    // console.log('fimSemestre1', fimSemestre1);
                    const dataInicio = new Date(value + "T00:00:00");

                    if (dataInicio < inicioSemestre1 || dataInicio > fimSemestre1) {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'O campo termino do periodo deve estar dentro do 1º semestre. Verifique se o Campo Ano informado esta correto',
                        });
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
                            message: 'O campo termino do periodo deve estar dentro do 2º semestre. Verifique se o Campo Ano informado esta correto',
                        });
                    } else if (dataTermino.getFullYear() !== parseInt(formData.periodo_ano, 10)) {
                        // Verifica se o ano de periodo_data_termino é igual ao ano de formData.periodo_ano
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'O ano informado no campo Termino do Período deve ser igual ao ano informado no campo Ano.',
                        });
                    } else {
                        setMessage({
                            show: false,
                            type: null,
                            message: null
                        });
                    }
                }

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
                    <label htmlFor="periodo_data_termino"
                        style={formLabelStyle}
                        className="form-label">Término do Período
                    </label>
                    {checkWordInArray(getURI, 'consultar') ? (
                        <div className='p-2'>
                            {formData.periodo_data_termino}
                        </div>
                    ) : (
                        <input
                            data-api={`filtro-${origemForm}`}
                            type="date"
                            id="periodo_data_termino"
                            name="periodo_data_termino"
                            value={formData.periodo_data_termino || ''}
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
                    modalId="modal_data_fim"
                />
            </div>
        );
    };
</script>