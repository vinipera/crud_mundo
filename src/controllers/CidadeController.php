<?php
require_once __DIR__ . '/../models/cidade.php';

class cidadecontroller {
    private $cidadeModel;

    public function __construct($pdo) {
        // cria instância do model de cidades
        $this->cidadeModel = new cidade($pdo);
    }

    public function listar() {
        // retorna lista de todas as cidades
        return $this->cidadeModel->listartodas();
    }

    public function criar($dados) {
        // valida se campos obrigatórios estão preenchidos
        if (empty($dados['nome_cidade']) || empty($dados['populacao_cidade']) || empty($dados['id_pais'])) {
            return false;
        }

        // chama model para inserir nova cidade
        return $this->cidadeModel->inserir($dados);
    }

    public function atualizar($id, $dados) {
        // valida dados antes de atualizar
        if (empty($dados['nome_cidade']) || empty($dados['populacao_cidade']) || empty($dados['id_pais'])) {
            return false;
        }

        // chama model para atualizar cidade
        return $this->cidadeModel->atualizar($id, $dados);
    }

    public function excluir($id) {
        // chama model para excluir cidade
        return $this->cidadeModel->excluir($id);
    }

    public function contartotal() {
        // retorna quantidade total de cidades
        return $this->cidadeModel->contartotal();
    }

    public function buscarporid($id) {
        // busca cidade específica pelo id
        return $this->cidadeModel->buscarporid($id);
    }

    public function contarPorContinente() {
        // retorna contagem de cidades por continente
        return $this->cidadeModel->contarPorContinente();
    }   
}
?>