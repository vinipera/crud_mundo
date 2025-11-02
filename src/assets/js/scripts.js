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
