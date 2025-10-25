<?php
class Cidade {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodas() {
        $stmt = $this->pdo->query("
            SELECT c.*, p.id_pais as pais_id, p.nome_pais 
            FROM cidades c 
            JOIN paises p ON c.id_pais = p.id_pais 
            ORDER BY c.nome_cidade
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.nome_pais 
            FROM cidades c 
            JOIN paises p ON c.id_pais = p.id_pais 
            WHERE c.id_cidade = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        $stmt = $this->pdo->prepare("INSERT INTO cidades (nome_cidade, populacao_cidade, id_pais) VALUES (?, ?, ?)");
        return $stmt->execute([
            $dados['nome_cidade'],
            $dados['populacao_cidade'],
            $dados['id_pais']
        ]);
    }

    public function atualizar($id, $dados) {
        $stmt = $this->pdo->prepare("UPDATE cidades SET nome_cidade = ?, populacao_cidade = ?, id_pais = ? WHERE id_cidade = ?");
        return $stmt->execute([
            $dados['nome_cidade'],
            $dados['populacao_cidade'],
            $dados['id_pais'],
            $id
        ]);
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cidades WHERE id_cidade = ?");
        return $stmt->execute([$id]);
    }

    public function contarTotal() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM cidades");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function listarPorPais($id_pais) {
        $stmt = $this->pdo->prepare("SELECT * FROM cidades WHERE id_pais = ? ORDER BY nome_cidade");
        $stmt->execute([$id_pais]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
