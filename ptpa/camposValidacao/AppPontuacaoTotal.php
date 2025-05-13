<script type="text/babel">
    const AppPontuacaoTotal = (
        {
            parametros = {},
            formData = {},
            setFormData = () => { },
            somaTotal = () => { }
        }
    ) => {
        // Variáveis recebidas do Backend
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

        // Função que executa após a retirada do foco
        const handleBlur = (event) => {
            const { name, value } = event.target;

            console.log('handleBlur :: ', name);
            console.log('handleBlur :: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            setMessage({ show: false, type: null, message: null });
        }

        // Atualiza a pontuação total automaticamente com useEffect
        React.useEffect(() => {
            // Recalcula a pontuação total com base nos campos `_pts`
            const total = somaTotal(formData);
            setFormData((prev) => ({
                ...prev,
                prontuario_pontuacao_total: total
            }));
        }, [
            formData.prontuario_medidas_socioeducativas_pts,
            formData.prontuario_uso_de_drogas_pts,
            formData.prontuario_deficiencia_pts,
            formData.prontuario_necessita_mediador_pts,
            formData.prontuario_cad_unico_pts,
            formData.prontuario_referenciado_na_rede_pts,
            formData.prontuario_diagnostico_psicologico_pts,
        ]);

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
                {/* Pontuação total */}
                <div style={formGroupStyle}>
                    <label
                        htmlFor="PontuacaoTotal"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Pontuação total
                    </label>
                    <input
                        data-api="form-prontuario"
                        type="number"
                        value={formData.prontuario_pontuacao_total || 0}
                        onChange={handleChange}
                        className="form-control"
                        style={formControlStyle}
                        id="prontuario_pontuacao_total"
                        name="prontuario_pontuacao_total"
                        aria-describedby="prontuario_pontuacao_total"
                        readOnly
                    />
                </div>
                {/* Pontuação total */}
            </div >
        );
    };
</script>