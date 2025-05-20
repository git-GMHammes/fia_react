<script type="text/babel">
    const AppTermosUso = (
        {
            parametros = {},
            formData = {},
            setFormData = () => { },
            setTermoAceito
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
            // console.log("AppTermosUso: getURI", getURI);
            // console.log("AppTermosUso: debugMyPrint", debugMyPrint);
            const today = new Date();
            const options = { day: "numeric", month: "long", year: "numeric" };
            const formattedDate = today.toLocaleDateString("pt-BR", options);
            setDate(formattedDate);
        }, []);

        const submitAllForms = (filtro) => {
            console.log(`submitAllForms chamado com filtro: ${filtro}`);

            if (filtro === 'botao-termo') {
                console.log('Filtro para o botão detectado');
                setTermoAceito(true); // Altere o estado para `true`
                setFormData((prev) => ({
                    ...prev,
                    termo: true
                }));
                console.log('Estado termoAceito atualizado para true');
            } else if (filtro === 'botao-termo-off') {
                setTermoAceito(false); // Altere o estado para `true`
                setFormData((prev) => ({
                    ...prev,
                    termo: false
                }));
            } else {
                console.error('Filtro não identificado!');
            }
            // Fecha o modal do Bootstrap 5
            const modalElement = document.getElementById('staticTermoUso');
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
            if (name === 'termo' && check === false) {
                setCheck(true);
            } else if (name === 'termo' && check === true) {
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

            if (name === 'termo') {
                setTermoAceito(checked);
                console.log('Estado termoAceito atualizado:', checked);
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

        // console.log("AppTermosUso :: ", parametros);
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
                            {(formData.termo) ? (
                                <div>
                                    <input
                                        className="form-check-input"
                                        type="checkbox"
                                        id="termo"
                                        name="termo"
                                        checked={formData.termo || false} // Vinculado ao estado formData
                                        onChange={handleChange} // Manipula as mudanças
                                        disabled={!formData.termo || false} // Desabilita o checkbox se já estiver marcado
                                    />
                                    <label className="form-check-label" htmlFor="termo">
                                        {/* Button trigger modal */}
                                        <button type="button" className="btn m-0 p-0 text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#staticTermoUso">
                                            Para que os dados sejam enviados, você precisa concordar com os termos e politicas do Programa FIA/PTPA. clique aqui para ler  o termo.
                                        </button>
                                    </label>
                                </div>
                            ) : (
                                <div>
                                    {/* Button trigger modal */}
                                    < button type="button" className="btn m-0 p-0 text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#staticTermoUso">
                                        Para que os dados sejam enviados, você precisa concordar com os termos e politicas do Programa FIA/PTPA. clique aqui para ler  o termo.
                                    </button>
                                </div>
                            )}
                        </div>
                        {/* Modal */}
                        <div className="modal fade" id="staticTermoUso" data-bs-backdrop="static" data-bs-keyboard="false" tabIndex={-1} aria-labelledby="staticTermoUsoLabel" aria-hidden="true">
                            <div className="modal-dialog modal-dialog-centered">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <h5 className="modal-title" id="staticTermoUsoLabel">Termo de Aceite para Cadastro de Dados Pessoais</h5>
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
                                                        <h2 className="card-title text-center mb-4">Termo de Aceitação</h2>

                                                        <p className="card-text mb-4">
                                                            Ao realizar a inscrição no Programa Trabalho Protegido na Adolescência (PTPA), declaro que estou ciente e de acordo com as seguintes condições:
                                                        </p>

                                                        <div className="mb-4">
                                                            <p><strong>1.</strong> Confirmo que as informações fornecidas no formulário de inscrição são verdadeiras, completas e corretas.</p>

                                                            <p><strong>2.</strong> Reconheço que o Programa Trabalho Protegido na Adolescência possui critérios específicos para admissão, incluindo idade entre 15 e 16 anos e cinco meses até a data de início das aulas.</p>

                                                            <p><strong>3.</strong> Concordo que a seleção para o programa está sujeita à análise dos dados fornecidos e à disponibilidade de vagas na unidade educacional escolhida.</p>

                                                            <p><strong>4.</strong> Autorizo o uso das informações fornecidas para a análise de elegibilidade e para comunicações relacionadas ao programa.</p>

                                                            <p><strong>5.</strong> Estou ciente de que, em caso de falsidade de informações, minha inscrição poderá ser desconsiderada.</p>

                                                            <p><strong>6.</strong> Autorizo a consulta de informações adicionais, se necessário, junto às bases de dados públicas para a validação dos dados apresentados.</p>

                                                            <p><strong>7.</strong> Concordo em seguir as orientações e diretrizes do programa, conforme estipulado nos regulamentos internos do PTPA.</p>
                                                        </div>

                                                        <div className="text-muted small">
                                                            Este termo deve ser apresentado ao final do processo de inscrição, com a opção de aceite obrigatória para a finalização do cadastro.
                                                        </div>
                                                    </div>
                                                    <form>
                                                        <div className="mb-3">
                                                            <label htmlFor="location" className="form-label"><strong>Local e Data:</strong></label>
                                                            <input
                                                                type="text"
                                                                id="location"
                                                                className="form-control"
                                                                value={`Rio de Janeiro, ${date}`}
                                                                readOnly
                                                            />
                                                        </div>
                                                        {/*
                                                            */}
                                                        <div className="mb-3">
                                                            <label htmlFor="fullName" className="form-label"><strong>Nome Completo do Titular Responsável:</strong></label>
                                                            <input
                                                                type="text"
                                                                id="fullName"
                                                                name="fullName"
                                                                className="form-control"
                                                                value={formData.Responsavel_Nome || ''}
                                                                readOnly
                                                            />
                                                        </div>
                                                        {/*
                                                            */}
                                                        <div className="mb-3">
                                                            <label htmlFor="fullName" className="form-label"><strong>Nome Completo do Adolescente:</strong></label>
                                                            <input
                                                                type="text"
                                                                id="fullName"
                                                                className="form-control"
                                                                value={formData.Nome || ''}
                                                                readOnly
                                                            />
                                                        </div>
                                                        <div className="mb-3">
                                                            <label htmlFor="institution" className="form-label"><strong>Nome e Razão Social da
                                                                Instituição:</strong></label>
                                                            <input
                                                                type="text"
                                                                id="institution"
                                                                className="form-control"
                                                                value="Fundação para a Infância e Adolescência"
                                                                readOnly
                                                            />
                                                        </div>
                                                    </form>
                                                    <form className="was-validated" onSubmit={(e) => {
                                                        e.preventDefault();
                                                        submitAllForms(`botao-termo`);
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
                                        <form className="was-validated" onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`botao-termo-off`);
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