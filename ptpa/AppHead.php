<?php
$user_session = (session()->get('user_session')) ? (session()->get('user_session')) : (array());

$parametros_backend = array(
  'DEBUG_MY_PRINT' => false,
  'user_session' => $user_session,
  'request_scheme' => $_SERVER['REQUEST_SCHEME'],
  'server_name' => $_SERVER['SERVER_NAME'],
  'server_port' => $_SERVER['SERVER_PORT'],
  'getURI' => isset($metadata['getURI']) ? ($metadata['getURI']) : (array()),
  'base_url' => base_url(),
);
?>

<div class="app_head" data-result='<?php echo json_encode($parametros_backend); ?>'></div>

<script type="text/babel">
  const AppHead = () => {

    // Variáveis recebidas do Backend
    const parametros = JSON.parse(document.querySelector('.app_head').getAttribute('data-result'));

    // Prepara as Variáveis do REACT recebidas pelo BACKEND
    const user_session = parametros.user_session.FIA || {};
    const getURI = parametros.getURI;
    const debugMyPrint = parametros.DEBUG_MY_PRINT;
    const request_scheme = parametros.request_scheme;
    const server_name = parametros.server_name;
    const server_port = parametros.server_port;
    const base_url = parametros.base_url;
    // console.log("user_session:: ", user_session);

    // Nova constante de estilo para o texto "Footer"
    const headerTextStyle = {
      backgroundImage: 'linear-gradient(to right, #330033, #14007A)',
      color: 'white',
      textDecoration: 'none',
      padding: '10px'
    };

    const imgGovBr = {
      width: 'auto',
      height: '25px',
    };

    return (
      <div style={headerTextStyle}>
        <div className="row">
          <div className="col-12 col-sm-4">
            <div className="d-flex justify-content-center align-items-center h-100">
              <a href={`${base_url}index.php/fia/ptpa/principal/endpoint/indicadores`} role="button"><img src={`${base_url}assets/img/fia/logo_composta.png`} alt="" style={{ height: "80px" }} /></a>
            </div>
          </div>
          <div className="col-12 col-sm-4">
            <div className="d-flex justify-content-center align-items-center h-100">
              <div>
                {/*<img src={`${base_url}assets/img/fia/ptpa3.png`} alt="" style={{ height: "100px" }} />*/}
                <img src={`${base_url}assets/img/logos/governo_t.png`} alt="" style={{ height: "100px" }} />
              </div>
            </div>
          </div>
          <div className="col-12 col-sm-4">
            <div className="h-100">
              <div className="d-flex justify-content-center align-items-center m-5">
                <div className="dropdown bg-transparent text-white">

                  {/* Botão Login*/}
                  {Object.keys(user_session).length > 0 ? (
                    <div>
                      <div>
                        <button className="btn bg-transparent text-white dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                          <i className="bi bi-person" /> {user_session.Nome || 'Usuário'}
                        </button>
                        <ul className="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                          <li>
                            <div className="p-2" style={{ width: '18rem' }}>
                              <div className="border border-dark p-3">
                                <div className="card-text"><b>Perfil</b>: <i>{user_session.PerfilDescricao || ''}</i></div>
                                <div className="card-text mb-2"><b>Cargo</b>: <i>{user_session.CargoFuncao || ''}</i></div>
                                <div className="d-flex justify-content-end mt-4">
                                  <a className="btn btn-outline-danger mb-1" href={`${base_url}index.php/fia/ptpa/usuario/api/sair`} role="button">Sair</a>
                                </div>
                              </div>
                              {(debugMyPrint) && (
                                <div>
                                  <p>
                                    DEBUG TRUE
                                  </p>
                                  {/* Exibindo o JSON formatado em um <pre> */}
                                  <pre style={{ backgroundColor: "#f4f4f4", padding: "10px", borderRadius: "5px" }}>
                                    {JSON.stringify(user_session, null, 4)}
                                  </pre>
                                </div>
                              )}
                            </div>
                          </li>
                        </ul>
                      </div>
                      <div>
                        <button className="btn bg-transparent text-white dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                          <i className="bi bi-geo-alt" /> {user_session.NomeUnidade || 'Local da Unidade'}
                        </button>
                        <ul className="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                          <li>
                            <div className="p-2" style={{ width: '18rem' }}>
                              <div className="border border-dark p-3">
                                <div className="card-text mb-2"><b>Unidade</b>: <i>{user_session.NomeUnidade || ''}</i></div>
                                <div className="card-text mb-2"><b>Endereço</b>: <i>{user_session.EnderecoUnidade || ''}</i></div>
                                <div className="card-text mb-2"><b>Cidade</b>: <i>{user_session.CidadeUnidade || ''}</i></div>
                                <div className="d-flex justify-content-end mt-4">
                                  <a className="btn btn-outline-danger mb-1" href={`${base_url}index.php/fia/ptpa/usuario/api/sair`} role="button">Sair</a>
                                </div>
                              </div>
                              {(debugMyPrint) && (
                                <div>
                                  <p>
                                    DEBUG TRUE
                                  </p>
                                  {/* Exibindo o JSON formatado em um <pre> */}
                                  <pre style={{ backgroundColor: "#f4f4f4", padding: "10px", borderRadius: "5px" }}>
                                    {JSON.stringify(user_session, null, 4)}
                                  </pre>
                                </div>
                              )}
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  ) : (
                    <div>
                      {/* Botão GOV.BR*/}
                      {(
                        server_name === "127.0.0.1" ||
                        server_name === "localhost" ||
                        server_name === "fiaptpa.proderj.rj.gov.br"
                      ) && (
                          <a className="btn bg-transparent text-white" href={`${base_url}exemple/group/endpoint/gov_br`} role="button">
                            <img className="img-fluid" style={imgGovBr} src={`${base_url}assets/img/fia/gov_br_logo.webp`} alt="assets/img/fia/gov_br_logo.webp" />
                          </a>
                        )}
                    </div>
                  )}
                  {/* Botão Login*/}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  };
  const rootElement = document.querySelector('.app_head');
  const root = ReactDOM.createRoot(rootElement);
  root.render(<AppHead />);
  // ReactDOM.render(<AppHead />, document.querySelector('.app_head'));
</script>
<?php
$parametros_backend = array();
?>