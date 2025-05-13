<script type="text/babel">
    const AppDataForm = ({ formData = {}, setFormData = () => {}, parametros = {} }) => {
        
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

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });

            console.log('handleFocus: ', name);
            console.log('handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            console.log('handleFocus/message.show: ', message.show);
        };
        
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
            
            if (name === 'Data') {
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
                    <label htmlFor="Data" className="form-label">Data<strong style={{ color: 'red' }}>*</strong></label>
                    <input 
                        type="date"
                        id="Data"
                        name="Data"
                        value={formData.Data || ''}
                        min={dataMinima}
                        max={dataMaxima}
                        onChange={handleChange}
                        onFocus={handleFocus}
                        onBlur={handleBlur}
                        className="form-control"
                        required
                    />
                </div>
                {/* message.show && (
                    <span style={/*{ color: 'red', fontSize: '12px' }}>
                        {message.message}
                    </span>
                )*/}

                {/* <AppDataForm formData={formData} setFormData={setFormData} parametros={{ anosAtras: 16, anosFuturo: 5 }} /> */}

            </div>
        );
    };
</script>
