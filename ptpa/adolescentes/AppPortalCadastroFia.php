<?php

$parametros_backend = array(
    'title' => isset($metadata['page_title']) ? ($metadata['page_title']) : ('TITULO NÃO INFORMADO'),
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'environment' => isset($metadata['environment']) ? ($metadata['environment']) : ('PRD'),
    'base_url' => base_url(),
);

?>

<div class="app_posrtal_cad_fia" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppPortalCadastroFia = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_posrtal_cad_fia').getAttribute('data-result'));
        const [objeto, setObjeto] = React.useState([]);
        const base_url = parametros.base_url;
        const caminhoImagem = `${base_url}assets/img/logos/PRODERJ_2T.png`;
        console.log('caminhoImagem :: ', caminhoImagem)

        return (
            <div className="container font-sans">
                <h2 className="text-center my-4">Portal FIA</h2>

                {/* Imagem adicionada no meio do componente */}
                <div className="container">
                    <div className="text-center my-4">
                        <img
                            src={caminhoImagem}
                            alt="Logo do Sistema"
                            className="img-fluid rounded"
                            style={{ maxWidth: '600px', boxShadow: '0 16px 30px rgba(0,0,0,0.1)' }}
                        />
                    </div>
                </div>
                <h2 className="text-center my-4">Cadastro confirmado com sucesso.</h2>
                <h4 className="text-center my-4">Um e-mail de confirmação será encaminhado.</h4>
            </div>
        );
    };

    const rootElement = document.querySelector('.app_posrtal_cad_fia');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppPortalCadastroFia />);
</script>