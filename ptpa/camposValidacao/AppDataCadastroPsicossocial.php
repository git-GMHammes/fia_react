<script type="text/babel">
    const AppDataCadastroPsicossocial = (
        {
            parametros = {},
            formData = {},
            setFormData = () => { }
        }
    ) => {
        // Variáveis recebidas do Backend
        const origemForm = parametros.origemForm || 'erro';
        const [objeto, setObjeto] = React.useState([]);

        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleFocus para receber foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('handleFocus :: ', name);
            console.log('handleFocus :: ', value);

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('handleChange :: ', name);
            console.log('handleChange :: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            setMessage({ show: false, type: null, message: null });
        };

        // Função que executa após a retirada do handleBlur
        const handleBlur = (event) => {
            const { name, value } = event.target;

            console.log('handleBlur :: ', name);
            console.log('handleBlur :: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
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
        };

        const formControlStyle = {
            fontSize: '1.rem',
            borderColor: '#fff',
        };

        return (
            <div>
                {/* Data de cadastro psicossocial */}
                <div style={formGroupStyle}>
                    <label
                        htmlFor="prontuario_data_cadastro_psicossocial"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Data de cadastro psicossocial
                    </label>
                    <input
                        data-api={`form-${origemForm}`}
                        type="date"
                        id="prontuario_data_cadastro_psicossocial"
                        name="prontuario_data_cadastro_psicossocial"
                        value={formData.prontuario_data_cadastro_psicossocial || ''}
                        onChange={handleChange}
                        className="form-control"
                        style={formControlStyle}
                        aria-describedby="prontuario_data_cadastro_psicossocial"
                    />
                    <div className="m-1 p-1"></div>
                </div>
                {/* Data de cadastro psicossocial */}
            </div >
        );
    };
</script>