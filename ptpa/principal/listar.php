<?php
$parametros_backend = array(
    'DEBUG_MY_PRINT' => false,
    'request_scheme' => $_SERVER['REQUEST_SCHEME'],
    'server_name' => $_SERVER['SERVER_NAME'],
    'server_port' => $_SERVER['SERVER_PORT'],
    'result' => isset($result) ? ($result) : (array()),
    'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
    'base_url' => base_url(),
);
?>

<div class="app_listar_principal" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppListarPrincipal = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_listar_principal').getAttribute('data-result'));
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const request_scheme = parametros.request_scheme;
        const server_name = parametros.server_name;
        const server_port = parametros.server_port;
        const base_url = parametros.base_url;
        // 
        return (
            <div>
                {debugMyPrint?(
                    <div className="row">
                        <div className="alert alert-danger" role="alert">
                                Você esta utilizando o a Tela em modo DEBUG nenhuma API com relação a Banco de Dados irá funcionar
                        </div>
                    </div>
                ):null}
                Lista de periodos
            </div>
        );
    };
    ReactDOM.render(<AppListarPrincipal />, document.querySelector('.app_listar_principal'));
    const rootElement = document.querySelector('.app_listar_principal');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppListarPrincipal />);
</script>
<?php
$parametros_backend = array();
?>