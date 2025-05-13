<script type="text/babel">
    const AppDiagnosticoPsicologico = (
        {
            parametros = {},
            formData = {},
            setFormData = () => { },
            somaTotal = () => { }
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

            setFormData((prev) => {
                const updatedFormData = { ...prev, [name]: value };

                // Garante ajuste derivado aqui também
                if (name === "prontuario_diagnostico_psicologico") {
                    updatedFormData.prontuario_diagnostico_psicologico_pts = value === "Y" ? 1 : 0;
                }

                return updatedFormData;
            });

            setMessage({ show: false, type: null, message: null });
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('handleChange :: ', name);
            console.log('handleChange :: ', value);

            setFormData((prev) => {
                const updatedFormData = { ...prev, [name]: value };

                if (name === "prontuario_diagnostico_psicologico") {
                    updatedFormData.prontuario_diagnostico_psicologico_pts = value === "Y" ? 1 : 0;
                }

                // Sempre calcula a pontuação total após atualizar os campos relevantes
                updatedFormData.prontuario_pontuacao_total = somaTotal(updatedFormData);

                return updatedFormData;
            });

            setMessage({ show: false, type: null, message: null });
        };

        // Função que executa após a retirada do handleBlur
        const handleBlur = (event) => {
            const { name, value } = event.target;

            console.log('handleBlur :: ', name);
            console.log('handleBlur :: ', value);

            setFormData((prev) => {
                const updatedFormData = { ...prev, [name]: value };

                // console.log('Updated formData (Blur):', updatedFormData);
                return updatedFormData;
            });

            setMessage({ show: false, type: null, message: null });
        };

        React.useEffect(() => {
            setFormData((prev) => ({
                ...prev,
                prontuario_pontuacao_total: somaTotal(prev),
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

        React.useEffect(() => {
            // console.log('formData atualizado:', formData);
        }, [formData]);

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

        return (
            <div>
                {/* Diagnóstico psicológico */}
                <div style={formGroupStyle}>
                    <label
                        htmlFor="prontuario_diagnostico_psicologico"
                        style={formLabelStyle}
                        className="form-label">
                        Diagnóstico psicológico
                    </label>
                    <div className="form-check">
                        <input
                            data-api={`form-${origemForm}`}
                            type="radio"
                            className="form-check-input"
                            id="prontuario_diagnostico_psicologico1"
                            name="prontuario_diagnostico_psicologico"
                            value="Y"
                            checked={formData.prontuario_diagnostico_psicologico === "Y"}
                            onChange={handleChange}
                        />
                        <label className="form-check-label" htmlFor="prontuario_diagnostico_psicologico1">Sim</label>
                    </div>
                    <div className="form-check">
                        <input
                            data-api={`form-${origemForm}`}
                            type="radio"
                            className="form-check-input"
                            id="prontuario_diagnostico_psicologico2"
                            name="prontuario_diagnostico_psicologico"
                            value="N"
                            checked={formData.prontuario_diagnostico_psicologico === "N"}
                            onChange={handleChange}
                        />
                        <label className="form-check-label" htmlFor="prontuario_diagnostico_psicologico2">Não</label>
                    </div>
                </div>
                {/* Diagnóstico psicológico */}
            </div >
        );
    };
</script>