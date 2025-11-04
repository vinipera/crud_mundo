// assets/js/scripts.js

// Função de pesquisa dinâmica
function filtrarTabelas() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const searchInfo = document.getElementById('searchInfo');
    
    // Atualizar informação da pesquisa
    if (searchTerm === '') {
        searchInfo.textContent = 'Digite para pesquisar em países e cidades';
        // Mostrar todas as linhas quando não há pesquisa
        document.querySelectorAll('.pais-row, .cidade-row').forEach(row => {
            row.style.display = '';
        });
        document.getElementById('noResultsPaises').style.display = 'none';
        document.getElementById('noResultsCidades').style.display = 'none';
        return;
    } else {
        searchInfo.textContent = `Pesquisando por: "${searchTerm}"`;
    }
    
    // Filtrar países
    const paisRows = document.querySelectorAll('.pais-row');
    let paisesEncontrados = 0;
    
    paisRows.forEach(row => {
        const nome = row.getAttribute('data-nome') || '';
        const continente = row.getAttribute('data-continente') || '';
        
        if (nome.includes(searchTerm) || continente.includes(searchTerm)) {
            row.style.display = '';
            paisesEncontrados++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Mostrar/ocultar mensagem de nenhum resultado para países
    const noResultsPaises = document.getElementById('noResultsPaises');
    noResultsPaises.style.display = (paisesEncontrados === 0 && searchTerm !== '') ? 'block' : 'none';
    
    // Filtrar cidades
    const cidadeRows = document.querySelectorAll('.cidade-row');
    let cidadesEncontradas = 0;
    
    cidadeRows.forEach(row => {
        const nome = row.getAttribute('data-nome') || '';
        const pais = row.getAttribute('data-pais') || '';
        
        if (nome.includes(searchTerm) || pais.includes(searchTerm)) {
            row.style.display = '';
            cidadesEncontradas++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Mostrar/ocultar mensagem de nenhum resultado para cidades
    const noResultsCidades = document.getElementById('noResultsCidades');
    noResultsCidades.style.display = (cidadesEncontradas === 0 && searchTerm !== '') ? 'block' : 'none';
}

// Funções para abrir e fechar modais
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Previne scroll da página
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restaura scroll
    }
}

function openEditCidadeModal(id, nome, populacao, idPais) {
    console.log('=== DEBUG openEditCidadeModal ===');
    console.log('Parâmetros recebidos:', { id, nome, populacao, idPais });
    
    // Preencher campos básicos
    document.getElementById('edit_id_cidade').value = id;
    document.getElementById('edit_nome_cidade').value = nome;
    document.getElementById('edit_populacao_cidade').value = populacao;
    
    // DEBUG: Verificar elemento select
    const paisSelect = document.getElementById('edit_id_pais');
    console.log('Elemento select encontrado:', paisSelect);
    
    if (paisSelect) {
        console.log('Opções disponíveis no select:');
        for (let i = 0; i < paisSelect.options.length; i++) {
            console.log(`Opção ${i}: valor="${paisSelect.options[i].value}", texto="${paisSelect.options[i].text}"`);
        }
        
        console.log('Tentando definir valor para:', idPais);
        paisSelect.value = idPais;
        console.log('Valor definido. Valor atual do select:', paisSelect.value);
        
        // Verificar se o valor foi definido corretamente
        if (paisSelect.value != idPais) {
            console.log('❌ ERRO: Valor não foi definido corretamente!');
            console.log('Tipo de idPais:', typeof idPais);
            console.log('Tipo do valor do select:', typeof paisSelect.value);
        } else {
            console.log('✅ Valor definido com sucesso!');
        }
    } else {
        console.log('❌ ERRO: Elemento edit_id_pais não encontrado!');
    }
    
    openModal('modalCidade');
}

function openEditPaisModal(id, nome, continente, populacao, capital, moeda, sigla, idioma, bandeira = '') {
    document.getElementById('edit_id_pais').value = id;
    document.getElementById('edit_nome_pais').value = nome;
    
    // Garantir que o continente seja selecionado corretamente
    const selectContinente = document.getElementById('edit_continente');
    selectContinente.value = continente;
    
    // Se não encontrou correspondência, definir como "Desconhecido"
    if (!selectContinente.value) {
        selectContinente.value = 'Desconhecido';
    }
    
    document.getElementById('edit_populacao_pais').value = populacao;
    document.getElementById('edit_capital').value = capital || '';
    document.getElementById('edit_moeda').value = moeda || '';
    document.getElementById('edit_sigla').value = sigla || '';
    document.getElementById('edit_idioma').value = idioma || '';
    document.getElementById('edit_bandeira').value = bandeira || '';
    
    openModal('modalPais');
}

// Função para obter e exibir clima em modal
function obterClimaModal(idCidade) {
    const modal = document.getElementById('modalClima');
    const climaBody = document.getElementById('clima-body');
    
    // Mostrar modal com loading
    climaBody.innerHTML = '<div class="loading-clima">🌤️ Buscando dados climáticos...</div>';
    modal.style.display = 'block';
    
    // CORREÇÃO: Passar ambos os parâmetros corretamente
    fetch(`?get_clima=true&id_cidade=${idCidade}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                climaBody.innerHTML = `
                    <div class="weather-main">
                        <img src="https://openweathermap.org/img/wn/${data.clima.icone}@2x.png" alt="${data.clima.descricao}" class="weather-icon-large">
                        <div>
                            <div class="weather-temp">${data.clima.temperatura}°C</div>
                            <div class="weather-desc">${data.clima.descricao}</div>
                            <div class="weather-location">
                                ${data.cidade}, ${data.pais}
                            </div>
                        </div>
                    </div>
                    
                    <div class="weather-details-grid">
                        <div class="weather-detail-item">
                            <span class="weather-detail-label">Sensação Térmica</span>
                            <span class="weather-detail-value">${data.clima.sensacao}°C</span>
                        </div>
                        <div class="weather-detail-item">
                            <span class="weather-detail-label">Umidade</span>
                            <span class="weather-detail-value">${data.clima.umidade}%</span>
                        </div>
                        <div class="weather-detail-item">
                            <span class="weather-detail-label">Vento</span>
                            <span class="weather-detail-value">${data.clima.vento} km/h</span>
                        </div>
                        <div class="weather-detail-item">
                            <span class="weather-detail-label">Pressão</span>
                            <span class="weather-detail-value">${data.clima.pressao} hPa</span>
                        </div>
                        <div class="weather-detail-item">
                            <span class="weather-detail-label">Mínima</span>
                            <span class="weather-detail-value">${data.clima.temp_min}°C</span>
                        </div>
                        <div class="weather-detail-item">
                            <span class="weather-detail-label">Máxima</span>
                            <span class="weather-detail-value">${data.clima.temp_max}°C</span>
                        </div>
                    </div>
                `;
            } else {
                climaBody.innerHTML = `<div class="error-clima">❌ ${data.message || 'Erro ao buscar dados do clima'}</div>`;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            climaBody.innerHTML = `<div class="error-clima">❌ Erro de conexão ao buscar dados climáticos</div>`;
        });
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Botão Voltar ao Topo
    const btnTopo = document.getElementById('btnTopo');
    
    // Mostrar/ocultar botão baseado no scroll
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            btnTopo.style.display = 'flex';
        } else {
            btnTopo.style.display = 'none';
        }
    });
    
    // Função para voltar ao topo suavemente
    btnTopo.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Limpar pesquisa quando o usuário clicar no X do input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('search', function() {
            if (this.value === '') {
                filtrarTabelas();
            }
        });
    }
    
    // Fechar modal ao clicar fora dele
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target == modal) {
                closeModal(modal.id);
            }
        });
    }

    // Fechar modal com ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (modal.style.display === 'block') {
                    closeModal(modal.id);
                }
            });
        }
    });
});

// Variável global para armazenar dados da API
let dadosAPIEncontrados = null;

// Validar país antes de enviar
async function validarPais(event) {
    event.preventDefault();
    
    const nomePais = document.getElementById('nome_pais').value.trim();
    const btnSubmit = document.getElementById('btnSubmitPais');
    
    if (!nomePais) {
        enviarFormularioPais();
        return false;
    }
    
    // Mostrar loading no botão
    btnSubmit.innerHTML = '🌍 Buscando na API...';
    btnSubmit.disabled = true;
    
    try {
        const response = await fetch(`?buscar_pais_api=true&nome_pais=${encodeURIComponent(nomePais)}`);
        const data = await response.json();
        
        if (data.success && data.dados) {
            dadosAPIEncontrados = data.dados;
            mostrarModalConfirmacaoAPI(data.dados);
        } else {
            // Se não encontrou dados na API, enviar formulário normalmente
            enviarFormularioPais();
        }
    } catch (error) {
        console.error('Erro na busca API:', error);
        enviarFormularioPais();
    } finally {
        // Restaurar botão
        btnSubmit.innerHTML = 'Adicionar País';
        btnSubmit.disabled = false;
    }
    
    return false;
}

// Mostrar modal com dados da API
function mostrarModalConfirmacaoAPI(dados) {
    // Preencher dados no modal
    if (dados.bandeira) {
        document.getElementById('confirm_bandeira').src = dados.bandeira;
        document.getElementById('confirm_bandeira').style.display = 'block';
    } else {
        document.getElementById('confirm_bandeira').style.display = 'none';
    }
    
    document.getElementById('confirm_capital').textContent = dados.capital || 'Não informada';
    document.getElementById('confirm_moeda').textContent = dados.moeda || 'Não informada';
    document.getElementById('confirm_sigla').textContent = dados.sigla || 'Não informada';
    document.getElementById('confirm_idioma').textContent = dados.idioma || 'Não informada';
    
    // Mostrar modal
    openModal('modalConfirmacaoAPI');
}

// Usar dados da API
function usarDadosAPI() {
    if (dadosAPIEncontrados) {
        // Preencher campos do formulário com dados da API
        if (dadosAPIEncontrados.capital) {
            document.getElementById('capital').value = dadosAPIEncontrados.capital;
        }
        if (dadosAPIEncontrados.moeda) {
            document.getElementById('moeda').value = dadosAPIEncontrados.moeda;
        }
        if (dadosAPIEncontrados.sigla) {
            document.getElementById('sigla').value = dadosAPIEncontrados.sigla;
        }
        if (dadosAPIEncontrados.idioma) {
            document.getElementById('idioma').value = dadosAPIEncontrados.idioma;
        }
        if (dadosAPIEncontrados.bandeira) {
            document.getElementById('bandeira').value = dadosAPIEncontrados.bandeira;
        }
        
        // Atualizar continente se for diferente de "Desconhecido"
        if (dadosAPIEncontrados.continente && dadosAPIEncontrados.continente !== 'Desconhecido') {
            document.getElementById('continente').value = dadosAPIEncontrados.continente;
        }
    }
    
    closeModal('modalConfirmacaoAPI');
    enviarFormularioPais();
}

// Ignorar dados da API
function ignorarDadosAPI() {
    closeModal('modalConfirmacaoAPI');
    enviarFormularioPais();
}

// Enviar formulário
function enviarFormularioPais() {
    document.getElementById('formPais').submit();
}