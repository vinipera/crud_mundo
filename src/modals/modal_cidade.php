<!-- Modal para Editar Cidade -->
<div id="modalCidade" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalCidade')">&times;</span>
        <h3>Editar Cidade</h3>
        <form method="POST" id="formEditCidade">
            <input type="hidden" name="action" value="update_cidade">
            <input type="hidden" name="id_cidade" id="edit_id_cidade">
            
            <label for="edit_nome_cidade">Nome:</label>
            <input type="text" id="edit_nome_cidade" name="nome_cidade" required>
            
            <label for="edit_populacao_cidade">População:</label>
            <input type="number" id="edit_populacao_cidade" name="populacao_cidade" required>
            
            <label for="edit_id_pais">País:</label>
            <select id="edit_id_pais" name="id_pais" required>
                <option value="">Selecione um país</option>
                <?php foreach ($paises as $pais): ?>
                    <option value="<?php echo htmlspecialchars($pais['id_pais']); ?>">
                        <?php echo htmlspecialchars($pais['nome_pais']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="btn-primary">Atualizar Cidade</button>
        </form>
    </div>
</div>
