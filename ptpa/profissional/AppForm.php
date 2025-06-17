<script type="text/babel">
    const AppForm = (
        {
            parametros = {}
        }
    ) => {

        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const base_url = parametros.base_url;
        const token_csrf = parametros.token_csrf || 'erro';
        const json = '1';
        const origemForm = parametros.origemForm || '';
        const title = parametros.title || '';
        // const atualizar_id = checkWordInArray(getURI, 'atualizar') ? parametros.atualizar_id : 'erro';
        const atualizar_id = parametros.atualizar_id;

        //Base APIs
        // const api_post_atualizar_profissional = parametros.api_post_atualizar_profissional || '';
        const api_post_cadastrar_profissional = parametros.api_post_cadastrar_profissional || '';
        const api_get_atualizar_profissional = parametros.api_get_atualizar_profissional || '';
        const api_get_atualizar_unidade = parametros.api_get_atualizar_unidade || '';
        const api_post_filter_unidade = parametros.api_post_filter_unidade || '';
        const api_get_profissao = parametros.api_get_profissao || '';
        const api_get_programa = parametros.api_get_programa || '';
        const api_get_unidade = parametros.api_get_unidade || '';
        const api_get_perfil = parametros.api_get_perfil || '';
        const api_get_cargo = parametros.api_get_cargo || '';

        // Variáveis da API
        // const [profissoes, setProfissoes] = React.useState([]);
        const [perfis, setPerfis] = React.useState([]);
        const [cargos, setCargos] = React.useState([]);
        const [unidades, setUnidades] = React.useState([]);
        const [programas, setProgramas] = React.useState([]);

        // Variáveis Uteis
        // const [pagination, setPagination] = React.useState(null);
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);

        // Função para atualizar os estados ao redimensionar a tela
        const [tamanhoTela, setTamanhoTela] = React.useState({
            largura: window.innerWidth,
            altura: window.innerHeight
        });

        // Gera nome Completo 
        const Nome = ["João", "Pedro", "Lucas", "Gabriel", "Matheus", "Leonardo", "Gustavo", "Rafael", "Daniel", "Thiago", "Bruno", "André", "Felipe", "Eduardo", "Ricardo", "Rodrigo", "Alexandre", "Fernando", "Vinícius", "Marcelo", "Antônio", "Carlos", "José", "Miguel", "Davi", "Maria", "Ana", "Juliana", "Camila", "Mariana", "Beatriz", "Fernanda", "Larissa", "Vanessa", "Patrícia", "Gabriela", "Amanda", "Letícia", "Rafaela", "Bruna", "Isabel", "Carolina", "Natália", "Jéssica", "Bianca", "Luana", "Tatiane", "Daniela", "Adriana", "Sabrina"];
        const Nome_Meio = ["Smith", "Johnson", "Williams", "Brown", "Jones", "Miller", "Davis", "Garcia", "Rodriguez", "Wilson", "Martinez", "Anderson", "Taylor", "Thomas", "Hernandez", "Moore", "Martin", "Jackson", "Thompson", "White", "Lopez", "Lee", "Gonzalez", "Harris", "Clark", "Lewis", "Robinson", "Walker", "Perez", "Hall", "Young", "Allen", "Sanchez", "Wright", "King", "Scott", "Green"];
        const SobreNome = ["Bauer", "Becker", "Braun", "Busch", "Dietrich", "Engel", "Faber", "Fischer", "Frank", "Frey", "Friedrich", "Fuchs", "Geiger", "Graf", "Groß", "Günther", "Haas", "Hartmann", "Heinrich", "Hermann", "Hoffmann", "Holz", "Huber", "Jäger", "Keller", "König", "Krause", "Krüger", "Kuhn", "Lang", "Lehmann", "Lenz", "Lorenz", "Maier", "Menzel"];

        // Função para gerar um índice aleatório
        function gerarIndice(arr = []) {
            return Math.floor(Math.random() * arr.length);
        }

        const nomeCompleto = Nome[gerarIndice(Nome)] + " " +
            Nome_Meio[gerarIndice(Nome_Meio)] + " " +
            SobreNome[gerarIndice(SobreNome)];

        // Gera Endereço
        const End1 = ["Avenida", "Rua", "Beco", "Viela", "Travessa", "Alameda", "Praça", "Largo", "Rodovia", "Estrada", "Caminho", "Passagem", "Viaduto", "Túnel", "Morro", "Ponte", "Calçada", "Escadaria", "Jardim", "Rampa", "Pátio", "Cais", "Parque", "Quarteirão", "Zona", "Distrito", "Vão"];
        const End2 = ["Comendador", "Doutor", "Mestre", "Barão", "Visconde", "Marquês", "Duque/Duquesa", "Príncipe/Princesa", "Rei", "Professor", "Engenheiro", "Doutor", "Arquiteto", "Padre", "Bispo", "Arcebispo", "Cardeal", "Rabbi", "Prefeito", "Governador", "Presidente", "Vice-Presidente", "Ministro", "Senador", "Juiz", "Promotor", "Defensor", "Procurador", "Embaixador", "Cônsul", "Reitor", "Comandante", "General", "Coronel", "Diretor"];
        const EndNome = Nome[gerarIndice(Nome)];
        const EndNome_Meio = SobreNome[gerarIndice(Nome_Meio)];
        const EndBairro3 = ["Copacabana", "Ipanema", "Barra da Tijuca", "Botafogo", "Leblon", "Lapa", "Flamengo", "Glória", "Santa Teresa", "Jardim Botânico", "Méier", "Tijuca", "São Conrado", "Campo Grande", "Bangu", "Madureira", "Ilha do Governador", "Jacarepaguá", "Recreio dos Bandeirantes", "Barra de Guaratiba", "Lagoa", "Centro", "Catete", "Laranjeiras", "Andaraí", "Rio Comprido", "Jacaré", "Cidade Nova", "Vila Isabel", "Rio de Janeiro (bairro central)", "Caju", "Cidade de Deus", "Cosme Velho", "São Cristóvão", "Maracanã", "Engenho de Dentro", "Vila Valqueire", "Grajaú", "Bonsucesso", "Ramos", "Praça da Bandeira", "Ilha do Fundão", "Penha", "Brás de Pina", "Jardim Carioca", "Sampaio", "Freguesia (Jacarepaguá)", "Campinho", "Barra da Tijuca (Centro)", "Barra da Tijuca (Zona Oeste)", "Padre Miguel", "Anil", "Botafogo", "Cosme Velho", "Cidade Nova", "Praça Seca", "Vargem Grande", "Vargem Pequena", "Jardim Guanabara", "Lins de Vasconcelos", "Barra de Guaratiba", "Mangueira", "Catumbi", "Benfica", "Jardim América", "Engenho Novo", "Engenho de Dentro", "Cordovil", "Ilha do Governador", "Ilha de Paquetá", "Penha Circular", "Duque de Caxias", "Cavalcanti", "Realengo", "Uruguai", "Roque", "Encantado", "Sargento Roncalli", "Campo Grande", "Itanhangá", "Oswaldo Cruz", "Cosme Velho", "Barra da Tijuca", "Freguesia", "Santa Cruz", "Guaratiba", "Madureira", "Jardim da Saúde", "Taquara", "Vila Isabel", "Colégio", "Morro do Amorim", "Barra do Piraí", "São Cristóvão", "Recreio dos Bandeirantes", "Nova Iguaçu", "Cidade Nova", "Laje do Muriaé", "Ramos", "São João de Meriti", "Icaraí", "Centro", "São Francisco", "Charitas", "Fonseca", "Santa Rosa", "Engenhoca", "Jardim Icaraí", "Vital Brasil", "Barreto", "Boa Viagem", "Ingá", "Caminho Niemeyer", "Gragoatá", "Barreto", "Ponta D'Areia", "São Domingos", "Rio do Ouro", "Ititioca", "Cubango", "Jardim Paraíso", "São Lourenço", "Boa Vista", "Jardim Beliche", "Itaipu", "Jurujuba", "Ilha das Caieiras", "Cachoeira", "Morro do Céu", "Maceió", "Ladeira", "Canto do Rio", "Brisa Mar", "Sapê", "Porto da Pedra", "Vila Progresso", "Rua da República", "Tenente Jardim", "Santa Teresa", "Largo da Batalha", "São José", "Nossa Senhora das Graças", "Vila Militar", "Jardim Icaraí", "Arariboia", "São João Batista", "Baldeador", "São Gonçalo", "Águas Lindas", "Vila Progresso", "Morro do Estado", "Vila Rica", "Jardim das Flores", "Rio do Ouro", "Alameda", "São Lourenço", "Sete Cidades", "Itaipu", "Vila da Paz", "Maravista", "Parque da Cidade", "Barreto", "Uruguai", "Belém", "Boa Vista", "Maruí", "Boa Viagem", "Vitória", "Vila Jardim", "Barracão", "Olaria", "São Gonçalo", "Sol e Mar", "Peixe Galo", "Engenhoca", "Grajaú", "Meia Légua", "Batelão", "Morro do Céu", "Ilha do Governador", "Bairro Morumbi", "Vila Caetano", "Bairro Nova Cidade", "Espinheiros", "Praia das Flechas", "Loteamento Solares", "Almedina", "Andorinhas", "Verbo Divino", "Niterói Shopping", "Parque Regente", "Pedro do Rio", "Subindo", "Morro do Paraguai", "Porto Real", "Mata da Glória", "Boa Vista Nova", "Jardim Tropical", "Saco do Mamanguá", "Ponta da Areia", "Aterrado", "Barra Mansa", "Belmonte", "Bom Retiro", "Cidade do Aço", "Conforto", "Cruzeiro", "Jardim Amália", "Jardim Belmonte", "Jardim Paraíba", "Jardim Primavera", "Jardim Progresso", "Jardim Vitoria", "Limoeiro", "Monte Castelo", "Niterói", "Padre Josimo", "Parque Floresta", "Parque Maíra", "Parque Randolfe", "Ponte Alta", "Retiro", "Santa Cruz", "Santo Agostinho", "Santo Antônio", "São João", "São Judas Tadeu", "São Luiz", "Vila Rica", "Vila Santa Catarina", "Vila Santa Isabel", "Vila Tavares", "Vila Verde", "Volta Grande", "Vila Velha", "Alvorada", "Angélica", "Aparecida", "Bonsucesso", "Chácara Dona Clara", "Colônia Santo Antônio", "Comary", "Dom Bosco", "Exposição", "Fazendinha", "Floresta", "Freitas", "Gama", "Jardim Alegria", "Jardim Nogueira", "Jardim Ouro Verde", "Jardim Sul", "São Gabriel", "São José", "São Sebastião", "Ponte Alta", "Retiro", "Recanto Verde", "Raul Veiga", "Redentor", "Pedreira", "Parque São Luiz", "Parque Sul", "Parque Ipanema", "Parque Morumbi", "Parque Nossa Senhora das Graças", "Palácio", "Panorama", "Ponto Chic", "Quarteirão da Cultura", "Quilômetro 14", "Realengo", "São Caetano", "São Cristóvão", "São Francisco", "São Paulo", "São Vicente", "Sitio do Pica Pau", "Sitio da Pedra", "Sitio Laje", "Sitio dos Palmares", "Alvorada", "Jardim Calábria", "Jardim Imperador", "Jardim Mandira", "Vila Real", "Vila São José", "Vila São Vicente", "Vila São Sebastião", "Vila Feliz", "Loteamento Progresso", "Loteamento Bandeirantes", "Loteamento Pedreira", "Loteamento Palmeira", "Praça Brasil", "Estádio da Cidadania", "Bairro Santa Maria", "Bairro São Luiz", "Bairro Ouro Verde", "Bairro Novo Horizonte"];

        const EndCEP = (min, max) => {
            return `${Math.floor(Math.random() * (max - min + 1)) + min}`.replace(/(\d{5})(\d{3})/, '$1-$2');
        };

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

        // Gerar nome completo
        const enderecoCompleto = Nome[gerarIndice(End1)] + " " +
            Nome_Meio[gerarIndice(End2)] + " " +
            Nome_Meio[gerarIndice(EndNome)] + " " +
            Nome_Meio[gerarIndice(EndNome_Meio)] + " " +
            SobreNome[gerarIndice(EndBairro3)];

        // Gera celular
        const randomCelular = () => {
            // Gera DDD aleatório entre 21, 22 e 23
            const ddds = [21, 22, 23];
            const ddd = ddds[Math.floor(Math.random() * ddds.length)];

            // Gera os 8 dígitos do número
            const firstPart = Math.floor(Math.random() * 9000) + 1000; // 4 dígitos entre 1000-9999
            const secondPart = Math.floor(Math.random() * 9000) + 1000; // 4 dígitos entre 1000-9999

            // Formata o número no padrão (DDD) 9XXXX-XXXX
            return `(${ddd})9${firstPart}-${secondPart}`;
        };

        // Gera telefone fixo
        const randomTelFixo = () => {
            // Gera DDD aleatório entre 21, 22 e 23
            const ddds = [21, 22, 23];
            const ddd = ddds[Math.floor(Math.random() * ddds.length)];

            // Gera os 8 dígitos do número
            const firstPart = Math.floor(Math.random() * 9000) + 1000; // 4 dígitos entre 1000-9999
            const secondPart = Math.floor(Math.random() * 9000) + 1000; // 4 dígitos entre 1000-9999

            // Formata o número no padrão (DDD) XXXX-XXXX
            return `(${ddd}) ${firstPart}-${secondPart}`;
        };

        const handleNewPhone = () => {
            setPhoneNumber(generatePhone());
        };

        const today = new Date().toISOString().split('T')[0];

        // Gera CPF
        const randomCPF = Array.from({ length: 11 }, () =>
            Math.floor(Math.random() * 10)
        ).join('');

        // Função para gerar PerfilId (3 a 6)
        const randomPerfilId = () => {
            const num1 = Math.floor(Math.random() * (6 - 3 + 1)) + 3;
            return num1;
        };

        // Função para gerar CargoFuncaoId (1 a 6)
        const randomCargoFuncaoId = () => {
            const num2 = Math.floor(Math.random() * 6) + 1;
            return num2;
        };

        const gerarDataPassada = () => {
            const dias = Math.floor(Math.random() * 360) + 1; // Entre 1 e 360 dias
            const dataAtual = new Date();
            dataAtual.setDate(dataAtual.getDate() - dias); // Subtrai os dias
            return dataAtual.toISOString().split('T')[0]; // Retorna a data no formato AAAA-MM-DD
        };

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            //
            token_csrf: token_csrf,
            json: '1',
            alocar: checkWordInArray(getURI, 'alocarfuncionario') ? true : false,
            //
            id: null,
            PeriodoId: null,
            CadastroId: null,
            PerfilId: debugMyPrint == true ? `${randomPerfilId()}` : null,
            PerfilDescricao: null,
            CargoFuncaoId: debugMyPrint == true ? `${randomCargoFuncaoId()}` : null,
            CargoFuncao: null,
            ProgramaId: (atualizar_id === 'erro' || debugMyPrint === true) ? 1 : null,
            ProgramaSigla: null,
            AcessoCadastroID: null,
            UnidadeId: null,
            Unidade: null,
            NomeUnidade: null,
            AcessoId: '1',
            AcessoDescricao: null,
            ProntuarioId: null,
            Nome: debugMyPrint === true ? `${nomeCompleto}` : null,
            CPF: debugMyPrint === true ? `${randomCPF}` : null,
            TelefoneFixo: debugMyPrint === true ? `${randomTelFixo()}` : null,
            TelefoneMovel: debugMyPrint === true ? `${randomCelular()}` : null,
            TelefoneRecado: debugMyPrint === true ? `${randomCelular()}` : null,
            Email: debugMyPrint === true ? `${randomEmail()}` : null,
            DataCadastramento: null,
            DataTermUnid: null,
            DataInicioUnid: debugMyPrint === true ? today : null,
            CodProfissao: null,
            AcessoCreatedAt: null,
            AcessoUpdatedAt: null,
            ProfissaoCodigo: null,
            ProfissaoDescricao: null,
            ProfissaoFavorito: null,
            ProfissaoCreatedAt: null,
            ProfissaoUpdatedAt: null,
            ProfissaoDeletedAt: null,
            //
            DataAdmissao: debugMyPrint === true ? `${gerarDataPassada()}` : null,
            DataDemissao: null
        });

        // console.log('formData :: ', formData);

        const atualizarTamanhoTela = () => {
            setTamanhoTela({
                largura: window.innerWidth,
                altura: window.innerHeight
            });
        };

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

        // Estado para mensagens e validação
        const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [avisoCampo, setAvisoCampo] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        // Função handleFocus para garantir que o modal não seja exibido ao receber o foco
        const handleFocus = (event) => {
            const { name, value } = event.target;

            setMessage({ show: false, type: null, message: null });

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        // Função handleChange simplificada
        const handleChange = (event) => {
            const { name, value } = event.target;

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

        const handleBlur = (event) => {
            const { name, value } = event.target;

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

        const submitAllForms = async (filtro) => {
            // console.log('submitAllForms...');
            const setData = formData;
            let data = '';
            let dbResponse = [];
            let response = '';
            console.log('Dados a serem enviados:', setData);

            // Mapeamento dos campos com nomes amigáveis
            const camposObrigatorios = {
                Nome: 'Nome do funcionário',
                CPF: 'CPF do funcionário',
                Email: 'E-mail do funcionário',
                TelefoneRecado: 'O telefone do funcionário',
                ProgramaId: 'Programas',
                PerfilId: 'Perfil',
                CargoFuncaoId: 'Cargo do funcionário',
                DataAdmissao: 'Data de admissão',
                UnidadeId: 'Unidade',
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
                return;
            }

            if (filtro === `filtro-${origemForm}`) {
                // Convertendo os dados do setPost em JSON
                // console.log(`filtro-${origemForm}`);
                // console.log(`${base_url}${api_post_cadastrar_profissional}`);

                response = await fetch(`${base_url}${api_post_cadastrar_profissional}`, {
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

                // Processa os dados recebidos da resposta
                // console.log('submitAllForms Funcionário: ', data);

                if (
                    data.status &&
                    data.status === 'success' &&
                    data.result &&
                    data.result.affectedRows &&
                    data.result.affectedRows > 0
                ) {
                    dbResponse = data.result.dbResponse;
                    // Função para exibir o alerta (success, danger, warning, info)
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `${resposta} realizada com sucesso`
                    });

                    if (checkWordInArray(getURI, 'profissional')) {
                        redirectTo('index.php/fia/ptpa/profissional/endpoint/exibir');
                    } else if (checkWordInArray(getURI, 'alocarfuncionario')) {
                        redirectTo('index.php/fia/ptpa/alocarfuncionario/endpoint/exibir');
                    } else {
                        redirectTo('index.php/fia/ptpa/profissional/endpoint/exibir');
                    }

                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `Não foi possivel realizar o ${resposta}`,
                    });
                }
            }
        };

        // UseEffect para adicionar e remover o listener de redimensionamento
        React.useEffect(() => {
            window.addEventListener('resize', atualizarTamanhoTela);

            // Limpa o listener quando o componente for desmontado
            return () => {
                window.removeEventListener('resize', atualizarTamanhoTela);
            };
        }, []);

        // React.useEffect
        React.useEffect(() => {

            // Função para carregar todos os dados necessários
            const loadData = async () => {

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchProfissionais();
                    await fetchPerfis();
                    await fetchCargos();
                    await fetchProgramas();
                    await fetchUnidades();
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setIsLoading(false);
                    setFormData((formData) => ({
                        ...formData,
                        ...(checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'cadastrar') ? { UnidadeId: atualizar_id.replace('/', '') } : {})
                    }));
                }
            };

            loadData();
        }, []);

        // Fetch para obter os Profiussionais
        const fetchProfissionais = async () => {
            // console.log('fetchProfissionais ...');
            // console.log('src/app/Views/fia/ptpa/profissional/AppForm.php');
            try {
                if (checkWordInArray(getURI, 'cadastrar')) {
                    setFormData((prev) => ({
                        ...prev,
                    }));
                    return false;
                }
                const response = await fetch(base_url + api_get_atualizar_profissional);
                const data = await response.json();
                // console.log('Profissionais:: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {

                    setFormData((prev) => ({
                        ...prev,
                        ...data.result.dbResponse[0]
                    }));
                }
            } catch (error) {
                console.error('Erro ao carregar Profissionais: ' + error.message);
            }
        };

        // Fetch para obter os Programas
        const fetchProgramas = async () => {
            try {
                const response = await fetch(base_url + api_get_programa, {
                    method: 'POST', // Define o método como POST
                    headers: {
                        'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
                    },
                    body: JSON.stringify({}) // Corpo da requisição vazio
                });
                const data = await response.json();
                // console.log('Programas:: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse[0]) {
                    setProgramas(data.result.dbResponse);
                    setDataLoading(false);
                }
            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Programa: ' + error.message
                });
                // setError('Erro ao carregar Programa: ' + error.message);
            }
        };

        // Fetch para obter os Perfis
        const fetchPerfis = async () => {
            try {
                const response = await fetch(base_url + api_get_perfil, {
                    method: 'POST', // Define o método como POST
                    headers: {
                        'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
                    },
                    body: JSON.stringify({}) // Corpo da requisição vazio
                });
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Perfil: ', data);
                    setPerfis(data.result.dbResponse);
                    setDataLoading(false);
                }
            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Perfil: ' + error.message
                });
                // setError('Erro ao carregar Perfis: ' + error.message);
            }
        };

        // Fetch para obter os Cargos
        const fetchCargos = async () => {
            try {
                const response = await fetch(base_url + api_get_cargo, {
                    method: 'POST', // Define o método como POST
                    headers: {
                        'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
                    },
                    body: JSON.stringify({}) // Corpo da requisição vazio
                });
                const data = await response.json();
                // console.log('Cargos:: ', data);
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    setCargos(data.result.dbResponse);
                    setDataLoading(false);
                }
            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Cargo: ' + error.message
                });
                // setError('Erro ao carregar Cargos: ' + error.message);
            }
        };

        // Fetch para obter os Unidade
        const fetchUnidades = async () => {
            let url = base_url + api_get_unidade + '?limit=90000';
            try {
                const response = await fetch(url, {
                    method: 'POST', // Define o método como POST
                    headers: {
                        'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
                    },
                    body: JSON.stringify({}) // Corpo da requisição vazio
                });
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('Unidades :: ', data);
                    setUnidades(data.result.dbResponse);
                    setDataLoading(false);
                }

            } catch (error) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Erro ao carregar Unidade: ' + error.message
                });
                // setError('Erro ao carregar Unidades: ' + error.message);
            }
        };

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        const extrairNumero = (path) => {
            //console.log('path :: ', path);
            // return path.replace('/', '');
        };

        const renderCampoUnidade = () => {
            return (
                <div>
                    <form className="needs-validation" onSubmit={(e) => {
                        e.preventDefault();
                        submitAllForms(`filtro-${origemForm}`, formData);
                    }}>
                        {dataLoading ? (
                            <div style={formGroupStyle}>
                                <label
                                    htmlFor="ProgramaId"
                                    style={formLabelStyle}
                                    className="form-label">Unidade
                                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                                </label>
                                <div className='p-2'>
                                    <AppLoading parametros={{
                                        tipoLoading: "progress",
                                        carregando: dataLoading
                                    }} />
                                </div>
                            </div>
                        ) : (
                            (checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                                <div>
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="ProgramaId"
                                            style={formLabelStyle}
                                            className="form-label">Unidade
                                            {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                                        </label>
                                        <div className='p-2'>
                                            {formData.NomeUnidade}
                                        </div>
                                    </div>
                                </div>
                            ) : (
                                <div>
                                    <AppSelect
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Unidade',
                                            nameField: 'UnidadeId',
                                            errorMessage: '', // Mensagem de Erro personalizada
                                            attributeFieldKey: ['id', 'key'], // Chave do campo
                                            attributeFieldName: ['unidades_nome', 'value'], // Nome do campo
                                            attributeRequired: true,
                                            attributeDisabled: false,
                                            objetoArrayKey: [
                                                { key: '1', value: 'Opção 1' },
                                                { key: '2', value: 'Opção 2' },
                                                { key: '3', value: 'Opção 3' },
                                                { key: '4', value: 'Opção 4' }
                                            ],
                                            api_get: `${api_post_filter_unidade}`,
                                            api_post: `${api_post_filter_unidade}`,
                                            api_filter: `${api_post_filter_unidade}`
                                        }}
                                    />
                                </div>
                            )
                        )}
                    </form>
                </div>
            );
        };

        if (debugMyPrint && isLoading) {
            return <div className="d-flex justify-content-center align-items-center min-vh-100">
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        }

        if (debugMyPrint && error) {
            return <div className="d-flex justify-content-center align-items-center min-vh-100">
                <div className="alert alert-danger" role="alert">
                    {error}
                </div>
            </div>
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

        return (
            <div className="ms-3 me-3">

                {/* Formulário de Profissional */}
                <div className="card mb-2">
                    <div className="card-body">
                        <div className="row">
                            <div className="col-12 col-sm-4">
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
                                    <input
                                        data-api={`filtro-${origemForm}`}
                                        type="hidden"
                                        id="AcessoCadastroID"
                                        name="AcessoCadastroID"
                                        value={formData.AcessoCadastroID || ''}
                                        onChange={handleChange}
                                        required
                                    />
                                    {/* ACESSO ID */}
                                    <input
                                        data-api={`filtro-${origemForm}`}
                                        type="hidden"
                                        id="acesso_id"
                                        name="acesso_id"
                                        value={formData.acesso_id || '1'}
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

                                {/* Nome / CPF / E-mail*/}
                                <form className="needs-validation" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    <AppNome formData={formData} setFormData={setFormData} parametros={parametros} />
                                </form>
                            </div>
                            <div className="col-12 col-sm-4">
                                <form className="needs-validation" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    {/*
                                    <AppCpf formData={formData} setFormData={setFormData} parametros={parametros} />
                                    */}
                                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                                        <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                            fieldAttributes={{
                                                attributeOrigemForm: `${origemForm}`,
                                                labelField: 'CPF',
                                                labelColor: 'black', // gray, red, black,
                                                nameField: 'CPF',
                                                errorMessage: '', // Mensagem de Erro personalizada
                                                attributePlaceholder: '', // placeholder 
                                                attributeMinlength: 4, // minlength 
                                                attributeMaxlength: 14, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                attributeAutocomplete: 'off', // on, off ]
                                                attributeRequired: false,
                                                attributeDisabled:
                                                    checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar') ||
                                                    checkWordInArray(getURI, 'consultar') ||
                                                    checkWordInArray(getURI, 'consultarfunc'), // cobre os dois contextos
                                                attributeReadOnly: checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar'),
                                                attributeMask: 'CPF', // CPF, Telefone, CEP, , SEI, Processo.
                                            }}
                                        />
                                    ) : (
                                        <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                            fieldAttributes={{
                                                attributeOrigemForm: `${origemForm}`,
                                                labelField: 'CPF',
                                                labelColor: 'black', // gray, red, black,
                                                nameField: 'CPF',
                                                errorMessage: '', // Mensagem de Erro personalizada
                                                attributePlaceholder: '', // placeholder 
                                                attributeMinlength: 4, // minlength 
                                                attributeMaxlength: 14, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                                attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                                attributeAutocomplete: 'on', // on, off ]
                                                attributeRequired: true,
                                                attributeDisabled:
                                                    checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar') ||
                                                    checkWordInArray(getURI, 'consultar') ||
                                                    checkWordInArray(getURI, 'consultarfunc'), // cobre os dois contextos
                                                attributeReadOnly: checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar'),
                                                attributeMask: 'CPF', // CPF, Telefone, CEP, , SEI, Processo.
                                            }}
                                        />
                                    )}
                                </form>
                            </div>
                            <div className="col-12 col-sm-4">
                                <form className="needs-validation" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    <AppEmail formData={formData} setFormData={setFormData} parametros={parametros} />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Telefone / Programas / Perfil */}
                <div className="card mb-2">
                    <div className="card-body">
                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <form className="needs-validation" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    <AppTelefoneRecado formData={formData} setFormData={setFormData} parametros={parametros} />
                                </form>
                            </div>
                            <div className="col-12 col-sm-4">
                                <form className="needs-validation" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="ProgramaId"
                                            style={formLabelStyle}
                                            className="form-label">Programas
                                            {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                                        </label>
                                        {dataLoading ? (
                                            <div className="p-2">
                                                <AppLoading parametros={{
                                                    tipoLoading: "progress",
                                                    carregando: dataLoading
                                                }} />
                                            </div>
                                        ) : (
                                            (checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (

                                                <div className='p-2'>
                                                    {formData.ProgramaSigla}
                                                </div>
                                            ) : (
                                                <select data-api={`filtro-${origemForm}`}
                                                    id="ProgramaId"
                                                    name="ProgramaId"
                                                    value={formData.ProgramaId || ''}
                                                    className="form-select form-select-sm"
                                                    onFocus={handleFocus}
                                                    onChange={handleChange}
                                                    onBlur={handleBlur}
                                                    style={formControlStyle}
                                                    aria-label="Default select"
                                                    disabled={(checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')) ? true : false}
                                                    required
                                                >
                                                    {
                                                        <option value="">Seleção Nula</option>
                                                    }
                                                    {programas.map(programa_select => (
                                                        <option key={programa_select.id} value={programa_select.id}>
                                                            {programa_select.Sigla}
                                                        </option>
                                                    ))}
                                                </select>
                                            )
                                        )}
                                    </div>
                                </form>
                            </div>

                            <div className="col-12 col-sm-4">
                                <form className="needs-validation" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="PerfilId"
                                            style={formLabelStyle}
                                            className="form-label">Perfil
                                            {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                                        </label>
                                        {dataLoading ? (
                                            <div className="p-2">
                                                <AppLoading parametros={{
                                                    tipoLoading: "progress"
                                                }} />
                                            </div>
                                        ) : (
                                            (checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (

                                                <div className='p-2'>
                                                    {formData.PerfilDescricao}
                                                </div>
                                            ) : (
                                                <select data-api={`filtro-${origemForm}`}
                                                    id="PerfilId"
                                                    name="PerfilId"
                                                    value={formData.PerfilId || ''}
                                                    className="form-select form-select-sm"
                                                    onFocus={handleFocus}
                                                    onChange={handleChange}
                                                    onBlur={handleBlur}
                                                    style={formControlStyle}
                                                    aria-label="Default select"
                                                    disabled={(checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar')) ? true : false}
                                                    required
                                                >
                                                    {
                                                        <option value="">Seleção Nula</option>
                                                    }
                                                    {perfis.map(perfil_select => (
                                                        <option key={perfil_select.id} value={perfil_select.id}>
                                                            {perfil_select.perfil}
                                                        </option>
                                                    ))}
                                                </select>
                                            )
                                        )}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Cargo / Admissão / Demissão / Unidade */}
                <div className="card mb-4">
                    <div className="card-body">
                        <div className="row">
                            {/* Cargo */}
                            <div className="col-12 col-sm-4">
                                <form className="needs-validation" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    <div style={formGroupStyle}>
                                        <label
                                            htmlFor="CargoFuncaoId"
                                            style={formLabelStyle}
                                            className="form-label">Cargo
                                            {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                                        </label>
                                        {dataLoading ? (
                                            <div className="p-2">
                                                <AppLoading parametros={{
                                                    tipoLoading: "progress",
                                                    carregando: dataLoading
                                                }} />
                                            </div>
                                        ) : (
                                            (checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                                                <div className='p-2'>
                                                    {formData.CargoFuncao}
                                                </div>
                                            ) : (
                                                <select data-api={`filtro-${origemForm}`}
                                                    id="CargoFuncaoId"
                                                    name="CargoFuncaoId"
                                                    value={formData.CargoFuncaoId || ''}
                                                    className="form-select form-select-sm"
                                                    onChange={handleChange}
                                                    style={formControlStyle}
                                                    aria-label="Default select"
                                                    required
                                                >
                                                    <option value="">Seleção Nula</option>
                                                    {cargos.map(cargos_select => (
                                                        <option key={cargos_select.id} value={cargos_select.id}>
                                                            {cargos_select.cargo_funcao}
                                                        </option>
                                                    ))}
                                                </select>
                                            )
                                        )}
                                    </div>
                                </form>
                            </div>

                            {/* Admissão */}
                            <div className="col-12 col-sm-4">
                                <form className="needs-validation" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}>
                                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                                        <div style={formGroupStyle}>
                                            <div>
                                                <label
                                                    htmlFor="DataDemissao"
                                                    style={formLabelStyle}
                                                    className="form-label">Admissão
                                                    {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                                                </label>
                                                {dataLoading ? (
                                                    <div className="p-2">
                                                        <AppLoading parametros={{
                                                            tipoLoading: "progress",
                                                            carregando: dataLoading
                                                        }} />
                                                    </div>
                                                ) : (
                                                    <div>
                                                        <AppDataPtBr parametros={formData.DataAdmissao} />
                                                    </div>
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
                                                labelField: 'Admissão',
                                                nameField: 'DataAdmissao',
                                                attributeMax: 'Profissional', // maxDate - Profissional, Periodo.0 
                                                attributeRequired: true,
                                                attributeReadOnly: checkWordInArray(getURI, 'alocarfuncionario') && checkWordInArray(getURI, 'atualizar') ? true : false,
                                                attributeDisabled: false,
                                                attributeMask: 'Profissional', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                            }} />
                                    )}
                                    {showEmptyMessage && (
                                        <span style={{ color: 'red', fontSize: '12px' }}>
                                            {avisoCampo || ''}
                                        </span>
                                    )}
                                </form>
                            </div>
                            <div className="col-12 col-sm-4">
                                {/* Data de Demissão */}
                                {
                                    (
                                        (
                                            checkWordInArray(getURI, 'profissional') &&
                                            checkWordInArray(getURI, 'consultar')
                                        ) ||
                                        (
                                            checkWordInArray(getURI, 'profissional') &&
                                            checkWordInArray(getURI, 'atualizar')
                                        ) ||
                                        (
                                            checkWordInArray(getURI, 'alocarfuncionario') &&
                                            checkWordInArray(getURI, 'consultarfunc')
                                        )

                                    ) && (

                                        <form className="needs-validation" onSubmit={(e) => {
                                            e.preventDefault();
                                            submitAllForms(`filtro-${origemForm}`, formData);
                                        }}>
                                            {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? (
                                                <div style={formGroupStyle}>
                                                    <div>
                                                        <label
                                                            htmlFor="DataDemissao"
                                                            style={formLabelStyle}
                                                            className="form-label">Demissão
                                                            {(checkWordInArray(getURI, 'consultar') || checkWordInArray(getURI, 'consultarfunc')) ? null : (<strong style={requiredField}>*</strong>)}
                                                        </label>
                                                        {dataLoading ? (
                                                            <div className="p-2">
                                                                <AppLoading parametros={{
                                                                    tipoLoading: "progress",
                                                                    carregando: dataLoading
                                                                }} />
                                                            </div>
                                                        ) : (
                                                            <AppDataPtBr
                                                                parametros={formData.DataDemissao}
                                                            />
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
                                                        labelField: 'Demissão',
                                                        nameField: 'DataDemissao',
                                                        attributeMax: 'Profissional', // maxDate - Profissional, Periodo.
                                                        attributeRequired: true,
                                                        attributeReadOnly: false,
                                                        attributeDisabled: checkWordInArray(getURI, 'alocarfuncionario') ? true : false,
                                                        attributeMask: 'Profissional', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                    }} />
                                            )}
                                        </form>
                                    )}
                                {/* Unidade */}
                                {
                                    (
                                        (
                                            checkWordInArray(getURI, 'alocarfuncionario') &&
                                            checkWordInArray(getURI, 'cadastrar')
                                        ) ||
                                        (
                                            checkWordInArray(getURI, 'alocarfuncionario') &&
                                            checkWordInArray(getURI, 'atualizar')
                                        ) ||
                                        (
                                            checkWordInArray(getURI, 'profissional') &&
                                            checkWordInArray(getURI, 'cadastrar')
                                        )
                                    )
                                    &&
                                    (
                                        <div>
                                            {renderCampoUnidade(atualizar_id)}
                                        </div>
                                    )
                                }

                            </div>
                        </div>
                    </div>
                </div>

                {
                    (
                        checkWordInArray(getURI, 'alocarfuncionario') &&
                        checkWordInArray(getURI, 'consultarfunc')
                    )
                        ? (
                            <div className="card mb-4">
                                <div className="card-body">
                                    <div className="row">
                                        {/* Unidade */}
                                        <div className="col-12 col-sm-4">
                                            {renderCampoUnidade(atualizar_id)}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ) : (null)
                }

                {/* Botão de voltar e salvar */}
                <div className="ms-3 me-3">
                    <div className="row">
                        <div className="col-12">
                            <form
                                className="needs-validation d-flex justify-content-between align-items-center"
                                onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`, formData);
                                }}
                            >
                                <div className="d-flex gap-2">
                                    {/* Botão Voltar */}
                                    {checkWordInArray(getURI, 'alocarfuncionario') ? (
                                        <button
                                            className="btn btn-danger"
                                            onClick={(e) => {
                                                e.preventDefault();
                                                window.history.back(); // Retorna à página anterior
                                            }}
                                        >
                                            Voltar
                                        </button>
                                    ) : (
                                        <button
                                            className="btn btn-danger"
                                            onClick={(e) => {
                                                e.preventDefault();
                                                window.history.back(); // Retorna à página anterior
                                            }}
                                        >
                                            Voltar
                                        </button>
                                    )}

                                    {/* Botão Salvar */}
                                    {!checkWordInArray(getURI, 'consultar') && !checkWordInArray(getURI, 'consultarfunc') && (
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

                {typeof AppJson === "undefined" ? (
                    <div>

                        <AppJson
                            parametros={parametros}
                            dbResponse={formData}
                        />

                    </div>
                ) : (
                    <div>
                        {/* <p className="text-danger">AppJson não lacançado.</p> */}
                    </div>
                )}

                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message} modalId={'modal_form'}
                />
            </div>
        );
    };
</script>