<?php
class Pais {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM paises ORDER BY nome_pais");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM paises WHERE id_pais = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        $stmt = $this->pdo->prepare("INSERT INTO paises (nome_pais, continente, populacao_pais, capital, moeda, bandeira, sigla, idioma) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $dados['nome_pais'],
            $dados['continente'],
            $dados['populacao_pais'],
            $dados['capital'] ?? null,
            $dados['moeda'] ?? null,
            $dados['bandeira'] ?? null,
            $dados['sigla'] ?? null,
            $dados['idioma'] ?? null
        ]);
    }

    public function atualizar($id, $dados) {
        $stmt = $this->pdo->prepare("UPDATE paises SET nome_pais = ?, continente = ?, populacao_pais = ?, capital = ?, moeda = ?, bandeira = ?, sigla = ?, idioma = ? WHERE id_pais = ?");
        return $stmt->execute([
            $dados['nome_pais'],
            $dados['continente'],
            $dados['populacao_pais'],
            $dados['capital'] ?? null,
            $dados['moeda'] ?? null,
            $dados['bandeira'] ?? null,
            $dados['sigla'] ?? null,
            $dados['idioma'] ?? null,
            $id
        ]);
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM paises WHERE id_pais = ?");
        return $stmt->execute([$id]);
    }

    public function contarTotal() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM paises");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function buscarPorNome($nome) {
        $stmt = $this->pdo->prepare("SELECT * FROM paises WHERE nome_pais = ? LIMIT 1");
        $stmt->execute([$nome]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
