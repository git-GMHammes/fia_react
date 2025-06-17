<script type="text/babel">
    const AppForm = ({ parametros = {} }) => {

        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        // const atualizar_id = parametros.atualizar_id || 'erro';
        const debugMyPrint = parametros.DEBUG_MY_PRINT || '';
        const token_csrf = parametros.token_csrf || 'erro';
        const origemForm = parametros.origemForm || '';
        const base_url = parametros.base_url || '';
        const getURI = parametros.getURI || [];
        const json = '1';
        // console.log('origemForm: ', origemForm);

        //Base Cadastro Unidades
        // const api_post_atualizar_unidade = parametros.api_post_atualizar_unidade || '';
        const api_get_municipio = parametros.api_get_municipio || '';
        const api_get_periodo = parametros.api_get_periodo || '';
        const api_post_cadastrar_unidade = parametros.api_post_cadastrar_unidade || '';
        const api_get_atualizar_unidade = parametros.api_get_atualizar_unidade || '';
        const api_post_filtrarassinatura_unidade = parametros.api_post_filtrarassinatura_unidade || '';

        // Variáveis da API
        const [periodos, setPeriodos] = React.useState([]);
        const [municipios, setMunicipios] = React.useState([]);

        // Variáveis Uteis
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const [isLoading, setIsLoading] = React.useState(true);
        const [error, setError] = React.useState(null);
        const faker = window.faker;
        const [showModal, setShowModal] = React.useState(false);
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função para gerar uma string aleatória com letras maiúsculas e números
        const gerarContagemAleatoria = (comprimento) => {
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Letras maiúsculas e números
            let resultado = '';
            for (let i = 0; i < comprimento; i++) {
                resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
            return resultado;
        };

        // Arrays fornecidos
        const CentrosFIA = ["Unidade Profissionalizante", "Unidade Educacional", "Unidade de Educação", "Unidade de Apoio Educacional", "Unidadede Educacional", "Unidade complexo de ensino", "Unidade Técnica", "Unidade Profissionalizante", "Unidade de Ensino", "Unidade de Aplicação", "Polo Educacional", "Unidade de Formação", "Unidade de Referência", "Unidade Integral", "Unidade Especializada", "Unidade Comunitária", "Unidade Experimental", "Unidade Parque", "Unidade Sustentável", "Unidade de Aprendizagem", "Unidade Inclusiva"];
        const Nome = ["João", "Pedro", "Lucas", "Gabriel", "Matheus", "Leonardo", "Gustavo", "Rafael", "Daniel", "Thiago", "Bruno", "André", "Felipe", "Eduardo", "Ricardo", "Rodrigo", "Alexandre", "Fernando", "Vinícius", "Marcelo", "Antônio", "Carlos", "José", "Miguel", "Davi", "Maria", "Ana", "Juliana", "Camila", "Mariana", "Beatriz", "Fernanda", "Larissa", "Vanessa", "Patrícia", "Gabriela", "Amanda", "Letícia", "Rafaela", "Bruna", "Isabel", "Carolina", "Natália", "Jéssica", "Bianca", "Luana", "Tatiane", "Daniela", "Adriana", "Sabrina"];
        const Nome_Meio = ["Smith", "Johnson", "Williams", "Brown", "Jones", "Miller", "Davis", "Garcia", "Rodriguez", "Wilson", "Martinez", "Anderson", "Taylor", "Thomas", "Hernandez", "Moore", "Martin", "Jackson", "Thompson", "White", "Lopez", "Lee", "Gonzalez", "Harris", "Clark", "Lewis", "Robinson", "Walker", "Perez", "Hall", "Young", "Allen", "Sanchez", "Wright", "King", "Scott", "Green"];
        const SobreNome = ["Bauer", "Becker", "Braun", "Busch", "Dietrich", "Engel", "Faber", "Fischer", "Frank", "Frey", "Friedrich", "Fuchs", "Geiger", "Graf", "Groß", "Günther", "Haas", "Hartmann", "Heinrich", "Hermann", "Hoffmann", "Holz", "Huber", "Jäger", "Keller", "König", "Krause", "Krüger", "Kuhn", "Lang", "Lehmann", "Lenz", "Lorenz", "Maier", "Menzel"];

        // Função para gerar um índice aleatório
        function gerarIndice(arr) {
            return Math.floor(Math.random() * arr.length);
        }

        // Gera Ins
        function randomInt(n) {
            let resultado = '';
            for (let i = 0; i < n; i++) {
                resultado += Math.floor(Math.random() * 10);
            }
            return resultado;
        }

        // Gerar nome completo
        const nomeCompleto = CentrosFIA[gerarIndice(CentrosFIA)] + " " +
            Nome[gerarIndice(Nome)] + " " +
            Nome_Meio[gerarIndice(Nome_Meio)] + " " +
            SobreNome[gerarIndice(SobreNome)];

        // Condicional para debugMyPrint
        // const unidades_nome = debugMyPrint ? nomeCompleto : null;

        const End1 = ["Avenida", "Rua", "Beco", "Viela", "Travessa", "Alameda", "Praça", "Largo", "Rodovia", "Estrada", "Caminho", "Passagem", "Viaduto", "Túnel", "Morro", "Ponte", "Calçada", "Escadaria", "Jardim", "Rampa", "Pátio", "Cais", "Parque", "Quarteirão", "Zona", "Distrito", "Vão"];
        const End2 = ["Comendador", "Doutor", "Mestre", "Barão", "Visconde", "Marquês", "Duque/Duquesa", "Príncipe/Princesa", "Rei", "Professor", "Engenheiro", "Doutor", "Arquiteto", "Padre", "Bispo", "Arcebispo", "Cardeal", "Rabbi", "Prefeito", "Governador", "Presidente", "Vice-Presidente", "Ministro", "Senador", "Juiz", "Promotor", "Defensor", "Procurador", "Embaixador", "Cônsul", "Reitor", "Comandante", "General", "Coronel", "Diretor"];
        const EndNome = Nome[gerarIndice(Nome)];
        const EndNome_Meio = SobreNome[gerarIndice(Nome_Meio)];
        const EndBairro3 = ["Copacabana", "Ipanema", "Barra da Tijuca", "Botafogo", "Leblon", "Lapa", "Flamengo", "Glória", "Santa Teresa", "Jardim Botânico", "Méier", "Tijuca", "São Conrado", "Campo Grande", "Bangu", "Madureira", "Ilha do Governador", "Jacarepaguá", "Recreio dos Bandeirantes", "Barra de Guaratiba", "Lagoa", "Unidade", "Catete", "Laranjeiras", "Andaraí", "Rio Comprido", "Jacaré", "Cidade Nova", "Vila Isabel", "Rio de Janeiro (bairro central)", "Caju", "Cidade de Deus", "Cosme Velho", "São Cristóvão", "Maracanã", "Engenho de Dentro", "Vila Valqueire", "Grajaú", "Bonsucesso", "Ramos", "Praça da Bandeira", "Ilha do Fundão", "Penha", "Brás de Pina", "Jardim Carioca", "Sampaio", "Freguesia (Jacarepaguá)", "Campinho", "Barra da Tijuca (Unidade)", "Barra da Tijuca (Zona Oeste)", "Padre Miguel", "Anil", "Botafogo", "Cosme Velho", "Cidade Nova", "Praça Seca", "Vargem Grande", "Vargem Pequena", "Jardim Guanabara", "Lins de Vasconcelos", "Barra de Guaratiba", "Mangueira", "Catumbi", "Benfica", "Jardim América", "Engenho Novo", "Engenho de Dentro", "Cordovil", "Ilha do Governador", "Ilha de Paquetá", "Penha Circular", "Duque de Caxias", "Cavalcanti", "Realengo", "Uruguai", "Roque", "Encantado", "Sargento Roncalli", "Campo Grande", "Itanhangá", "Oswaldo Cruz", "Cosme Velho", "Barra da Tijuca", "Freguesia", "Santa Cruz", "Guaratiba", "Madureira", "Jardim da Saúde", "Taquara", "Vila Isabel", "Colégio", "Morro do Amorim", "Barra do Piraí", "São Cristóvão", "Recreio dos Bandeirantes", "Nova Iguaçu", "Cidade Nova", "Laje do Muriaé", "Ramos", "São João de Meriti", "Icaraí", "Unidade", "São Francisco", "Charitas", "Fonseca", "Santa Rosa", "Engenhoca", "Jardim Icaraí", "Vital Brasil", "Barreto", "Boa Viagem", "Ingá", "Caminho Niemeyer", "Gragoatá", "Barreto", "Ponta D'Areia", "São Domingos", "Rio do Ouro", "Ititioca", "Cubango", "Jardim Paraíso", "São Lourenço", "Boa Vista", "Jardim Beliche", "Itaipu", "Jurujuba", "Ilha das Caieiras", "Cachoeira", "Morro do Céu", "Maceió", "Ladeira", "Canto do Rio", "Brisa Mar", "Sapê", "Porto da Pedra", "Vila Progresso", "Rua da República", "Tenente Jardim", "Santa Teresa", "Largo da Batalha", "São José", "Nossa Senhora das Graças", "Vila Militar", "Jardim Icaraí", "Arariboia", "São João Batista", "Baldeador", "São Gonçalo", "Águas Lindas", "Vila Progresso", "Morro do Estado", "Vila Rica", "Jardim das Flores", "Rio do Ouro", "Alameda", "São Lourenço", "Sete Cidades", "Itaipu", "Vila da Paz", "Maravista", "Parque da Cidade", "Barreto", "Uruguai", "Belém", "Boa Vista", "Maruí", "Boa Viagem", "Vitória", "Vila Jardim", "Barracão", "Olaria", "São Gonçalo", "Sol e Mar", "Peixe Galo", "Engenhoca", "Grajaú", "Meia Légua", "Batelão", "Morro do Céu", "Ilha do Governador", "Bairro Morumbi", "Vila Caetano", "Bairro Nova Cidade", "Espinheiros", "Praia das Flechas", "Loteamento Solares", "Almedina", "Andorinhas", "Verbo Divino", "Niterói Shopping", "Parque Regente", "Pedro do Rio", "Subindo", "Morro do Paraguai", "Porto Real", "Mata da Glória", "Boa Vista Nova", "Jardim Tropical", "Saco do Mamanguá", "Ponta da Areia", "Aterrado", "Barra Mansa", "Belmonte", "Bom Retiro", "Cidade do Aço", "Conforto", "Cruzeiro", "Jardim Amália", "Jardim Belmonte", "Jardim Paraíba", "Jardim Primavera", "Jardim Progresso", "Jardim Vitoria", "Limoeiro", "Monte Castelo", "Niterói", "Padre Josimo", "Parque Floresta", "Parque Maíra", "Parque Randolfe", "Ponte Alta", "Retiro", "Santa Cruz", "Santo Agostinho", "Santo Antônio", "São João", "São Judas Tadeu", "São Luiz", "Vila Rica", "Vila Santa Catarina", "Vila Santa Isabel", "Vila Tavares", "Vila Verde", "Volta Grande", "Vila Velha", "Alvorada", "Angélica", "Aparecida", "Bonsucesso", "Chácara Dona Clara", "Colônia Santo Antônio", "Comary", "Dom Bosco", "Exposição", "Fazendinha", "Floresta", "Freitas", "Gama", "Jardim Alegria", "Jardim Nogueira", "Jardim Ouro Verde", "Jardim Sul", "São Gabriel", "São José", "São Sebastião", "Ponte Alta", "Retiro", "Recanto Verde", "Raul Veiga", "Redentor", "Pedreira", "Parque São Luiz", "Parque Sul", "Parque Ipanema", "Parque Morumbi", "Parque Nossa Senhora das Graças", "Palácio", "Panorama", "Ponto Chic", "Quarteirão da Cultura", "Quilômetro 14", "Realengo", "São Caetano", "São Cristóvão", "São Francisco", "São Paulo", "São Vicente", "Sitio do Pica Pau", "Sitio da Pedra", "Sitio Laje", "Sitio dos Palmares", "Alvorada", "Jardim Calábria", "Jardim Imperador", "Jardim Mandira", "Vila Real", "Vila São José", "Vila São Vicente", "Vila São Sebastião", "Vila Feliz", "Loteamento Progresso", "Loteamento Bandeirantes", "Loteamento Pedreira", "Loteamento Palmeira", "Praça Brasil", "Estádio da Cidadania", "Bairro Santa Maria", "Bairro São Luiz", "Bairro Ouro Verde", "Bairro Novo Horizonte"];

        const ceps = [
            "20000100", "20000200", "20000300", "20010010", "20020050", "20030060", "20040070", "20060090", "20070100", "21000110", "21010120", "21020130", "21030140", "21040150", "21050160", "21060170", "21070180", "21080190", "21090200", "22010220", "22020230", "22030240", "22040250", "22050260", "22070280", "22080290", "22090300", "23000310", "23010320", "23020330", "23030340", "23040350", "23050360", "23060370", "23070380", "23080390", "23090400", "24010410", "24020420", "24040440", "24050450", "24060460", "24070470", "24080480", "24090490", "24110510", "24120520", "24130530", "24140540", "24150550", "24160560", "24170570", "24180580", "24190590", "24200600", "24210610", "24220620", "24230630", "24240640", "24250650", "24260660", "24270670", "24280680", "24290690", "24300700", "24310710", "24320720", "24330730", "24340740", "24350750", "24400760", "24410770", "24420780", "24430790", "24440800", "24450810", "24460820", "24470830", "24480840", "23821730", "24490850", "24510870", "24520880", "24530890", "24540900", "24550910", "24560920", "24570930", "24580940", "24590950", "24600960", "24610970", "24620980", "24630990", "24020150", "24020125", "24030215", "24020071", "24030005", "24020074", "24030000", "24020012", "24020077", "24220031", "24210390", "24210400", "24230051", "24310430", "24310420", "24350310", "24350370", "24350390", "24370025", "24370205", "24370210", "24722220", "24722230", "24740010", "24740015", "24740020", "24740300", "24740305", "24740310", "24740315", "24740320", "24740325", "24740330", "24740335", "24740340", "24740345", "24740350", "24740355", "24743000", "24743005", "24743010", "24743015", "24743020", "22753690", "24743030", "24743035", "24743040", "24743045", "24743050", "24743055", "24743060", "24743065", "24743070", "24743075", "24743080", "24743085", "24743090", "24743095", "24743100", "24743105", "24743110", "24743115", "24743120", "24743125", "24743130", "24743135", "24743140", "24743145", "24743150", "24743155", "24743160", "24743165", "24743170", "24743175", "24743180", "24743185", "24743190", "24743195", "24743200", "24743205", "24743210", "24743215", "24743220", "24743225", "24743230", "24743235", "24743240", "24743245", "24743250", "24743255", "24743260", "24743265", "24743270", "24743275", "24743280", "24743285", "24743290", "24743295", "24743300", "20050030", "22410003", "21941590", "20021000", "20031170", "20230010", "22250040", "22410001", "20271110", "20511330", "22631000", "20710130", "20710230", "20930000", "21330630", "21941904", "22210080", "22290240", "22430160", "20040002", "20040006", "20211901", "20520053", "20530350", "20740032", "20921030", "21041010", "21715000", "22010002", "22061020", "22210001", "22250140", "22250901", "22431000", "22460060", "22631050", "22775003", "23020001", "20040020", "20050000", "20050002", "20060050", "20070020", "20071004", "20211160", "20270280", "20511170", "20521160", "20530001", "20710010", "20921430", "21061970", "21210010", "21251080", "21330230", "21351050", "21650001", "21725180", "21941011", "22010010", "22071060", "22221001", "22221020", "22241000", "22250070", "22270000", "22280000", "22290160", "22410160", "22451041", "22461002", "20010000", "20010020", "20011000", "20020050", "20031050", "20040001", "20050001", "20071000", "20081001", "20210010", "20211001", "20230020", "20231050", "20511260", "20530160", "20760040", "20771001", "20930001", "21021190", "21545001", "22010001", "22070010", "22231001", "22240000", "22250010", "22270001", "22281001", "22291001"
        ];

        function getRandomCep() {
            const index = Math.floor(Math.random() * ceps.length);
            return ceps[index];
        }

        const EndCidade = ["Angra dos Reis", "Aperibé", "Araruama", "Areal", "Armação dos Búzios", "Barra do Piraí", "Barra Mansa", "Belém", "Bom Jardim", "Bom Jesus do Itabapoana", "Cabo Frio", "Cachoeiras de Macacu", "Cambuci", "Campos dos Goytacazes", "Cantagalo", "Carapebus", "Cardoso Moreira", "Carmo", "Casimiro de Abreu", "Comendador Levy Gasparian", "Conceição de Macabu", "Cordeiro", "Duas Barras", "Duque de Caxias", "Engenheiro Paulo de Frontin", "Guapimirim", "Iguaba Grande", "Itaboraí", "Itaguaí", "Italva", "Itaocara", "Itaperuna", "Laje do Muriaé", "Macaé", "Macuco", "Magé", "Mangaratiba", "Maricá", "Mendes", "Mesquita", "Miguel Pereira", "Miracema", "Natividade", "Nilópolis", "Niterói", "Nova Friburgo", "Nova Iguaçu", "Paracambi", "Paraíba do Sul", "Parati", "Paty do Alferes", "Petrópolis", "Pinheiral", "Piraí", "Porciúncula", "Quatis", "Queimados", "Rio Bonito", "Rio Claro", "Rio das Flores", "Rio das Ostras", "Rio de Janeiro", "Santa Maria Madalena", "Santo Antônio de Pádua", "São Fidélis", "São Gonçalo", "São João da Barra", "São João de Meriti", "São José de Ubá", "São José do Vale do Rio Preto", "São Pedro da Aldeia", "São Sebastião do Alto", "Sapucaia", "Saquarema", "Seropédica", "Silva Jardim", "Sumidouro", "Tanguá", "Teresópolis", "Trajano de Moraes", "Três Rios", "Valença", "Varre-Sai", "Vassouras", "Volta Redonda", "Arraial do Cabo", "Rio das Ostras", "Itaocara", "Quissamã", "Paraty", "Cabo Frio", "Mangaratiba"];

        const random = (arr) => arr[Math.floor(Math.random() * arr.length)];

        const Logradouro = `${random(End1)} ${random(End2)} ${EndNome} ${EndNome_Meio}`;

        const gerarDataPassada = () => {
            const dias = Math.floor(Math.random() * 360) + 1; // Entre 1 e 360 dias
            const dataAtual = new Date();
            dataAtual.setDate(dataAtual.getDate() - dias); // Subtrai os dias
            return dataAtual.toISOString().split('T')[0]; // Retorna a data no formato AAAA-MM-DD
        };

        const dataPassada = gerarDataPassada();

        // Cadastro Sem Numero no endereço -  Estado para armazenar o valor Y/N diretamente
        // const [responsaveis, setResponsaveis] = React.useState([]);
        const [semNumeroValue, setSemNumeroValue] = React.useState("N");
        const [isCheckedSemNumero, setIsCheckedSemNumero] = React.useState(semNumeroValue === "Y");
        const [readOnlyNumero, setReadOnlyNumero] = React.useState(false)

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

        // formData
        const [formData, setFormData] = React.useState({
            //
            token_csrf: token_csrf,
            json: '1',
            dropMunicipio: true,
            //
            id: null,
            idUnidades: null,
            unidade_assinatura: null,
            municipio_id: debugMyPrint === true ? Math.floor(Math.random() * 92) + 1 : null,
            unidades_nome: debugMyPrint ? nomeCompleto : null,

            unidades_CEP: debugMyPrint === true ? getRandomCep() : null,
            unidades_Logradouro: null,
            unidades_Numero: debugMyPrint === true ? randomInt(4).toString() : null,
            unidades_Complemento: null,
            unidades_Municipio: null,
            unidades_Bairro: null,
            unidades_UF: null,

            CEP: null,
            Logradouro: null,
            Municipio: null,
            Bairro: null,
            UF: null,
            Estado: null,
            DDD: null,
            GIA: null,
            IBGE: null,
            Regiao: null,
            SIAFI: null,
            checkMunicipio: false,

            unidades_endereco: debugMyPrint === true ? Logradouro : null,

            unidades_cap_atendimento: debugMyPrint === true ? Math.floor(Math.random() * 500) + 50 : null,
            unidades_data_cadastramento: debugMyPrint === true ? dataPassada : null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
            municipios_id: null,
            municipios_id_municipio: null,
            municipios_nome_municipio: null,
            municipios_id_regiao: null,
            municipios_nome_regiao: null,
            municipios_id_mesoregiao: null,
            municipios_nome_mesoregiao: null,
            municipios_id_uf: null,
            municipios_nome_uf: null,
            municipio_created_at: null,
            municipio_updated_at: null,
            municipio_deleted_at: null,
        });

        // Handler para quando o checkbox muda
        const handleCheckboxChange = (e) => {
            const checked = e.target.checked;
            const value = checked ? "Y" : "N";

            // Atualiza os estados do checkbox
            setIsCheckedSemNumero(checked);
            setSemNumeroValue(value);

            if (value === 'Y') {
                setReadOnlyNumero(true);
            } else {
                setReadOnlyNumero(false);
            }

            // Atualiza o formData - tanto o valor do checkbox quanto o Numero
            setFormData(prevData => ({
                ...prevData,
                checkSemNumero: value,
                // Se marcado, coloca "S/N", senão mantém ou gera número
                unidades_Numero: checked ? "S/N" : (prevData.Numero === "S/N" ? randomInt(4).toString() : prevData.Numero)
            }));

            console.log('Checkbox alterado:', {
                checked,
                valor: value,
                novoNumero: checked ? "S/N" : (formData.Numero === "S/N" ? randomInt(4).toString() : formData.Numero)
            });
        };

        // Função para verificar se a palavra está no array
        const handleFocus = (event) => {
            setMessage({ show: false, type: null, message: null });
            const { name, value } = event.target;
            console.log('name handleFocus: ', name);
            console.log('value handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;
            // console.log('name handleChange: ', name);
            // console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

        };

        // submitAllForms
        const submitAllForms = async (filtro) => {
            console.log('submitAllForms...');
            const setData = formData;
            console.log('setData: ', setData);
            console.log('filtro: ', filtro);

            let data = '';
            let dbResponse = [];
            let response = '';

            // Mapeamento dos campos com nomes amigáveis
            const camposObrigatorios = {
                // municipio_id: 'Município da Unidade',
                unidades_nome: 'Nome da Unidade',
                unidades_cap_atendimento: 'Capacidade de Atendimento',
                unidades_Logradouro: 'Endereço da Unidade',
                unidades_Municipio: 'Município da Unidade',
                unidades_CEP: 'CEP da Unidade',
                unidades_Numero: 'Número da Unidade',
                unidades_Bairro: 'Bairro da Unidade',
            };

            // Verificar se algum dos campos está vazio ou nulo
            const camposVazios = Object.keys(camposObrigatorios).filter(campo => !setData[campo]);

            if (camposVazios.length > 0) {
                const nomesCamposVazios = camposVazios.map(campo => camposObrigatorios[campo]);
                setMessage({
                    show: true,
                    type: 'light',
                    message: `<b>Os campos</b>: <br/> ${nomesCamposVazios.join("<br/>")}<br/> <b>não podem estar em branco</b>`,
                });

                // Adicione este timer para limpar a mensagem após 5 segundos
                setTimeout(() => {
                    setMessage({
                        show: false,
                        type: null,
                        message: null
                    });
                }, 3000);

                return false;
            }

            if (filtro === `filtro-unidade`) {
                // Convertendo os dados do setPost em JSON
                console.log(`filtro-unidade`);
                fetchPostUnidade(setData);
                return false;
            }

        };

        // POST submitAllForms
        const fetchPostUnidade = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_cadastrar_unidade, customPage = '') => {
            console.log('fetchPostUnidade...');
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            console.log('url :: ', url);

            const setData = formData;
            try {

                let resposta = '';

                if (checkWordInArray(getURI, 'cadastrar')) {
                    resposta = 'Cadastro';
                } else if (checkWordInArray(getURI, 'atualizar')) {
                    resposta = 'Atualização';
                } else if (checkWordInArray(getURI, 'consultar')) {
                    resposta = 'Consulta'
                } else if (checkWordInArray(getURI, 'alocarfuncionario')) {
                    resposta = 'Alocar'
                } else if (checkWordInArray(getURI, 'consultarfunc')) {
                    resposta = 'Consulta Funcionário'
                } else if (checkWordInArray(getURI, '')) {
                    resposta = 'Alocar'
                } else {
                    resposta = 'Ação';
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('fetchPostUnidade - data: ', data.result.affectedRows);

                if (data.result && data.result.affectedRows && data.result.affectedRows > 0) {
                    console.log('data.result :: ', data.result);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `${resposta} realizada com sucesso`
                    });
                    setFormData({});

                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `Não foi possivel realizar o ${resposta}`,
                    });
                }
                redirectTo('index.php/fia/ptpa/unidade/endpoint/exibir');
                return true;

            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                return null;
            }
        };

        // POST fetchPostFiltrarAssinatura
        const fetchPostFiltrarAssinatura = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_filtrarassinatura_unidade) => {
            console.log('fetchPostFiltrarAssinatura...');
            const url = custonBaseURL + custonApiPostObjeto;
            console.log('url:', url);

            const SetData = {
                unidades_nome: formData.unidades_nome,
                unidades_endereco: formData.unidades_endereco,
                municipio_id: formData.municipio_id
            };

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(SetData),
            });

            const data = await response.json();
            // console.log('fetchPostFiltrarAssinatura - data: ', data);

            if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length === 0) {
                console.log('fetchPostFiltrarAssinatura - data: ', data.result);
                return true
            } else {
                console.log('fetchPostFiltrarAssinatura... Não tem data.result.dbResponse.length');
                return false
            }

            try {
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
            }
        };

        // Fetch para obter os Municipios
        const fetchMunicipios = async () => {
            try {
                // console.log('base_url + api_get_municipio: ', base_url + api_get_municipio);
                const response = await fetch(base_url + api_get_municipio, {
                    method: 'POST', // Define o método como POST
                    headers: {
                        'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
                    },
                    body: JSON.stringify({}) // Corpo da requisição vazio
                });
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    // console.log('Municipio: ', data);
                    setMunicipios(data.result.dbResponse);
                    setDataLoading(false);
                }
            } catch (error) {
                setError('Erro ao carregar Municipios: ' + error.message);
            }
        };

        // Fetch para obter os Periodos
        const fetchPeriodos = async () => {
            try {
                // console.log('base_url + api_get_periodo: ', base_url + api_get_periodo);

                const response = await fetch(base_url + api_get_periodo, {
                    method: 'POST', // Define o método como POST
                    headers: {
                        'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
                    },
                    body: JSON.stringify({}) // Corpo da requisição vazio
                });
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    // console.log('Periodo: ', data);
                    setPeriodos(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Periodo: ' + error.message);
            }
        };

        // Fetch para obter as Unidades
        const fetchUnidades = async () => {
            try {
                if (checkWordInArray(getURI, 'cadastrar')) {
                    setFormData((prev) => ({
                        ...prev,
                    }));
                    return false;
                }
                // console.log(base_url + api_get_atualizar_unidade);

                const response = await fetch(base_url + api_get_atualizar_unidade);
                const data = await response.json();
                if (
                    data.result &&
                    data.result.dbResponse &&
                    data.result.dbResponse.length > 0 &&
                    data.result.dbResponse[0]['unidades_Numero'] &&
                    data.result.dbResponse[0]['unidades_Numero'] === 'S/N'
                ) {
                    setIsCheckedSemNumero('Y');
                }

                if (
                    data.result &&
                    data.result.dbResponse &&
                    data.result.dbResponse.length > 0
                ) {
                    console.log('fetchUnidades:: ', data.result.dbResponse[0]);

                    setFormData((prev) => ({
                        ...prev,
                        ...data.result.dbResponse[0]
                    }));
                }
            } catch (error) {
                console.error('Erro ao carregar Profissionais: ' + error.message);
            }
        };

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        // Adicione este useEffect para sincronizar os campos relacionados
        React.useEffect(() => {
            // Verificação para evitar loops infinitos
            const shouldUpdate =
                formData.CEP !== formData.unidades_CEP ||
                formData.Logradouro !== formData.unidades_Logradouro ||
                formData.Municipio !== formData.unidades_Municipio ||
                formData.Bairro !== formData.unidades_Bairro ||
                formData.UF !== formData.unidades_UF;

            if (shouldUpdate) {
                setFormData(prevState => ({
                    ...prevState,
                    // Sincroniza os campos, dando prioridade aos campos principais
                    unidades_CEP: prevState.CEP || prevState.unidades_CEP,
                    unidades_Logradouro: prevState.Logradouro || prevState.unidades_Logradouro,
                    unidades_Municipio: prevState.Municipio || prevState.unidades_Municipio,
                    unidades_Bairro: prevState.Bairro || prevState.unidades_Bairro,
                    unidades_UF: prevState.UF || prevState.unidades_UF
                }));
            }
        }, [formData.CEP, formData.Logradouro, formData.Municipio, formData.Bairro, formData.UF]);

        // React.useEffect
        React.useEffect(() => {
            console.log('React.useEffect - src/app/Views/fia/ptpa/unidades/AppForm.php...');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                // console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchUnidades();
                    await fetchPeriodos();
                    await fetchMunicipios();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        const renderUndiadeForm = () => {
            return (
                <div>
                    {/* Formulário de Unidade */}

                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {formData.id !== 'erro' && (
                                    <input
                                        data-api={`filtro-${origemForm}`}
                                        type="hidden"
                                        id="id"
                                        name="id"
                                        value={formData.id || ''}
                                        onChange={handleChange}
                                        required
                                    />
                                )}
                                <input
                                    data-api={`filtro-${origemForm}`}
                                    type="hidden"
                                    id="token_csrf"
                                    name="token_csrf"
                                    value={formData.token_csrf || token_csrf}
                                    onChange={handleChange}
                                    required
                                />
                                {/*
                                    <input
                                        data-api={`filtro-${origemForm}`}
                                        type="hidden"
                                        id="AcessoCadastroID"
                                        name="AcessoCadastroID"
                                        value={formData.AcessoCadastroID || ''}
                                        onChange={handleChange}
                                        required
                                    />
                                    <input
                                        data-api={`filtro-${origemForm}`}
                                        type="hidden"
                                        id="PerfilId"
                                        name="PerfilId"
                                        value={formData.PerfilId || ''}
                                        onChange={handleChange}
                                        required
                                    />  
                                    */}
                                <input
                                    data-api={`filtro-${origemForm}`}
                                    type="hidden"
                                    id="json"
                                    name="json"
                                    value={formData.json || json}
                                    onChange={handleChange}
                                    required
                                />
                                <input
                                    data-api={`filtro-${origemForm}`}
                                    type="hidden"
                                    id="unidades_data_cadastramento"
                                    name="unidades_data_cadastramento"
                                    value={formData.unidades_data_cadastramento || ''}
                                    onChange={handleChange}
                                    required
                                />
                                <input
                                    data-api={`filtro-${origemForm}`}
                                    type="hidden"
                                    id="unidades_UF"
                                    name="unidades_UF"
                                    value={formData.unidades_UF || ''}
                                    onChange={handleChange}
                                    required
                                />
                            </form>

                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {checkWordInArray(getURI, 'consultar') ? (
                                    <div style={formGroupStyle}>
                                        {checkWordInArray(getURI, 'exibir') ? (
                                            <label
                                                htmlFor="unidades_nome"
                                                style={formLabelStyle} className="form-label">Nome (Unidade)
                                            </label>

                                        ) : (
                                            <label
                                                htmlFor="unidades_nome"
                                                style={formLabelStyle} className="form-label">Nome (Unidade)
                                                {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                            </label>
                                        )}
                                        <div className='p-2'>
                                            {formData.unidades_nome ? (
                                                <div>
                                                    {formData.unidades_nome}
                                                </div>
                                            ) : (
                                                <div className='text-muted'>
                                                    ...
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                ) : (
                                    <div>

                                        <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                            fieldAttributes={{
                                                attributeOrigemForm: `${origemForm}`,
                                                labelField: 'Nome (Unidade)',
                                                labelColor: 'black', // gray, red, black,
                                                nameField: 'unidades_nome',
                                                errorMessage: '', // Mensagem de Erro personalizada
                                                setMessage: () => setMessage(),
                                                attributePlaceholder: '', // placeholder
                                                attributeMinlength: 2, // minlength
                                                attributeMaxlength: 100, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Processo: 41, Certidão: 38
                                                attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                                attributeAutocomplete: 'on', // on, off ]
                                                attributeRequired: true,
                                                attributeReadOnly: false,
                                                attributeDisabled: false,
                                                attributeMask: '', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                            }}
                                        />
                                    </div>
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-6">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {checkWordInArray(getURI, 'consultar') ? (
                                    <div style={formGroupStyle}>
                                        {checkWordInArray(getURI, 'exibir') ? (
                                            <label
                                                htmlFor="unidades_cap_atendimento"
                                                style={formLabelStyle} className="form-label">Capacidade de Atendimento
                                            </label>

                                        ) : (
                                            <label
                                                htmlFor="unidades_cap_atendimento"
                                                style={formLabelStyle} className="form-label">Capacidade de Atendimento
                                                {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                            </label>
                                        )}
                                        <div className='p-2'>
                                            {formData.unidades_cap_atendimento ? (
                                                <div>
                                                    {formData.unidades_cap_atendimento}
                                                </div>
                                            ) : (
                                                <div className='text-muted'>
                                                    ...
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                ) : (
                                    <div>
                                        <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                            fieldAttributes={{
                                                attributeOrigemForm: `${origemForm}`,
                                                labelField: 'Capacidade de Atendimento',
                                                labelColor: 'black', // gray, red, black,
                                                nameField: 'unidades_cap_atendimento',
                                                attributePlaceholder: '', // placeholder 
                                                attributeMinlength: 2, // minlength 
                                                attributeMaxlength: 4, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                attributeAutocomplete: 'on', // on, off ]
                                                attributeRequired: true,
                                                attributeReadOnly: false,
                                                attributeDisabled: false,
                                                attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                            }}
                                        />
                                    </div>
                                )}
                            </form>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {/* CEP */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'CEP',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'unidades_CEP',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 5, // minlength 
                                        attributeMaxlength: 9, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: 'CEP', // CPF, Telefone, CEP, , SEI, Processo.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {/* LOGRADOURO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Logradouro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'unidades_Logradouro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 22, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-2">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {/* Numero Opcional */}
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor={`checkSemNumero`}
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        {`Sem número`}
                                        <strong style={requiredField}>*</strong>
                                    </label>
                                    <div className="form-check m-1">
                                        <input
                                            className="form-check-input"
                                            type="checkbox"
                                            checked={isCheckedSemNumero}
                                            onChange={handleCheckboxChange}
                                            id="checkSemNumero"
                                            name="checkSemNumero"
                                        />
                                        <label className="form-check-label" htmlFor="checkSemNumero">
                                            S/N
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div className="col-12 col-sm-2">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {/* NUMERO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Número -',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'unidades_Numero',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 1, // minlength 
                                        attributeMaxlength: 6, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: false,
                                        attributeReadOnly: readOnlyNumero,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                    }}
                                />
                            </form>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {/* COMPLEMENTO */}
                                <AppText
                                    submitAllForms
                                    parametros={parametros}
                                    formData={formData}
                                    setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Complemento',
                                        labelColor: 'black',
                                        nameField: 'unidades_Complemento',
                                        attributeMinlength: 4,
                                        attributeMaxlength: 100,
                                        attributePattern: 'Caracter, Inteiro',
                                        attributeAutocomplete: 'on',
                                        attributeRequired: false,
                                        attributeReadOnly: false, // isso já tá sendo controlado no ternário acima
                                        attributeDisabled: false,
                                        attributeMask: '',
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {/* BAIRRO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Bairro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'unidades_Bairro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 50, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="needs-validation" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(formData.dropMunicipio) ? (
                                    <div>
                                        {/* MUNICÍPIO/SELECT */}
                                        <AppSelect
                                            parametros={parametros}
                                            formData={formData}
                                            setFormData={setFormData}
                                            fieldAttributes={{
                                                attributeOrigemForm: `${origemForm}`,
                                                labelField: 'Município',
                                                nameField: 'unidades_Municipio',
                                                errorMessage: '', // Mensagem de Erro personalizada
                                                attributeFieldKey: ['nome_municipio', 'key'], // Alterado para ex. id_municipio 
                                                attributeFieldName: ['nome_municipio', 'value'], // Alterado o segundo valor para ex. 'value'
                                                attributeRequired: true,
                                                attributeDisabled: false,
                                                objetoArrayKey: [
                                                    { key: '1', value: 'Opção 1' },
                                                    { key: '2', value: 'Opção 2' },
                                                    { key: '3', value: 'Opção 3' },
                                                    { key: '4', value: 'Opção 4' }
                                                ],
                                                api_get: `${api_get_municipio}`,
                                                api_post: `${api_get_municipio}`,
                                                api_filter: `${api_get_municipio}`,
                                            }}
                                        />
                                    </div>
                                ) : (
                                    <div>
                                        {/* MUNICÍPIO/TEXT */}
                                        <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                            fieldAttributes={{
                                                attributeOrigemForm: `${origemForm}`,
                                                labelField: 'Município',
                                                labelColor: 'black', // gray, red, black,
                                                nameField: 'unidades_Municipio',
                                                errorMessage: '', // Mensagem de Erro personalizada
                                                attributePlaceholder: '', // placeholder 
                                                attributeMinlength: 4, // minlength 
                                                attributeMaxlength: 100, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                                attributeAutocomplete: 'on', // on, off ]
                                                attributeRequired: true,
                                                attributeReadOnly: true,
                                                attributeDisabled: false,
                                                attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                            }}
                                        />
                                    </div>
                                )}
                            </form>
                        </div>
                    </div>
                </div>

            );
        }

        const renderUndiadeConsult = () => {
            return (
                <div>
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor={`htmlForm`}
                                    style={formLabelStyle}
                                    className="form-label"
                                >
                                    Nome (Unidade)
                                    {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className="m-1">{formData.unidades_nome || `Nome não informado`}</div>
                            </div>
                        </div>
                        <div className="col-12 col-sm-6">
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor={`htmlForm`}
                                    style={formLabelStyle}
                                    className="form-label"
                                >
                                    Capacidade de Atendimento
                                    {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className="m-1">{formData.unidades_cap_atendimento || `Capacidade não informada`}</div>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor={`htmlForm`}
                                    style={formLabelStyle}
                                    className="form-label"
                                >
                                    CEP
                                    {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className="m-1">{formData.unidades_CEP || `CEP não informado`}</div>
                            </div>
                        </div>
                        <div className="col-12 col-sm-4">
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor={`htmlForm`}
                                    style={formLabelStyle}
                                    className="form-label"
                                >
                                    Logradouro
                                    {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className="m-1">{formData.unidades_Logradouro || `Logradouro não informado`}</div>
                            </div>
                        </div>
                        <div className="col-12 col-sm-4">
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor={`htmlForm`}
                                    style={formLabelStyle}
                                    className="form-label"
                                >
                                    Número
                                    {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className="m-1">{formData.unidades_Numero || `Número não informado`}</div>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor={`htmlForm`}
                                    style={formLabelStyle}
                                    className="form-label"
                                >
                                    Complemento
                                    {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className="m-1">{formData.unidades_Complemento || `Complemento não informado`}</div>
                            </div>
                        </div>
                        <div className="col-12 col-sm-4">
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor={`htmlForm`}
                                    style={formLabelStyle}
                                    className="form-label"
                                >
                                    Bairro
                                    {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className="m-1">{formData.unidades_Bairro || `Bairro não informado`}</div>
                            </div>
                        </div>
                        <div className="col-12 col-sm-4">
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor={`htmlForm`}
                                    style={formLabelStyle}
                                    className="form-label"
                                >
                                    Município
                                    {checkWordInArray(getURI, 'consultar') ? (null) : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className="m-1">{formData.unidades_Municipio || `Município não informado`}</div>
                            </div>
                        </div>
                    </div>
                </div>
            );
        }

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

        const formControlStyle = {
            fontSize: '1rem',
            borderColor: '#fff',
        };

        const requiredField = {
            color: '#FF0000',
        };

        // Return
        return (
            <div className="ms-3 me-3">
                <div className="card mb-4">
                    <div className="card-body">
                        {(
                            checkWordInArray(getURI, 'consultar')
                        ) && (
                                <div>
                                    {/* Formulário de Consulta */}
                                    {renderUndiadeConsult()}
                                </div>
                            )}

                        {(
                            checkWordInArray(getURI, 'cadastrar') ||
                            checkWordInArray(getURI, 'atualizar')
                        ) && (
                                <div>
                                    {/* Formulário de Cadastro */}
                                    {renderUndiadeForm()}
                                </div>
                            )}

                        {/* Botão de voltar e salvar */}
                        {!checkWordInArray(getURI, 'detalhar') && (
                            <div className="m-3">
                                <div className="row">
                                    <div className="col-12">
                                        <form className="needs-validation d-flex justify-content-between align-items-center"
                                            onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}
                                        >
                                            <div className="d-flex gap-2">
                                                {/* Botão Voltar */}
                                                {!checkWordInArray(getURI, 'alocarfuncionario') && (
                                                    <a
                                                        className="btn btn-danger"
                                                        href={`${base_url}index.php/fia/ptpa/unidade/endpoint/exibir`}
                                                        role="button"
                                                    >
                                                        Voltar
                                                    </a>
                                                )}

                                                {/* Botão Salvar */}
                                                {!checkWordInArray(getURI, 'consultar') && (
                                                    <input
                                                        className="btn btn-success"
                                                        type="submit"
                                                        value="Salvar"
                                                    />
                                                )}
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Exibe o componente de alerta */}
                        {
                            message !== null && (
                                <AppMessageCard
                                    parametros={message}
                                    modalId={`modal_form_unidade_${gerarContagemAleatoria(7)}`}
                                />
                            )
                        }

                        {showModal && (
                            <div className="modal fade show d-block" tabIndex="-1" role="dialog">
                                <div className="modal-dialog modal-dialog-centered" role="document">
                                    <div className="modal-content">
                                        <div className="modal-header">
                                            <h5 className="modal-title">Cadastro Concluído</h5>
                                            <button
                                                type="button"
                                                className="btn-close"
                                                onClick={() => setShowModal(false)}
                                                aria-label="Close"
                                            ></button>
                                        </div>
                                        <div className="modal-body">
                                            <p>Unidade cadastrada com sucesso! Escolha a próxima ação:</p>
                                        </div>
                                        <div className="modal-footer">
                                            <button
                                                className="btn btn-primary"
                                                onClick={() => {
                                                    setShowModal(false);
                                                    window.location.href = `${base_url}index.php/fia/ptpa/alocarfuncionario/endpoint/cadastrar`;
                                                }}
                                            >
                                                Alocar funcionário
                                            </button>
                                            <button
                                                className="btn btn-primary"
                                                onClick={() => {
                                                    setShowModal(false);
                                                    window.location.href = `${base_url}index.php/fia/ptpa/unidade/endpoint/cadastrar`;
                                                }}
                                            >
                                                Cadastrar Nova Unidade
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )
                        }
                    </div>
                </div>
            </div >
        );
    };
</script>