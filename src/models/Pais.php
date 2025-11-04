<?php
class pais {
    private $pdo;

    public function __construct($pdo) {
        // recebe conexão com banco de dados
        $this->pdo = $pdo;
    }

    public function listartodos() {
        // busca todos os países ordenados por nome
        $stmt = $this->pdo->query("select * from paises order by nome_pais");
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    public function buscarporid($id) {
        // busca país específico pelo id
        $stmt = $this->pdo->prepare("select * from paises where id_pais = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        // insere novo país no banco
        $stmt = $this->pdo->prepare("insert into paises (nome_pais, continente, populacao_pais, capital, moeda, bandeira, sigla, idioma) values (?, ?, ?, ?, ?, ?, ?, ?)");
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
        // atualiza dados de um país
        $stmt = $this->pdo->prepare("update paises set nome_pais = ?, continente = ?, populacao_pais = ?, capital = ?, moeda = ?, bandeira = ?, sigla = ?, idioma = ? where id_pais = ?");
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
        // exclui país do banco
        $stmt = $this->pdo->prepare("delete from paises where id_pais = ?");
        return $stmt->execute([$id]);
    }

    public function contartotal() {
        // conta total de países cadastrados
        $stmt = $this->pdo->query("select count(*) as total from paises");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function buscarpornome($nome) {
        // busca país pelo nome exato
        $stmt = $this->pdo->prepare("select * from paises where nome_pais = ? limit 1");
        $stmt->execute([$nome]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>