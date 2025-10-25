// assets/js/scripts.js

// Função de pesquisa dinâmica
function filtrarTabelas() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const searchInfo = document.getElementById('searchInfo');
    
    // Atualizar informação da pesquisa
    if (searchTerm === '') {
        searchInfo.textContent = 'Digite para pesquisar em países e cidades';
    } else {
        searchInfo.textContent = `Pesquisando por: "${searchTerm}"`;
    }
    
    // Filtrar países
    const paisRows = document.querySelectorAll('.pais-row');
    let paisesEncontrados = 0;
    
    paisRows.forEach(row => {
        const nome = row.getAttribute('data-nome');
        const continente = row.getAttribute('data-continente');
        const nomeElement = row.querySelector('.pais-nome');
        const continenteElement = row.querySelector('.pais-continente');
        
        // Destacar texto correspondente
        destacarTexto(nomeElement, searchTerm);
        destacarTexto(continenteElement, searchTerm);
        
        if (nome.includes(searchTerm) || continente.includes(searchTerm)) {
            row.style.display = '';
            paisesEncontrados++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Mostrar/ocultar mensagem de nenhum resultado para países
    const noResultsPaises = document.getElementById('noResultsPaises');
    if (paisesEncontrados === 0 && searchTerm !== '') {
        noResultsPaises.style.display = 'block';
    } else {
        noResultsPaises.style.display = 'none';
    }
    
    // Filtrar cidades
    const cidadeRows = document.querySelectorAll('.cidade-row');
    let cidadesEncontradas = 0;
    
    cidadeRows.forEach(row => {
        const nome = row.getAttribute('data-nome');
        const pais = row.getAttribute('data-pais');
        const nomeElement = row.querySelector('.cidade-nome');
        const paisElement = row.querySelector('.cidade-pais');
        
        // Destacar texto correspondente
        destacarTexto(nomeElement, searchTerm);
        destacarTexto(paisElement, searchTerm);
        
        if (nome.includes(searchTerm) || pais.includes(searchTerm)) {
            row.style.display = '';
            cidadesEncontradas++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Mostrar/ocultar mensagem de nenhum resultado para cidades
    const noResultsCidades = document.getElementById('noResultsCidades');
    if (cidadesEncontradas === 0 && searchTerm !== '') {
        noResultsCidades.style.display = 'block';
    } else {
        noResultsCidades.style.display = 'none';
    }
}

// Função para destacar texto correspondente na pesquisa
function destacarTexto(element, searchTerm) {
    if (!element || searchTerm === '') {
        // Remove qualquer destaque anterior
        element.innerHTML = element.textContent;
        return;
    }
    
    const texto = element.textContent;
    const regex = new RegExp(`(${searchTerm})`, 'gi');
    const novoTexto = texto.replace(regex, '<mark class="search-highlight">$1</mark>');
    element.innerHTML = novoTexto;
}

// Funções de Modal
function openEditPaisModal(id, nome, continente, populacao, capital, moeda, sigla, idioma) {
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
    
    openModal('modalPais');
}

function openEditCidadeModal(id, nome, populacao, idPais) {
    document.getElementById('edit_id_cidade').value = id;
    document.getElementById('edit_nome_cidade').value = nome;
    document.getElementById('edit_populacao_cidade').value = populacao;
    document.getElementById('edit_id_pais').value = idPais;
    
    openModal('modalCidade');
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Função para obter e exibir clima em modal
function obterClimaModal(idCidade) {
    const modal = document.getElementById('modalClima');
    const climaBody = document.getElementById('clima-body');
    
    // Mostrar modal com loading
    climaBody.innerHTML = '<div class="loading-clima">🌤️ Buscando dados climáticos...</div>';
    modal.style.display = 'block';
    
    // Fazer requisição AJAX para obter clima
    fetch(`?get_clima=${idCidade}`)
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
                modal.style.display = 'none';
            }
        });
    }
});
