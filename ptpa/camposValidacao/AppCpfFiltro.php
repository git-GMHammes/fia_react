<script type="text/babel">
    const AppCpfFiltro = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        const origemForm = parametros.origemForm || '';
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função para adicionar a máscara de CPF a cada 3 números
        const applyMaskCPF = (cpf) => {
            cpf = cpf.replace(/\D/g, '');
            cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            return cpf;
        };

        // Função handleChange simplificada para aplicar máscara e rejeitar letras
        const handleChange = (event) => {
            const { name, value } = event.target;

            // console.log('name handleChange (CPF): ', name);
            // console.log('value handleChange (CPF): ', maskedValue);

            // Aplica a máscara e rejeita letras
            let maskedValue = value.replace(/[^\d.]/g, ''); // Remove tudo que não for dígito ou ponto
            if (name === 'CPF') {
                maskedValue = applyMaskCPF(maskedValue);
            }

            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue
            }));
        };

        // Função handleBlur simplificada (sem validações)
        const handleBlur = () => {
            const { name, value } = event.target;
            // console.log('name handleBlur (CPF): ', name);
            // console.log('value handleBlur (CPF): ', value);

            setMessage({ show: false, type: null, message: null });
        };

        // Estilos personalizados
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
            color: 'gray',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                <label
                    htmlFor="CPF"
                    style={formLabelStyle}
                    className="form-label">CPF
                </label>
                <input
                    data-api={`filtro-${origemForm}`}
                    type="text"
                    id="CPF"
                    name="CPF"
                    value={formData.CPF || ''}
                    maxLength="14"
                    onFocus={handleFocus}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className="form-control form-control-sm"
                    style={formControlStyle}
                    aria-label=".form-control-sm example"
                />
            </div>
        );
    };
</script>