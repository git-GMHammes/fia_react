<script type="text/babel">
    const AppCadastroUnico = (
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

            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setFormData((prev) => {
                const updatedFormData = { ...prev, [name]: value };

                // Garante ajuste derivado aqui também
                if (name === "prontuario_cad_unico") {
                    updatedFormData.prontuario_cad_unico_pts = value === "Y" ? 1 : 0;
                }

                return updatedFormData;
            });

            setMessage({ show: false, type: null, message: null });
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            console.log('handleChange: ', name);
            console.log('handleChange: ', value);

            setFormData((prev) => {
                const updatedFormData = { ...prev, [name]: value };

                if (name === "prontuario_cad_unico") {
                    updatedFormData.prontuario_cad_unico_pts = value === "Y" ? 1 : 0;
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

            console.log('name handleBlur: ', name);
            console.log('value handleBlur: ', value);

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

        const formControlStyle = {
            fontSize: '1.rem',
            borderColor: '#fff',
        };

        return (
            <div>
                {/* Cadastro único */}
                <div style={formGroupStyle}>
                    <label
                        htmlFor="prontuario_cad_unico"
                        style={formLabelStyle} className="form-label"
                    >
                        Cadastro único
                    </label>
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <div className="form-check">
                                <input
                                    data-api={`form-${origemForm}`}
                                    type="radio"
                                    className="form-check-input"
                                    id="prontuario_cad_unico1"
                                    name="prontuario_cad_unico"
                                    value="Y"
                                    checked={formData.prontuario_cad_unico === "Y"}
                                    onChange={handleChange}
                                />
                                <label className="form-check-label" htmlFor="prontuario_cad_unico1">Sim</label>
                            </div>
                            <div className="form-check">
                                <input
                                    data-api={`form-${origemForm}`}
                                    type="radio"
                                    className="form-check-input"
                                    id="prontuario_cad_unico2"
                                    name="prontuario_cad_unico"
                                    value="N"
                                    checked={formData.prontuario_cad_unico === "N"}
                                    onChange={handleChange}
                                />
                                <label className="form-check-label" htmlFor="prontuario_cad_unico2">Não</label>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6">
                            {(formData.prontuario_cad_unico === 'Y') && (
                                <div>
                                    <textarea
                                        data-api={`form-${origemForm}`}
                                        id="prontuario_programa_social"
                                        name="prontuario_programa_social"
                                        value={formData.prontuario_programa_social || ''}
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
                {/* Cadastro único */}
            </div >
        );
    };
</script>