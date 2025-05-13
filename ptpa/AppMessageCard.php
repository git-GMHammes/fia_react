<script type="text/babel">
    const AppMessageCard = ({ parametros = {}, modalId = {}, onClose = null }) => {
        // console.log("AppMessageCard rendering, parametros:", parametros);

        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
        const modalInstanceRef = React.useRef(null);
        const buttonRef = React.useRef(null);
        const previousShowRef = React.useRef(parametros.show);

        // console.log('src/app/Views/fia/ptpa/AppMessageCard.php');

        React.useEffect(() => {
            if (parametros.show) {
                setShowAlert(true);
                setAlertType(parametros.type);
                setAlertMessage(parametros.message);

                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    try {
                        // Armazenar a instância do modal na ref para uso posterior
                        modalInstanceRef.current = new bootstrap.Modal(modalElement);
                        modalInstanceRef.current.show();

                        // Listener para fechar o modal ao pressionar Tab
                        const handleTabPress = (event) => {
                            if (event.key === 'Tab') {
                                if (modalInstanceRef.current) {
                                    // Remover o foco do botão antes de esconder o modal
                                    if (buttonRef.current) {
                                        buttonRef.current.blur();
                                    }
                                    modalInstanceRef.current.hide();
                                    cleanupModal();
                                }
                            }
                        };

                        // Função para limpar o modal adequadamente
                        const cleanupModal = () => {
                            setTimeout(() => {
                                document.body.style.overflow = '';
                                document.body.classList.remove('modal-open');
                                const modalBackdrop = document.querySelector('.modal-backdrop');
                                if (modalBackdrop) {
                                    modalBackdrop.remove();
                                }
                            }, 300);
                        };

                        // Listener para o evento hidden.bs.modal
                        const handleModalHidden = () => {
                            // Remover o foco do botão antes de aplicar aria-hidden
                            if (buttonRef.current) {
                                buttonRef.current.blur();
                            }
                            cleanupModal();
                        };

                        // Adicionar ouvintes de eventos
                        modalElement.addEventListener('hidden.bs.modal', handleModalHidden);
                        window.addEventListener('keydown', handleTabPress);

                        // Função de limpeza
                        return () => {
                            window.removeEventListener('keydown', handleTabPress);
                            modalElement.removeEventListener('hidden.bs.modal', handleModalHidden);
                        };
                    } catch (error) {
                        console.error("Erro ao inicializar o modal:", error);
                    }
                }
            }

        }, [parametros.show, parametros.type, parametros.message, modalId]);

        // Método explícito para fechar o modal
        const closeModal = () => {
            console.log("Fechando modal programaticamente");
            if (modalInstanceRef.current) {
                try {
                    // Fechar usando a API do Bootstrap
                    modalInstanceRef.current.hide();
                } catch (error) {
                    console.error("Erro ao fechar modal via Bootstrap:", error);
                    // Fallback: fechar manualmente
                    cleanupModal();
                }
            } else {
                cleanupModal();
            }
            setShowAlert(false);
        };

        // Efeito para fechar o modal
        React.useEffect(() => {
            if (parametros.show === false && showAlert === true) {
                console.log("Detectada solicitação para fechar o modal");
                closeModal();
            }
        }, [parametros.show, showAlert]);

        const getClassForType = () => {
            switch (alertType) {
                case 'primary': return 'bg-primary text-white';
                case 'secondary': return 'bg-secondary text-white';
                case 'success': return 'bg-success text-white';
                case 'danger': return 'bg-danger text-white';
                case 'warning': return 'bg-warning text-dark';
                case 'info': return 'bg-info text-dark';
                case 'light': return 'bg-light text-dark';
                case 'dark': return 'bg-dark text-white';
                default: return 'bg-light text-white';
            }
        };

        const handleClose = () => {
            // Remover o foco do botão antes de fechar o modal
            if (buttonRef.current) {
                buttonRef.current.blur();
            }
            setShowAlert(false);
        };

        return (
            <div>
                <div
                    className="modal fade m-0 p-0"
                    id={modalId}
                    tabIndex={-1}
                    aria-labelledby="exampleModalLabel"
                    data-bs-backdrop="static"
                    data-bs-keyboard="false"
                >
                    <div className="modal-dialog modal-dialog-centered">
                        <div className="modal-content bg-dark">
                            <div className={`card ${getClassForType()}`} style={{ width: '100%', height: '100%', borderRadius: '0' }}>
                                <div className="card-body d-flex flex-column align-items-center m-3 p-3">
                                    <p
                                        className="card-text text-center"
                                        dangerouslySetInnerHTML={{ __html: alertMessage }}
                                    >
                                    </p>
                                    <button
                                        ref={buttonRef}
                                        type="button"
                                        className="btn btn-outline-dark btn-sm m-2 p-2"
                                        data-bs-dismiss="modal"
                                        onClick={handleClose}
                                    >
                                        Ok
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    };
</script>