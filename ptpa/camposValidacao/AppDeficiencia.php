<script type="text/babel">
    const AppDeficiencia = (
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

            setFormData((prev) => {
                const updatedFormData = { ...prev, [name]: value };

                // Garante ajuste derivado aqui também
                if (name === "prontuario_deficiencia") {
                    updatedFormData.prontuario_deficiencia_pts = value === "Y" ? 1 : 0;
                }

                return updatedFormData;
            });

        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('handleChange :: ', name);
            console.log('handleChange :: ', value);

            setFormData((prev) => {
                const updatedFormData = { ...prev, [name]: value };

                if (name === "prontuario_deficiencia") {
                    updatedFormData.prontuario_deficiencia_pts = value === "Y" ? 1 : 0;
                }

                // Sempre calcula a pontuação total após atualizar os campos relevantes
                updatedFormData.prontuario_pontuacao_total = somaTotal(updatedFormData);

                return updatedFormData;
            });

            setMessage({ show: false, type: null, message: null });
        };

        // Função que executa após a retirada do foco
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
        }

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
        
        const formControlStyle = {
            fontSize: '1.rem',
            borderColor: '#fff',
        };

        return (
            <div>
                {/* Deficiência */}
                <div style={formGroupStyle}>
                    <label
                        htmlFor="prontuario_deficiencia"
                        style={formLabelStyle}
                        className="form-label"
                    >
                        Deficiência
                    </label>
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <div className="form-check">
                                <input
                                    data-api="form-prontuario"
                                    type="radio"
                                    className="form-check-input"
                                    id="prontuario_deficiencia1"
                                    name="prontuario_deficiencia"
                                    value="Y"
                                    checked={formData.prontuario_deficiencia === "Y"}
                                    onChange={handleChange}
                                />
                                <label className="form-check-label" htmlFor="prontuario_deficiencia1">Sim</label>
                            </div>
                            <div className="form-check">
                                <input
                                    data-api="form-prontuario"
                                    type="radio"
                                    className="form-check-input"
                                    id="prontuario_deficiencia2"
                                    name="prontuario_deficiencia"
                                    value="N"
                                    checked={formData.prontuario_deficiencia === "N"}
                                    onChange={handleChange}
                                />
                                <label className="form-check-label" htmlFor="prontuario_deficiencia2">Não</label>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6">
                            {(formData.prontuario_deficiencia === 'Y') && (
                                <div>
                                    <textarea
                                        data-api="form-prontuario"
                                        id="prontuario_descricao_deficiencia"
                                        name="prontuario_descricao_deficiencia"
                                        value={formData.prontuario_descricao_deficiencia || ''}
                                        onChange={handleChange}
                                        placeholder="Em caso de 'Sim', descreva"
                                        className="form-control"
                                        style={formControlStyle}
                                    />
                                </div>
                            )}
                        </div>
                    </div>
                </div>
                {/* Deficiência */}
            </div >
        );
    };
</script>