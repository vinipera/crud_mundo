<?php
require_once __DIR__ . '/../models/pais.php';
require_once __DIR__ . '/../api/api_paises.php';

class paiscontroller {
    private $paisModel;

    public function __construct($pdo) {
        // cria instância do model de países
        $this->paisModel = new pais($pdo);
    }

    public function listar() {
        // retorna lista de todos os países
        return $this->paisModel->listartodos();
    }

    public function criar($dados) {
        // valida campos obrigatórios do país
        if (empty($dados['nome_pais']) || empty($dados['continente']) || empty($dados['populacao_pais'])) {
            return false;
        }

        // chama model para inserir novo país
        return $this->paisModel->inserir($dados);
    }

    public function atualizar($id, $dados) {
        // valida dados antes de atualizar país
        if (empty($dados['nome_pais']) || empty($dados['continente']) || empty($dados['populacao_pais'])) {
            return false;
        }

        // chama model para atualizar país
        return $this->paisModel->atualizar($id, $dados);
    }

    public function excluir($id) {
        // chama model para excluir país
        return $this->paisModel->excluir($id);
    }

    public function importardapi() {
        // verifica se já existem países cadastrados
        if ($this->paisModel->contartotal() > 0) {
            return ['success' => false, 'message' => '❌ importação cancelada: já existem países cadastrados no sistema.'];
        }

        // importa países da api externa
        return importarpaisapi($this->paisModel);
    }

    public function contartotal() {
        // retorna quantidade total de países
        return $this->paisModel->contartotal();
    }
}
?>