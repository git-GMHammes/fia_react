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

<div class="app_form_principal" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
    const AppFormPrincipal = () => {
        // Variáveis recebidas do Backend
        const parametros = JSON.parse(document.querySelector('.app_form_principal').getAttribute('data-result'));
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
                <div className="m-3">
                    <form className="row g-3 needs-validation" noValidate>
                        <div className="col-md-4">
                            <label htmlFor="validationCustom01" className="form-label">First name</label>
                            <input type="text" id="validationCustom01" name="campo" className="form-control" defaultValue="Mark" required />
                            <div className="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div className="col-md-4">
                            <label htmlFor="validationCustom02" className="form-label">Last name</label>
                            <input type="text" id="validationCustom02" className="form-control" defaultValue="Otto" required />
                            <div className="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div className="col-md-4">
                            <label htmlFor="validationCustomUsername" className="form-label">Username</label>
                            <div className="input-group has-validation">
                                <span id="inputGroupPrepend" className="input-group-text">@</span>
                                <input type="text" className="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required />
                                <div className="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6">
                            <label htmlFor="validationCustom03" className="form-label">City</label>
                            <input type="text" id="validationCustom03" className="form-control" required />
                            <div className="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div className="col-md-3">
                            <label htmlFor="validationCustom04" className="form-label">State</label>
                            <select id="validationCustom04" className="form-select" required>
                                <option selected disabled value>Choose...</option>
                                <option>...</option>
                            </select>
                            <div className="invalid-feedback">
                                Please select a valid state.
                            </div>
                        </div>
                        <div className="col-md-3">
                            <label htmlFor="validationCustom05" className="form-label">Zip</label>
                            <input type="text" id="validationCustom05" className="form-control" required />
                            <div className="invalid-feedback">
                                Please provide a valid zip.
                            </div>
                        </div>
                        <div className="col-12">
                            <div className="form-check">
                                <input className="form-check-input" type="checkbox" defaultValue id="invalidCheck" required />
                                <label className="form-check-label" htmlFor="invalidCheck">
                                    Agree to terms and conditions
                                </label>
                                <div className="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                        </div>
                        <div className="col-12">
                            <button className="btn btn-primary" type="submit">Submit form</button>
                        </div>
                    </form>
                    <form className="was-validated">
                        <div className="mb-3">
                            <label htmlFor="validationTextarea" className="form-label">Textarea</label>
                            <textarea id="validationTextarea" className="form-control" placeholder="Required example textarea" required defaultValue={""} />
                            <div className="invalid-feedback">
                                Please enter a message in the textarea.
                            </div>
                        </div>
                        <div className="form-check mb-3">
                            <input type="checkbox" id="validationFormCheck1" className="form-check-input" required />
                            <label className="form-check-label" htmlFor="validationFormCheck1">Check this checkbox</label>
                            <div className="invalid-feedback">Example invalid feedback text</div>
                        </div>
                        <div className="form-check">
                            <input type="radio" id="validationFormCheck2" name="radio-stacked" className="form-check-input" required />
                            <label className="form-check-label" htmlFor="validationFormCheck2">Toggle this radio</label>
                        </div>
                        <div className="form-check mb-3">
                            <input type="radio" id="validationFormCheck3" name="radio-stacked" className="form-check-input" required />
                            <label className="form-check-label" htmlFor="validationFormCheck3">Or toggle this other radio</label>
                            <div className="invalid-feedback">More example invalid feedback text</div>
                        </div>
                        <div className="mb-3">
                            <select className="form-select" required aria-label="select example">
                                <option value>Open this select menu</option>
                                <option value={1}>One</option>
                                <option value={2}>Two</option>
                                <option value={3}>Three</option>
                            </select>
                            <div className="invalid-feedback">Example invalid select feedback</div>
                        </div>
                        <div className="mb-3">
                            <input type="file" className="form-control" aria-label="file example" required />
                            <div className="invalid-feedback">Example invalid form file feedback</div>
                        </div>
                        <div className="mb-3">
                            <button className="btn btn-primary" type="submit" disabled>Submit form</button>
                        </div>
                    </form>
                </div>
            </div>
        );
    };
    // ReactDOM.render(<AppFormPrincipal />, document.querySelector('.app_form_principal'));
    
    const rootElement = document.querySelector('.app_form_principal');
    const root = ReactDOM.createRoot(rootElement);
    root.render(<AppFormPrincipal />);
</script>
<?php
$parametros_backend = array();
?>
