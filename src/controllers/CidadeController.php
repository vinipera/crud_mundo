<?php
require_once __DIR__ . '/../models/Cidade.php';

class CidadeController {
    private $cidadeModel;

    public function __construct($pdo) {
        $this->cidadeModel = new Cidade($pdo);
    }

    public function listar() {
        return $this->cidadeModel->listarTodas();
    }

    public function criar($dados) {
        // Validações
        if (empty($dados['nome_cidade']) || empty($dados['populacao_cidade']) || empty($dados['id_pais'])) {
            return false;
        }

        return $this->cidadeModel->inserir($dados);
    }

    public function atualizar($id, $dados) {
        // Validações
        if (empty($dados['nome_cidade']) || empty($dados['populacao_cidade']) || empty($dados['id_pais'])) {
            return false;
        }

        return $this->cidadeModel->atualizar($id, $dados);
    }

    public function excluir($id) {
        return $this->cidadeModel->excluir($id);
    }

    public function contarTotal() {
        return $this->cidadeModel->contarTotal();
    }

    public function buscarPorId($id) {
        return $this->cidadeModel->buscarPorId($id);
    }
}
?>
