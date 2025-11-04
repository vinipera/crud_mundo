<!-- Modal para Editar País -->
<div id="modalPais" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalPais')">&times;</span>
        <h3>Editar País</h3>
        <form method="POST" id="formEditPais">
            <input type="hidden" name="action" value="update_pais">
            <input type="hidden" name="id_pais" id="edit_id_pais">
            
            <label for="edit_nome_pais">Nome:</label>
            <input type="text" id="edit_nome_pais" name="nome_pais" required>
            
            <label for="edit_continente">Continente:</label>
            <select id="edit_continente" name="continente" required>
                <option value="América do Norte">América do Norte</option>
                <option value="América do Sul">América do Sul</option>
                <option value="Europa">Europa</option>
                <option value="Ásia">Ásia</option>
                <option value="Oceania">Oceania</option>
                <option value="Antártica">Antártica</option>
                <option value="África">África</option>
                <option value="América">América</option>
                <option value="América Central">América Central</option>
                <option value="Caribe">Caribe</option>
                <option value="Desconhecido">Desconhecido</option>
            </select>
            
            <label for="edit_populacao_pais">População:</label>
            <input type="number" id="edit_populacao_pais" name="populacao_pais" required>
            
            <label for="edit_capital">Capital:</label>
            <input type="text" id="edit_capital" name="capital">
            
            <label for="edit_moeda">Moeda:</label>
            <input type="text" id="edit_moeda" name="moeda">
            
            <label for="edit_sigla">Sigla:</label>
            <input type="text" id="edit_sigla" name="sigla" maxlength="5">
            
            <label for="edit_idioma">Idioma:</label>
            <input type="text" id="edit_idioma" name="idioma">
            
            <label for="edit_bandeira">URL da Bandeira:</label>
            <input type="text" id="edit_bandeira" name="bandeira">
            
            <button type="submit" class="btn-primary">Atualizar País</button>
        </form>
    </div>
</div>