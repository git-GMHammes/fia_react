<?php
$parametros_backend = array(
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url()
);
?>

<div class="main_responsavel" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const MainResponsavel = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.main_responsavel').getAttribute('data-result'));
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;

        return (
            <div>
                {debugMyPrint?(
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                                Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ):null}
                Tela Principal
            </div>
        );
    };
    //ReactDOM.render(<MainResponsavel />, document.querySelector('.main_responsavel'));

    const rootElement = document.querySelector('.main_responsavel');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<MainResponsavel />);
</script>
<?php
$parametros_backend = array();
?>