<script type="text/babel">
    const AppDataNascimento = ({ formData = {}, setFormData = () => {}, parametros = {} }) => {
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];

        const dataMinima = new Date();
        dataMinima.setFullYear(dataMinima.getFullYear() - 18); // 18 anos atrás

        const dataMaxima = new Date();
        dataMaxima.setFullYear(dataMaxima.getFullYear() - 14); // 14 anos atrás

        // Util
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;

        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Calcula a data atual
        const dataAtual = new Date();

        const calcularIdade = (Nascimento) => {
            const hoje = new Date();
            const nascimento = new Date(Nascimento);
            let idade = hoje.getFullYear() - nascimento.getFullYear();
            const mesAtual = hoje.getMonth();
            const diaAtual = hoje.getDate();

            // Ajusta a idade caso o mês ou dia atual seja antes do mês/dia de nascimento
            if (mesAtual < nascimento.getMonth() || (mesAtual === nascimento.getMonth() && diaAtual < nascimento.getDate())) {
                idade--;
            }

            return idade;
        };

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });

            if (name === 'Nascimento' && value) {
                const idade = calcularIdade(value);

                // Valida se a idade está dentro do intervalo permitido
                if (idade < 14 || idade > 18) {
                    alert("Data de nascimento inválida ou fora da faixa etária permitida.");
                    setFormData((prev) => ({
                        ...prev,
                        [name]: '' // Reseta o campo se a idade não estiver no intervalo
                    }));
                } else {
                    console.log(`Idade válida: ${idade} anos.`);
                }
            }

            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;
            setFormData((prev) => ({
                ...prev,
                [name]: value,
            }));
        };

        // Função de validação para garantir que a data esteja no intervalo permitido
        const handleBlur = (event) => {
            const { name, value } = event.target;

            if (name === 'Nascimento') {
                const dataSelecionada = new Date(value);
                const min = new Date(dataMinima);
                const max = new Date(dataMaxima);

                // Verifica se a data está dentro do intervalo permitido
                if (dataSelecionada < min || dataSelecionada > max) {
                    setFormData((prev) => ({
                        ...prev,
                        [name]: '',
                    }));
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Data de nascimento inválida ou fora da faixa etária permitida.',
                    });
                } else {
                    setMessage({ show: false, type: null, message: null });
                }
            }
        };

        const formGroupStyle = {
            position: 'relative',
            marginTop: '20px',
            padding: '5px',
            borderRadius: '8px',
            border: '1px solid #000',
        };

        const formLabelStyle = {
            position: 'absolute',
            top: '-15px',
            left: '20px',
            backgroundColor: 'white',
            padding: '0 5px',
        };

        const requiredField = {
            color: '#FF0000',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                <div style={formGroupStyle}>
                    <label 
                        htmlFor="Nascimento"
                        style={formLabelStyle} 
                        className="form-label">Data de Nascimento
                        {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                    </label>
                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                        <div className='p-2'>
                            {formData.Nascimento}
                        </div>
                    ) : (
                        <input data-api={`filtro-${origemForm}`}
                            type="date"
                            id="Nascimento"
                            name="Nascimento"
                            value={formData.Nascimento || ''}
                            min={dataMinima}
                            max={dataMaxima}
                            onFocus={handleFocus}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            style={formControlStyle}
                            className="form-control form-control-sm"
                            disabled={(checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')) ? true : false}
                            required
                        />
                    )}
                </div>
                
                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message}
                    modalId="modal_nascimento"
                />
            </div>
        );
    };
</script>
