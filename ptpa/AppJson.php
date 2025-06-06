<script type="text/babel">
  const AppJson = (
    {
      parametros = { parametros },
      dbResponse = {}
    }
  ) => {

    const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
    const environment = parametros.environment || 'PRD';

    return (
      <div>
        {(debugMyPrint && environment === 'DEV') && (
          <div>
            <div className="alert alert-danger m-5" role="alert">
              Exibição do JSON
            </div>
            <div className="d-flex justify-content-center align-items-center min-vh-100 m-5">
              <pre style={{ whiteSpace: "pre-wrap", wordWrap: "break-word" }}>
                {JSON.stringify(dbResponse, null, 2)}
              </pre>
            </div>
          </div>
        )}
      </div>
    );
  };
</script>