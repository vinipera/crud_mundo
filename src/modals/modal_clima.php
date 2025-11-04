<!-- Modal para Exibir Clima -->
<div id="modalClima" class="modal">
    <div class="modal-content clima-modal-content">
        <span class="close" onclick="fecharModalClima()">&times;</span>
        
        <div class="clima-body" id="clima-body">
            <!-- Conteúdo do clima será carregado aqui -->
        </div>
    </div>
</div>

<script>
// Função específica para fechar o modal do clima
function fecharModalClima() {
    const modal = document.getElementById('modalClima');
    modal.style.display = 'none';
}

// Fechar modal ao clicar fora do conteúdo
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalClima');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                fecharModalClima();
            }
        });
    }
});
</script>