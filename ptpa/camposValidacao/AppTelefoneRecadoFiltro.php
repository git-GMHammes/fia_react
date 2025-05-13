<script type="text/babel">
    const AppTelefoneRecadoFiltro = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const origemForm = parametros.origemForm || '';
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = () => {
            const { name, value } = event.target;

            setMessage((prev) => ({
                ...prev,
                show: false
            }));
        };

        // Função para adicionar a máscara de Telefone, permitindo espaço após o DDD
        const applyMaskTelefone = (telefone) => {
            telefone = telefone.replace(/\D/g, ''); // Remove tudo que não é número

            if (telefone.length === 11) { // Celular: (21)9NNNN-NNNN
                telefone = telefone.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})$/, '($1)$2$3-$4');
            } else if (telefone.length === 10) { // Fixo: (21)NNNN-NNNN
                telefone = telefone.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1)$2-$3');
            }

            return telefone;
        };

        // Função handleChange
        const handleChange = (event) => {
            const { name, value } = event.target;
            let maskedValue = value;
            if (name === 'TelefoneRecado') {
                maskedValue = applyMaskTelefone(value);
            }
            setFormData((prev) => ({
                ...prev,
                [name]: maskedValue
            }));
        };

        // Função handleBlur
        const handleBlur = (event) => {
            const { name, value } = event.target;

            setMessage({ show: false, type: null, message: null });
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
            color: 'gray',
        };

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        return (
            <div>
                <label
                    htmlFor="TelefoneRecado"
                    style={formLabelStyle}
                    className="form-label">Telefone
                </label>
                <input data-api={`filtro-${origemForm}`}
                    type="text"
                    id="TelefoneRecado"
                    name="TelefoneRecado"
                    value={formData.TelefoneRecado || ''}
                    maxLength="14"
                    onChange={handleChange}
                    onFocus={handleFocus}
                    onBlur={handleBlur}
                    className="form-control form-control-sm"
                    style={formControlStyle}
                    aria-label=".form-control-sm example"
                />
            </div>
        );
    };
</script>