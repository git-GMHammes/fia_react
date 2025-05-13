<script type="text/babel">
    const AppDataAdmissao = ({ formData = {}, setFormData = () => {}, parametros = {} }) => {
        
        // Calcula a data atual
        const dataAtual = new Date();

        // Função para calcular data com base no ano de referência
        const calcularData = (anos = 0) => {
            const data = new Date(dataAtual);
            data.setFullYear(dataAtual.getFullYear() + anos);
            return data.toISOString().split('T')[0];
        };

        // Configurações de data mínima e máxima com base nos parâmetros
        const dataMinima = parametros.minData || calcularData(-(parametros.anosAtras || 16));
        const dataMaxima = parametros.maxData || calcularData(parametros.anosFuturo || 0);
        
        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };
        
        // Função de validação para garantir que a data esteja no intervalo permitido
        const handleBlur = (event) => {
            const { name, value } = event.target;
            
            if (name === 'DataAdmissao') {
                const dataSelecionada = new Date(value);
                const min = new Date(dataMinima);
                const max = new Date(dataMaxima);

                // Verificação do intervalo da data
                if (dataSelecionada < min || dataSelecionada > max) {
                    setFormData((prev) => ({
                        ...prev,
                        [name]: ''
                    }));
                    console.log('Data fora do intervalo permitido');
                } else {
                    console.log('Data dentro do intervalo permitido');
                }
            }
        };

        return (
            <div>
                <div style={{ marginTop: '20px', position: 'relative' }}>
                    <label htmlFor="DataAdmissao" className="form-label">Data de Admissão<strong style={{ color: 'red' }}>*</strong></label>
                    <input 
                        type="date"
                        id="DataAdmissao"
                        name="DataAdmissao"
                        value={formData.DataAdmissao || ''}
                        min={dataMinima}
                        max={dataMaxima}
                        onChange={handleChange}
                        onBlur={handleBlur}
                        className="form-control"
                        required
                    />
                </div>
                {formData.Data && (
                    <span style={{ color: 'red', fontSize: '12px' }}>
                        Data de Admissão inválida. Por favor, insira uma data válida não superior à data atual.
                    </span>
                )}

                {/* <AppDataForm formData={formData} setFormData={setFormData} parametros={{ anosAtras: 16, anosFuturo: 5 }} /> */}

            </div>
        );
    };
</script>
