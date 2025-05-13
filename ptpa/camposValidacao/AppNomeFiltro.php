<script type="text/babel">
    const AppNomeFiltro = ({ formData = {}, setFormData = () => { }, parametros = {} }) => {

        // Parâmetros do backend
        const debugMyPrint = parametros.DEBUG_MY_PRINT;
        const origemForm = parametros.origemForm || '';
        const getURI = parametros.getURI || [];

        // Estado para mensagens e validação
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = () => {
            setMessage((prev) => ({
                ...prev,
                show: false
            }));
        };

        // Função para remover números, caracteres especiais e espaços duplicados
        const clearName = (name) => {
            return name
                .replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\s']/g, '')
                .replace(/\s{2,}/g, ' ')
                .replace(/'{2,}/g, "'"); // remove apóstrofo duplicado
        };

        // Função handleChange simplificada para aplicar a limpeza de nome
        const handleChange = (event) => {
            const { name, value } = event.target;

            let clearedValue = value;
            if (name === 'Nome') {
                clearedValue = clearName(value);
            }

            console.log('name handleChange (Nome): ', name);
            console.log('value handleChange (Nome): ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: clearedValue
            }));
        };

        // Função handleBlur com remoção de espaços no início e no final do nome
        const handleBlur = () => {
            const { name, value } = event.target;
            if (name === 'Nome') {
                const trimmedValue = value.trim();
                setFormData((prev) => ({
                    ...prev,
                    [name]: trimmedValue
                }));
            }
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
                    htmlFor="Nome"
                    style={formLabelStyle}
                    className="form-label">Nome
                </label>
                <input
                    data-api={`filtro-${origemForm}`}
                    type="text"
                    id="Nome"
                    name="Nome"
                    value={formData.Nome || ''}
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