<?php
require_once __DIR__ . '/../models/Pais.php';
require_once __DIR__ . '/../api/api_paises.php';

class PaisController {
    private $paisModel;

    public function __construct($pdo) {
        $this->paisModel = new Pais($pdo);
    }

    public function listar() {
        return $this->paisModel->listarTodos();
    }

    public function criar($dados) {
        // Validações
        if (empty($dados['nome_pais']) || empty($dados['continente']) || empty($dados['populacao_pais'])) {
            return false;
        }

        return $this->paisModel->inserir($dados);
    }

    public function atualizar($id, $dados) {
        // Validações
        if (empty($dados['nome_pais']) || empty($dados['continente']) || empty($dados['populacao_pais'])) {
            return false;
        }

        return $this->paisModel->atualizar($id, $dados);
    }

    public function excluir($id) {
        return $this->paisModel->excluir($id);
    }

    public function importarDaAPI() {
        // Verificar se já existem países
        if ($this->paisModel->contarTotal() > 0) {
            return ['success' => false, 'message' => '❌ Importação cancelada: Já existem países cadastrados no sistema.'];
        }

        return importarPaisesAPI($this->paisModel);
    }

    public function contarTotal() {
        return $this->paisModel->contarTotal();
    }
}
?>
