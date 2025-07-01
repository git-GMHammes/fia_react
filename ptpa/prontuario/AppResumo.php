<script type="text/babel">
    const AppResumo = (
        {
            parametros = {},
            formData = {},
            setFormData = () => { },
            setResumoConfirmado
        }
    ) => {
        const [date, setDate] = React.useState("");
        const [check, setCheck] = React.useState(false);
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null,
        });

        React.useEffect(() => {
            // console.log("AppResumo: getURI", getURI);
            // console.log("AppResumo: debugMyPrint", debugMyPrint);
            const today = new Date();
            const options = { day: "numeric", month: "long", year: "numeric" };
            const formattedDate = today.toLocaleDateString("pt-BR", options);
            setDate(formattedDate);
        }, []);

        const submitAllForms = (filtro) => {
            console.log(`submitAllForms chamado com filtro: ${filtro}`);

            if (filtro === 'botao-resumo') {
                console.log('Filtro para o botão detectado');
                setResumoConfirmado(true); // Altere o estado para `true`
                setFormData((prev) => ({
                    ...prev,
                    resumo: true
                }));
                console.log('Estado resumoConfirmado atualizado para true');
            } else if (filtro === 'botao-resumo-off') {
                setResumoConfirmado(false); // Altere o estado para `true`
                setFormData((prev) => ({
                    ...prev,
                    resumo: false
                }));
            } else {
                console.error('Filtro não identificado!');
            }
            // Fecha o modal do Bootstrap 5
            const modalElement = document.getElementById('modalResumoAlteracoes');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide(); // Fecha o modal
            }
        };

        // Função handleFocus para receber foco
        const handleFocus = async (event) => {
            const { name, value } = event.target;

            // console.log('handleFocus: ', name);
            // console.log('handleFocus: ', value);

            setMessage({ show: false, type: null, message: null });
            if (name === 'resumo' && check === false) {
                setCheck(true);
            } else if (name === 'resumo' && check === true) {
                setCheck(false);
            }

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança de campo
            if (name === 'variavel_001') {
                // console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, type, checked, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: type === 'checkbox' ? checked : value,
            }));

            if (name === 'resumo') {
                setResumoConfirmado(checked);
                console.log('Estado resumoConfirmado atualizado:', checked);
            }

            console.log('FormData:', formData);
        };


        // Função que executa após a retirada do foco
        const handleBlur = async (event) => {
            const { name, value } = event.target;

            // console.log('name handleBlur: ', name);
            // console.log('value handleBlur: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Verifica se a mudança é no campo 'variavel_001'
            if (name === 'variavel_001') {
                // console.log('variavel_001');
                // submitAllForms('filtro-api');
            }
            setMessage({ show: false, type: null, message: null });
        }

        // console.log("AppResumo :: ", parametros);
        // return false;

        return (
            <div>
                {(typeof AppJson === "undefined1")
                    ? (
                        <div>

                            <AppJson
                                parametros={parametros}
                                dbResponse={getURI}
                            />

                        </div>
                    ) : (
                        <div>
                            {/* <p className="text-danger">AppJson não lacançado.</p> */}
                        </div>
                    )
                }
                <div className="row">
                    <div className="col-12 col-sm-12 m-4">
                        <div className="form-check">
                            <div>
                                <input
                                    className="form-check-input"
                                    type="checkbox"
                                    id="resumo"
                                    name="resumo"
                                    checked={formData.resumo || false} // Vinculado ao estado formData
                                    onChange={handleChange} // Manipula as mudanças
                                    disabled={!formData.resumo || false} // Desabilita o checkbox se já estiver marcado
                                />
                                <label className="form-check-label" htmlFor="resumo">
                                </label>
                                {/* Button trigger modal */}
                                <button type="button" className="btn m-0 p-0 text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalResumoAlteracoes">
                                    Clique aqui para ler o resumo das alterações.
                                </button>
                            </div>
                        </div>

                        {/* Modal */}
                        <div className="modal fade" id="modalResumoAlteracoes" data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby="modalResumoAlteracoesLabel" aria-hidden="true">
                            <div className="modal-dialog modal-dialog-centered">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5 className="modal-title" id="modalResumoAlteracoesLabel">Resumo das Alterações</h5>
                                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                                    </div>
                                    <div className="modal-body">
                                        <div className="container mt-5">
                                            <div className="card">
                                                <div className="card-header bg-primary text-white">
                                                    <h4 className="text-center">Leia com atenção</h4>
                                                </div>
                                                <div className="card-body shadow-lg">
                                                    <div className="card-body">
                                                        <h2 className="card-title text-center mb-4">Campos Alterados</h2>
                                                    </div>
                                                    <form>
                                                        <div className="modal-body">
                                                            <p><strong>Nome do Adolescente:</strong> {formData.adolescente_Nome}</p>
                                                            <p><strong>Medidas Socioeducativas:</strong> {formData.prontuario_MedidasSocioEducativas}</p>
                                                            <p><strong>Uso de Drogas:</strong> {formData.prontuario_UsodeDrogas}</p>
                                                            <p><strong>Cadastro Único:</strong> {formData.prontuario_CadUnico}</p>
                                                            <p><strong>Encaminhamento de Orgão:</strong> {formData.prontuario_EncaminhamentoOrgao}</p>
                                                            <p><strong>Possui Deficiência:</strong> {formData.prontuario_Deficiencia}</p>
                                                            <p><strong>Necessita de Mediador:</strong> {formData.prontuario_NecesMediador}</p>
                                                            <p><strong>Diagnóstico Psicológico:</strong> {formData.prontuario_Diagnostico}</p>
                                                            <p><strong>Renda Familiar:</strong> {formData.prontuario_RendaFamiliar}</p>
                                                            <p><strong>Referência na Rede:</strong> {formData.prontuario_ReferenciaNaRede}</p>
                                                            <p><strong>Tipo familiar:</strong> {formData.prontuario_TipoFamiliar}</p>
                                                            <p><strong>Participação em Programas Sociais:</strong> {formData.prontuario_ParticipacaoProg}</p>
                                                            <p><strong>Vulnerabilidade:</strong> {formData.prontuario_Vulnerabilidade}</p>
                                                            <p><strong>Pontuação Total:</strong> {formData.prontuario_PontuacaoTotal}</p>
                                                        </div>
                                                    </form>
                                                    <form className="needs-validation" noValidate onSubmit={(e) => {
                                                        e.preventDefault();
                                                        submitAllForms(`botao-resumo`);
                                                    }}>
                                                        <button
                                                            className="btn btn-outline-success btn-sm"
                                                            type="submit">
                                                            Confirmar, Aceitar e Salvar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="modal-footer">
                                        <form className="needs-validation" noValidate onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`botao-resumo-off`);
                                        }}>
                                            <button
                                                className="btn btn-outline-danger"
                                                type="submit"
                                            >
                                                Não Concordo
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div >
        );
    };
</script>