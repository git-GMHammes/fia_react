<script type="text/babel">
    const AppForm = ({ parametros = {} }) => {

        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const getURI = parametros.getURI;
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf || 'erro';
        const json = '1';
        const atualizar_id = parametros.atualizar_id || 'erro';
        const origemForm = parametros.origemForm || '';
        const title = parametros.title || '';

        //Base Cadastro Adolescentes
        const api_get_sexo = parametros.api_get_sexo;
        const api_get_genero = parametros.api_get_genero;
        const api_get_municipio = parametros.api_get_municipio;
        const api_get_responsavel = parametros.api_get_responsavel;
        const api_get_periodo = parametros.api_get_periodo;
        const api_post_filter_unidade = parametros.api_post_filter_unidade || '';
        const api_filter_unidades = parametros.api_filter_unidades || '';
        const api_post_filter_responsaveis = parametros.api_post_filter_responsaveis;
        const api_post_atualizar_adolescente = parametros.api_post_atualizar_adolescente || '';
        const api_post_cadastrar_adolescente = parametros.api_post_cadastrar_adolescente || '';
        const api_get_atualizar_adolescente = parametros.api_get_atualizar_adolescente || '';
        const api_get_adolescente = parametros.api_get_adolescente || '';
        const api_post_confirma_email = parametros.api_post_confirma_email || '';
        const api_get_unidade = parametros.api_get_unidade;

        // Variáveis da API
        const [listaSexos, setListaSexos] = React.useState([]);
        const [generos, setGeneros] = React.useState([]);
        const [periodos, setPeriodos] = React.useState([]);
        const [municipios, setMunicipios] = React.useState([]);
        const [responsaveis, setResponsaveis] = React.useState([]);

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

        const [isCPFOptional, setIsCPFOptional] = React.useState(false);
        const [isChoiceMade, setIsChoiceMade] = React.useState(false); // CPF Matricula
        const [choice, setChoice] = React.useState(''); // CPF Obrigatorio
        const [camposObrigatorios, setCamposObrigatorios] = React.useState({});

        const handleChoice = (option) => {
            setChoice(() => option); // Atualiza a escolha do usuário
            setIsChoiceMade(true); // Marca que a escolha foi feita

            if (option === 'cpf') {
                setIsChoiceMade('cpf'); // Reseta a escolha
                setCamposObrigatorios(() => ({
                    CPF: 'CPF',
                    //
                    Nome: 'Nome Completo',
                    CEP: 'CEP',
                    Endereco: 'Endereço',
                    Numero: 'Número',
                    Municipio: 'Município',
                    Nascimento: 'Data de Nascimento',
                    SexoId: 'Sexo',
                    GeneroIdentidadeId: 'Gênero',
                    Complemento: 'Complemto',
                    UnidadeId: 'Unidade',
                    Email: 'Email',
                    Etnia: 'Etnia',
                    Escolaridade: 'Escolaridade',
                    TipoEscola: 'Tipo de Escola',
                    turno_escolar: 'Turno escolar',
                    NomeEscola: 'Nome da Escola',
                    Responsavel_Nome: 'Nome do Responsável',
                    Responsavel_TelefoneMovel: 'Responsável TelefoneMovel',
                    Responsavel_CPF: 'Responsável CPF',
                }));
            } else if (option === 'certidao') {

                setIsChoiceMade('certidao'); // Reseta a escolha
                setCamposObrigatorios(() => ({
                    Certidao: 'Certidao',
                    Folha: 'Folha',
                    Livro: 'Livro',
                    Circunscricao: 'Circunscrição',
                    Zona: 'Zona',
                    Registro: 'Nº Registro',
                    //
                    Nome: 'Nome Completo',
                    CEP: 'CEP',
                    Endereco: 'Endereço',
                    Numero: 'Número',
                    Municipio: 'Município',
                    Nascimento: 'Data de Nascimento',
                    SexoId: 'Sexo',
                    GeneroIdentidadeId: 'Gênero',
                    Complemento: 'Complemto',
                    UnidadeId: 'Unidade',
                    Email: 'Email',
                    Etnia: 'Etnia',
                    Escolaridade: 'Escolaridade',
                    TipoEscola: 'Tipo de Escola',
                    turno_escolar: 'Turno escolar',
                    NomeEscola: 'Nome da Escola',
                    Responsavel_Nome: 'Nome do Responsável',
                    Responsavel_TelefoneMovel: 'Responsável TelefoneMovel',
                    Responsavel_CPF: 'Responsável CPF',
                }));
            }
        };

        // Variáveis 
        // const [datasPeriodos, setDatasPeriodos] = React.useState([]);
        const [onEscolaridade, setOnEscolaridade] = React.useState(true);
        const [termoAceito, setTermoAceito] = React.useState(false);
        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [tabNav, setTabNav] = React.useState('dadosCPF');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null,
        });

        // CEP - Estados para o componente
        const [units, setUnits] = React.useState([]);
        const [originalUnits, setOriginalUnits] = React.useState([]);
        const viacep = 'https://viacep.com.br/ws/';
        const opencep = 'https://opencep.com/v1/';

        const handleReset = () => {
            setChoice('');
        };

        // Função para gerar uma string aleatória com letras maiúsculas e números
        const gerarContagemAleatoria = (comprimento) => {
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Letras maiúsculas e números
            let resultado = '';
            for (let i = 0; i < comprimento; i++) {
                resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
            return resultado;
        };

        // CEP - Função para calcular a diferença entre CEPs
        const calculateCepDistance = (cep1, cep2) => {
            const cleanCep1 = cep1.replace(/\D/g, '');
            const cleanCep2 = cep2.replace(/\D/g, '');
            return Math.abs(parseInt(cleanCep1) - parseInt(cleanCep2));
        };

        // CEP -  Função para ordenar unidades por proximidade do CEP
        const orderUnitsByCepProximity = (cepReference) => {
            if (!cepReference) return;

            const orderedUnits = [...units].sort((a, b) => {
                const distanceA = calculateCepDistance(a.unidades_cep, cepReference);
                const distanceB = calculateCepDistance(b.unidades_cep, cepReference);
                return distanceA - distanceB;
            });

            setUnits(orderedUnits);
        };

        // CEP - Função para aplicar máscara de CEP
        const applyMaskCEP = (value) => {
            return value
                .replace(/\D/g, '') // Remove tudo o que não é dígito
                .replace(/(\d{5})(\d)/, '$1-$2') // Coloca hífen entre o 5º e o 6º dígitos
                .substring(0, 9); // Limita o tamanho
        };

        const getFormattedDate = () => {
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const today = new Date();
            return today.toLocaleDateString('pt-BR', options);
        };

        const getRandomLetters = () => {
            const letters = 'abcdefghijklmnopqrstuvwxyz';
            let result = '';
            for (let i = 0; i < 9; i++) {
                const randomIndex = Math.floor(Math.random() * letters.length);
                result += letters[randomIndex];
            }
            return result;
        };

        // Gera nome Completo 
        const Nome = ["João", "Pedro", "Lucas", "Gabriel", "Matheus", "Leonardo", "Gustavo", "Rafael", "Daniel", "Thiago", "Bruno", "André", "Felipe", "Eduardo", "Ricardo", "Rodrigo", "Alexandre", "Fernando", "Vinícius", "Marcelo", "Antônio", "Carlos", "José", "Miguel", "Davi", "Maria", "Ana", "Juliana", "Camila", "Mariana", "Beatriz", "Fernanda", "Larissa", "Vanessa", "Patrícia", "Gabriela", "Amanda", "Letícia", "Rafaela", "Bruna", "Isabel", "Carolina", "Natália", "Jéssica", "Bianca", "Luana", "Tatiane", "Daniela", "Adriana", "Sabrina"];
        const Nome_Meio = ["Smith", "Johnson", "Williams", "Brown", "Jones", "Miller", "Davis", "Garcia", "Rodriguez", "Wilson", "Martinez", "Anderson", "Taylor", "Thomas", "Hernandez", "Moore", "Martin", "Jackson", "Thompson", "White", "Lopez", "Lee", "Gonzalez", "Harris", "Clark", "Lewis", "Robinson", "Walker", "Perez", "Hall", "Young", "Allen", "Sanchez", "Wright", "King", "Scott", "Green"];
        const SobreNome = ["Bauer", "Becker", "Braun", "Busch", "Dietrich", "Engel", "Faber", "Fischer", "Frank", "Frey", "Friedrich", "Fuchs", "Geiger", "Graf", "Groß", "Günther", "Haas", "Hartmann", "Heinrich", "Hermann", "Hoffmann", "Holz", "Huber", "Jäger", "Keller", "König", "Krause", "Krüger", "Kuhn", "Lang", "Lehmann", "Lenz", "Lorenz", "Maier", "Menzel"];

        // Função para gerar um índice aleatório
        function gerarIndice(arr) {
            return Math.floor(Math.random() * arr.length);
        }

        const nomeCompleto = Nome[gerarIndice(Nome)] + " " +
            Nome_Meio[gerarIndice(Nome_Meio)] + " " +
            SobreNome[gerarIndice(SobreNome)];
        
        const nomeCompleto2 = Nome[gerarIndice(Nome)] + " " +
            Nome_Meio[gerarIndice(Nome_Meio)] + " " +
            SobreNome[gerarIndice(SobreNome)];

        // Gera CPF
        const randomCPFad = Array.from({ length: 11 }, () =>
            Math.floor(Math.random() * 10)
        ).join('');

        // Gera CPF
        const randomCPFrs = Array.from({ length: 11 }, () =>
            Math.floor(Math.random() * 10)
        ).join('');

        // Gera RG
        function randomRG(n) {
            let resultado = '';
            for (let i = 0; i < n; i++) {
                resultado += Math.floor(Math.random() * 10);
            }
            return resultado;
        }

        // Gera Endereço
        const CentrosFIA = ["Colégio", "Centro Educacional", "Unidade de Educação", "Centro de Apoio Educacional", "Instituto Educacional", "Complexo Escolar", "Escola Técnica", "Escola Profissionalizante", "Centro de Ensino", "Escola de Aplicação", "Polo Educacional", "Centro de Formação", "Escola de Referência", "Escola Integral", "Escola Especializada", "Escola Comunitária", "Escola Experimental", "Escola Parque", "Escola Sustentável", "Centro de Aprendizagem", "Escola Inclusiva"];
        const End1 = ["Avenida", "Rua", "Beco", "Viela", "Travessa", "Alameda", "Praça", "Largo", "Rodovia", "Estrada", "Caminho", "Passagem", "Viaduto", "Túnel", "Morro", "Ponte", "Calçada", "Escadaria", "Jardim", "Rampa", "Pátio", "Cais", "Parque", "Quarteirão", "Zona", "Distrito", "Vão"];
        const End2 = ["Comendador", "Doutor", "Mestre", "Barão", "Visconde", "Marquês", "Duque/Duquesa", "Príncipe/Princesa", "Rei", "Professor", "Engenheiro", "Doutor", "Arquiteto", "Padre", "Bispo", "Arcebispo", "Cardeal", "Rabbi", "Prefeito", "Governador", "Presidente", "Vice-Presidente", "Ministro", "Senador", "Juiz", "Promotor", "Defensor", "Procurador", "Embaixador", "Cônsul", "Reitor", "Comandante", "General", "Coronel", "Diretor"];
        const EndNome = Nome[gerarIndice(Nome)];
        const EndNome_Meio = SobreNome[gerarIndice(Nome_Meio)];
        const EndBairro3 = ["Copacabana", "Ipanema", "Barra da Tijuca", "Botafogo", "Leblon", "Lapa", "Flamengo", "Glória", "Santa Teresa", "Jardim Botânico", "Méier", "Tijuca", "São Conrado", "Campo Grande", "Bangu", "Madureira", "Ilha do Governador", "Jacarepaguá", "Recreio dos Bandeirantes", "Barra de Guaratiba", "Lagoa", "Centro", "Catete", "Laranjeiras", "Andaraí", "Rio Comprido", "Jacaré", "Cidade Nova", "Vila Isabel", "Rio de Janeiro (bairro central)", "Caju", "Cidade de Deus", "Cosme Velho", "São Cristóvão", "Maracanã", "Engenho de Dentro", "Vila Valqueire", "Grajaú", "Bonsucesso", "Ramos", "Praça da Bandeira", "Ilha do Fundão", "Penha", "Brás de Pina", "Jardim Carioca", "Sampaio", "Freguesia (Jacarepaguá)", "Campinho", "Barra da Tijuca (Centro)", "Barra da Tijuca (Zona Oeste)", "Padre Miguel", "Anil", "Botafogo", "Cosme Velho", "Cidade Nova", "Praça Seca", "Vargem Grande", "Vargem Pequena", "Jardim Guanabara", "Lins de Vasconcelos", "Barra de Guaratiba", "Mangueira", "Catumbi", "Benfica", "Jardim América", "Engenho Novo", "Engenho de Dentro", "Cordovil", "Ilha do Governador", "Ilha de Paquetá", "Penha Circular", "Duque de Caxias", "Cavalcanti", "Realengo", "Uruguai", "Roque", "Encantado", "Sargento Roncalli", "Campo Grande", "Itanhangá", "Oswaldo Cruz", "Cosme Velho", "Barra da Tijuca", "Freguesia", "Santa Cruz", "Guaratiba", "Madureira", "Jardim da Saúde", "Taquara", "Vila Isabel", "Colégio", "Morro do Amorim", "Barra do Piraí", "São Cristóvão", "Recreio dos Bandeirantes", "Nova Iguaçu", "Cidade Nova", "Laje do Muriaé", "Ramos", "São João de Meriti", "Icaraí", "Centro", "São Francisco", "Charitas", "Fonseca", "Santa Rosa", "Engenhoca", "Jardim Icaraí", "Vital Brasil", "Barreto", "Boa Viagem", "Ingá", "Caminho Niemeyer", "Gragoatá", "Barreto", "Ponta D'Areia", "São Domingos", "Rio do Ouro", "Ititioca", "Cubango", "Jardim Paraíso", "São Lourenço", "Boa Vista", "Jardim Beliche", "Itaipu", "Jurujuba", "Ilha das Caieiras", "Cachoeira", "Morro do Céu", "Maceió", "Ladeira", "Canto do Rio", "Brisa Mar", "Sapê", "Porto da Pedra", "Vila Progresso", "Rua da República", "Tenente Jardim", "Santa Teresa", "Largo da Batalha", "São José", "Nossa Senhora das Graças", "Vila Militar", "Jardim Icaraí", "Arariboia", "São João Batista", "Baldeador", "São Gonçalo", "Águas Lindas", "Vila Progresso", "Morro do Estado", "Vila Rica", "Jardim das Flores", "Rio do Ouro", "Alameda", "São Lourenço", "Sete Cidades", "Itaipu", "Vila da Paz", "Maravista", "Parque da Cidade", "Barreto", "Uruguai", "Belém", "Boa Vista", "Maruí", "Boa Viagem", "Vitória", "Vila Jardim", "Barracão", "Olaria", "São Gonçalo", "Sol e Mar", "Peixe Galo", "Engenhoca", "Grajaú", "Meia Légua", "Batelão", "Morro do Céu", "Ilha do Governador", "Bairro Morumbi", "Vila Caetano", "Bairro Nova Cidade", "Espinheiros", "Praia das Flechas", "Loteamento Solares", "Almedina", "Andorinhas", "Verbo Divino", "Niterói Shopping", "Parque Regente", "Pedro do Rio", "Subindo", "Morro do Paraguai", "Porto Real", "Mata da Glória", "Boa Vista Nova", "Jardim Tropical", "Saco do Mamanguá", "Ponta da Areia", "Aterrado", "Barra Mansa", "Belmonte", "Bom Retiro", "Cidade do Aço", "Conforto", "Cruzeiro", "Jardim Amália", "Jardim Belmonte", "Jardim Paraíba", "Jardim Primavera", "Jardim Progresso", "Jardim Vitoria", "Limoeiro", "Monte Castelo", "Niterói", "Padre Josimo", "Parque Floresta", "Parque Maíra", "Parque Randolfe", "Ponte Alta", "Retiro", "Santa Cruz", "Santo Agostinho", "Santo Antônio", "São João", "São Judas Tadeu", "São Luiz", "Vila Rica", "Vila Santa Catarina", "Vila Santa Isabel", "Vila Tavares", "Vila Verde", "Volta Grande", "Vila Velha", "Alvorada", "Angélica", "Aparecida", "Bonsucesso", "Chácara Dona Clara", "Colônia Santo Antônio", "Comary", "Dom Bosco", "Exposição", "Fazendinha", "Floresta", "Freitas", "Gama", "Jardim Alegria", "Jardim Nogueira", "Jardim Ouro Verde", "Jardim Sul", "São Gabriel", "São José", "São Sebastião", "Ponte Alta", "Retiro", "Recanto Verde", "Raul Veiga", "Redentor", "Pedreira", "Parque São Luiz", "Parque Sul", "Parque Ipanema", "Parque Morumbi", "Parque Nossa Senhora das Graças", "Palácio", "Panorama", "Ponto Chic", "Quarteirão da Cultura", "Quilômetro 14", "Realengo", "São Caetano", "São Cristóvão", "São Francisco", "São Paulo", "São Vicente", "Sitio do Pica Pau", "Sitio da Pedra", "Sitio Laje", "Sitio dos Palmares", "Alvorada", "Jardim Calábria", "Jardim Imperador", "Jardim Mandira", "Vila Real", "Vila São José", "Vila São Vicente", "Vila São Sebastião", "Vila Feliz", "Loteamento Progresso", "Loteamento Bandeirantes", "Loteamento Pedreira", "Loteamento Palmeira", "Praça Brasil", "Estádio da Cidadania", "Bairro Santa Maria", "Bairro São Luiz", "Bairro Ouro Verde", "Bairro Novo Horizonte"];

        // Complemento
        const arrayComplemento = ["Fundos", "Frente", "Lado", "Casa", "Apartamento 403", "Sala", "Loja", "Sobreloja", "Cobertura", "Galpão", "Prédio Azul", "Conjunto", "Bloco 2", "Torre Verde", "Edifício branco", "Residência 8", "Sobrado"];
        const randomComplemento = arrayComplemento[gerarIndice(arrayComplemento)];

        // Cidade
        const EndCidade = ["Angra dos Reis", "Aperibé", "Araruama", "Areal", "Armação dos Búzios", "Barra do Piraí", "Barra Mansa", "Belém", "Bom Jardim", "Bom Jesus do Itabapoana", "Cabo Frio", "Cachoeiras de Macacu", "Cambuci", "Campos dos Goytacazes", "Cantagalo", "Carapebus", "Cardoso Moreira", "Carmo", "Casimiro de Abreu", "Comendador Levy Gasparian", "Conceição de Macabu", "Cordeiro", "Duas Barras", "Duque de Caxias", "Engenheiro Paulo de Frontin", "Guapimirim", "Iguaba Grande", "Itaboraí", "Itaguaí", "Italva", "Itaocara", "Itaperuna", "Laje do Muriaé", "Macaé", "Macuco", "Magé", "Mangaratiba", "Maricá", "Mendes", "Mesquita", "Miguel Pereira", "Miracema", "Natividade", "Nilópolis", "Niterói", "Nova Friburgo", "Nova Iguaçu", "Paracambi", "Paraíba do Sul", "Parati", "Paty do Alferes", "Petrópolis", "Pinheiral", "Piraí", "Porciúncula", "Quatis", "Queimados", "Rio Bonito", "Rio Claro", "Rio das Flores", "Rio das Ostras", "Rio de Janeiro", "Santa Maria Madalena", "Santo Antônio de Pádua", "São Fidélis", "São Gonçalo", "São João da Barra", "São João de Meriti", "São José de Ubá", "São José do Vale do Rio Preto", "São Pedro da Aldeia", "São Sebastião do Alto", "Sapucaia", "Saquarema", "Seropédica", "Silva Jardim", "Sumidouro", "Tanguá", "Teresópolis", "Trajano de Moraes", "Três Rios", "Valença", "Varre-Sai", "Vassouras", "Volta Redonda", "Arraial do Cabo", "Rio das Ostras", "Itaocara", "Quissamã", "Paraty", "Cabo Frio", "Mangaratiba"];

        const escolherMunicipioAleatorio = () => {
            const indiceAleatorio = Math.floor(Math.random() * EndCidade.length);
            return EndCidade[indiceAleatorio];
        };

        // Sexo Biologico
        const arraySexoBiologico = ["1", "2"];
        const randomSexoBiologico = arraySexoBiologico[gerarIndice(arraySexoBiologico)];

        // Gerar nome completo
        const randomEscola = CentrosFIA[gerarIndice(CentrosFIA)] + " " +
            Nome[gerarIndice(Nome)] + " " +
            Nome_Meio[gerarIndice(Nome_Meio)] + " " +
            SobreNome[gerarIndice(SobreNome)];

        const etniasNoBrasil = ["Branca", "Preta", "Parda", "Amarela", "Indígena"];
        const randomEtinia = etniasNoBrasil[gerarIndice(etniasNoBrasil)]

        const randomEndereco = Nome[gerarIndice(End1)] + " " +
            End1[gerarIndice(End1)] + " " +
            End2[gerarIndice(End2)] + " " +
            Nome[gerarIndice(Nome)] + " " +
            SobreNome[gerarIndice(SobreNome)] + " " +
            EndBairro3[gerarIndice(EndBairro3)];

        const EndCEP = (min, max) => {
            return `${Math.floor(Math.random() * (max - min + 1)) + min}`.replace(/(\d{5})(\d{3})/, '$1-$2');
        };

        // Data de Nascimento Responsável
        const randomDataAdolescente = () => {
            // Converte anos em dias (considerando anos bissextos aproximadamente)
            const minDias = 14 * 365.25;
            const maxDias = 16 * 365.25;

            // Gera um número aleatório de dias entre 30 e 70 anos
            const dias = Math.floor(Math.random() * (maxDias - minDias + 1) + minDias);

            // Obtém a data atual
            const dataAtual = new Date();

            // Subtrai os dias calculados
            dataAtual.setDate(dataAtual.getDate() - dias);

            // Retorna a data no formato AAAA-MM-DD
            return dataAtual.toISOString().split('T')[0];
        };

        // Gera celular
        const randomCelular = () => {
            // Gera DDD aleatório entre 21, 22 e 23
            const ddds = [21, 22, 23];
            const ddd = ddds[Math.floor(Math.random() * ddds.length)];

            // Gera os 8 dígitos do número
            const firstPart = Math.floor(Math.random() * 9000) + 1000; // 4 dígitos entre 1000-9999
            const secondPart = Math.floor(Math.random() * 9000) + 1000; // 4 dígitos entre 1000-9999

            // Formata o número no padrão (DDD) 9XXXX-XXXX
            return `(${ddd}) 9${firstPart}-${secondPart}`;
        };

        const orgaos = [
            { sigla: 'SSP', nome: 'Secretaria de Segurança Pública' },
            { sigla: 'PC', nome: 'Polícia Civil' },
            { sigla: 'DETRAN', nome: 'Departamento Estadual de Trânsito' },
            { sigla: 'PM', nome: 'Polícia Militar' },
            { sigla: 'CNT', nome: 'Carteira Nacional de Habilitação' },
            { sigla: 'DIC', nome: 'Diretoria de Identificação Civil' },
            { sigla: 'DGPC', nome: 'Direção Geral da Polícia Civil' },
            { sigla: 'IFP', nome: 'Instituto Félix Pacheco' },
            { sigla: 'IPF', nome: 'Instituto Pereira Faustino' },
            { sigla: 'IGP', nome: 'Instituto Geral de Perícias' },
            { sigla: 'IIRGD', nome: 'Instituto de Identificação Ricardo Gumbleton Daunt' },
            { sigla: 'MEX', nome: 'Ministério do Exército' },
            { sigla: 'MMA', nome: 'Ministério da Marinha' },
            { sigla: 'MAE', nome: 'Ministério da Aeronáutica' },
            { sigla: 'POF', nome: 'Polícia Federal' },
            { sigla: 'POM', nome: 'Polícia Militar' },
            { sigla: 'SJS', nome: 'Secretaria da Justiça e Segurança' },
            { sigla: 'SESP', nome: 'Secretaria de Estado da Segurança Pública' },
            { sigla: 'SES', nome: 'Secretaria de Estado de Segurança' },
            { sigla: 'SECC', nome: 'Secretaria de Estado da Casa Civil' }
        ];

        // Gera E-mail
        const randomEmail = () => {
            // Arrays com possíveis componentes do email
            const nomes = ["krt", "jvn", "mzx", "wpq", "hbd", "rsl", "nfy", "ctk", "glm", "dxv", "bzp", "yqw", "ahc", "eki", "onm", "utr", "lvx", "fsd", "wjk", "qbp", "mhc", "zny", "vrt", "xkd", "pql", "bwm", "gsf", "inh", "tka", "yev", "dqr", "ucx", "jpb", "wlm", "fht", "nzv", "akx", "oqy", "bdp", "msc", "gwt", "ivr", "khl", "xnf", "pye", "ubd", "tmc", "rzq", "wkv", "lfh", "jxp", "sny", "bmt", "cgw", "dqv", "ekr", "fls", "hnt", "imx", "jpy", "kqz", "lra", "msb", "ntc", "oud", "pve", "qwf", "rxg", "syh", "tzi", "uaj", "vbk", "wcl", "xdm", "yen", "zfo", "agp", "bhq", "cir", "djs", "ekt", "flu", "gmv", "hnw", "iox", "jpy", "kqz", "lra", "msb", "ntc", "oud", "pve", "qwf", "rxg", "syh", "tzi", "uaj", "vbk", "wcl", "xdm", "yen", "zfo"];
            const sobrenomes = ["joao", "mari", "pedr", "ana", "carl", "juli", "luiz", "gabi", "davi", "beat", "rafa", "vita", "thia", "dani", "feli", "brun", "vini", "cami", "guil", "bia", "andr", "lari", "marc", "isa", "tati", "lean", "manu", "rica", "juca", "duda", "rena", "nath", "theo", "luca", "edu", "livi", "gust", "caro", "arth", "soph", "fred", "fern", "robe", "alin", "hele", "roge", "tama", "mila", "thom", "clau", "alex", "vict", "rebi", "luis", "laur", "otto", "sara", "ivan", "olga", "raul", "ines", "omar", "iris", "igor", "elza", "hugo", "vera", "enzo", "ruth", "caio", "rosa", "jose", "jane", "paulo", "eva", "leo", "ada", "aldo", "ida", "rui", "lia", "zeus", "mel", "gil", "lua", "ian", "bia", "tom", "zoe", "val", "isa", "max", "amy", "ivo", "luz", "ben", "mai", "teo", "ava", "dan", "joy"];
            const dominios = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'];
            const separadores = ['.', '_', ''];

            // Seleciona elementos aleatórios dos arrays
            const nome = nomes[Math.floor(Math.random() * nomes.length)];
            const sobrenome = sobrenomes[Math.floor(Math.random() * sobrenomes.length)];
            const dominio = dominios[Math.floor(Math.random() * dominios.length)];
            const separador = separadores[Math.floor(Math.random() * separadores.length)];
            const numero = Math.floor(Math.random() * 999); // Número aleatório de 0 a 999

            // Decide aleatoriamente se inclui o número
            const incluiNumero = Math.random() > 0.5;

            // Monta o email
            const emailLocal = incluiNumero
                ? `${nome}${separador}${sobrenome}${numero}`
                : `${nome}${separador}${sobrenome}`;

            return `${emailLocal}@${dominio}`;
        };

        // Função para selecionar um órgão aleatório
        const randomOrgaoExpedidor = () => {
            const indiceAleatorio = Math.floor(Math.random() * orgaos.length);
            return orgaos[indiceAleatorio].sigla;
        };

        // Recebe data de Nascimento e gera um periodo minimo e máximo
        const calculateDates = (date) => {
            // Converte a string para objeto Date
            const currentDate = new Date(date);

            // Calcula a data mínima (14 anos)
            const minDate = new Date(currentDate);
            minDate.setFullYear(minDate.getFullYear() + 14);

            // Calcula a data máxima (16 anos e 6 meses)
            const maxDate = new Date(currentDate);
            maxDate.setFullYear(maxDate.getFullYear() + 16);
            maxDate.setMonth(maxDate.getMonth() + 6);

            // Função para formatar a data em DD/MM/YYYY
            const formatDate = (date) => {
                return date.toLocaleDateString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            };

            return {
                minDate: formatDate(minDate),
                maxDate: formatDate(maxDate)
            };
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            cep: debugMyPrint ? `${EndCEP(22000000, 29000000)}` : null,
            unit: '',
            //
            filterResponsavel: null,
            //
            token_csrf: token_csrf,
            json: '1',
            termo: false,
            //
            id: null,
            Nome: debugMyPrint ? nomeCompleto : null,
            CPF: debugMyPrint ? `${randomCPFad}` : null,
            RG: debugMyPrint ? randomRG(8) : null,
            ExpedidorRG: debugMyPrint ? randomOrgaoExpedidor() : null,
            ExpedicaoRG: null,
            CEP: debugMyPrint ? `${EndCEP(22000000, 29000000)}` : null,
            Endereco: debugMyPrint ? randomEndereco : null,
            Numero: debugMyPrint ? randomRG(4) : null,
            Complemento: debugMyPrint ? randomComplemento : null,
            Bairro: null,
            UF: `RJ`,
            Municipio: debugMyPrint ? escolherMunicipioAleatorio() : null,
            Nascimento: debugMyPrint ? randomDataAdolescente() : null,
            PeriodoId: debugMyPrint ? '2' : null,
            CadastroId: null,
            PerfilId: debugMyPrint ? '2' : null,
            PerfilDescricao: null,
            SexoId: debugMyPrint ? '1' : null,
            SexoBiologico: null,
            GeneroIdentidadeId: debugMyPrint ? `${randomRG(1)}, ${randomRG(1)}, ${randomRG(1)}` : null,
            Genero: null,
            GeneroIdentidadeDescricao: null,
            AcessoCadastroID: null,
            UnidadeId: null,
            Unidade: null,
            NomeUnidade: null,
            AcessoId: null,
            AcessoDescricao: null,
            ProntuarioId: null,
            NomeMae: null,
            TelefoneFixo: null,
            TelefoneMovel: null,
            TelefoneRecado: debugMyPrint ? randomCelular() : null,
            Email: debugMyPrint ? randomEmail() : null,
            NMatricula: null,
            Certidao: null,
            Etnia: debugMyPrint ? randomEtinia : null,
            Escolaridade: debugMyPrint ? '7º Ano Ensino Fundamental' : null,
            Registro: null,
            Folha: null,
            Livro: null,
            Circunscricao: null,
            Zona: null,
            UFRegistro: null,
            TipoEscola: debugMyPrint ? 'Publica' : null,
            turno_escolar: debugMyPrint ? randomSexoBiologico : null,
            NomeEscola: debugMyPrint ? randomEscola : null,
            DataCadastramento: null,
            DataTermUnid: null,
            DataInicioUnid: null,
            DataAdmissao: null,
            DataDemissao: null,
            CodProfissao: null,
            ResponsavelID: null,
            Responsavel_Nome: debugMyPrint ? nomeCompleto2 : null,
            Responsavel_Email: null,
            Responsavel_TelefoneFixo: null,
            Responsavel_TelefoneMovel: debugMyPrint ? randomCelular() : null,
            Responsavel_TelefoneRecado: null,
            Responsavel_Endereco: null,
            Responsavel_CPF: debugMyPrint ? randomCPFrs : null,
            ProfissaoCodigo: null,
            ProfissaoDescricao: null,
            ProfissaoFavorito: null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
        });

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            // console.log('handleChange: ', name);
            // console.log('handleChange: ', value);

            setMessage({ show: false, type: null, message: null });

            if (name === 'cep') {
                setUnits(originalUnits);
            }

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

            let processedValue = value;

            if (name === 'cep') {
                processedValue = applyMaskCEP(value);
                setFormData((prev) => ({
                    ...prev,
                    [name]: processedValue,
                    CEP: processedValue
                }));
                return true;
            }

            if (name === 'unit') {

                setFormData((prev) => ({
                    ...prev,
                    UnidadeId: value
                }));
                return true;
            }

            if (name === 'Escolaridade' && value === 'Outro') {
                setOnEscolaridade(false);
            }

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            // Se o campo for o CPF, faz a validação
            if (name === 'cpf' && !isValidCPF(value)) {
                const cpfInput = event.target;
                cpfInput.classList.add('is-invalid');
                setError('CPF inválido');
            }
        };

        const handleBlur = async (event) => {
            const { name, value } = event.target;

            // console.log('name handleBlur: ', name);
            // console.log('value handleBlur: ', value);

            if (
                formData.Nascimento &&
                Date.parse(formData.Nascimento) &&
                name === 'unit'
            ) {
                // console.log('Nascimento: ', formData.Nascimento);
                // console.log('ID Unidade: ', formData.unit);
                const setData = {
                    UnidadeId: value
                }
                // console.log('setData: ', setData);
                fetchPeriodos(setData);
            }

            if (name === 'cep') {
                orderUnitsByCepProximity(value);
                const busca1 = await fetchViaCep(value);
                const busca2 = await fetchOpenCep(value);

                if (busca1) {

                } else if (busca2) {

                } else {
                    // console.log('CEP não encontrado');
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'CEP não encontrado'
                    });
                }
            }

            if (name === 'DataAdmissao' && value === '') {
                return true;
            }

            const dataAtual = new Date();
            dataAtual.setHours(0, 0, 0, 0); // Ajusta para data atual sem horas para comparação precisa

            // Converte as datas apenas se os campos tiverem valor
            const dataAdmissao = formData.DataAdmissao ? new Date(formData.DataAdmissao) : null;
            const dataDemissao = formData.DataDemissao ? new Date(formData.DataDemissao) : null;

            let errorMessage = '';

            // Se o campo estiver vazio, exibe uma mensagem abaixo do campo e redefine o valor
            if (!value && name === 'DataAdmissao' && message.show === false) {

                setMessage({
                    show: true,
                    type: 'light',
                    message: "Informe uma data para admissão do funcionário."
                });

                setFormData((prev) => ({
                    ...prev,
                    [name]: ''
                }));

            } else if (name === 'DataAdmissao' && message.show === false) {
                const limiteMinimo = new Date(dataAtual);
                limiteMinimo.setFullYear(limiteMinimo.getFullYear() - 34);

                // Verifica se a data de admissão é menor que 34 anos a partir da data atual
                if (dataAdmissao && dataAdmissao < limiteMinimo && message.show === false) {
                    setShowEmptyMessage(true);
                    setAvisoCampo('A Data deve ser maior do que 34 anos a partir da data atual');
                } else if (dataAdmissao && dataAdmissao > dataAtual && message.show === false) {
                    // Verifica se a data de admissão é superior à data atual
                    errorMessage = 'Data de Admissão inválida. Por favor, insira uma data válida não superior à data atual.';

                    setMessage({
                        show: true,
                        type: 'light',
                        message: errorMessage
                    });

                    // Zera o valor do campo de data de admissão
                    setFormData((prev) => ({
                        ...prev,
                        DataAdmissao: ''
                    }));
                }
            } else if (name === 'DataDemissao') {
                if (dataDemissao && dataDemissao < dataAdmissao && message.show === false) {
                    // Verifica se a data de demissão é anterior à data de admissão
                    setShowEmptyMessage(true);

                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Data de Demissão inválida. Por favor, insira uma data válida superior à Data de Admissão e à data atual.'
                    });

                    // Zera o valor do campo de data de demissão
                    setFormData((prev) => ({
                        ...prev,
                        DataDemissao: ''
                    }));
                } else if (dataDemissao && dataDemissao > dataAtual && message.show === false) {
                    // Verifica se a data de demissão é superior à data atual
                    errorMessage = 'A Data de Demissão não pode ser superior a hoje';

                    setMessage({
                        show: true,
                        type: 'light',
                        message: errorMessage
                    });

                    // Zera o valor do campo de data de demissão
                    setFormData((prev) => ({
                        ...prev,
                        DataDemissao: ''
                    }));
                }
            } else {
                setFormData((prev) => ({
                    ...prev,
                    [name]: value
                }));
                setMessage({ show: false, type: 'light', message: errorMessage });
            }
        };

        // Função para verificar campos obrigatórios
        const validarCamposObrigatorios = (dados, camposObrigatorios) => {
            const camposVazios = Object.keys(camposObrigatorios).filter(campo => !dados[campo]);

            // Retorna o status e os campos vazios
            return {
                isValid: camposVazios.length === 0, // true se não houver campos vazios
                camposVazios, // Lista dos campos que estão vazios
            };
        };

        // Função para trocar de aba
        const handleTabClick = (tab) => {
            // console.log('handleTabClick: ', tab);
            setTabNav(tab); // Atualiza a aba selecionada
        };

        // submitAllForms
        const submitAllForms = async (filtro) => {
            console.log('submitAllForms...');

            // Cria um objeto para armazenar os dados dos inputs
            const inputs = document.querySelectorAll(`[data-api="${filtro}"]`);
            const inputValues = {};
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    // Para checkbox, considera o estado "checked"
                    inputValues[input.name] = input.checked;
                } else if (input.type === 'radio') {
                    // Para radio, seleciona o que está marcado
                    if (input.checked) {
                        inputValues[input.name] = input.value;
                    }
                } else if (input.tagName === 'SELECT') {
                    // Para select, pega o valor selecionado
                    inputValues[input.name] = input.value;
                } else {
                    // Para outros tipos de input (text, number, etc.)
                    inputValues[input.name] = input.value;
                }
            });

            // Adiciona os valores dos inputs ao formData
            const setData = {
                ...formData,
                ...inputValues
            };

            let data = '';
            let dbResponse = [];
            let response = '';

            if (filtro === `filtro-${origemForm}`) {
                // Convertendo os dados do setPost em JSON
                // console.log(`filtro-${origemForm}`);
                // console.log(`${base_url}${api_post_cadastrar_adolescente}`);


                if (isCPFOptional === true) {
                    delete camposObrigatorios.CPF;
                }

                const { isValid, camposVazios } = validarCamposObrigatorios(setData, camposObrigatorios);

                if (!isValid) {
                    // console.log('isValid :: ', isValid);
                    const nomesCamposVazios = camposVazios.map(campo => camposObrigatorios[campo]);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `<b>Os campos</b>: <br/> ${nomesCamposVazios.join("<br/>")}<br/> <b>não podem estar em branco</b>`,
                    });
                    return;
                }

                console.log("Campos Obrigatórios na validação:", camposObrigatorios);

                response = await fetch(`${base_url}${api_post_cadastrar_adolescente}`, {
                    method: 'POST',
                    body: JSON.stringify(setData),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.statusText}`);
                }

                data = await response.json();

                let resposta = '';

                if (checkWordInArray(getURI, 'cadastrar') || checkWordInArray(getURI, 'drupal')) {
                    resposta = 'Cadastro';
                } else if (checkWordInArray(getURI, 'atualizar')) {
                    resposta = 'Atualização';
                } else if (checkWordInArray(getURI, 'consultar')) {
                    resposta = 'Consulta'
                } else {
                    resposta = 'Ação';
                }

                // Processa os dados recebidos da resposta
                console.log(' resposta :: ', resposta);

                if (
                    data.result &&
                    data.result.affectedRows &&
                    data.result.affectedRows > 0
                ) {
                    dbResponse = data.result.dbResponse;
                    if (resposta === 'Cadastro') {
                        console.log('dbResponse: Cadastro realizado com sucesso', dbResponse);
                        setMessage({
                            show: true,
                            type: 'light',
                            message: `${resposta} realizada com sucesso. Foi enviado um email de confirmação`
                        });
                        fetchPostconfirmaEmail();
                        redirectTo('index.php/fia/ptpa/adolescente/endpoint/drupal');
                        return true;
                    }
                    console.log('dbResponse: Passou!!!!!');

                    if (checkWordInArray(getURI, 'atualizar')) {
                        setMessage({
                            show: true,
                            type: 'light',
                            message: `${resposta} realizada com sucesso. Foi enviado um email de confirmação`
                        });
                        redirectTo('index.php/fia/ptpa/adolescente/endpoint/exibir');
                    }

                    return dbResponse;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `Não foi possivel realizar a ${resposta}`
                    });
                    return null;
                }
            }
        };

        // POST Padrão 
        const fetchPostconfirmaEmail = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_confirma_email) => {
            console.log('fetchPostconfirmaEmail...');

            const url = custonBaseURL + custonApiPostObjeto;
            const setData = {
                ...formData,
                setFrom: null,
                setMail: formData.Email,
                setCC: null,
                setBCC: null,
                setSubject: 'Confirmação de cadastro FIA-PTPA' + getFormattedDate(),
                messageMail: 'Cadastro realizado com sucesso',
            };
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                //
                const data = await response.json();
                console.log('data', data);
                // 
                if (data.result && data.result === 'success') {
                    // 
                    // console.log('Foi enviado um email de confirmação');
                    //
                } else {
                    // console.log('Erro ao enviar o email de confirmação');
                    setIsLoading(false);
                }
                redirectTo('index.php/fia/ptpa/adolescente/endpoint/exibir');
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // formData.termo
        React.useEffect(() => {
            setTermoAceito(formData.termo || false);
        }, [formData.termo]);

        React.useEffect(() => {
            if (periodos.length > 0) {
                periodos.forEach((periodo) => {
                    let data_periodo = periodo.periodo_data_inicio
                        ? periodo.periodo_data_inicio
                        : '';

                    if (formData.Nascimento) {
                        let recebe_data_15_165 = calculateDates(formData.Nascimento);

                        // Convertendo as datas corretamente
                        let dataPeriodo = new Date(data_periodo);
                        // Convertendo de DD/MM/YYYY para Date
                        let minDate = new Date(recebe_data_15_165.minDate.split('/').reverse().join('-'));
                        let maxDate = new Date(recebe_data_15_165.maxDate.split('/').reverse().join('-'));

                        // Normalizar as datas removendo o horário
                        dataPeriodo.setHours(0, 0, 0, 0);
                        minDate.setHours(0, 0, 0, 0);
                        maxDate.setHours(0, 0, 0, 0);

                        if (dataPeriodo >= minDate && dataPeriodo <= maxDate) {
                            // console.log('Data está no período válido (15 a 16.5 anos)')
                        } else {
                            setMessage({
                                show: true,
                                type: 'light',
                                message: 'A Unidade selecionada não possui vagas para a idade de nascimento informado'
                            });
                        }// console.log
                    }
                });
            }
        }, [periodos, formData.Nascimento]);

        React.useEffect(() => {
            // ('React.useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                // console.log('loadData iniciando...');
                setIsLoading(true);
                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchResponsaveis();
                    await fetchAdolescentes();
                    await fetchSexos();
                    await fetchPostUnidade();
                    await fetchGeneros();
                    await fetchMunicipios();
                    // await fetchPeriodos();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                }
            };

            loadData();
        }, []);

        // Função para validar pelo ViaCEP
        const fetchViaCep = async (setCep) => {
            if (!setCep) return false;
            // Remove máscara de CEP
            const buscaCep = setCep.replace(/[^\d]/g, '');
            const url = `${viacep}${buscaCep}/json`;
            // console.log('fetchViaCep url:', url);
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`OpenCEP fetch failed: ${response.statusText}`);
                }
                const data = await response.json();
                // console.log('fetchViaCep Data:', data);
                const returnLogradouro = `${data.logradouro || ''}, ${data.bairro || ''}`;
                const returnMunicipio = data.localidade || '';
                setFormData((prev) => ({
                    ...prev,
                    Endereco: returnLogradouro,
                    Municipio: returnMunicipio,
                }));
                return true;
            } catch (error) {
                // console.log('Error fetching ViaCEP data:', error);
                return false;
            }
        };

        // Função para validar pelo OpenCEP
        const fetchOpenCep = async (set_cep) => {
            if (!set_cep) return false;
            // Remove máscara de CEP
            const buscaCep = set_cep.replace(/[^\d]/g, '');
            const url = `${opencep}${buscaCep}`;
            // console.log('fetchOpenCep url:', url);
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`OpenCEP fetch failed: ${response.statusText}`);
                }
                const data = await response.json();
                // console.log('OpenCEP Data:', data);
                const returnLogradouro = `${data.logradouro || ''}, ${data.bairro || ''}`;
                const returnMunicipio = data.localidade || '';
                setFormData((prev) => ({
                    ...prev,
                    Endereco: returnLogradouro,
                    localidade: returnMunicipio,
                }));
                return true;
            } catch (error) {
                // console.log('Error fetching OpenCEP data:', error);
                return false;
            }
        };

        // POST fetchPostUnidade
        const fetchPostUnidade = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_filter_unidades, customPage = '') => {
            const url = custonBaseURL + custonApiPostObjeto + customPage + '?limit=90000';
            console.log('fetchPostUnidade url:', url);
            const setData = formData;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                // 
                const data = await response.json();
                console.log('fetchPostUnidade data:', data);
                // 
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('fetchPostUnidade dbResponse ::', dbResponse);
                    setUnits(dbResponse);
                    setOriginalUnits(dbResponse);
                    setDataLoading(false);
                    return true;
                    //
                } else {
                    setMessage({
                        show: false,
                        type: 'light',
                        message: 'Não foram encontradas Unidades cadastradas'
                    });
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        const fetchAdolescentes = async () => {
            // console.log('fetchAdolescentes ...');
            try {
                if (checkWordInArray(getURI, 'cadastrar')) {
                    setFormData((prev) => ({
                        ...prev,
                    }));
                    return false;
                }

                const response = await fetch(base_url + api_get_atualizar_adolescente);
                // console.log(base_url + api_get_atualizar_adolescente);
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Adolescentes: ', data);
                    setFormData((prevFormData) => ({
                        ...prevFormData,
                        ...data.result.dbResponse[0]
                    }));
                }
            } catch (error) {
                setError('Erro ao carregar Adolescentes: ' + error.message);
            }
        };

        // Fetch para obter os Responsaveis Filtrados
        const fetchResponsaveis = async () => {
            try {
                const response = await fetch(base_url + api_get_responsavel, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }); const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Responsável Filtrados: ', data);
                    setResponsaveis(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Responsaveis FIltrados: ' + error.message);
            }
        };

        // Fetch para obter os Sexos
        const fetchSexos = async () => {
            try {
                const url = base_url + api_get_sexo;
                // console.log('fetchSexos url:', url);
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                const data = await response.json();
                // console.log('fetchSexos data:', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const setDbSexo = data.result.dbResponse;
                    // console.log('setDbSexo :: ', setDbSexo);
                    setListaSexos(setDbSexo);
                    setDataLoading(false);
                }
            } catch (error) {
                setError('Erro ao carregar Sexos: ' + error.message);
            }
        };

        // Fetch para obter os Gêneros
        const fetchGeneros = async () => {
            try {
                const response = await fetch(base_url + api_get_genero)
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Genero: ', data);
                    setGeneros(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Gêneros: ' + error.message);
            }
        };

        // Fetch para obter os Municípios
        const fetchMunicipios = async () => {
            try {
                const response = await fetch(base_url + api_get_municipio, {
                    method: 'POST', // Define o método como POST
                    headers: {
                        'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
                    },
                    body: JSON.stringify({}) // Corpo da requisição vazio
                });
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Municipio: ', data);
                    setMunicipios(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Municípios: ' + error.message);
            }
        };

        // Fetch para obter os Periodos
        const fetchPeriodos = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_get_periodo, customPage = '') => {
            // console.log('fetchPeriodos... ');

            const url = custonBaseURL + custonApiPostObjeto + customPage + '?limit=90000';
            // console.log('fetchPeriodos url:', url);

            const setData = formData;
            // console.log('fetchPeriodos setData ::', setData);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('fetchPeriodos data ::', data);

                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('fetchPeriodos dbResponse ::', dbResponse);
                    // 
                    setPeriodos(dbResponse);
                    setPagination('list');
                    // 
                } else {
                    setPeriodos([]);
                    setIsLoading(false);
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
            }
        };

        // Fetch para obter os Municípios
        const fetchPeriodos1 = async () => {
            try {
                const response = await fetch(base_url + api_get_periodo, {
                    method: 'POST', // Define o método como POST
                    headers: {
                        'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
                    },
                    body: JSON.stringify({}) // Corpo da requisição vazio
                });
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('fetchPeriodos[1]: ', data);
                    setPeriodos(data.result.dbResponse);
                }
            } catch (error) {
                setError('Erro ao carregar Periodos: ' + error.message);
            }
        };

        if (debugMyPrint && error) {
            return <div className="d-flex justify-content-center align-items-center min-vh-100">
                <div className="alert alert-danger" role="alert">
                    {error}
                </div>
            </div>
        }

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        const generoOptions = [
            { id: '1', genero: 'Não Opinar' },
            { id: '2', genero: 'Transgênero' },
            { id: '3', genero: 'Não-binário' },
            { id: '4', genero: 'Cisgênero' },
            { id: '5', genero: 'Agênero' },
            { id: '6', genero: 'Gênero-Fluido' },
            { id: '7', genero: 'Bigênero' },
            { id: '8', genero: 'Pangênero' },
            { id: '9', genero: 'Genderqueer' },
            { id: '10', genero: 'Two-Spirit' },
            { id: '11', genero: 'Demiboy' },
            { id: '12', genero: 'Demigirl' },
        ];

        const renderCPF = () => {
            // console.log('listaSexos :: ', listaSexos);
            // console.log('formData :: ', formData);
            // console.log('setFormData :: ', setFormData);
            return (
                <div className="border border-top-0 mb-4 p-4">
                    <div className="row">
                        {/* Opção CPF do Formulário */}
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>

                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'CPF',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'CPF', 
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 15, // maxlength - Telefone: 14, CPF: 15, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: 'CPF', // CPF, Telefone, CEP, , SEI, Processo.
                                    }}
                                />
                                
                            </form>
                            {!checkWordInArray(getURI, 'consultar') && !checkWordInArray(getURI, 'atualizar') && (
                                <>
                                    <input
                                        type="checkbox"
                                        id="isCPFOptional"
                                        name="isCPFOptional"
                                        defaultChecked={isCPFOptional}
                                        onChange={(e) => setIsCPFOptional(e.target.checked)}
                                    />
                                    <label
                                        htmlFor="isCPFOptional"
                                        style={{ marginLeft: '8px' }}
                                    >
                                        Campo opcional para adolescentes com menos de 16 anos.
                                    </label>
                                </>
                            )}
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppNome formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppEmail formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label htmlFor="Nascimento" style={formLabelStyle} className="form-label">
                                            Data de Nascimento
                                        </label>
                                        <div className="p-2">
                                            {formData.Nascimento ? (
                                                <>{formData.Nascimento}</>
                                            ) : (
                                                <span className="text-muted">Não informado</span>
                                            )}
                                        </div>
                                    </div>
                                ) : (
                                    <AppDate
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Data de Nascimento',
                                            nameField: 'Nascimento',
                                            attributeMax: '',
                                            attributeRequired: true,
                                            attributeReadOnly: false,
                                            attributeDisabled: false,
                                            attributeMask: 'Adolescente',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>

                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'RG',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'RG', 
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 2, // minlength 
                                        attributeMaxlength: 22, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                    }}
                                />
                                
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppOrgaoExpedidor formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="cep"
                                        style={formLabelStyle}
                                        className="form-label">CEP
                                        {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                    </label>
                                    {checkWordInArray(getURI, 'consultar') ? (
                                        <div className="p-2">
                                            {formData.CEP || formData.cep || <span className="text-muted">Não informado</span>}
                                        </div>
                                    ) : (
                                        <input
                                            type="text"
                                            id="cep"
                                            name="cep"
                                            className="form-control"
                                            value={formData.cep || ''}
                                            onChange={handleChange}
                                            onFocus={handleFocus}
                                            onBlur={handleBlur}
                                            style={{ ...formControlStyle, boxShadow: 'none' }}
                                            data-api={`filtro-${origemForm}`}
                                            required
                                        />
                                    )}
                                </div>
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            style={formLabelStyle}
                                            className="form-label">Endereço
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Endereco}
                                        </div>
                                    </div>
                                ) : (
                                    <AppText
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Endereço',
                                            labelColor: 'black',
                                            nameField: 'Endereco',
                                            attributeMinlength: 4,
                                            attributeMaxlength: 150,
                                            attributePattern: 'Caracter',
                                            attributeAutocomplete: 'on',
                                            attributeRequired: true,
                                            attributeReadOnly: checkWordInArray(getURI, 'consultar'),
                                            attributeDisabled: checkWordInArray(getURI, 'consultar'),
                                            attributeMask: '',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="Numero"
                                            style={formLabelStyle}
                                            className="form-label">Número
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Numero}
                                        </div>
                                    </div>
                                ) : (
                                    <AppText
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Número',
                                            labelColor: 'black',
                                            nameField: 'Numero',
                                            attributeMinlength: 2,
                                            attributeMaxlength: 10,
                                            attributePattern: 'Inteiro',
                                            attributeAutocomplete: 'on',
                                            attributeRequired: true,
                                            attributeReadOnly: checkWordInArray(getURI, 'consultar'),
                                            attributeDisabled: checkWordInArray(getURI, 'consultar'),
                                            attributeMask: '',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="Complemento"
                                            style={formLabelStyle}
                                            className="form-label"
                                        > Complemento
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Complemento}
                                        </div>
                                    </div>
                                ) : (
                                    <AppText
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Complemento',
                                            labelColor: 'black',
                                            nameField: 'Complemento',
                                            attributeMinlength: 4,
                                            attributeMaxlength: 100,
                                            attributePattern: 'Caracter, Inteiro',
                                            attributeAutocomplete: 'on',
                                            attributeRequired: true,
                                            attributeReadOnly: false, // isso já tá sendo controlado no ternário acima
                                            attributeDisabled: false,
                                            attributeMask: '',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="Municipio"
                                            style={formLabelStyle}
                                            className="form-label"
                                        > Município
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Municipio}
                                        </div>
                                    </div>
                                ) : (
                                    <AppText
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Município',
                                            labelColor: 'black',
                                            nameField: 'Municipio',
                                            attributeMinlength: 4,
                                            attributeMaxlength: 100,
                                            attributePattern: 'Caracter',
                                            attributeAutocomplete: 'on',
                                            attributeRequired: true,
                                            attributeReadOnly: false,
                                            attributeDisabled: false,
                                            attributeMask: '',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label htmlFor="unit"
                                            style={formLabelStyle}
                                            className="form-label">Unidade
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.NomeUnidade
                                                ? formData.NomeUnidade
                                                : <span className="text-muted">Não informado</span>}
                                        </div>
                                    </div>
                                ) : (
                                    <div style={formGroupStyle}>
                                        <label htmlFor="unit" style={formLabelStyle} className="form-label">
                                            Unidade<strong style={requiredField}>*</strong>
                                        </label>
                                        {(dataLoading && units) ? (
                                            <div className="p-2">
                                                <AppLoading parametros={{
                                                    tipoLoading: "progress",
                                                    carregando: dataLoading
                                                }} />
                                            </div>
                                        ) : (
                                            <select
                                                data-api={`filtro-${origemForm}`}
                                                id="unit"
                                                name="unit"
                                                value={formData.UnidadeId || ''}
                                                onFocus={handleFocus}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                style={formControlStyle}
                                                className="form-select form-select-sm"
                                                required
                                                aria-label="Default select 2"
                                            >
                                                <option value="">Seleção Nula</option>
                                                {units.map(unit_list => (
                                                    <option key={`${unit_list.id}`} value={unit_list.id}>
                                                        {unit_list.unidades_nome} - {unit_list.unidades_cep}
                                                    </option>
                                                ))}
                                            </select>
                                        )}
                                    </div>
                                )}
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {dataLoading ? (
                                    <div>
                                        <div>&nbsp;</div>
                                        <AppLoading parametros={{
                                            tipoLoading: "progress",
                                            carregando: dataLoading
                                        }} />
                                    </div>
                                ) : (
                                    (checkWordInArray(getURI, 'consultar')) ? (
                                        <div>
                                            <div style={formGroupStyle}>
                                                <label
                                                    htmlFor="GeneroIdentidadeId"
                                                    style={formLabelStyle}
                                                    className="form-label">Gênero
                                                    {(checkWordInArray(getURI, 'consultar')) ? null : (<strong style={requiredField}>*</strong>)}
                                                </label>
                                                <div className='p-2'>
                                                    {formData.GeneroIdentidadeId
                                                        ? formData.GeneroIdentidadeId.split(',').map(id => {
                                                            const genero = generoOptions.find(g => String(g.id) === id.trim());
                                                            return genero ? genero.genero : `ID ${id}`;
                                                        }).join(', ')
                                                        : <span className="text-muted">Não informado</span>}
                                                </div>
                                            </div>
                                        </div>
                                    ) : (
                                        <div>
                                            <AppSelectBtnCheck
                                                submitAllForms
                                                parametros={parametros}
                                                formData={formData}
                                                setFormData={setFormData}
                                                fieldAttributes={{
                                                    attributeOrigemForm: `${origemForm}`,
                                                    labelField: 'Gênero',
                                                    nameField: 'GeneroIdentidadeId',
                                                    errorMessage: '', // Mensagem de Erro personalizada
                                                    attributeRequired: true,
                                                    attributeDisabled: false,
                                                    attributeFieldKey: ['id', 'key'], // Chave do campo
                                                    attributeFieldName: ['genero', 'value'], // Nome do campo
                                                    objetoArrayKey: [
                                                        { key: '1', value: 'Opção 1' },
                                                        { key: '2', value: 'Opção 2' },
                                                        { key: '3', value: 'Opção 3' },
                                                        { key: '4', value: 'Opção 4' }
                                                    ],
                                                    api_get: `${api_get_genero}`,
                                                    api_post: `${api_get_genero}`,
                                                    api_filter: `${api_get_genero}`,
                                                }}
                                            />
                                        </div>
                                    )
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {checkWordInArray(getURI, 'consultar') ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="Etnia"
                                            style={formLabelStyle}>Etnia
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Etnia ? (
                                                <span>{formData.Etnia}</span>
                                            ) : (
                                                <span className="text-muted">Não informado</span>
                                            )}
                                        </div>
                                    </div>
                                ) : (
                                    <div style={formGroupStyle}>
                                        <label htmlFor="Etnia" style={formLabelStyle}>
                                            Etnia<strong style={requiredField}>*</strong>
                                        </label>
                                        <select
                                            data-api="form-adolescente"
                                            id="Etnia"
                                            name="Etnia"
                                            className="form-select form-select-sm"
                                            value={formData.Etnia || ''}
                                            onChange={handleChange}
                                            style={formControlStyle}
                                            required
                                            aria-label="Default select 0"
                                        >
                                            <option value="">Seleção Nula</option>
                                            {etniasNoBrasil.map(etinia_select => (
                                                <option key={etinia_select} value={etinia_select}>
                                                    {etinia_select}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                )}
                            </form>
                        </div>

                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="SexoId"
                                        style={formLabelStyle}
                                        className="form-label">Sexo
                                        {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                    </label>
                                    {dataLoading ? (
                                        <div className="p-2">
                                            <AppLoading parametros={{
                                                tipoLoading: "progress",
                                                carregando: dataLoading
                                            }} />
                                        </div>
                                    ) : (
                                        <>
                                            {(checkWordInArray(getURI, 'consultar')) ? (
                                                <div className="p-2">
                                                    {listaSexos.find(item => item.id == formData.SexoId)?.sexo_biologico || (
                                                        <span className="text-muted">Não informado</span>
                                                    )}
                                                </div>
                                            ) : (
                                                <select
                                                    data-api={`filtro-${origemForm}`}
                                                    id="SexoId"
                                                    name="SexoId"
                                                    value={formData.SexoId || ''}
                                                    onChange={handleChange}
                                                    style={formControlStyle}
                                                    className="form-select form-select-sm"
                                                    required
                                                    aria-label="Default select 2"
                                                >
                                                    <option value="">Seleção Nula</option>
                                                    {listaSexos.map(sexo_select => (
                                                        <option key={sexo_select.id} value={sexo_select.id}>
                                                            {sexo_select.sexo_biologico}
                                                        </option>
                                                    ))}
                                                </select>
                                            )}
                                        </>
                                    )}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )
        }

        const renderCertidao = () => {
            return (
                <div className="border border-top-0 mb-4 p-4">
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppCertidaoNascimento formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppRegistro formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppZona formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppFolha formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppLivro formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppCircunscricao formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        {/* Opção CPF do Formulário */}
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppNome formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppEmail formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label htmlFor="Nascimento" style={formLabelStyle} className="form-label">
                                            Data de Nascimento
                                        </label>
                                        <div className="p-2">
                                            {formData.Nascimento ? (
                                                <>{formData.Nascimento}</>
                                            ) : (
                                                <span className="text-muted">Não informado</span>
                                            )}
                                        </div>
                                    </div>
                                ) : (
                                    <AppDate
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Data de Nascimento',
                                            nameField: 'Nascimento',
                                            attributeMax: '',
                                            attributeRequired: true,
                                            attributeReadOnly: false,
                                            attributeDisabled: false,
                                            attributeMask: 'Adolescente',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppRG formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <AppOrgaoExpedidor formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="cep"
                                        style={formLabelStyle}
                                        className="form-label">CEP
                                        {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                    </label>
                                    {checkWordInArray(getURI, 'consultar') ? (
                                        <div className="p-2">
                                            {formData.CEP || formData.cep || <span className="text-muted">Não informado</span>}
                                        </div>
                                    ) : (
                                        <input
                                            type="text"
                                            id="cep"
                                            name="cep"
                                            className="form-control"
                                            value={formData.cep || ''}
                                            onChange={handleChange}
                                            onFocus={handleFocus}
                                            onBlur={handleBlur}
                                            style={{ ...formControlStyle, boxShadow: 'none' }}
                                            data-api={`filtro-${origemForm}`}
                                            required
                                        />
                                    )}
                                </div>
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-8">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            style={formLabelStyle}
                                            className="form-label">Endereço
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Endereco}
                                        </div>
                                    </div>
                                ) : (
                                    <AppText
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Endereço',
                                            labelColor: 'black',
                                            nameField: 'Endereco',
                                            attributeMinlength: 4,
                                            attributeMaxlength: 150,
                                            attributePattern: 'Caracter',
                                            attributeAutocomplete: 'on',
                                            attributeRequired: true,
                                            attributeReadOnly: checkWordInArray(getURI, 'consultar'),
                                            attributeDisabled: checkWordInArray(getURI, 'consultar'),
                                            attributeMask: '',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="Numero"
                                            style={formLabelStyle}
                                            className="form-label">Número
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Numero}
                                        </div>
                                    </div>
                                ) : (
                                    <AppText
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Número',
                                            labelColor: 'black',
                                            nameField: 'Numero',
                                            attributeMinlength: 2,
                                            attributeMaxlength: 10,
                                            attributePattern: 'Inteiro',
                                            attributeAutocomplete: 'on',
                                            attributeRequired: true,
                                            attributeReadOnly: checkWordInArray(getURI, 'consultar'),
                                            attributeDisabled: checkWordInArray(getURI, 'consultar'),
                                            attributeMask: '',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="Complemento"
                                            style={formLabelStyle}
                                            className="form-label"
                                        > Complemento
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Complemento}
                                        </div>
                                    </div>
                                ) : (
                                    <AppText
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Complemento',
                                            labelColor: 'black',
                                            nameField: 'Complemento',
                                            attributeMinlength: 4,
                                            attributeMaxlength: 100,
                                            attributePattern: 'Caracter, Inteiro',
                                            attributeAutocomplete: 'on',
                                            attributeRequired: true,
                                            attributeReadOnly: false, // isso já tá sendo controlado no ternário acima
                                            attributeDisabled: false,
                                            attributeMask: '',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="Municipio"
                                            style={formLabelStyle}
                                            className="form-label"
                                        > Município
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Municipio}
                                        </div>
                                    </div>
                                ) : (
                                    <AppText
                                        submitAllForms
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Município',
                                            labelColor: 'black',
                                            nameField: 'Municipio',
                                            attributeMinlength: 4,
                                            attributeMaxlength: 100,
                                            attributePattern: 'Caracter',
                                            attributeAutocomplete: 'on',
                                            attributeRequired: true,
                                            attributeReadOnly: false,
                                            attributeDisabled: false,
                                            attributeMask: '',
                                        }}
                                    />
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {(checkWordInArray(getURI, 'consultar')) ? (
                                    <div style={formGroupStyle}>
                                        <label htmlFor="unit"
                                            style={formLabelStyle}
                                            className="form-label">Unidade
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.NomeUnidade
                                                ? formData.NomeUnidade
                                                : <span className="text-muted">Não informado</span>}
                                        </div>
                                    </div>
                                ) : (
                                    <div style={formGroupStyle}>
                                        <label htmlFor="unit" style={formLabelStyle} className="form-label">
                                            Unidade<strong style={requiredField}>*</strong>
                                        </label>
                                        {(dataLoading && units) ? (
                                            <div className="p-2">
                                                <AppLoading parametros={{
                                                    tipoLoading: "progress",
                                                    carregando: dataLoading
                                                }} />
                                            </div>
                                        ) : (
                                            <select
                                                data-api={`filtro-${origemForm}`}
                                                id="unit"
                                                name="unit"
                                                value={formData.UnidadeId || ''}
                                                onFocus={handleFocus}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                style={formControlStyle}
                                                className="form-select form-select-sm"
                                                required
                                                aria-label="Default select 2"
                                            >
                                                <option value="">Seleção Nula</option>
                                                {units.map(unit_list => (
                                                    <option key={`${unit_list.id}`} value={unit_list.id}>
                                                        {unit_list.unidades_nome} - {unit_list.unidades_cep}
                                                    </option>
                                                ))}
                                            </select>
                                        )}
                                    </div>
                                )}
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {dataLoading ? (
                                    <div>
                                        <div>&nbsp;</div>
                                        <AppLoading parametros={{
                                            tipoLoading: "progress",
                                            carregando: dataLoading
                                        }} />
                                    </div>
                                ) : (
                                    (checkWordInArray(getURI, 'consultar')) ? (
                                        <div>
                                            <div style={formGroupStyle}>
                                                <label
                                                    htmlFor="GeneroIdentidadeId"
                                                    style={formLabelStyle}
                                                    className="form-label">Gênero
                                                    {(checkWordInArray(getURI, 'consultar')) ? null : (<strong style={requiredField}>*</strong>)}
                                                </label>
                                                <div className='p-2'>
                                                    {formData.GeneroIdentidadeId
                                                        ? formData.GeneroIdentidadeId.split(',').map(id => {
                                                            const genero = generoOptions.find(g => String(g.id) === id.trim());
                                                            return genero ? genero.genero : `ID ${id}`;
                                                        }).join(', ')
                                                        : <span className="text-muted">Não informado</span>}
                                                </div>
                                            </div>
                                        </div>
                                    ) : (
                                        <div>
                                            <AppSelectBtnCheck
                                                submitAllForms
                                                parametros={parametros}
                                                formData={formData}
                                                setFormData={setFormData}
                                                fieldAttributes={{
                                                    attributeOrigemForm: `${origemForm}`,
                                                    labelField: 'Gênero',
                                                    nameField: 'GeneroIdentidadeId',
                                                    errorMessage: '', // Mensagem de Erro personalizada
                                                    attributeRequired: true,
                                                    attributeDisabled: false,
                                                    attributeFieldKey: ['id', 'key'], // Chave do campo
                                                    attributeFieldName: ['genero', 'value'], // Nome do campo
                                                    objetoArrayKey: [
                                                        { key: '1', value: 'Opção 1' },
                                                        { key: '2', value: 'Opção 2' },
                                                        { key: '3', value: 'Opção 3' },
                                                        { key: '4', value: 'Opção 4' }
                                                    ],
                                                    api_get: `${api_get_genero}`,
                                                    api_post: `${api_get_genero}`,
                                                    api_filter: `${api_get_genero}`,
                                                }}
                                            />
                                        </div>
                                    )
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                {checkWordInArray(getURI, 'consultar') ? (
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="Etnia"
                                            style={formLabelStyle}>Etnia
                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                        </label>
                                        <div className="p-2">
                                            {formData.Etnia ? (
                                                <span>{formData.Etnia}</span>
                                            ) : (
                                                <span className="text-muted">Não informado</span>
                                            )}
                                        </div>
                                    </div>
                                ) : (
                                    <div style={formGroupStyle}>
                                        <label htmlFor="Etnia" style={formLabelStyle}>
                                            Etnia<strong style={requiredField}>*</strong>
                                        </label>
                                        <select
                                            data-api="form-adolescente"
                                            id="Etnia"
                                            name="Etnia"
                                            className="form-select form-select-sm"
                                            value={formData.Etnia || ''}
                                            onChange={handleChange}
                                            style={formControlStyle}
                                            required
                                            aria-label="Default select 0"
                                        >
                                            <option value="">Seleção Nula</option>
                                            {etniasNoBrasil.map(etinia_select => (
                                                <option key={etinia_select} value={etinia_select}>
                                                    {etinia_select}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                )}
                            </form>
                        </div>


                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`, formData);
                            }}>
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="SexoId"
                                        style={formLabelStyle}
                                        className="form-label">Sexo
                                        {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                    </label>
                                    {dataLoading ? (
                                        <div className="p-2">
                                            <AppLoading parametros={{
                                                tipoLoading: "progress",
                                                carregando: dataLoading
                                            }} />
                                        </div>
                                    ) : (
                                        <>
                                            {(checkWordInArray(getURI, 'consultar')) ? (
                                                <div className="p-2">
                                                    {listaSexos.find(item => item.id == formData.SexoId)?.sexo_biologico || (
                                                        <span className="text-muted">Não informado</span>
                                                    )}
                                                </div>
                                            ) : (
                                                <select
                                                    data-api={`filtro-${origemForm}`}
                                                    id="SexoId"
                                                    name="SexoId"
                                                    value={formData.SexoId || ''}
                                                    onChange={handleChange}
                                                    style={formControlStyle}
                                                    className="form-select form-select-sm"
                                                    required
                                                    aria-label="Default select 2"
                                                >
                                                    <option value="">Seleção Nula</option>
                                                    {listaSexos.map(sexo_select => (
                                                        <option key={sexo_select.id} value={sexo_select.id}>
                                                            {sexo_select.sexo_biologico}
                                                        </option>
                                                    ))}
                                                </select>
                                            )}
                                        </>
                                    )}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )
        }
        // console.log('Enviando para:', `${base_url}${parametros.api_post}`);

        {/* Styles */ }
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

        const dropdownStyle = {
            ...formGroupStyle, // Reaproveita o estilo base
            cursor: 'pointer',
        };

        return (
            <>
                {!isChoiceMade ? (
                    // Interface Inicial para escolha de cadastro
                    <div className="choice-container text-center p-5">
                        <h3>{checkWordInArray(getURI, 'cadastrar')
                            ? 'Como deseja realizar o cadastro?'
                            : checkWordInArray(getURI, 'consultar')
                                ? 'Como deseja consultar os dados?'
                                : checkWordInArray(getURI, 'atualizar')
                                    ? 'Como deseja atualizar os dados?'
                                    : 'Como deseja realizar o cadastro?'}
                        </h3>
                        <button
                            className="btn btn-primary m-3"
                            style={{ width: '200px' }}
                            onClick={() => {
                                handleChoice('cpf')
                            }}
                        >
                            Por CPF
                        </button>
                        <button
                            className="btn btn-secondary m-3"
                            style={{ width: '200px' }}
                            onClick={() => {
                                handleChoice('certidao')
                            }}
                        >
                            Certidão de Nascimento
                        </button>
                    </div>
                ) : (
                    // {/* Formulário de Dados Pessoais */}
                    <div className="ms-3 me-3">
                        <div>
                            <div className="b-3 p-3">
                                <form className="was-validated" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    <input type="hidden" id="acesso_id" name="acesso_id" value="2" />
                                    <input type="hidden" id="perfil_id" name="perfil_id" value="1" />
                                    {(atualizar_id !== 'erro') && (
                                        <input type="hidden" id="id" name="id" value={atualizar_id} />
                                    )}
                                </form>
                                <ul className="nav nav-tabs">
                                    <li className="nav-item">
                                        <a className="nav-link active" aria-current="page" >Dados Pessoais do Adolescente</a>
                                    </li>
                                </ul>
                                {(isChoiceMade === 'cpf') && (
                                    <div>
                                        {renderCPF()}
                                    </div>
                                )}
                                {(isChoiceMade === 'certidao') && (
                                    <div>
                                        {renderCertidao()}
                                    </div>
                                )}

                                {/* Fim do Formulário de Dados Pessoais */}

                                {/* Formulário de Escolaridade */}
                                <ul className="nav nav-tabs">
                                    <li className="nav-item">
                                        <a className="nav-link active" aria-current="page" >Dados Escolares</a>
                                    </li>
                                </ul>
                                <div className="border border-top-0 mb-4 p-4">
                                    <div className="row">
                                        <div className="col-12 col-sm-6">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}>
                                                {checkWordInArray(getURI, 'consultar') ? (
                                                    <div style={formGroupStyle}>
                                                        <label
                                                            htmlFor="TipoEscola"
                                                            style={formLabelStyle}
                                                            className="form-label">
                                                            Tipo de escola
                                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                                        </label>
                                                        <div className="p-2">
                                                            {formData.TipoEscola}
                                                        </div>
                                                    </div>
                                                ) : (
                                                    <div style={formGroupStyle}>
                                                        <label htmlFor="TipoEscola" style={formLabelStyle} className="form-label">
                                                            Tipo de escola<strong style={requiredField}>*</strong>
                                                        </label>
                                                        <select
                                                            data-api={`filtro-${origemForm}`}
                                                            id="TipoEscola"
                                                            name="TipoEscola"
                                                            value={formData.TipoEscola || ''}
                                                            onChange={handleChange}
                                                            style={formControlStyle}
                                                            className="form-select form-select-sm"
                                                            aria-label="Default select 6"
                                                            required
                                                            disabled={checkWordInArray(getURI, 'consultar')}
                                                        >
                                                            <option value="">Seleção Nula</option>
                                                            <option value="Privada">Privada (Bolsa 100%)</option>
                                                            <option value="Publica">Pública</option>
                                                        </select>
                                                    </div>
                                                )}
                                            </form>
                                        </div>
                                        <div className="col-12 col-sm-6">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}>
                                                {(onEscolaridade) ? (
                                                    <div style={formGroupStyle}>
                                                        <label
                                                            htmlFor="Escolaridade"
                                                            style={formLabelStyle}
                                                            className="form-label">Escolaridade
                                                            {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                                        </label>
                                                        {checkWordInArray(getURI, 'consultar') ? (
                                                            <div className="p-2">
                                                                {formData.Escolaridade}
                                                            </div>
                                                        ) : (
                                                            <select
                                                                data-api={`filtro-${origemForm}`}
                                                                id="Escolaridade"
                                                                name="Escolaridade"
                                                                value={formData.Escolaridade || ''}
                                                                onChange={handleChange}
                                                                style={formControlStyle}
                                                                className="form-select form-select-sm"
                                                                aria-label="Default select 5"
                                                                required
                                                            >
                                                                <option value="">Seleção Nula</option>
                                                                <option value={`6º Ano Ensino Fundamental`}>6º Ano Ensino Fundamental</option>
                                                                <option value={`7º Ano Ensino Fundamental`}>7º Ano Ensino Fundamental</option>
                                                                <option value={`8º Ano Ensino Fundamental`}>8º Ano Ensino Fundamental</option>
                                                                <option value={`9º Ano Ensino Fundamental`}>9º Ano Ensino Fundamental</option>
                                                                <option disabled>──────────</option>
                                                                <option value={`1º Ano do Ensino Médio`}>1º Ano do Ensino Médio</option>
                                                                <option value={`2º Ano do Ensino Médio`}>2º Ano do Ensino Médio</option>
                                                                <option value={`3º Ano do Ensino Médio`}>3º Ano do Ensino Médio</option>
                                                                <option disabled>──────────</option>
                                                                <option value={`EJA`}>EJA</option>
                                                                <option disabled>──────────</option>
                                                                <option value={`Outro`}>Outro:</option>
                                                            </select>
                                                        )}
                                                    </div>
                                                ) : (
                                                    <div style={formGroupStyle}>
                                                        <label
                                                            htmlFor="Escolaridade"
                                                            style={formLabelStyle}
                                                            className="form-label"
                                                        >
                                                            Especifique a Escolaridade<strong style={{ ...requiredField, cursor: 'pointer' }} onClick={() => setOnEscolaridade(true)}>*</strong>
                                                        </label>
                                                        <input
                                                            type="text"
                                                            id="Escolaridade"
                                                            name="Escolaridade"
                                                            value={formData.Escolaridade || ''}
                                                            onChange={handleChange}
                                                            style={formControlStyle}
                                                            className="form-control form-control-sm"
                                                            readOnly={false}
                                                            required
                                                        />
                                                    </div>
                                                )}
                                            </form>
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="col-12 col-sm-6">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}>
                                                {/*
                                                                */}
                                                <AppNomeEscola formData={formData} setFormData={setFormData} parametros={parametros} />
                                            </form>
                                        </div>
                                        <div className="col-12 col-sm-6">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}>
                                                <div style={formGroupStyle}>
                                                    <label
                                                        htmlFor="turno_escolar"
                                                        style={formLabelStyle}
                                                        className="form-label">Turno Escolar
                                                        {!checkWordInArray(getURI, 'consultar') ? (<strong style={requiredField}>*</strong>) : null}
                                                    </label>
                                                    {checkWordInArray(getURI, 'consultar') ? (
                                                        <div className="p-2">
                                                            {formData.turno_escolar}
                                                        </div>
                                                    ) : (
                                                        <select
                                                            data-api={`filtro-${origemForm}`}
                                                            id="turno_escolar"
                                                            name="turno_escolar"
                                                            value={formData.turno_escolar || ''}
                                                            onChange={handleChange}
                                                            style={formControlStyle}
                                                            className="form-select form-select-sm"
                                                            aria-label="Default select 5"
                                                            required
                                                        >
                                                            <option value="">Seleção Nula</option>
                                                            <option value={`Matutino`}>Matutino</option>
                                                            <option value={`Vespertino`}>Vespertino</option>
                                                            <option value={`Noturno`}>Noturno</option>
                                                            <option value={`Integral`}>Integral</option>
                                                        </select>
                                                    )}
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {/* Fim do Formulário de Escolaridade  */}

                                {/* Formulário Dados Responsável */}
                                <ul className="nav nav-tabs">
                                    <li className="nav-item">
                                        <a className="nav-link active" aria-current="page" >Dados Responsável</a>
                                    </li>
                                </ul>
                                <div className="border border-top-0 mb-4 p-4">
                                    <div className="row">
                                        <div className="col-12 col-sm-4">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}>
                                                {/*
                                                                */}
                                                <AppResponsavelNome formData={formData} setFormData={setFormData} parametros={parametros} />
                                            </form>
                                        </div>
                                        <div className="col-12 col-sm-4">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}>
                                                {/*
                                                                */}
                                                <AppResponsavelCPF formData={formData} setFormData={setFormData} parametros={parametros} />
                                            </form>
                                        </div>
                                        <div className="col-12 col-sm-4">
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`, formData);
                                            }}>
                                                {/*
                                                                */}
                                                <AppResponsavelTelefoneMovel formData={formData} setFormData={setFormData} parametros={parametros} />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {/* Fim do Formulário Dados Responsável */}
                            </div>
                        </div>
                    </div>
                )}

                {/* Fomulário Adolescente */}
                <form className="was-validated" onSubmit={(e) => {
                    e.preventDefault();
                    submitAllForms(`filtro-${origemForm}`, formData);
                }}>
                    {formData.id !== 'erro' && (
                        <div>
                            {/*
                                    */}
                            <input
                                data-api={`filtro-${origemForm}`}
                                type="hidden"
                                id="id"
                                name="id"
                                value={formData.id || ''}
                                onChange={handleChange}
                                required
                            />
                        </div>
                    )}
                    {/*
                            */}
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="token_csrf"
                        name="token_csrf"
                        value={formData.token_csrf || token_csrf}
                        onChange={handleChange}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="AcessoCadastroID"
                        name="AcessoCadastroID"
                        value={'2'}
                        onChange={handleChange}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="PerfilId"
                        name="PerfilId"
                        value={'1'}
                        onChange={handleChange}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="json"
                        name="json"
                        value={formData.json || json}
                        onChange={handleChange}
                        required
                    />
                </form>
                {isChoiceMade && (
                    <div>
                        {!checkWordInArray(getURI, 'consultar') && !checkWordInArray(getURI, 'atualizar') && (
                            <div className="mt-4">
                                <AppTermosUso
                                    parametros={parametros}
                                    formData={formData}
                                    setFormData={setFormData}
                                    setTermoAceito={setTermoAceito}
                                />
                            </div>
                        )}
                        <div className="row">
                            <div className="col-12 col-sm-12 m-4">
                                <div className="d-flex justify-content-right h-100ms-3 me-3">
                                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'drupal')|| checkWordInArray(getURI, 'cadastrar')) && (
                                        <form
                                            className="was-validated"
                                            onSubmit={(e) => {
                                                e.preventDefault();
                                                if (!termoAceito) return;
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}
                                        >
                                            <div className="d-flex gap-2">
                                                {!checkWordInArray(getURI, 'consultar') && (
                                                    <button
                                                        type="submit"
                                                        className="btn btn-primary me-2"
                                                        disabled={
                                                            !checkWordInArray(getURI, 'atualizar') && !termoAceito
                                                        }
                                                    >
                                                        Salvar
                                                    </button>
                                                )}
                                            </div>
                                        </form>
                                    )}
                                    {(checkWordInArray(getURI, 'atualizar')) && (
                                        <form
                                            className="was-validated"
                                            onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-${origemForm}`);
                                            }}
                                        >
                                            <button
                                                type="submit"
                                                className="btn btn-primary me-2"
                                            >
                                                Atualizar
                                            </button>
                                        </form>
                                    )}
                                    <button
                                        type="button"
                                        className="btn btn-secondary me-2"
                                        onClick={() => setIsChoiceMade(false)}
                                    >
                                        Voltar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Exibe o componente de alerta */}
                {(typeof AppMessageCard !== "undefined" && message !== null) ? (
                    <div>
                        {/* Renderização segura dos períodos, se necessário */}
                        {Array.isArray(periodos) && periodos.length > 0 && (
                            <small className="d-block mb-2">Períodos disponíveis: {periodos.length}</small>
                        )}
                        <AppMessageCard
                            parametros={message}
                            modalId="modal_form"
                        />
                    </div>
                ) : (
                    <div>
                        <p className="text-danger">AppMessageCard não lacançado.</p>
                    </div>
                )}

                {typeof AppJson === "undefined1" ? (
                    <div>

                        <AppJson
                            parametros={parametros}

                        />

                    </div>
                ) : (
                    <div>
                        {/* <p className="text-danger">AppJson não lacançado.</p> */}
                    </div>
                )}

            </>
        );
    };
</script>