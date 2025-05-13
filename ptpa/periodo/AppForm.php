<script type="text/babel">

    const AppForm = ({
        parametros = {}
    }) => {

        const funcSemestre = () => {
            // Algoritmo para selecionar aleatoriamente 1 ou 2 no return
            return Math.floor(Math.random() * 2) + 1;
        }


        // Prepara as Variáveis do REACT recebidas pelo BACKEND
        const checkWordInArray = (array, word) => array.includes(word) ? true : false;
        const json = '1';
        const origemForm = parametros.origemForm || '';
        const atualizar_id = parametros.atualizar_id || 'erro';
        const token_csrf = parametros.token_csrf || 'erro';
        const title = parametros.title || '';
        const getURI = parametros.getURI || [];
        const debugMyPrint = parametros.DEBUG_MY_PRINT || false;
        const base_url = parametros.base_url || '';
        const user_session = parametros.user_session.FIA || [];

        //Base APIs
        const api_get_unidade = parametros.api_get_unidade || '';
        const api_post_cadastrar_periodo = parametros.api_post_cadastrar_periodo || '';
        const api_post_atualizar_periodo = parametros.api_post_atualizar_periodo || '';
        const api_post_filtrarassinatura_periodo = parametros.api_post_filtrarassinatura_periodo || '';
        const api_get_atualizar_periodo = parametros.api_get_atualizar_periodo || '';

        // Variáveis da API
        const [periodos, setPeriodos] = React.useState([]);
        const [unidades, setUnidades] = React.useState([]);

        // Variáveis de Update
        const [periodoDataTermino, setPeriodoDataTermino] = React.useState('');
        const [periodoDataInicio, setPeriodoDataInicio] = React.useState('');
        const [periodoNumero, setPeriodoNumero] = React.useState('');
        const [periodoAno, setPeriodoAno] = React.useState('');
        const [periodoCapacidadeVagas, setPeriodoCapacidadeVagas] = React.useState('');

        // Variáveis Uteis
        const [showModal, setShowModal] = React.useState(false);
        const [capacidadeUnidade, setCapacidadeUnidade] = React.useState(false);
        const [dateLimits, setDateLimits] = React.useState({ min: '', max: '' });
        const [qtdCapacidadeUnidade, setQtdCapacidadeUnidade] = React.useState(0);
        const [nomeUnidade, setNomeUnidade] = React.useState('');
        const [unidadeId, setUnidadeId] = React.useState(user_session['UnidadeId'] || '');
        const [salvar, setSalvar] = React.useState(false);

        // Loading
        const [dataLoading, setDataLoading] = React.useState(true);

        // Definindo mensagens do Sistema
        const [showAlert, setShowAlert] = React.useState(false);
        const [alertType, setAlertType] = React.useState('');
        const [alertMessage, setAlertMessage] = React.useState('');
        const [message, setMessage] = React.useState({
            show: false,
            type: null,
            message: null
        });

        const funcAno = () => {
            // Obtém o ano atual
            const anoAtual = new Date().getFullYear();

            // Define o intervalo: 2 anos para o passado até 2 anos para o futuro
            const anoMin = anoAtual - 1;
            const anoMax = anoAtual + 1;

            // Gera um ano aleatório dentro do intervalo definido
            return Math.floor(Math.random() * (anoMax - anoMin + 1)) + anoMin;
        }

        const funcDtInicio = (semestre, ano) => {
            // Data atual
            const hoje = new Date();
            const anoAtual = hoje.getFullYear();

            // Intervalo de anos: 2 anos para o passado até 2 anos no futuro
            const anoMin = anoAtual - 1;
            const anoMax = anoAtual + 1;

            // Seleciona ano aleatório dentro do intervalo
            // const ano = Math.floor(Math.random() * (anoMax - anoMin + 1)) + anoMin;

            let dataInicio, dataFim;

            if (semestre === 1) {
                // Semestre 1: data entre 01/01/AAAA e 30/03/AAAA
                dataInicio = new Date(ano, 0, 1); // 1º de janeiro
                dataFim = new Date(ano, 2, 30);   // 30 de março
            } else {
                // Semestre 2: data entre 01/07/AAAA e 30/09/AAAA
                dataInicio = new Date(ano, 6, 1); // 1º de julho
                dataFim = new Date(ano, 8, 30);   // 30 de setembro
            }

            // Calcula diferença em milissegundos entre início e fim
            const diferenca = dataFim.getTime() - dataInicio.getTime();

            // Seleciona um momento aleatório dentro do intervalo
            const momentoAleatorio = Math.random() * diferenca;

            // Cria a data aleatória
            const dataAleatoria = new Date(dataInicio.getTime() + momentoAleatorio);

            // Formata a data no padrão AAAA-MM-DD
            const ano_formatado = dataAleatoria.getFullYear();
            const mes_formatado = String(dataAleatoria.getMonth() + 1).padStart(2, '0');
            const dia_formatado = String(dataAleatoria.getDate()).padStart(2, '0');

            return `${ano_formatado}-${mes_formatado}-${dia_formatado}`;
        }

        const funcDtFim = (semestre, ano) => {
            // Data atual
            const hoje = new Date();
            const anoAtual = hoje.getFullYear();

            // Intervalo de anos: 2 anos para o passado até 2 anos no futuro
            const anoMin = anoAtual - 1;
            const anoMax = anoAtual + 1;

            // Seleciona ano aleatório dentro do intervalo
            // const ano = Math.floor(Math.random() * (anoMax - anoMin + 1)) + anoMin;

            let dataInicio, dataFim;

            if (semestre === 1) {
                // Semestre 1: data entre 01/04/AAAA e 30/06/AAAA
                dataInicio = new Date(ano, 3, 1); // 1º de abril
                dataFim = new Date(ano, 5, 30);   // 30 de junho
            } else {
                // Semestre 2: data entre 01/10/AAAA e 30/12/AAAA
                dataInicio = new Date(ano, 9, 1); // 1º de outubro
                dataFim = new Date(ano, 11, 30);  // 30 de dezembro
            }

            // Calcula diferença em milissegundos entre início e fim
            const diferenca = dataFim.getTime() - dataInicio.getTime();

            // Seleciona um momento aleatório dentro do intervalo
            const momentoAleatorio = Math.random() * diferenca;

            // Cria a data aleatória
            const dataAleatoria = new Date(dataInicio.getTime() + momentoAleatorio);

            // Formata a data no padrão AAAA-MM-DD
            const ano_formatado = dataAleatoria.getFullYear();
            const mes_formatado = String(dataAleatoria.getMonth() + 1).padStart(2, '0');
            const dia_formatado = String(dataAleatoria.getDate()).padStart(2, '0');

            return `${ano_formatado}-${mes_formatado}-${dia_formatado}`;
        }

        // Função para gerar uma string aleatória com letras maiúsculas e números
        const gerarContagemAleatoria = (comprimento) => {
            const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Letras maiúsculas e números
            let resultado = '';
            for (let i = 0; i < comprimento; i++) {
                resultado += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
            return resultado;
        };

        const randomAno = funcAno();

        // Declare Todos os Campos do Formulário Aqui
        const [formData, setFormData] = React.useState({
            //
            token_csrf: token_csrf,
            json: '1',
            //
            id: null,
            municipio_created_at: null,
            municipio_deleted_at: null,
            municipio_id_importa: null,
            municipio_id_mesoregiao: null,
            municipio_id_regiao: null,
            municipio_id_uf: null,
            municipio_nome: null,
            municipio_nome_mesoregiao: null,
            municipio_nome_regiao: null,
            municipio_nome_uf: null,
            municipio_updated_at: null,
            periodo_ano: debugMyPrint ? randomAno : null,
            periodo_capacidade_vagas: null,
            periodo_created_at: null,
            periodo_data_inicio: debugMyPrint ? funcDtInicio(funcSemestre(), randomAno) : null,
            periodo_data_termino: debugMyPrint ? funcDtFim(funcSemestre(), randomAno) : null,
            periodo_deleted_at: null,
            periodo_numero: debugMyPrint ? funcSemestre() : null,
            periodo_unidade_id: null,
            periodo_updated_at: null,
            unidade_capacidade_atendimento: null,
            unidade_data_cadastramento: null,
            unidade_endereco: null,
            unidade_importa_id: null,
            unidade_municipio_id: null,
            unidade_nome: null,
            unidade_id: null,
            unidade_created_at: null,
            unidade_deleted_at: null,
            unidade_updated_at: null,
            updated_at: null
        });

        // Função que valida se o período informado corresponde ao semestre e ano corretos
        function consolidaPeriodo(periodo, inicioSemestre, fimSemestre, ano) {
            let setAlert = null;

            if (
                (periodo === null || periodo === undefined) &&
                (inicioSemestre === null || inicioSemestre === undefined) &&
                (fimSemestre === null || fimSemestre === undefined) &&
                (ano === null || ano === undefined)
            ) {
                console.log('periodo :: ', periodo);
                console.log('inicioSemestre :: ', inicioSemestre);
                console.log('fimSemestre :: ', fimSemestre);
                console.log('ano :: ', ano);

                return false;
            }

            // Utilitário para padronizar mensagens de erro
            const showError = (message) => {
                console.log(message);
                if (typeof setMessage === 'function') {
                    setMessage({ show: true, type: 'light', message });
                }
                return false;
            };

            // Normalização dos tipos de entrada
            periodo = parseInt(periodo, 10);
            ano = parseInt(ano, 10);

            // Validação do tipo do período (deve ser 1 ou 2)
            if (periodo !== 1 && periodo !== 2) {
                return showError("Período deve ser 1 ou 2");
            }

            // Validação do ano (deve ser no máximo 2 anos no passado ou 1 ano no futuro)
            const anoAtual = new Date().getFullYear();
            if (ano < (anoAtual - 2) || ano > (anoAtual + 1)) {
                return showError("Ano fora do limite permitido (2 anos no passado ou 1 ano no futuro)");
            }

            try {
                // Detectar e normalizar formato da data
                let dataInicio, dataFim;

                // Função auxiliar para interpretar o formato da data
                const parseDataString = (dataStr) => {
                    let partes;

                    // Verifica se é formato ISO (YYYY-MM-DD)
                    if (/^\d{4}-\d{2}-\d{2}$/.test(dataStr)) {
                        const data = new Date(dataStr);
                        return data;
                    }
                    // Verifica se é formato brasileiro com barra (DD/MM/YYYY) 
                    else if (/^\d{2}\/\d{2}\/\d{4}$/.test(dataStr)) {
                        partes = dataStr.split('/');
                        const data = new Date(
                            parseInt(partes[2], 10),
                            parseInt(partes[1], 10) - 1, // Mês em JS é 0-11
                            parseInt(partes[0], 10)
                        );
                        return data;
                    }
                    // Verifica se é formato brasileiro com hífen (DD-MM-YYYY)
                    else if (/^\d{2}-\d{2}-\d{4}$/.test(dataStr)) {
                        partes = dataStr.split('-');
                        const data = new Date(
                            parseInt(partes[2], 10),
                            parseInt(partes[1], 10) - 1, // Mês em JS é 0-11
                            parseInt(partes[0], 10)
                        );
                        return data;
                    }
                    // Formato inválido
                    else {
                        throw new Error(`Formato de data inválido: ${dataStr}`);
                    }
                };

                // Parse das datas
                dataInicio = parseDataString(inicioSemestre);
                dataFim = parseDataString(fimSemestre);

                // Configurar horas específicas
                dataInicio.setHours(0, 0, 0, 0); // 00:00:00
                dataFim.setHours(23, 59, 59, 999); // 23:59:59

                // Verificamos se as datas foram parseadas corretamente
                if (isNaN(dataInicio.getTime()) || isNaN(dataFim.getTime())) {
                    return showError("Data inválida fornecida");
                }

                // Verifica se o ano das datas corresponde ao ano informado
                if (dataInicio.getFullYear() !== ano || dataFim.getFullYear() !== ano) {
                    return showError("Ano não corresponde ao período informado");
                }

                // Define as datas de início e fim do semestre conforme o período
                let inicioSemestrePadrao, fimSemestrePadrao;

                if (periodo === 1) {
                    // 1º semestre: 01/01/AAAA 00:00:00 a 30/06/AAAA 23:59:59
                    inicioSemestrePadrao = new Date(ano, 0, 1, 0, 0, 0); // 1º de Janeiro
                    fimSemestrePadrao = new Date(ano, 5, 30, 23, 59, 59); // 30 de Junho
                } else {
                    // 2º semestre: 01/07/AAAA 00:00:00 a 31/12/AAAA 23:59:59
                    inicioSemestrePadrao = new Date(ano, 6, 1, 0, 0, 0); // 1º de Julho
                    fimSemestrePadrao = new Date(ano, 11, 31, 23, 59, 59); // 31 de Dezembro
                }

                // Verifica se as datas fornecidas estão dentro do período esperado
                if (dataInicio < inicioSemestrePadrao || dataFim > fimSemestrePadrao) {
                    return showError("Período informado não corresponde às datas de início e fim do semestre");
                }

                // Verifica se o período é maior que o semestre pretendido
                if (periodo === 1 && dataFim.getMonth() > 5) {
                    return showError("Período maior que o semestre pretendido");
                }
                if (periodo === 2 && dataInicio.getMonth() < 6) {
                    return showError("Período maior que o semestre pretendido");
                }

                // Se passou por todas as validações, retorna true
                console.log("Ano corresponde ao período informado");
                return true;

            } catch (error) {
                return showError("Erro ao processar as datas: " + error.message);
            }
        }

        // Função para verificar campos obrigatórios
        const validarCamposObrigatorios = (dados, camposObrigatorios) => {
            const camposVazios = Object.keys(camposObrigatorios).filter(campo => !dados[campo]);
            // Retorna o status e os campos vazios
            return {
                isValid: camposVazios.length === 0, // true se não houver campos vazios
                camposVazios, // Lista dos campos que estão vazios
            };
        };

        const submitAllForms = async (filtro) => {
            console.log('submitAllForms...');
            console.log('filtro :: ', filtro);
            const setData = formData;
            let data = '';
            let response = '';

            console.log('formData: ', formData);
            const consolida = consolidaPeriodo(formData.periodo_numero, formData.periodo_data_inicio, formData.periodo_data_termino, formData.periodo_ano);
            if (!consolida) {
                console.log(" Deu RUIM: consolida :: ", consolida);
                return false;
            }

            if (filtro === `filtro-periodo`) {
                console.log(`filtro-periodo ...`);
                fetchPostCadastraPeriodo();
                return true;

            } else if (filtro === `filtro-capacidade`) {
                fetchPostAtualizaPeriodo();
                return true;
            }

        };

        // POST Padrão 
        const fetchPostFiltrarAssinatura = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_filtrarassinatura_periodo, customPage = '') => {
            // console.log('fetchPostFiltrarAssinatura...');
            const url = custonBaseURL + custonApiPostObjeto + customPage;

            const setData = {
                periodo_ano: formData.periodo_ano,
                periodo_numero: formData.periodo_numero,
                unidade_id: formData.unidade_id,
                periodo_capacidade_vagas: formData.periodo_capacidade_vagas,
            };

            console.log('fetchPostFiltrarAssinatura - setData: ', setData);
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                console.log('fetchPostFiltrarAssinatura - data: ', data);
                // return false;
                if (data.result && Array.isArray(data.result.dbResponse) && data.result.dbResponse.length === 0) {
                    console.log('return :: ', false);
                    return false
                } else {
                    console.log('return :: ', true);
                    return true
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
            }
        };

        // POST Padrão 
        const fetchPostCadastraPeriodo = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_cadastrar_periodo, customPage = '') => {
            setMessage({ show: false, type: null, message: null });
            console.log('salvar :: ', salvar);

            if (checkWordInArray(getURI, 'cadastrar')) {
                const assinaturaResult = await fetchPostFiltrarAssinatura();
                console.log('assinaturaResult:', assinaturaResult);
                if (assinaturaResult) {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Já existe um Ano e um Periodo cadastrado para esta Unidade. Só permitido alterar a quantiade de vagas ou a Unidade.'
                    });
                    console.log('Parei pq a unidade já existe.');
                    setSalvar(false);
                    return false;
                }

            } else if (
                checkWordInArray(getURI, 'atualizar') &&
                salvar &&
                formData.periodo_data_termino === periodoDataTermino &&
                formData.periodo_data_inicio === periodoDataInicio &&
                formData.periodo_numero === periodoNumero &&
                formData.periodo_ano === periodoAno &&
                formData.periodo_capacidade_vagas === periodoCapacidadeVagas
            ) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Não foi possível realizar a atualização, pois não houve alteração nos dados.'
                });
                console.log('Parei pq não houve alteração nos dados.');
                setSalvar(false);
                return false;
            }

            const url = custonBaseURL + custonApiPostObjeto + customPage;
            console.log('url :: ', url);
            const setData = formData;

            // Mapeamento dos campos com nomes amigáveis
            const camposObrigatorios = {
                periodo_numero: 'Período',
                periodo_ano: 'Ano',
                periodo_data_inicio: 'Inicio do período',
                periodo_data_termino: 'Término do período'
            };

            // Validação de campos obrigatórios
            const { isValid, camposVazios } = validarCamposObrigatorios(setData, camposObrigatorios);

            if (!isValid) {
                // console.log('isValid :: ', isValid);
                const nomesCamposVazios = camposVazios.map(campo => camposObrigatorios[campo]);
                setMessage({
                    show: true,
                    type: 'light',
                    message: `<b>Os campos</b>: <br/> ${nomesCamposVazios.join("<br/>")}<br/> <b>não podem estar em branco</b>`,
                });
                return false;
            }

            let resposta = '';

            if (checkWordInArray(getURI, 'cadastrar')) {
                resposta = 'Cadastro';
            } else if (checkWordInArray(getURI, 'atualizar')) {
                resposta = 'Atualização';
            } else if (checkWordInArray(getURI, 'consultar')) {
                resposta = 'Consulta';
            } else {
                resposta = 'Ação';
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                console.log('fetchPostCadastraPeriodo - data: ', data);

                if (data.result.insertID && data.result.insertID > 0) {
                    let periodoId = data.result.insertID || 'erro';
                    console.log('fetchPostCadastraPeriodo - data.insertID: ', data.result.insertID);
                    setFormData((prev) => ({
                        ...prev,
                        id: periodoId
                    }));
                } else if (data.result.updateID && data.result.updateID > 0) {
                    let periodoId = data.result.updateID || 'erro';
                    console.log('fetchPostCadastraPeriodo - data.updateID: ', data.result.updateID);
                    setFormData((prev) => ({
                        ...prev,
                        id: periodoId
                    }));
                } else {
                    console.log('fetchPostCadastraPeriodo - PASSOU DIRETO');
                }

                if (data.result && data.result.affectedRows && data.result.affectedRows > 0) {

                    console.log('fetchPostCadastraPeriodo - data: ', data.result);

                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Período cadastrado com sucesso'
                    });
                    setTimeout(() => {
                        setShowModal(true);
                    }, 200);
                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Erro ao cadastrar período'
                    });
                }

            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // POST Padrão 
        const fetchPostAtualizaPeriodo = async (custonBaseURL = base_url, custonApiPostObjeto = api_post_atualizar_periodo, customPage = '') => {
            const url = custonBaseURL + custonApiPostObjeto + customPage;
            const setData = formData;
            console.log('fetchPostAtualizaPeriodo...', setData);

            const verificaDuplicata = await fetchPostFiltrarAssinatura();
            console.log('verificaDuplicata :: ', verificaDuplicata);

            if (verificaDuplicata) {
                setMessage({
                    show: true,
                    type: 'light',
                    message: 'Já existe um Ano e um Periodo cadastrado para esta Unidade. Só permitido alterar a quantiade de vagas ou a Unidade.'
                });
                console.log('Parei pq a unidade já existe.');
                setSalvar(false);
                return false;
            }


            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(setData),
                });
                const data = await response.json();
                // console.log('fetchPost - data: ', data);

                if (data.result && data.result.affectedRows && data.result.affectedRows > 0) {
                    console.log('fetchPostAtualizaPeriodo - data.result.affectedRows: ', data.result.affectedRows);
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Periodo e quantidade de vagas atualizados com sucesso'
                    });
                    redirectTo('index.php/fia/ptpa/periodo/endpoint/exibir');
                    return false;

                } else {
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'Erro ao atualizar período ou quantidade de vagas'
                    });
                }
            } catch (error) {
                console.error('Erro ao enviar dados:', error);
                // Aqui você pode adicionar lógica adicional para exibir o erro para o usuário
                return null;
            }
        };

        // console.log('formData :: ', formData);

        // Função handleFocus no focu

        const sanitizeInput = (name, value) => {
            if (name === 'periodo_capacidade_vagas') {
                return value.replace(/\D/g, '');
            }
            return value;
        };

        const handleFocus = (event) => {
            setMessage({ show: false, type: null, message: null });
            const { name, value } = event.target;
            // console.log('name handleFocus: ', name);
            // console.log('value handleFocus: ', value);

            setFormData((prev) => ({
                ...prev,
                [name]: value
            }));
        };

        const handleChange = (event) => {
            setMessage({ show: false, type: 'light', message: '' });
            const { name, value } = event.target;
            console.log("Campo alterado:", name, "Valor:", value);

            if (name === 'periodo_capacidade_vagas') {
                // Verifica se o último caractere é válido (apenas número, sem espaços)
                const lastChar = value.charAt(value.length - 1);
                const isLastCharValid = /[0-9]/.test(lastChar);

                if (!isLastCharValid) {
                    // Se for inválido, remove o último caractere
                    const newValue = value.slice(0, -1);

                    // Atualiza o valor do campo de forma controlada
                    event.target.value = newValue;

                    // Atualiza o estado do formulário com o valor sem o último caractere
                    setFormData(prev => ({
                        ...prev,
                        [name]: newValue
                    }));

                    // Exibe a mensagem de erro
                    setMessage({
                        show: true,
                        type: 'light',
                        message: 'O Campo deve receber apenas numeros'
                    });

                    // Interrompe o processamento
                    return false;
                } else {
                    // Se for válido, atualiza normalmente
                    setFormData(prev => ({
                        ...prev,
                        [name]: value
                    }));
                }
            }

            // Verifica se é o campo unidade_id
            if (name === "unidade_id") {
                const selectedUnit = unidades.find(unit => unit.id === value);

                setFormData((prev) => ({
                    ...prev,
                    UnidadeId: value,
                    unidades_nome: 'TESTE AQUI'
                }));

                if (selectedUnit) {
                    console.log("selectedUnit :: ", selectedUnit);
                    setFormData((prev) => ({
                        ...prev,
                        unidade_id: selectedUnit.id,
                        unidade_nome: selectedUnit.unidades_nome || prev.unidades_nome, // Garante que unidades_nome não seja null
                        periodo_capacidade_vagas: selectedUnit.unidades_cap_atendimento || prev.periodo_capacidade_vagas, // Garante que capacidade não seja null
                    }));
                    setCapacidadeUnidade(true);
                    return false;
                } else {
                    console.warn("Nenhuma unidade encontrada para o valor selecionado.");
                }
            };
            // Atualiza manualmente o campo periodo_capacidade_vagas
            if (name === "periodo_capacidade_vagas") {
                setFormData((prev) => ({
                    ...prev,
                    periodo_capacidade_vagas: value,
                }));
                return false;
            }

            // Atualiza manualmente o campo unidades_nome
            if (name === "unidades_nome") {
                setFormData((prev) => ({
                    ...prev,
                    unidades_nome: value,
                }));
                return false;
            }

            // console.log("Estado atual do formData:", formData);
            setSalvar(true);
        };

        // Função handleBlur no desfoco
        const handleBlur = (event) => {
            const { name, value } = event.target;
            setMessage({ show: false, type: null, message: null });
        };

        const handleVoltar = () => {
            const confirmaVoltar = window.confirm('Tem certeza que deseja voltar e perder as modificações?');
            if (confirmaVoltar) {
                window.location.href = `${base_url}index.php/fia/ptpa/periodo/endpoint/exibir`;
            }
        };

        // Fetch para obter os Periodos
        const fetchPeriodos = async () => {

            try {
                const uri = base_url + api_get_atualizar_periodo;
                const response = await fetch(uri);
                // console.log("URL da API:", base_url + api_get_atualizar_periodo);
                const data = await response.json();

                if (data.result && data.result.dbResponse && data.result.dbResponse.length > 0) {
                    const periodoData = data.result.dbResponse[0];
                    console.log('fetchPeriodos (src/app/Views/fia/ptpa/periodo/AppForm.php) :: ', periodoData);
                    // Atualizar o estado do formData mantendo as outras variáveis
                    setFormData((prev) => ({
                        ...prev,
                        ...periodoData,
                    }));
                    setCapacidadeUnidade(true);
                    // 
                    setPeriodoDataTermino(periodoData.periodo_data_termino)
                    setPeriodoDataInicio(periodoData.periodo_data_inicio)
                    setPeriodoNumero(periodoData.periodo_numero)
                    setPeriodoAno(periodoData.periodo_ano)
                    setPeriodoCapacidadeVagas(periodoData.periodo_capacidade_vagas)
                }

            } catch (error) {
                console.error('Erro ao carregar Períodos: ' + error.message);
            }
        };

        // Fetch para obter os Unidades
        const fetchUnidades = async () => {

            let url = base_url + api_get_unidade + '?limit=90000';
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
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
            }
        };

        const redirectTo = (url) => {
            const uri = base_url + url;
            setTimeout(() => {
                window.location.href = uri;
            }, 4000);
        };

        // React useEffect
        React.useEffect(() => {
            // console.log('React useEffect - Carregar Dados Iniciais');

            // Função para carregar todos os dados necessários
            const loadData = async () => {
                console.log('loadData iniciando...');

                try {
                    // Chama as funções de fetch para carregar os dados
                    await fetchPeriodos();
                    await fetchUnidades();
                    await consolidaPeriodo(formData.periodo_numero, formData.periodo_data_inicio, formData.periodo_data_termino, formData.periodo_ano);
                } catch (error) {
                    console.error('Erro ao carregar dados:', error);
                } finally {
                    setDataLoading(false);
                }
            };

            loadData();
        }, []);


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
            <div className="ms-3 me-3">
                <div className="card mb-4">
                    <div className="card-body">

                        {/* INÍCIO FORM 1 */}
                        <form className="was-validated" onSubmit={(e) => {
                            e.preventDefault();
                            submitAllForms(`filtro-${origemForm}`, formData);
                        }}>
                            {atualizar_id !== 'erro' && (
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
                            />
                            <input
                                data-api={`filtro-${origemForm}`}
                                type="hidden"
                                id="json"
                                name="json"
                                value={formData.json || json}
                                onChange={handleChange}
                            />
                            {/* Campos do primeiro form */}
                            <div className="row">
                                <div className="col-12 col-sm-6 mb-3">
                                    <AppPeriodo
                                        formData={formData}
                                        setFormData={setFormData}
                                        parametros={parametros}
                                        salvar={salvar}
                                        setSalvar={setSalvar}
                                    />
                                </div>
                                <div className="col-12 col-sm-6 mb-3">
                                    <AppPeriodoAno
                                        formData={formData}
                                        setFormData={setFormData}
                                        parametros={parametros}
                                        salvar={salvar}
                                        setSalvar={setSalvar}
                                    />
                                </div>
                            </div>
                        </form>
                        {/* FIM FORM 1 */}

                        {/* INÍCIO FORM 2 */}
                        <form className="was-validated" onSubmit={(e) => {
                            e.preventDefault();
                            submitAllForms(`filtro-${origemForm}`, formData);
                        }}>
                            {
                                (checkWordInArray(getURI, 'periodo') && checkWordInArray(getURI, 'consultar')
                                ) ? (
                                    <div className="row">
                                        <div className="col-12 col-sm-6 mb-3">
                                            <AppDate
                                                submitAllForms
                                                parametros={parametros}
                                                formData={formData}
                                                setFormData={setFormData}
                                                fieldAttributes={{
                                                    attributeOrigemForm: `${origemForm}`,
                                                    labelField: 'Inicio do Período',
                                                    nameField: 'periodo_data_inicio',
                                                    attributeRequired: false,
                                                    attributeReadOnly: true,
                                                    attributeDisabled: false,
                                                    attributeMask: 'Periodo', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                }} />
                                        </div>

                                        <div className="col-12 col-sm-6 mb-3">
                                            <AppDate
                                                submitAllForms
                                                parametros={parametros}
                                                formData={formData}
                                                setFormData={setFormData}
                                                fieldAttributes={{
                                                    attributeOrigemForm: `${origemForm}`,
                                                    labelField: 'Término do Período',
                                                    nameField: 'periodo_data_termino',
                                                    attributeRequired: false,
                                                    attributeReadOnly: true,
                                                    attributeDisabled: false,
                                                    attributeMask: 'Periodo', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                }} />
                                        </div>
                                    </div>

                                ) : (

                                    <div className="row">
                                        <div className="col-12 col-sm-6 mb-3">
                                            <AppDate
                                                submitAllForms
                                                parametros={parametros}
                                                formData={formData}
                                                setFormData={setFormData}
                                                fieldAttributes={{
                                                    attributeOrigemForm: `${origemForm}`,
                                                    labelField: 'Inicio do Período',
                                                    nameField: 'periodo_data_inicio',
                                                    attributeMax: 'Periodo', // maxDate - Profissional, Periodo.
                                                    attributeRequired: true,
                                                    attributeReadOnly: false,
                                                    attributeDisabled: false,
                                                    attributeMask: 'Periodo', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                }} />
                                        </div>
                                        <div className="col-12 col-sm-6 mb-3">
                                            <AppDate
                                                submitAllForms
                                                parametros={parametros}
                                                formData={formData}
                                                setFormData={setFormData}
                                                fieldAttributes={{
                                                    attributeOrigemForm: `${origemForm}`,
                                                    labelField: 'Término do Período',
                                                    nameField: 'periodo_data_termino',
                                                    attributeMax: 'Periodo', // maxDate - Profissional, Periodo.
                                                    attributeRequired: true,
                                                    attributeReadOnly: false,
                                                    attributeDisabled: false,
                                                    attributeMask: 'Periodo', // Adolescente, Filtro-Unidades, Periodo, Filtro-Periodo, Profissional, Filtro-Profissional.
                                                }} />
                                        </div>
                                    </div>
                                )}
                        </form>
                        {/* FIM FORM 2 */}
                    </div>
                </div>

                {/* INÍCIO FORM 3 */}
                <form className="was-validated" onSubmit={(e) => {
                    e.preventDefault();
                    submitAllForms(`filtro-${origemForm}`, formData);
                }}>
                    <div className="row">
                        <div className="col-12 col-sm-12">
                            <div className="ms-3 me-3">
                                <div className="d-flex justify-content-start gap-2">
                                    <a
                                        className="btn btn-secondary"
                                        href="#"
                                        onClick={(e) => {
                                            e.preventDefault(); // Previne o comportamento de redirecionamento padrão
                                            handleVoltar();
                                        }}
                                        role="button">Voltar
                                    </a>
                                    {!checkWordInArray(getURI, 'consultar') && (
                                        <input
                                            className={`btn btn-primary ${salvar === false ? 'disabled' : ''}`}
                                            type="submit"
                                            value="Salvar"
                                        />
                                    )}
                                    {checkWordInArray(getURI, 'atualizar') && (
                                        <div className="">
                                            {/* Botão para abrir o modal manualmente */}
                                            <button
                                                type="button"
                                                className="btn btn-outline-primary"
                                                onClick={() => {
                                                    // Exibe o modal antes de enviar os dados
                                                    setShowModal(true);
                                                }}
                                            >
                                                Confirmar Vagas da Unidade
                                            </button>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {/* FIM FORM 3 */}

                <div>
                    {/* Modal */}
                    <div
                        className={`modal fade ${showModal ? 'show d-block' : ''}`}
                        tabIndex={-1}
                        style={{ display: showModal ? 'block' : 'none' }}
                        aria-labelledby="confirmaVagasUnidadesLabel"
                        aria-hidden={!showModal}
                    >
                        <div className="modal-dialog modal-dialog-centered">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title">Confirma Utilização de Vagas da Unidade</h5>
                                    <button
                                        type="button"
                                        className="btn-close"
                                        onClick={() => setShowModal(false)}
                                        aria-label="Close"
                                    />
                                </div>
                                <div className="modal-body">
                                    <div>

                                        <div className="d-flex justify-content-center mb-3 mt-3">
                                            <div className="fs-5">
                                                <form className="was-validated" onSubmit={(e) => {
                                                    e.preventDefault();
                                                    submitAllForms(`filtro-capacidade`);
                                                }}>
                                                    <input
                                                        data-api={`filtro-capacidade`}
                                                        type="hidden"
                                                        id="unidade_id"
                                                        name="unidade_id"
                                                        value={formData.unidade_id || ''}
                                                        onChange={handleChange}
                                                        required
                                                    />
                                                    <input
                                                        data-api={`filtro-capacidade`}
                                                        type="hidden"
                                                        id="id"
                                                        name="id"
                                                        value={formData.id || ''}
                                                        onChange={handleChange}
                                                        required
                                                    />
                                                    Deseja que a capacidade total da unidade seja replicada para o período que acaba de ser cadastrado?
                                                    <hr />
                                                    Unidade, Capacidade:
                                                    <select data-api={`filtro-capacidade`}
                                                        id="unidade_id"
                                                        name="unidade_id"
                                                        value={formData.UnidadeId || ''}
                                                        className="form-select"
                                                        onChange={handleChange}
                                                        style={formControlStyle}
                                                        aria-label="Default select"
                                                        required
                                                    >
                                                        <option value="">Seleção Nula</option>
                                                        {unidades.map(unidade => (
                                                            <option key={unidade.id} value={unidade.id}>
                                                                {unidade.unidades_nome}, {unidade.unidades_cap_atendimento}
                                                            </option>
                                                        ))}
                                                    </select>
                                                </form>
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                    {capacidadeUnidade && (
                                        <div>
                                            <form className="was-validated" onSubmit={(e) => {
                                                e.preventDefault();
                                                submitAllForms(`filtro-capacidade`);
                                            }}>
                                                <input
                                                    data-api={`filtro-capacidade`}
                                                    type="hidden"
                                                    id="id"
                                                    name="id"
                                                    value={formData.id || ''}
                                                    onChange={handleChange}
                                                    required
                                                />
                                                <input
                                                    data-api={`filtro-capacidade`}
                                                    type="hidden"
                                                    id="token_csrf"
                                                    name="token_csrf"
                                                    value={formData.token_csrf || token_csrf}
                                                    onChange={handleChange}
                                                    required
                                                />
                                                <input
                                                    data-api={`filtro-capacidade`}
                                                    type="hidden"
                                                    id="unidade_id"
                                                    name="unidade_id"
                                                    value={formData.unidade_id || ''}
                                                    onChange={handleChange}
                                                    required
                                                />
                                                <input
                                                    data-api={`filtro-capacidade`}
                                                    type="hidden"
                                                    id="json"
                                                    name="json"
                                                    value={formData.json || json}
                                                    onChange={handleChange}
                                                    required
                                                />
                                                <div style={formGroupStyle}>
                                                    <label htmlFor="unidades_nome" style={formLabelStyle} className="form-label">
                                                        Nome da Unidade
                                                    </label>
                                                    <input
                                                        type="text"
                                                        id="unidades_nome"
                                                        name="unidades_nome"
                                                        value={formData.unidade_nome || ''}
                                                        className="form-control"
                                                        onChange={handleChange}
                                                        style={formControlStyle}
                                                        disabled
                                                    />
                                                </div>
                                                <div style={formGroupStyle}>
                                                    <label htmlFor="periodo_capacidade_vagas" style={formLabelStyle} className="form-label">
                                                        Quantidade de Vagas
                                                    </label>
                                                    <input
                                                        type="text"
                                                        id="periodo_capacidade_vagas"
                                                        name="periodo_capacidade_vagas"
                                                        value={formData.periodo_capacidade_vagas || ''}
                                                        className="form-control"
                                                        onChange={handleChange}
                                                        style={formControlStyle}
                                                    />
                                                </div>
                                                <div className="mb-3 mt-3">
                                                    <input className="btn btn-outline-primary" type="submit" value="Enviar" />
                                                </div>
                                            </form>
                                        </div>
                                    )}
                                </div>
                                <div className="modal-footer">

                                    <div>
                                        <a className="btn btn-outline-secondary" href={`${base_url}index.php/fia/ptpa/periodo/endpoint/exibir`} role="button">Listar Período</a>
                                    </div>

                                    <button
                                        type="button"
                                        className="btn btn-outline-secondary"
                                        onClick={() => setShowModal(false)}
                                    >Fechar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Exibe o componente de alerta */}
                <AppMessageCard
                    parametros={message}
                    modalId={`modal_form_periodo_unidade`}
                />
            </div>
        );
    };
</script>