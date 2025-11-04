<!-- Modal de Confirmação de Dados da API -->
<div id="modalConfirmacaoAPI" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalConfirmacaoAPI')">&times;</span>
        <h3>Dados Encontrados</h3>
        
        <div class="api-confirm-content">
            <div class="api-data-section">
                <div class="flag-preview-large">
                    <img id="confirm_bandeira" src="" alt="Bandeira">
                </div>
                
                <div class="api-data-grid">
                    <div class="api-data-item">
                        <span class="api-data-label">Capital:</span>
                        <span id="confirm_capital" class="api-data-value"></span>
                    </div>
                    <div class="api-data-item">
                        <span class="api-data-label">Moeda:</span>
                        <span id="confirm_moeda" class="api-data-value"></span>
                    </div>
                    <div class="api-data-item">
                        <span class="api-data-label">Sigla:</span>
                        <span id="confirm_sigla" class="api-data-value"></span>
                    </div>
                    <div class="api-data-item">
                        <span class="api-data-label">Idioma:</span>
                        <span id="confirm_idioma" class="api-data-value"></span>
                    </div>
                </div>
            </div>
            
            <div class="api-confirm-actions">
                <p class="api-confirm-question">Deseja adicionar estas informações ao país?</p>
                <div class="api-buttons">
                    <button type="button" onclick="usarDadosAPI()" class="btn-primary btn-confirm">
                        Sim, usar dados da API
                    </button>
                    <button type="button" onclick="ignorarDadosAPI()" class="btn-secondary btn-cancel">
                        Não, usar apenas meus dados
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>