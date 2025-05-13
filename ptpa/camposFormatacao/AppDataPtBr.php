<script type="text/babel">
    const AppDataPtBr = (
        {
            parametros
        }
    ) => {
        // Converte a data recebida no formato "AAAA-MM-DD" para "DD-MM-AAAA" sem fuso horário
        const formatarDataPtBr = (dataIso) => {
            if (!dataIso || dataIso === '0000-00-00') {
                return (
                    <div className="p-2">
                        <span className="text-danger">Data em branco</span>
                    </div>
                );

            }

            const [ano, mes, dia] = dataIso.split('-');
            return (
                <div className="p-2">
                    {dia}-{mes}-{ano}
                </div>
            );
        };

        return (
            <div>
                <span>{formatarDataPtBr(parametros)}</span>
            </div>
        );
    };
</script>


<!-- <AppDataPtBr key={index} parametros={item.periodo_data_inicio} /> -->