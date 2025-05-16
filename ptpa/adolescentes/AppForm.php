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

        // APIs
        const api_get_sexo = parametros.api_get_sexo;
        const api_get_municipio = parametros.api_get_municipio;
        const api_get_periodo = parametros.api_get_periodo;
        const api_post_filter_responsaveis = parametros.api_post_filter_responsaveis;
        const api_post_atualizar_adolescente = parametros.api_post_atualizar_adolescente || '';
        const api_post_cadastrar_adolescente = parametros.api_post_cadastrar_adolescente || '';
        const api_get_atualizar_adolescente = parametros.api_get_atualizar_adolescente || '';
        const api_get_adolescente = parametros.api_get_adolescente || '';
        const api_post_confirma_email = parametros.api_post_confirma_email || '';
        const getVar_page = parametros.getVar_page || '';
        const api_get_escolaridade = parametros.api_get_escolaridade;
        const api_post_escolaridade_cadastrar = parametros.api_post_escolaridade_cadastrar;
        const api_post_escolaridade_filtrar = parametros.api_post_escolaridade_filtrar;
        const api_get_genero = parametros.api_get_genero;
        const api_post_genero_cadastrar = parametros.api_post_genero_cadastrar;
        const api_post_genero_filtrar = parametros.api_post_genero_filtrar;
        const api_get_selectunidade = parametros.api_get_selectunidade || '';
        const api_post_filter_unidade = parametros.api_post_filter_unidade || '';
        const api_filter_unidades = parametros.api_filter_unidades || '';

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
        function randomInt(n) {
            let resultado = '';
            for (let i = 0; i < n; i++) {
                resultado += Math.floor(Math.random() * 10);
            }
            return resultado;
        }

        // Complemento
        const arrayComplemento = ["Fundos", "Frente", "Lado", "Casa", "Apartamento 403", "Sala", "Loja", "Sobreloja", "Cobertura", "Galpão", "Prédio Azul", "Conjunto", "Bloco 2", "Torre Verde", "Edifício branco", "Residência 8", "Sobrado"];
        const randomComplemento = arrayComplemento[gerarIndice(arrayComplemento)];

        const ceps = [
            "23535639", "23520244", "23010392", "21941614", "20000100", "20000300", "20010010", "20020050", "20030060", "20040070", "20060090", "21665300", "21000110", "21010120", "21020130", "21030140", "21040150", "21050160", "21060170", "21070180", "21080190", "21090200", "22010220", "22020230", "22030240", "21645510", "22050260", "22070280", "22080290", "22090300", "23000310", "23010320", "23020330", "23030340", "23040350", "23050360", "23060370", "23070380", "23080390", "21830498", "23090400", "57025041", "24020420", "24040440", "69915455", "24060460", "24070470", "24090490", "24110510", "24120520", "24130530", "24150550", "24160560", "24170570", "24180580", "21725280", "22245030", "21650331", "24200600", "24210610", "24220620", "24230630", "24240640", "24250650", "24260660", "24270670", "24280680", "24290690", "24300700", "24310710", "24320720", "24330730", "24340740", "24350750", "24400760", "24410770", "24420780", "24430790", "24440800", "24450810", "24460820", "22763590", "24480840", "23821730", "24490850", "24510870", "24520880", "24530890", "24540900", "24550910", "24560920", "24570930", "24580940", "24590950", "24600960", "24610970", "24620980", "24630990", "24020150", "24020125", "24030215", "24020071", "24030005", "24020074", "24030000", "24020012", "24020077", "24220031", "24210390", "24210400", "24230051", "24310430", "24310420", "24350310", "24350370", "24350390", "24370025", "24370205", "24370210", "24722220", "24722230", "24740010", "24740015", "24740020", "24740300", "24740305", "24740310", "24740315", "24740320", "24740325", "24740330", "24740335", "24740340", "24740350", "24740355", "24743000", "24743005", "24743010", "24743015", "24743020", "22753690", "24743030", "24743035", "24743040", "24743045", "24743050", "24743055", "24743060", "24743065", "24743070", "21532240", "23042320", "23013350", "24743080", "24743085", "24743090", "24743095", "24743100", "24743105", "24743110", "24743115", "24743120", "24743125", "24743130", "24743135", "24743140", "24743145", "24743150", "24743155", "24743160", "24743165", "24743170", "24743175", "24743180", "24743185", "24743190", "24743195", "24743200", "20766820", "24743210", "24743215", "24743220", "22723225", "24743230", "24743235", "24743240", "24743245", "24743250", "24743255", "24743260", "24743265", "24743270", "24743275", "24743280", "24743285", "24743290", "24743295", "24743300", "20050030", "22410003", "21941590", "20021000", "20031170", "20230010", "22250040", "22410001", "20271110", "20511330", "22631000", "20710130", "20710230", "20930000", "21330630", "21941904", "22210080", "22290240", "22430160", "20040002", "20040006", "20211901", "20520053", "20530350", "20740032", "20921030", "21041010", "21715000", "22010002", "22061020", "22210001", "22250140", "22250901", "22431000", "22460060", "22631050", "22775003", "23020001", "20040020", "20050000", "20050002", "20060050", "21843708", "20071004", "20211160", "20270280", "20511170", "20521160", "20530001", "20710010", "20921430", "21061970", "21210010", "21251080", "21330230", "21351050", "21650001", "21725180", "21941011", "22010010", "22071060", "22221001", "22221020", "22241000", "22250070", "22270000", "22280000", "22290160", "22410160", "22451041", "22461002", "20010000", "20010020", "20011000", "20020050", "20031050", "20040001", "20050001", "20071000", "20081001", "20210010", "20211001", "20299924", "20231050", "20511260", "20530160", "20760040", "20771001", "20930001", "21021190", "21545001", "22010001", "22070010", "22231001", "22240000", "22250010", "22270001", "22281001", "22291001", "22621070", "23030235", "22715580", "21830264", "23042122", "21051510", "21940300", "23575217", "20261235", "21730680", "20940230", "21842420", "21843708"
        ];

        function getRandomCep() {
            const index = Math.floor(Math.random() * ceps.length);
            return ceps[index];
        }

        // Cidade
        const EndCidade = ["Angra dos Reis", "Aperibé", "Araruama", "Areal", "Armação dos Búzios", "Barra do Piraí", "Barra Mansa", "Belém", "Bom Jardim", "Bom Jesus do Itabapoana", "Cabo Frio", "Cachoeiras de Macacu", "Cambuci", "Campos dos Goytacazes", "Cantagalo", "Carapebus", "Cardoso Moreira", "Carmo", "Casimiro de Abreu", "Comendador Levy Gasparian", "Conceição de Macabu", "Cordeiro", "Duas Barras", "Duque de Caxias", "Engenheiro Paulo de Frontin", "Guapimirim", "Iguaba Grande", "Itaboraí", "Itaguaí", "Italva", "Itaocara", "Itaperuna", "Laje do Muriaé", "Macaé", "Macuco", "Magé", "Mangaratiba", "Maricá", "Mendes", "Mesquita", "Miguel Pereira", "Miracema", "Natividade", "Nilópolis", "Niterói", "Nova Friburgo", "Nova Iguaçu", "Paracambi", "Paraíba do Sul", "Parati", "Paty do Alferes", "Petrópolis", "Pinheiral", "Piraí", "Porciúncula", "Quatis", "Queimados", "Rio Bonito", "Rio Claro", "Rio das Flores", "Rio das Ostras", "Rio de Janeiro", "Santa Maria Madalena", "Santo Antônio de Pádua", "São Fidélis", "São Gonçalo", "São João da Barra", "São João de Meriti", "São José de Ubá", "São José do Vale do Rio Preto", "São Pedro da Aldeia", "São Sebastião do Alto", "Sapucaia", "Saquarema", "Seropédica", "Silva Jardim", "Sumidouro", "Tanguá", "Teresópolis", "Trajano de Moraes", "Três Rios", "Valença", "Varre-Sai", "Vassouras", "Volta Redonda", "Arraial do Cabo", "Rio das Ostras", "Itaocara", "Quissamã", "Paraty", "Cabo Frio", "Mangaratiba"];

        const turnos = ["Seleção Nula", "Matutino", "Vespertino", "Noturno", "Integral"];
        const turnoAleatorio = turnos[Math.floor(Math.random() * turnos.length)];

        const generosArray = ["Terceiro Gênero", "Masculino", "Feminino", "Two-Spirit", "Genderqueer", "Pangênero", "Agênero"];
        const identidadeGeneroRandom = generosArray[Math.floor(Math.random() * generosArray.length)];

        const escolherMunicipioAleatorio = () => {
            const indiceAleatorio = Math.floor(Math.random() * EndCidade.length);
            return EndCidade[indiceAleatorio];
        };

        // Sexo Biologico
        const arraySexoBiologico = ["1", "2"];
        const randomSexoBiologico = arraySexoBiologico[gerarIndice(arraySexoBiologico)];

        // Gerar nome completo
        const CentrosFIA = ["Colégio", "Centro Educacional", "Unidade de Educação", "Centro de Apoio Educacional", "Instituto Educacional", "Complexo Escolar", "Escola Técnica", "Escola Profissionalizante", "Centro de Ensino", "Escola de Aplicação", "Polo Educacional", "Centro de Formação", "Escola de Referência", "Escola Integral", "Escola Especializada", "Escola Comunitária", "Escola Experimental", "Escola Parque", "Escola Sustentável", "Centro de Aprendizagem", "Escola Inclusiva"];
        const randomEscola = CentrosFIA[gerarIndice(CentrosFIA)] + " " +
            Nome[gerarIndice(Nome)] + " " +
            Nome_Meio[gerarIndice(Nome_Meio)] + " " +
            SobreNome[gerarIndice(SobreNome)];

        const etniasNoBrasil = ["Branca", "Preta", "Parda", "Amarela", "Indígena"];
        const randomEtinia = etniasNoBrasil[gerarIndice(etniasNoBrasil)]

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
        const funcCalculateDates = (date) => {
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

        // const api_get_responsavel = parametros.api_get_responsavel;

        // Variáveis de APIs
        const [listaSexos, setListaSexos] = React.useState([]);
        const [ListPeriodos, setListPeriodos] = React.useState([]);
        const [generos, setGeneros] = React.useState([]);
        const [escolariades, setEscolaridades] = React.useState([]);
        const [municipios, setMunicipios] = React.useState([]);

        // Cadastro Sem Numero no endereço -  Estado para armazenar o valor Y/N diretamente
        // const [responsaveis, setResponsaveis] = React.useState([]);
        const [semNumeroValue, setSemNumeroValue] = React.useState("N");
        const [isCheckedSemNumero, setIsCheckedSemNumero] = React.useState(semNumeroValue === "Y");
        const [readOnlyNumero, setReadOnlyNumero] = React.useState(false)

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

        // Escolhas
        const [isCPFOptional, setIsCPFOptional] = React.useState(false);
        const [isChoiceMade, setIsChoiceMade] = React.useState(false); // CPF Matricula
        const [choice, setChoice] = React.useState(''); // CPF Obrigatorio
        const [camposObrigatorios, setCamposObrigatorios] = React.useState({});

        // const [openCloseField, setOpenCloseField] = React.useState(false); DECREPTO
        // const [indexEscolariadde, setIndexEscolariadde] = React.useState(randomInt(4)); DECREPTO
        // const [prioritizesSelect, setPrioritizesSelect] = React.useState(true); DECREPTO
        // const [prioritizesOther, setPrioritizesOther] = React.useState(false); DECREPTO

        {/* CAMPOS OBRIGATÓRIOS */ }
        const handleChoice = (option) => {
            setChoice(() => option); // Atualiza a escolha do usuário
            setIsChoiceMade(true); // Marca que a escolha foi feita

            {/* CPF - CAMPOS OBRIGATÓRIOS */ }
            if (option === 'cpf') {
                setFormData((prev) => ({
                    ...prev,
                    Certidao: ''
                }));
                setIsChoiceMade('cpf'); // Reseta a escolha
                setCamposObrigatorios(() => ({
                    CPF: 'CPF',
                    Nome: 'Nome Completo',
                    Email: 'Email',
                    Nascimento: 'Data de Nascimento',
                    CEP: 'CEP',
                    Logradouro: 'Logradouro',
                    Numero: 'Número',
                    Bairro: 'Bairro',
                    Municipio: 'Município',
                    UF: 'UF',
                    unidade_id: 'Unidade',
                    unit: 'Unidade',
                    genero_identidade: 'Gênero',
                    Etnia: 'Etnia',
                    sexo_biologico_id: 'Sexo',
                    TipoEscola: 'Tipo de Escola',
                    NomeEscola: 'Nome da Escola',
                    Escolaridade: 'Escolaridade',
                    turno_escolar: 'Turno escolar',
                    Responsavel_Nome: 'Nome do Responsável',
                    Responsavel_TelefoneMovel: 'Responsável TelefoneMovel',
                    Responsavel_CPF: 'Responsável CPF',
                    // Complemento: 'Complemto',
                }));
            } else if (option === 'certidao') {
                {/* CERTIDÃO - CAMPOS OBRIGATÓRIOS */ }
                console.log(`------------------------`);
                console.log(`option === 'certidao'`);
                setFormData((prev) => ({
                    ...prev,
                    CPF: ''
                }));
                setIsChoiceMade('certidao'); // Reseta a escolha
                setCamposObrigatorios(() => ({
                    Certidao: 'Certidao',
                    NumRegistro: 'Nº Registro',
                    Zona: 'Zona',
                    Folha: 'Folha',
                    Livro: 'Livro',
                    Circunscricao: 'Circunscrição',
                    unidade_id: 'Unidade',
                    //
                    Nome: 'Nome Completo',
                    Email: 'Email',
                    Nascimento: 'Data de Nascimento',
                    CEP: 'CEP',
                    Logradouro: 'Logradouro',
                    Numero: 'Número',
                    Bairro: 'Bairro',
                    Municipio: 'Município',
                    UF: 'UF',
                    unidade_id: 'Unidade',
                    unit: 'Unidade',
                    genero_identidade: 'Gênero',
                    Etnia: 'Etnia',
                    sexo_biologico_id: 'Sexo',
                    TipoEscola: 'Tipo de Escola',
                    NomeEscola: 'Nome da Escola',
                    Escolaridade: 'Escolaridade',
                    turno_escolar: 'Turno escolar',
                    Responsavel_Nome: 'Nome do Responsável',
                    Responsavel_TelefoneMovel: 'Responsável TelefoneMovel',
                    Responsavel_CPF: 'Responsável CPF',
                }));
            }
        };

        // Variáveis 
        // const [datasPeriodos, setDatasPeriodos] = React.useState([]);
        // const [showEmptyMessage, setShowEmptyMessage] = React.useState(false);
        const [onEscolaridade, setOnEscolaridade] = React.useState(true);
        const [termoAceito, setTermoAceito] = React.useState(false);
        const [error, setError] = React.useState(null);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);
        const [tabNav, setTabNav] = React.useState('dadosCPF');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null,
        });

        // Função para verificar campos obrigatórios
        const validarCamposObrigatorios = (dados, camposObrigatorios) => {
            const camposVazios = Object.keys(camposObrigatorios).filter(campo => !dados[campo]);

            // Retorna o status e os campos vazios
            return {
                isValid: camposVazios.length === 0, // true se não houver campos vazios
                camposVazios, // Lista dos campos que estão vazios
            };
        };

        // CEP - Estados para o componente
        const [units, setUnits] = React.useState([]);
        const [originalUnits, setOriginalUnits] = React.useState([]);
        const viacep = 'https://viacep.com.br/ws/';
        const opencep = 'https://opencep.com/v1/';

        const handleReset = () => {
            setChoice('');
        };

        {/* formData BK*/ }
        const [formData, setFormData] = React.useState({
            unit: '',
            outro: null,
            checkSemNumero: semNumeroValue,
            checkMunicipio: true,
            dropMunicipio: true,
            filterResponsavel: null,
            token_csrf: token_csrf,
            json: '1',
            termo: false,
            //
            id: null,
            Nome: debugMyPrint ? nomeCompleto : null,
            CPF: debugMyPrint ? `${randomCPFad}` : null,
            RG: debugMyPrint ? randomInt(8) : null,
            ExpedidorRG: debugMyPrint ? randomOrgaoExpedidor() : null,
            ExpedicaoRG: null,
            CEP: debugMyPrint ? `${getRandomCep()}` : null,

            Logradouro: null,
            Bairro: null,
            Municipio: null,
            Estado: null,
            UF: null,
            DDD: null,
            GIA: null,
            IBGE: null,
            Regiao: null,
            SIAFI: null,

            Numero: debugMyPrint ? randomInt(4) : null,
            Complemento: debugMyPrint ? randomComplemento : null,

            Municipio: debugMyPrint ? escolherMunicipioAleatorio() : null,
            Nascimento: debugMyPrint ? randomDataAdolescente() : null,
            PeriodoId: debugMyPrint ? '2' : null,
            CadastroId: null,
            perfil_id: '1',
            PerfilDescricao: null,
            sexo_biologico_id: debugMyPrint ? '1' : null,
            SexoBiologico: null,
            genero_identidade: debugMyPrint ? identidadeGeneroRandom : null,
            GeneroIdentidadeDescricao: null,
            AcessoCadastroID: null,
            unidade_id: null,
            Unidade: null,
            NomeUnidade: null,
            acesso_id: '2',
            AcessoDescricao: null,
            ProntuarioId: null,
            NomeMae: null,
            TelefoneFixo: null,
            TelefoneMovel: null,
            TelefoneRecado: debugMyPrint ? randomCelular() : null,
            Email: debugMyPrint ? randomEmail() : null,

            Logradouro: null,
            Bairro: null,
            Municipio: null,
            Estado: null,
            UF: null,
            DDD: null,
            GIA: null,
            IBGE: null,
            Regiao: null,
            SIAFI: null,

            NMatricula: null,
            Etnia: debugMyPrint ? randomEtinia : null,
            Escolaridade: debugMyPrint ? '1º Ano do Ensino Médio' : null,
            Certidao: debugMyPrint ? randomInt(31) : null,
            NumRegistro: null,
            Folha: null,
            Livro: null,
            Circunscricao: null,
            Zona: null,
            UFRegistro: null,
            TipoEscola: debugMyPrint ? 'Publica' : null,
            turno_escolar: debugMyPrint ? turnoAleatorio : null,
            NomeEscola: debugMyPrint ? randomEscola : null,
            DataCadastramento: null,
            DataTermUnid: null,
            DataInicioUnid: null,
            DataAdmissao: null,
            DataDemissao: null,
            CodProfissao: null,
            ResponsavelID: null,
            Responsavel_Nome: debugMyPrint ? nomeCompleto2 : null,
            Responsavel_CPF: debugMyPrint ? randomCPFrs : null,
            Responsavel_Email: null,
            Responsavel_TelefoneFixo: null,
            Responsavel_TelefoneMovel: debugMyPrint ? randomCelular() : null,
            Responsavel_TelefoneRecado: null,

            Responsavel_Logradouro: null,
            Responsavel_Numero: null,
            Responsavel_Complemento: null,
            Responsavel_Bairro: null,
            Responsavel_Municipio: null,
            Responsavel_Estado: null,
            Responsavel_UF: null,
            Responsavel_DDD: null,
            Responsavel_GIA: null,
            Responsavel_IBGE: null,
            Responsavel_Regiao: null,
            Responsavel_SIAFI: null,
            Responsavel_Unidade: null,

            ProfissaoCodigo: null,
            ProfissaoDescricao: null,
            ProfissaoFavorito: null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
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

            if (debugMyPrint) {
                // Atualiza o formData - tanto o valor do checkbox quanto o Numero
                setFormData(prevData => ({
                    ...prevData,
                    checkSemNumero: value,
                    // Se marcado, coloca "S/N", senão mantém ou gera número
                    Numero: checked ? "S/N" : (prevData.Numero === "S/N" ? randomInt(4).toString() : prevData.Numero)
                }));
            }
        };

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

        {/* handleChange */ }
        const handleChange = (event) => {
            const { name, value } = event.target;

            // console.log('--------------------------------');
            // console.log('handleChange');
            // console.log('--------------------------------');
            // console.log('name handleChange: ', name);
            // console.log('value handleChange: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));

            if (name === 'Escolaridade' && prioritizesSelect === true) {
                const myTimer = setTimeout(() => {
                    setFormData((prev) => ({
                        ...prev,
                        [name]: value
                    }));
                    setPrioritizesOther(false);
                }, 300);

                return true;
            }

            let processedValue = value;

            if (name === 'unit') {

                setFormData((prev) => ({
                    ...prev,
                    UnidadeId: value
                }));
                return true;
            }

            // Se o campo for o CPF, faz a validação
            if (name === 'cpf' && !isValidCPF(value)) {
                const cpfInput = event.target;
                cpfInput.classList.add('is-invalid');
                setError('CPF inválido');
            }
        };

        // const handlePrioritizesSelect = () => {
        // const myTimer = setTimeout(() => {
        // setPrioritizesSelect(true);
        // setPrioritizesOther(false);
        // }, 300);
        // };

        // const handlePrioritizesOther = () => {
        // const myTimer = setTimeout(() => {
        // setPrioritizesSelect(false);
        // setPrioritizesOther(true);
        // // setOpenCloseField(true); DECREPTO
        // }, 300);
        // };

        // React.useEffect(() => {
        // console.log('--------------------------------');
        // console.log('react.useEffect');
        // console.log('--------------------------------');
        // console.log('prioritizesOther: ', prioritizesOther);
        // console.log('prioritizesSelect: ', prioritizesSelect);
        // }, [prioritizesOther, prioritizesSelect]);

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
                    Id_Unidade: value
                }
                // console.log('setData: ', setData);
                fetchPeriodos(setData);
            }

            // Modificação no trecho do handleBlur para o campo CEP
            if (
                name === 'CEP' &&
                value.length >= 8 &&
                checkWordInArray(getURI, 'cadastrar') === false
            ) {
                // Buscar endereço pelo CEP
                // console.log("Alteração de CEP realizada no handleBlur");
                orderUnitsByCEPProximity(value);
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
                    // setShowEmptyMessage(true);
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
                    // setShowEmptyMessage(true);

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

        // Função para trocar de aba
        const handleTabClick = (tab) => {
            // console.log('handleTabClick: ', tab);
            setTabNav(tab); // Atualiza a aba selecionada
        };

        {/* SUBMITALLFORMS */ }
        const submitAllForms = async (filtro) => {
            // console.log('src/app/Views/fia/ptpa/adolescentes/AppForm.php');
            // console.log('filtro: ', filtro);
            // console.log('submitAllForms...');

            // Primeiro, resetamos o estado para false para garantir que uma nova chamada seja detectada
            setMessage({ show: false, type: null, message: null });

            const setData = formData;
            let data = '';
            let dbResponse = [];
            let response = '';


            if (isCPFOptional === true) {
                delete camposObrigatorios.CPF;
            }

            if (isCheckedSemNumero === true) {
                delete camposObrigatorios.Numero;
            }

            const { isValid, camposVazios } = validarCamposObrigatorios(setData, camposObrigatorios);

            if (!isValid) {
                const nomesCamposVazios = camposVazios.map(campo => camposObrigatorios[campo]);
                setTimeout(() => {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: `<b>Os campos</b>: <br/> ${nomesCamposVazios.join("<br/>")}<br/> <b>não podem estar em branco</b>`,
                    });
                    console.log('camposVazios :: ', `<b>Os campos</b>: <br/> ${nomesCamposVazios.join("<br/>")}<br/> <b>não podem estar em branco</b>`);
                }, 10);
                return false;
            }

            if (filtro === `filtro-adolescente`) {
                console.log('submitAllForms - filtro-adolescente...');
                fetchPostAdolescente(setData);
            }
        };

        // Função para ordenar unidades pelo CEP mais próximo
        const orderUnitsByCEPProximity = (userCEP) => {
            // Remover caracteres não-numéricos do CEP do usuário
            const cleanUserCEP = userCEP.replace(/\D/g, '');

            if (!cleanUserCEP || cleanUserCEP.length !== 8) {
                console.error('CEP inválido para ordenação:', userCEP);
                return;
            }

            // Cria uma cópia do array original para ordenar
            const sortedUnits = [...originalUnits].map(unit => {
                // Limpa o CEP da unidade (remove caracteres não-numéricos)
                const cleanUnitCEP = unit.unidades_CEP ? unit.unidades_CEP.replace(/\D/g, '') : '';

                // Calcula a "proximidade" - quanto menor este número, mais próximo o CEP
                // Método: calcular a diferença absoluta entre os CEPs como números
                const proximity = cleanUnitCEP ? Math.abs(parseInt(cleanUserCEP) - parseInt(cleanUnitCEP)) : Number.MAX_SAFE_INTEGER;

                // Retorna objeto com a unidade original e sua proximidade
                return { ...unit, proximity };
            });

            // Ordena pelo valor de proximidade (do mais próximo ao mais distante)
            sortedUnits.sort((a, b) => a.proximity - b.proximity);

            // Atualiza o estado units com a nova ordem
            setUnits(sortedUnits);

            // Se houver unidades, define a mais próxima como selecionada
            if (sortedUnits.length > 0) {
                const closestUnit = sortedUnits[0];
                // console.log('Unidade mais próxima:', closestUnit.unidades_nome, '- CEP:', closestUnit.unidades_CEP);

                // Atualiza o formData com a unidade mais próxima
                setFormData(prevData => ({
                    ...prevData,
                    UnidadeId: closestUnit.id,
                    unit: closestUnit.id,
                    Unidade: closestUnit.unidades_nome
                }));
            }
        };

        // POST Padrão 
        const fetchPostconfirmaEmail = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_confirma_email) => {
            // console.log('fetchPostconfirmaEmail...');

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
                // console.log('data', data);
                // 
                if (data.result && data.result === 'success') {
                    // 
                    console.log('Foi enviado um email de confirmação');
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

        // POST fetchPostUnidade
        const fetchPostUnidade = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_get_selectunidade, customPage = '') => {
            // console.log('-------------------');
            // console.log('fetchPostUnidade...');
            // console.log('-------------------');
            const queryParams = new URLSearchParams(formData).toString();
            const url = `${custonBaseURL}${custonApiPostObjeto}${customPage}?limit=90000&${queryParams}`;
            // console.log('fetchPostUnidade url:', url);

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                const data = await response.json();
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('fetchPostUnidade dbResponse ::', dbResponse);
                    setUnits(dbResponse);
                    setOriginalUnits(dbResponse);
                    setDataLoading(false);
                    return true;
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
            setIsLoading(true);
            const url = base_url + api_get_atualizar_adolescente;
            // console.log('---------------------');
            // console.log('fetchAdolescentes ...');
            // console.log('url: ', url);
            try {
                if (checkWordInArray(getURI, 'cadastrar')) {
                    setFormData((prev) => ({
                        ...prev,
                    }));
                    return false;
                }

                const response = await fetch(url);
                // console.log(base_url + api_get_atualizar_adolescente);
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {

                    // console.log('Adolescentes: ', data.result.dbResponse[0]);

                    setFormData((prevFormData) => ({
                        ...prevFormData,
                        ...data.result.dbResponse[0]
                    }));

                    setCamposObrigatorios(() => ({
                        Nome: 'Nome Completo',
                        Email: 'Email',
                        Nascimento: 'Data de Nascimento',
                        CEP: 'CEP',
                        Logradouro: 'Logradouro',
                        Numero: 'Número',
                        Bairro: 'Bairro',
                        Municipio: 'Município',
                        UF: 'UF',
                        unit: 'Unidade',
                        genero_identidade: 'Gênero',
                        Etnia: 'Etnia',
                        sexo_biologico_id: 'Sexo',
                        TipoEscola: 'Tipo de Escola',
                        NomeEscola: 'Nome da Escola',
                        Escolaridade: 'Escolaridade',
                        turno_escolar: 'Turno escolar',
                        Responsavel_Nome: 'Nome do Responsável',
                        Responsavel_TelefoneMovel: 'Responsável TelefoneMovel',
                        Responsavel_CPF: 'Responsável CPF',
                    }));
                }
            } catch (error) {
                setError('Erro ao carregar Adolescentes: ' + error.message);
            }
        };

        {/* POST de SALVAR ADOLESCENTE */ }
        const fetchPostAdolescente = async (formData = {}, custonBaseURL = base_url, custonApiPostObjeto = api_post_cadastrar_adolescente, customPage = getVar_page) => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            console.log('------------------------------');
            console.log('fetchPostAdolescente url:', url);

            const setData = formData;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                console.log('fetchPostAdolescente url:', url);

                if (data.result && data.result.affectedRows && data.result.affectedRows > 0) {
                    const dbResponse = data.result.dbResponse;
                    // console.log('fetchPostAdolescente dbResponse ::', dbResponse);
                    (checkWordInArray(getURI, 'atualizar')) ? (
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'Adolescente atualizado com sucesso.'
                        })
                    ) : (
                        setMessage({
                            show: true,
                            type: 'light',
                            message: 'Adolescente cadastrado com sucesso.'
                        })
                    )
                    if (checkWordInArray(getURI, 'cadastrar')) {
                        redirectTo('index.php/fia/ptpa/adolescente/endpoint/sucessocadastro');
                    } else if (checkWordInArray(getURI, 'drupal')) {
                        redirectTo('index.php/fia/ptpa/adolescente/endpoint/sucessocadastro');
                    } else if (checkWordInArray(getURI, 'atualizar')) {
                        redirectTo('index.php/fia/ptpa/adolescente/endpoint/exibir');
                    } else {
                        redirectTo('index.php/fia/ptpa/adolescente/endpoint/sucessocadastro');
                    }
                    return true;
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Não foram encontradas objeto cadastradas'
                    });
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return false;
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

        // Fetch para obter os Escolaridade - DECREPTO
        // const fetchEscolaridade = async () => {
        // try {
        // console.log('----------------------------------');
        // console.log('fetchEscolaridade');
        // console.log('----------------------------------');
        // const url = base_url + api_get_escolaridade;
        // console.log('url :: ', url)
        // const response = await fetch(url);
        // const data = await response.json();
        // console.log('const data :: ', data);
        // if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
        // console.log('fetchEscolaridade data.result.dbResponse :: ', data.result.dbResponse);
        // setEscolaridades(data.result.dbResponse);
        // }
        // } catch (error) {
        // setError('Erro ao carregar Escolaridade: ' + error.message);
        // }
        // };

        // Fetch para obter os Gêneros
        const fetchGeneros = async () => {
            try {
                const response = await fetch(base_url + api_get_genero)
                const data = await response.json();
                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    // console.log('fetchGeneros data.result.dbResponse :: ', data.result.dbResponse);
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
                    setListPeriodos(dbResponse);
                    setPagination('list');
                    // 
                } else {
                    setListPeriodos([]);
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
                    setListPeriodos(data.result.dbResponse);
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

        // Função para redirecionar após 4 segundos
        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        // Função para verificar se uma palavra está presente em uma string
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

        {/* RENDER CONSULTA BK*/ }
        const renderConsulta = () => {
            return (
                <div className="m-3">
                    <ul className="nav nav-tabs">
                        <li className="nav-item">
                            <a className="nav-link active" aria-current="page" >Dados Pessoais do Adolescente</a>
                        </li>
                    </ul>
                    <div className="border border-top-0 mb-4 p-4">
                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        CPF
                                    </label>
                                    <div className="m-2">
                                        {formData.CPF || 'CPF não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Nome Completo
                                    </label>
                                    <div className="m-2">
                                        {formData.Nome || 'Nome completo não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        E-mail
                                    </label>
                                    <div className="m-2">
                                        {formData.Email || 'E-mail não informado'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Data de Nascimento
                                    </label>
                                    <div className="m-2">
                                        {formData.Nascimento || 'Data de Nascimento não informada'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        RG
                                    </label>
                                    <div className="m-2">
                                        {formData.RG || 'RG não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Expedidor
                                    </label>
                                    <div className="m-2">
                                        {formData.ExpedidorRG || 'Expedidor do RG não informado'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Certidão
                                    </label>
                                    <div className="m-2">
                                        {formData.Certidao || 'Certidão de Nascimento não informada'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Registro
                                    </label>
                                    <div className="m-2">
                                        {formData.NumRegistro || 'Registro não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Zona
                                    </label>
                                    <div className="m-2">
                                        {formData.Zona || 'Zona não informada'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Folha
                                    </label>
                                    <div className="m-2">
                                        {formData.Folha || 'Folha não informada'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Livro
                                    </label>
                                    <div className="m-2">
                                        {formData.Livro || 'Livro não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Circunscrição
                                    </label>
                                    <div className="m-2">
                                        {formData.Circunscricao || 'Circunscrição não informada'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        CEP
                                    </label>
                                    <div className="m-2">
                                        {formData.CEP || 'CEP não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Logradouro
                                    </label>
                                    <div className="m-2">
                                        {formData.Logradouro || 'Logradouro não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Número
                                    </label>
                                    <div className="m-2">
                                        {formData.Numero || 'Número não informado'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Complemento
                                    </label>
                                    <div className="m-2">
                                        {formData.Complemento || 'Complemento não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Bairro
                                    </label>
                                    <div className="m-2">
                                        {formData.Bairro || 'Bairro não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Municípoio
                                    </label>
                                    <div className="m-2">
                                        {formData.Municipio || 'Município não informado'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        UF
                                    </label>
                                    <div className="m-2">
                                        {formData.UF || 'UF não informada'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Unidade
                                    </label>
                                    <div className="m-2">
                                        {formData.Unidade || 'Unidade não informada'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Gênero
                                    </label>
                                    <div className="m-2">
                                        {formData.genero_identidade || 'Gênero não informado'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-6">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Etnia
                                    </label>
                                    <div className="m-2">
                                        {formData.Etnia || 'Etnia não informada'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-6">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Sexo
                                    </label>
                                    <div className="m-2">
                                        {listaSexos.find(item => item.id == formData.sexo_biologico_id)?.sexo_biologico || (
                                            <span className="text-muted">Não informado</span>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <ul className="nav nav-tabs">
                        <li className="nav-item">
                            <a className="nav-link active" aria-current="page" >Dados Escolares</a>
                        </li>
                    </ul>
                    <div className="border border-top-0 mb-4 p-4">
                        <div className="row">
                            <div className="col-12 col-sm-6">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Tipo de Escola
                                    </label>
                                    <div className="m-2">
                                        {formData.TipoEscola || 'Tipo de Escola não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-6">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Escolaridade
                                    </label>
                                    <div className="m-2">
                                        {formData.Escolaridade || 'Escolaridade não informada'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-12 col-sm-6">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Nome da Escola
                                    </label>
                                    <div className="m-2">
                                        {formData.NomeEscola || 'Nome da Escola não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-6">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Turno Escolar
                                    </label>
                                    <div className="m-2">
                                        {formData.turno_escolar || 'Turno Escolar não informado'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul className="nav nav-tabs">
                        <li className="nav-item">
                            <a className="nav-link active" aria-current="page" >Dados do Responsável</a>
                        </li>
                    </ul>
                    <div className="border border-top-0 mb-4 p-4">
                        <div className="row">
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Nome (Responsável)
                                    </label>
                                    <div className="m-2">
                                        {formData.Responsavel_Nome || 'Nome do Responsável não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        CPF (Responsável)
                                    </label>
                                    <div className="m-2">
                                        {formData.Responsavel_CPF || 'CPF do Responsável não informado'}
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-sm-4">
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="Livro"
                                        style={formLabelStyle}
                                        className="form-label"
                                    >
                                        Telefone (Responsável)
                                    </label>
                                    <div className="m-2">
                                        {formData.Responsavel_TelefoneMovel || 'Telefone do Responsável não informado'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            );
        }

        {/* RENDER CPF BK*/ }
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
                                submitAllForms(`filtro-${origemForm}`);
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
                                        attributeMaxlength: 14, // maxlength - Telefone: 14, CPF: 15, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/*
                                <AppNome formData={formData} setFormData={setFormData} parametros={parametros} />
                                */}
                                {/* NOME ADOLESCENTE */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Nome Completo',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Nome',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppEmail
                                    formData={formData}
                                    setFormData={setFormData}
                                    parametros={parametros}
                                />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* RG BK*/}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'RG',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'RG',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 11, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Órgao Expedidor',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'ExpedidorRG',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 30, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            {/* CEP */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'CEP',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'CEP',
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
                        </div>
                        <div className="col-12 col-sm-4">
                            {/* LOGRADOURO */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'Logradouro',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'Logradouro',
                                    errorMessage: '', // Mensagem de Erro personalizada
                                    attributePlaceholder: '', // placeholder 
                                    attributeMinlength: 4, // minlength 
                                    attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                    attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                    attributeAutocomplete: 'on', // on, off ]
                                    attributeRequired: true,
                                    attributeReadOnly: false,
                                    attributeDisabled: false,
                                    attributeMask: '', // CPF, Telefone, CEP, , SEI, Processo.
                                }}
                            />

                        </div>
                        <div className="col-12 col-sm-2">
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
                        </div>
                        <div className="col-12 col-sm-2">
                            {/* NUMERO */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'Número',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'Numero',
                                    errorMessage: '', // Mensagem de Erro personalizada
                                    setMessage: () => setMessage(),
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
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            {/* COMPLEMENTO */}
                            <AppText submitAllForms parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'Complemento',
                                    labelColor: 'black',
                                    nameField: 'Complemento',
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
                        </div>
                        <div className="col-12 col-sm-6">
                            {/* BAIRRO */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'Bairro',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'Bairro',
                                    errorMessage: '', // Mensagem de Erro personalizada
                                    attributePlaceholder: '', // placeholder 
                                    attributeMinlength: 4, // minlength 
                                    attributeMaxlength: 70, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                    attributePattern: 'Caracter, Inteiro', // Inteiro, Caracter, Senha
                                    attributeAutocomplete: 'on', // on, off ]
                                    attributeRequired: true,
                                    attributeReadOnly: false,
                                    attributeDisabled: false,
                                    attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                }}
                            />

                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            {(formData.dropMunicipio) ? (
                                <div>
                                    {/* MUNICÍPIO/SELECT */}
                                    <AppSelect
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Municípoio',
                                            nameField: 'Municipio',
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
                                            nameField: 'Municipio',
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
                        </div>
                        <div className="col-12 col-sm-6">
                            {/* UF */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'UF',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'UF',
                                    errorMessage: '', // Mensagem de Erro personalizada
                                    attributePlaceholder: '', // placeholder 
                                    attributeMinlength: 1, // minlength 
                                    attributeMaxlength: 2, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                    attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                    attributeAutocomplete: 'on', // on, off ]
                                    attributeRequired: true,
                                    attributeReadOnly: false,
                                    attributeDisabled: false,
                                    attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                }}
                            />

                        </div>
                    </div>

                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                                value={formData.unit || ''}
                                                onFocus={handleFocus}
                                                onChange={handleChange}
                                                onBlur={handleBlur}
                                                style={formControlStyle}
                                                className="form-select form-select-sm"
                                                required
                                                aria-label="Default select 2"
                                            >
                                                <option value="">Seleção Nula</option>
                                                {units.map(escolaridade_list => (
                                                    <option key={`${escolaridade_list.id}`} value={escolaridade_list.id}>
                                                        {escolaridade_list.unidades_nome} - {escolaridade_list.unidades_CEP}
                                                    </option>
                                                ))}
                                            </select>
                                        )}
                                    </div>
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                            {/* V-2 IDENTIDADE de GENERO */}
                                            {typeof AppSelectCheck !== "undefined" ? (
                                                <div style={formGroupStyle}>
                                                    <label
                                                        htmlFor="dynamicSelect"
                                                        style={formLabelStyle}
                                                        className="form-label"
                                                    >
                                                        Gênero V-2
                                                        <strong style={requiredField}>*</strong>
                                                    </label>
                                                    {(isLoading) && (
                                                        <div className="p-2">
                                                            <AppLoading parametros={{
                                                                tipoLoading: "progress",
                                                                carregando: isLoading
                                                            }} />
                                                        </div>
                                                    )}
                                                    {(!isLoading) && (
                                                        <AppSelectCheck
                                                            parametros={parametros}
                                                            formData={formData}
                                                            setFormData={setFormData}
                                                            fieldAttributes={{
                                                                attributeOrigemForm: `origemForm`,
                                                                labelField: 'Gênero V-2',
                                                                nameField: 'genero_identidade',
                                                                btnCollor: '', // primary, secondary, success, info, warning, danger, light, dark
                                                                btnOutline: true, // true ou false
                                                                btnSize: 'sm', // sm, lg
                                                                btnRounded: '2', // 2, 5, pill
                                                                errorMessage: '', // Mensagem de Erro personalizada
                                                                attributeFieldValue: 'genero', // O que será capturado da API
                                                                attributeFieldLabel: 'genero', // O que será exibido no form = AppSelect
                                                                attributeRequired: true,
                                                                attributeDisabled: false,
                                                                objetoArrayKey: [
                                                                    { key: '1', value: 'Opção 1' },
                                                                    { key: '2', value: 'Opção 2' },
                                                                    { key: '3', value: 'Opção 3' },
                                                                    { key: '4', value: 'Opção 4' }
                                                                ],
                                                                api_get: `${api_get_genero}`,
                                                                api_post: `${api_post_genero_cadastrar}`,
                                                                api_filter: `${api_post_genero_filtrar}`,
                                                            }}
                                                        />
                                                    )}
                                                </div>
                                            ) : (
                                                <div>
                                                    <p className="text-danger">AppSelectCheck não lacançado.</p>
                                                </div>
                                            )}
                                        </div>
                                    )
                                )}
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="sexo_biologico_id"
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
                                                    {listaSexos.find(item => item.id == formData.sexo_biologico_id)?.sexo_biologico || (
                                                        <span className="text-muted">Não informado</span>
                                                    )}
                                                </div>
                                            ) : (
                                                <select
                                                    data-api={`filtro-${origemForm}`}
                                                    id="sexo_biologico_id"
                                                    name="sexo_biologico_id"
                                                    value={formData.sexo_biologico_id || ''}
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

        {/* RENDER CERTIDÃO BK*/ }
        const renderCertidao = () => {
            return (
                <div className="border border-top-0 mb-4 p-4">
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* CERTIDÃO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Certidão',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Certidao',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 16, // minlength 
                                        attributeMaxlength: 40, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Certdao: 39
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: 'Certidao', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* REGISTRO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Registro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'NumRegistro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 7, // minlength 
                                        attributeMaxlength: 8, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Certdao: 39
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* ZONA */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Zona',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Zona',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 2, // minlength 
                                        attributeMaxlength: 3, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Certdao: 39
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* FOLHA */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Folha',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Folha',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 4, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* LIVRO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Livro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Livro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 5, // minlength 
                                        attributeMaxlength: 6, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* NOME CIRCUNSCRIÇÃO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Circunscrição',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Circunscricao',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 6, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                    }}
                                />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        {/* Opção CPF do Formulário */}
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* NOME ADOLESCENTE */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Nome Completo',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Nome',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppEmail formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* RG BK */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'RG',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'RG',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 11, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Órgao Expedidor',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'ExpedidorRG',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 30, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* CEP */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'CEP',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'CEP',
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
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-8">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* LOGRADOURO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Logradouro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Logradouro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                        {/* Numero Opcional */}
                        <div className="col-12 col-sm-2">
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
                        </div>
                        <div className="col-12 col-sm-2">
                            {/* NUMERO */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'Número',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'Numero',
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
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                        nameField: 'Complemento',
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* BAIRRO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Bairro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Bairro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 70, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Caracter, Inteiro', // Inteiro, Caracter, Senha
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
                            {/* UF */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'UF',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'UF',
                                    errorMessage: '', // Mensagem de Erro personalizada
                                    attributePlaceholder: '', // placeholder 
                                    attributeMinlength: 1, // minlength 
                                    attributeMaxlength: 2, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                    attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                    attributeAutocomplete: 'on', // on, off ]
                                    attributeRequired: true,
                                    attributeReadOnly: false,
                                    attributeDisabled: false,
                                    attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                }}
                            />

                        </div>
                    </div>

                    <div className="row">
                        <div className="col-12 col-sm-6">
                            {(formData.dropMunicipio) ? (
                                <div>
                                    {/* MUNICÍPIO/SELECT */}
                                    <AppSelect
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Municípoio',
                                            nameField: 'Municipio',
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
                                            nameField: 'Municipio',
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
                        </div>

                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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

                                        {/* UNIDADES */}
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
                                                {units.map(escolaridade_list => (
                                                    <option key={`${escolaridade_list.id}`} value={escolaridade_list.id}>
                                                        {escolaridade_list.unidades_nome} - {escolaridade_list.unidades_CEP}
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
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <div style={formGroupStyle}>
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
                                        ) : (
                                            <div>
                                                {/* V-2 IDENTIDADE de GENERO */}
                                                {typeof AppSelectCheck !== "undefined" ? (
                                                    <div>
                                                        <label
                                                            htmlFor="dynamicSelect"
                                                            style={formLabelStyle}
                                                            className="form-label"
                                                        >
                                                            Gênero V-2
                                                            <strong style={requiredField}>*</strong>
                                                        </label>
                                                        {(isLoading) && (
                                                            <div className="p-2">
                                                                <AppLoading parametros={{
                                                                    tipoLoading: "progress",
                                                                    carregando: isLoading
                                                                }} />
                                                            </div>
                                                        )}
                                                        {(!isLoading) && (
                                                            <AppSelectCheck
                                                                parametros={parametros}
                                                                formData={formData}
                                                                setFormData={setFormData}
                                                                fieldAttributes={{
                                                                    attributeOrigemForm: `origemForm`,
                                                                    labelField: 'Gênero V-2',
                                                                    nameField: 'genero_identidade',
                                                                    btnCollor: 'danger', // primary, secondary, success, info, warning, danger, light, dark
                                                                    btnOutline: true, // true ou false
                                                                    btnSize: 'sm', // sm, lg
                                                                    btnRounded: '2', // 2, 5, pill
                                                                    errorMessage: '', // Mensagem de Erro personalizada
                                                                    attributeFieldValue: 'genero', // O que será capturado da API
                                                                    attributeFieldLabel: 'genero', // O que será exibido no form = AppSelect
                                                                    attributeRequired: true,
                                                                    attributeDisabled: false,
                                                                    objetoArrayKey: [
                                                                        { key: '1', value: 'Opção 1' },
                                                                        { key: '2', value: 'Opção 2' },
                                                                        { key: '3', value: 'Opção 3' },
                                                                        { key: '4', value: 'Opção 4' }
                                                                    ],
                                                                    api_get: `${api_get_genero}`,
                                                                    api_post: `${api_post_genero_cadastrar}`,
                                                                    api_filter: `${api_post_genero_filtrar}`,
                                                                }}
                                                            />
                                                        )}
                                                    </div>
                                                ) : (
                                                    <div>
                                                        <p className="text-danger">AppSelectCheck não lacançado.</p>
                                                    </div>
                                                )}
                                            </div>
                                        )
                                    )}
                                </div>
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="sexo_biologico_id"
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
                                                    {listaSexos.find(item => item.id == formData.sexo_biologico_id)?.sexo_biologico || (
                                                        <span className="text-muted">Não informado</span>
                                                    )}
                                                </div>
                                            ) : (
                                                <select
                                                    data-api={`filtro-${origemForm}`}
                                                    id="sexo_biologico_id"
                                                    name="sexo_biologico_id"
                                                    value={formData.sexo_biologico_id || ''}
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

        {/* RENDER ATUALIZAR BK*/ }
        const renderAtualizar = () => {
            return (
                <div className="border border-top-0 mb-4 p-4">
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>

                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Certidão',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Certidao',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 16, // minlength 
                                        attributeMaxlength: 40, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Certdao: 39
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: 'Certidao', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* REGISTRO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Registro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'NumRegistro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 7, // minlength 
                                        attributeMaxlength: 8, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Certdao: 39
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* ZONA */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Zona',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Zona',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 2, // minlength 
                                        attributeMaxlength: 3, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Certdao: 39
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* FOLHA */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Folha',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Folha',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 4, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* LIVRO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Livro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Livro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 5, // minlength 
                                        attributeMaxlength: 6, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* CIRCUNSCRIÇÃO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Circunscrição',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Circunscricao',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 6, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                    }}
                                />
                            </form>
                        </div>
                    </div>
                    <div className="row">
                        {/* Opção CPF do Formulário */}
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* NOME ADOLESCENTE */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Nome Completo',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Nome',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppEmail formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                        {/* CPF / ATUALIZAR */}
                        <div className="col-12 col-sm-3">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                        attributeMaxlength: 14, // maxlength - Telefone: 14, CPF: 15, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                        <div className="col-12 col-sm-3">
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* RG BK */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'RG',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'RG',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 11, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                        <div className="col-12 col-sm-3">
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Órgao Expedidor',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'ExpedidorRG',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 3, // minlength 
                                        attributeMaxlength: 30, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                        <div className="col-12 col-sm-3">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* CEP */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'CEP',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'CEP',
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
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-8">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* LOGRADOURO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Logradouro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Logradouro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
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
                        {/* Numero Opcional */}
                        <div className="col-12 col-sm-2">
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
                        </div>
                        <div className="col-12 col-sm-2">
                            {/* NUMERO */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'Número',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'Numero',
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
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                        nameField: 'Complemento',
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
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                {/* BAIRRO */}
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Bairro',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Bairro',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 70, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                        attributePattern: 'Caracter, Inteiro', // Inteiro, Caracter, Senha
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
                            {/* UF */}
                            <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                fieldAttributes={{
                                    attributeOrigemForm: `${origemForm}`,
                                    labelField: 'UF',
                                    labelColor: 'black', // gray, red, black,
                                    nameField: 'UF',
                                    errorMessage: '', // Mensagem de Erro personalizada
                                    attributePlaceholder: '', // placeholder 
                                    attributeMinlength: 1, // minlength 
                                    attributeMaxlength: 2, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22
                                    attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                    attributeAutocomplete: 'on', // on, off ]
                                    attributeRequired: true,
                                    attributeReadOnly: false,
                                    attributeDisabled: false,
                                    attributeMask: '', // CPF, Telefone, CEP, SEI, Processo.
                                }}
                            />

                        </div>
                    </div>

                    <div className="row">

                        <div className="col-12 col-sm-6">
                            {(formData.dropMunicipio) ? (
                                <div>
                                    {/* MUNICÍPIO/SELECT */}
                                    <AppSelect
                                        parametros={parametros}
                                        formData={formData}
                                        setFormData={setFormData}
                                        fieldAttributes={{
                                            attributeOrigemForm: `${origemForm}`,
                                            labelField: 'Municípoio',
                                            nameField: 'Municipio',
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
                                            nameField: 'Municipio',
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
                        </div>

                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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

                                        {/* UNIDADES */}
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
                                                {units.map(escolaridade_list => (
                                                    <option key={`${escolaridade_list.id}`} value={escolaridade_list.id}>
                                                        {escolaridade_list.unidades_nome} - {escolaridade_list.unidades_CEP}
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
                                submitAllForms(`filtro-${origemForm}`);
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
                                            {/* V-2 IDENTIDADE de GENERO */}
                                            {typeof AppSelectCheck !== "undefined" ? (
                                                <div style={formGroupStyle}>
                                                    <label
                                                        htmlFor="dynamicSelect"
                                                        style={formLabelStyle}
                                                        className="form-label"
                                                    >
                                                        Gênero V-2
                                                        <strong style={requiredField}>*</strong>
                                                    </label>
                                                    {(isLoading) && (
                                                        <div className="p-2">
                                                            <AppLoading parametros={{
                                                                tipoLoading: "progress",
                                                                carregando: isLoading
                                                            }} />
                                                        </div>
                                                    )}
                                                    {(!isLoading) && (
                                                        <AppSelectCheck
                                                            parametros={parametros}
                                                            formData={formData}
                                                            setFormData={setFormData}
                                                            fieldAttributes={{
                                                                attributeOrigemForm: `origemForm`,
                                                                labelField: 'Gênero V-2',
                                                                nameField: 'genero_identidade',
                                                                btnCollor: '', // primary, secondary, success, info, warning, danger, light, dark
                                                                btnOutline: true, // true ou false
                                                                btnSize: 'sm', // sm, lg
                                                                btnRounded: '2', // 2, 5, pill
                                                                errorMessage: '', // Mensagem de Erro personalizada
                                                                attributeFieldValue: 'genero', // O que será capturado da API
                                                                attributeFieldLabel: 'genero', // O que será exibido no form = AppSelect
                                                                attributeRequired: true,
                                                                attributeDisabled: false,
                                                                objetoArrayKey: [
                                                                    { key: '1', value: 'Opção 1' },
                                                                    { key: '2', value: 'Opção 2' },
                                                                    { key: '3', value: 'Opção 3' },
                                                                    { key: '4', value: 'Opção 4' }
                                                                ],
                                                                api_get: `${api_get_genero}`,
                                                                api_post: `${api_post_genero_cadastrar}`,
                                                                api_filter: `${api_post_genero_filtrar}`,
                                                            }}
                                                        />
                                                    )}
                                                </div>
                                            ) : (
                                                <div>
                                                    <p className="text-danger">AppSelectCheck não lacançado.</p>
                                                </div>
                                            )}
                                        </div>
                                    )
                                )}
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <div style={formGroupStyle}>
                                    <label
                                        htmlFor="sexo_biologico_id"
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
                                                    {listaSexos.find(item => item.id == formData.sexo_biologico_id)?.sexo_biologico || (
                                                        <span className="text-muted">Não informado</span>
                                                    )}
                                                </div>
                                            ) : (
                                                <select
                                                    data-api={`filtro-${origemForm}`}
                                                    id="sexo_biologico_id"
                                                    name="sexo_biologico_id"
                                                    value={formData.sexo_biologico_id || ''}
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
                </div >
            )
        }

        {/* RENDER DADOS ESCOLARES - DECREPTO*/ }
        const renderEscolaridade = () => {
            return (
                <div className="border border-top-0 mb-4 p-4">
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
                            {/* ESCOLARIDADE */}
                            <div style={formGroupStyle}>
                                <label htmlFor="TipoEscola" style={formLabelStyle} className="form-label">
                                    Escolaridade - v3<strong style={requiredField}>*</strong>
                                </label>
                                <form className="was-validated" onSubmit={(e) => {
                                    e.preventDefault();
                                    submitAllForms(`filtro-${origemForm}`);
                                }}>
                                    {/* ESCOLARIDADE */}
                                    {typeof AppSelectRadio !== "undefined" ? (
                                        <div>
                                            {(isLoading) && (
                                                <div className="p-2">
                                                    <AppLoading parametros={{
                                                        tipoLoading: "progress",
                                                        carregando: isLoading
                                                    }} />
                                                </div>
                                            )}
                                            {(!isLoading) && (
                                                <AppSelectRadio
                                                    parametros={parametros}
                                                    formData={formData}
                                                    setFormData={setFormData}
                                                    fieldAttributes={{
                                                        attributeOrigemForm: `origemForm`,
                                                        labelField: 'Escolaridade',
                                                        nameField: 'Escolaridade',
                                                        btnCollor: '', // primary, secondary, success, info, warning, danger, light, dark
                                                        btnOutline: true, // true ou false
                                                        btnSize: 'sm', // sm, lg
                                                        btnRounded: '2', // 2, 5, pill
                                                        errorMessage: '', // Mensagem de Erro personalizada
                                                        attributeFieldValue: 'escolaridade', // O que será capturado da API
                                                        attributeFieldLabel: 'escolaridade', // O que será exibido no form = nameField
                                                        attributeRequired: true,
                                                        attributeDisabled: false,
                                                        objetoArrayKey: [
                                                            { key: '1', value: 'Opção 1' },
                                                            { key: '2', value: 'Opção 2' },
                                                            { key: '3', value: 'Opção 3' },
                                                            { key: '4', value: 'Opção 4' }
                                                        ],
                                                        api_get: `${api_get_escolaridade}`,
                                                        api_post: `${api_post_escolaridade_cadastrar}`,
                                                        api_filter: `${api_post_escolaridade_filtrar}`,
                                                    }}
                                                />
                                            )}
                                            {/* api_get: {api_get_escolaridade},*/}
                                            {/* api_post: {api_post_escolaridade_cadastrar},*/}
                                            {/* api_filter: {api_post_escolaridade_filtrar},*/}
                                        </div>
                                    ) : (
                                        <div>
                                            <p className="text-danger">AppSelectRadio não lacançado.</p>
                                        </div>
                                    )}

                                </form>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppNomeEscola formData={formData} setFormData={setFormData} parametros={parametros} />
                            </form>
                        </div>
                        <div className="col-12 col-sm-6">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
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
            );
        }

        {/* RENDER RESPONSÁVEL */ }
        const renderDadosResponsavel = () => {
            return (
                <div className="border border-top-0 mb-4 p-4">
                    <div className="row">
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Nome',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Responsavel_Nome',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 150, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Processo: 41, Certidão: 38
                                        attributePattern: 'Caracter', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: '', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'CPF',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Responsavel_CPF',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 14, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Processo: 41, Certidão: 38
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: 'CPF', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                        <div className="col-12 col-sm-4">
                            <form className="was-validated" onSubmit={(e) => {
                                e.preventDefault();
                                submitAllForms(`filtro-${origemForm}`);
                            }}>
                                <AppText parametros={parametros} formData={formData} setFormData={setFormData}
                                    fieldAttributes={{
                                        attributeOrigemForm: `${origemForm}`,
                                        labelField: 'Celular',
                                        labelColor: 'black', // gray, red, black,
                                        nameField: 'Responsavel_TelefoneMovel',
                                        errorMessage: '', // Mensagem de Erro personalizada
                                        attributePlaceholder: '', // placeholder 
                                        attributeMinlength: 4, // minlength 
                                        attributeMaxlength: 14, // maxlength - Telefone: 14, CPF: 14, CEP: 9, Processo Judicial: 20, Processo SEI: 22, Processo: 41, Certidão: 38
                                        attributePattern: 'Inteiro', // Inteiro, Caracter, Senha
                                        attributeAutocomplete: 'on', // on, off ]
                                        attributeRequired: true,
                                        attributeReadOnly: false,
                                        attributeDisabled: false,
                                        attributeMask: 'Telefone', // CPF, Telefone, CEP, SEI, Processo, Certidao.
                                    }}
                                />
                            </form>
                        </div>
                    </div>
                </div>
            );
        }

        {/* RENDER COMANDOS E TERMO */ }
        const renderComandosTermo = () => {
            return (
                <div className="p-4">
                    {(
                        isChoiceMade &&
                        checkWordInArray(getURI, 'drupal') ||
                        isChoiceMade &&
                        checkWordInArray(getURI, 'cadastrar')
                    ) && (
                            <div className="mt-2">
                                <AppTermosUso
                                    parametros={parametros}
                                    formData={formData}
                                    setFormData={setFormData}
                                    setTermoAceito={setTermoAceito}
                                />
                            </div>
                        )}
                    <div className="d-flex justify-content-start m-2">
                        {(
                            isChoiceMade &&
                            checkWordInArray(getURI, 'drupal') ||
                            isChoiceMade &&
                            checkWordInArray(getURI, 'cadastrar')
                        ) && (
                                // APENAS UM FORMULÁRIO para o botão Salvar
                                <form
                                    className="was-validated"
                                    onSubmit={(e) => {
                                        e.preventDefault();
                                        // console.log("Form submitted - attempting to call submitAllForms");
                                        if (!termoAceito) {
                                            // console.log("Termo não aceito, retornando");
                                            return;
                                        }
                                        submitAllForms(`filtro-adolescente`);
                                    }}
                                >
                                    <div className="m-1">
                                        {/* BOTÃO DE SALVAR\ */}
                                        <button
                                            type="submit"
                                            className="btn btn-primary me-2"
                                            disabled={!checkWordInArray(getURI, 'atualizar') && !termoAceito}
                                        >
                                            Salvar
                                        </button>
                                    </div>
                                </form>
                            )}

                        {/* Botão Atualizar */}
                        {(
                            !isChoiceMade &&
                            checkWordInArray(getURI, 'atualizar')
                        ) && (
                                <form
                                    className="was-validated"
                                    onSubmit={(e) => {
                                        e.preventDefault();
                                        // console.log("Atualizar form submitted");
                                        submitAllForms(`filtro-${origemForm}`);
                                    }}
                                >
                                    <div className="m-1">
                                        <button
                                            type="submit"
                                            className="btn btn-primary me-2"
                                        >
                                            Atualizar
                                        </button>
                                    </div>
                                </form>
                            )}
                        {(
                            isChoiceMade &&
                            checkWordInArray(getURI, 'drupal')
                        ) && (
                                <div className="m-1">
                                    <button
                                        type="button"
                                        className="btn btn-secondary me-2"
                                        onClick={() => {
                                            setIsChoiceMade(false);
                                        }}
                                    >
                                        Voltar
                                    </button>
                                </div>
                            )}
                        {(
                            !isChoiceMade &&
                            checkWordInArray(getURI, 'consultar') ||
                            !isChoiceMade &&
                            checkWordInArray(getURI, 'atualizar')
                        ) && (
                                <div className="m-1">
                                    <a className="btn btn-secondary" href={`${base_url}index.php/fia/ptpa/adolescente/endpoint/exibir`} role="button">
                                        Voltar
                                    </a>
                                </div>
                            )}
                    </div>
                </div>
            );
        }

        {/* useEffect PRINCIPAL */ }
        React.useEffect(() => {
            const loadData = async () => {
                // console.log('React.useEffect(() => {}...');
                setIsLoading(true);

                try {
                    // Usar Promise.all para fazer todas as chamadas em paralelo
                    const [adolescentesData, sexosData, unidadesData, generosData, municipiosData] =
                        await Promise.all([
                            fetchAdolescentes(),
                            fetchSexos(),
                            fetchPostUnidade(),
                            fetchGeneros(),
                            fetchMunicipios(),
                            // fetchEscolaridade() - DECREPTO
                        ]);
                    // console.log('Proicessados: fetchAdolescentes(), etchSexos(), fetchPostUnidade(), fetchGeneros(), fetchMunicipios()...');
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setTimeout(() => {
                        setIsLoading(false);
                    }, 500);

                }
            };

            loadData();
        }, []);
        {/* formData.unit, formData.unidade_id */ }
        React.useEffect(() => {
            // Verificar qual campo foi alterado por último e sincronizar o outro
            const lastChanged = formData.unit !== formData.unidade_id ? 'unit' : 'unidade_id';

            if (lastChanged === 'unit' && formData.unit !== formData.unidade_id) {
                setFormData(prevData => ({
                    ...prevData,
                    unidade_id: formData.unit
                }));
            } else if (lastChanged === 'unidade_id' && formData.unidade_id !== formData.unit) {
                setFormData(prevData => ({
                    ...prevData,
                    unit: formData.unidade_id
                }));
            }
        }, [formData.unit, formData.unidade_id]);
        {/* formData.termo */ }
        React.useEffect(() => {
            setTermoAceito(formData.termo || false);
        }, [formData.termo]);
        {/* ListPeriodos - formData.Nascimento */ }
        React.useEffect(() => {
            if (ListPeriodos.length > 0) {
                ListPeriodos.forEach((periodo) => {
                    let data_periodo = periodo.periodo_data_inicio
                        ? periodo.periodo_data_inicio
                        : '';

                    if (formData.Nascimento) {
                        let recebe_data_15_165 = funcCalculateDates(formData.Nascimento);

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
        }, [ListPeriodos, formData.Nascimento]);
        {/* formData.CEP */ }
        React.useEffect(() => {
            // Só executa se houver um CEP válido no formData
            if (formData.CEP && formData.CEP.length >= 8) {
                // console.log("Ordenando unidades pelo CEP:", formData.CEP);
                orderUnitsByCEPProximity(formData.CEP);
            }
        }, [formData.CEP]);
        {/* formData */ }
        React.useEffect(() => {
            // console.log("-------------------");
            // console.log("src/ app/ Views/ fia/ ptpa/ adolescentes/ AppForm.php");
            // console.log("FormData atualizado:", formData);
        }, [formData]);

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
                {/* CADASTRAR E ATUALIZAR */}
                {(checkWordInArray(getURI, 'consultar')) && (
                    <div>
                        {renderConsulta()}
                    </div>
                )}
                <form className="was-validated" onSubmit={(e) => {
                    e.preventDefault();
                    submitAllForms(`filtro-${origemForm}`);
                }}>
                    {formData.id !== 'erro' && (
                        <div>
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
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="UF"
                        name="UF"
                        value={formData.UF || ""}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="DDD"
                        name="DDD"
                        value={formData.DDD || ""}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="GIA"
                        name="GIA"
                        value={formData.GIA || ""}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="IBGE"
                        name="IBGE"
                        value={formData.IBGE || ""}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="Regiao"
                        name="Regiao"
                        value={formData.Regiao || ""}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="SIAFI"
                        name="SIAFI"
                        value={formData.SIAFI || ""}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="token_csrf"
                        name="token_csrf"
                        value={formData.token_csrf || token_csrf}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="AcessoCadastroID"
                        name="AcessoCadastroID"
                        value={'2'}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="acesso_id"
                        name="acesso_id"
                        value="2"
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="perfil_id"
                        name="perfil_id"
                        value={'1'}
                        required
                    />
                    <input
                        data-api={`filtro-${origemForm}`}
                        type="hidden"
                        id="json"
                        name="json"
                        value={formData.json || json}
                        required
                    />
                </form>

                {/* CADASTRAR E ATUALIZAR */}
                {(
                    !isChoiceMade && checkWordInArray(getURI, 'cadastrar') ||
                    !isChoiceMade && checkWordInArray(getURI, 'drupal')
                ) && (
                        // Interface Inicial para escolha de cadastro - apenas aparece para cadastro
                        <div className="choice-container text-center p-5">
                            <h3>Como deseja realizar o cadastro?</h3>
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
                    )}

                {/* CADASTRAR E ATUALIZAR */}
                {(!isChoiceMade && checkWordInArray(getURI, 'atualizar')) && (
                    <div className="b-3 p-3">
                        {/* Formulário de Adolescente (Atualizar) */}
                        <ul className="nav nav-tabs">
                            <li className="nav-item">
                                <a className="nav-link active" aria-current="page" >Dados Pessoais do Adolescente</a>
                            </li>
                        </ul>
                        {renderAtualizar()}

                        {/* Formulário de Escolaridade */}
                        <ul className="nav nav-tabs">
                            <li className="nav-item">
                                <a className="nav-link active" aria-current="page" >Dados Escolares</a>
                            </li>
                        </ul>
                        {renderEscolaridade()}

                        {/* Formulário Dados Responsável */}
                        <ul className="nav nav-tabs">
                            <li className="nav-item">
                                <a className="nav-link active" aria-current="page" >Dados Responsável</a>
                            </li>
                        </ul>
                        {renderDadosResponsavel()}
                    </div>
                )}

                {(isChoiceMade && !checkWordInArray(getURI, 'consultar')) && (
                    <div className="b-3 p-3">
                        <form className="was-validated" onSubmit={(e) => {
                            e.preventDefault();
                            submitAllForms(`filtro-${origemForm}`);
                        }}>
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

                        {/* Formulário de Escolaridade */}
                        <ul className="nav nav-tabs">
                            <li className="nav-item">
                                <a className="nav-link active" aria-current="page" >Dados Escolares</a>
                            </li>
                        </ul>
                        {renderEscolaridade()}

                        {/* Formulário Dados Responsável */}
                        <ul className="nav nav-tabs">
                            <li className="nav-item">
                                <a className="nav-link active" aria-current="page" >Dados Responsável</a>
                            </li>
                        </ul>
                        {renderDadosResponsavel()}
                    </div>
                )}

                {/* RENDER COMANDOS E TERMO */}
                {renderComandosTermo()}


                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message}
                    modalId={`modal_form_adolescente`}
                />
            </>
        );
    };
</script>