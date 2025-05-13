<script type="text/babel">
    const AppLoadingPage = ({
        parametros = {}
    }) => {
        // Use o parâmetro isLoadingPage para controlar o estado
        const [loading, setLoading] = React.useState(parametros.isLoadingPage || false);
        // src\app\Views\fia\ptpa\AppLoadingPage.php
        // Observe se o parâmetro isLoadingPage muda e atualize o estado
        React.useEffect(() => {
            setLoading(parametros.isLoadingPage || false);
        }, [parametros.isLoadingPage]);

        // Estilos para o overlay e o efeito de blur
        const overlayStyle = {
            position: 'fixed',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            backgroundColor: 'rgba(0, 0, 0, 0.5)',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            zIndex: 9999
        };

        // Aplicar blur ao fundo apenas quando estiver carregando
        React.useEffect(() => {
            const mainContent = document.getElementById('root');

            if (loading) {
                // Aplicar efeitos quando loading for true
                document.body.style.overflow = 'hidden';
                if (mainContent) {
                    mainContent.style.filter = 'blur(5px)';
                }
            } else {
                // Restaurar quando loading for false
                document.body.style.overflow = '';
                if (mainContent) {
                    mainContent.style.filter = '';
                }
            }

            return () => {
                document.body.style.overflow = '';
                const mainContent = document.getElementById('root');
                if (mainContent) {
                    mainContent.style.filter = '';
                }
            };
        }, [loading]);

        console.log("AppLoadingPage rendering, loading state:", loading);
        console.log("AppLoadingPage parametros:", parametros);

        return (
            <div>
                {loading && ReactDOM.createPortal(
                    <div style={overlayStyle}>
                        <div className="spinner-border text-light" role="status">
                            <span className="visually-hidden">Loading...</span>
                        </div>
                    </div>,
                    document.body
                )}
            </div>
        );
    };

</script>