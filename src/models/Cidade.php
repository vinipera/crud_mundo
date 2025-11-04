<?php
class cidade {
    private $pdo;

    public function __construct($pdo) {
        // recebe conexão com banco de dados
        $this->pdo = $pdo;
    }

    public function listartodas() {
        // busca todas as cidades com nome do país
        $stmt = $this->pdo->query("
            select c.*, p.id_pais as pais_id, p.nome_pais 
            from cidades c 
            join paises p on c.id_pais = p.id_pais 
            order by c.nome_cidade
        ");
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    public function buscarporid($id) {
        // busca cidade específica pelo id
        $stmt = $this->pdo->prepare("
            select c.*, p.nome_pais 
            from cidades c 
            join paises p on c.id_pais = p.id_pais 
            where c.id_cidade = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados) {
        // insere nova cidade no banco
        $stmt = $this->pdo->prepare("insert into cidades (nome_cidade, populacao_cidade, id_pais) values (?, ?, ?)");
        return $stmt->execute([
            $dados['nome_cidade'],
            $dados['populacao_cidade'],
            $dados['id_pais']
        ]);
    }

    public function atualizar($id, $dados) {
        // atualiza dados de uma cidade
        $stmt = $this->pdo->prepare("update cidades set nome_cidade = ?, populacao_cidade = ?, id_pais = ? where id_cidade = ?");
        return $stmt->execute([
            $dados['nome_cidade'],
            $dados['populacao_cidade'],
            $dados['id_pais'],
            $id
        ]);
    }

    public function excluir($id) {
        // exclui cidade do banco
        $stmt = $this->pdo->prepare("delete from cidades where id_cidade = ?");
        return $stmt->execute([$id]);
    }

    public function contartotal() {
        // conta total de cidades cadastradas
        $stmt = $this->pdo->query("select count(*) as total from cidades");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function listarporpais($id_pais) {
        // lista cidades de um país específico
        $stmt = $this->pdo->prepare("select * from cidades where id_pais = ? order by nome_cidade");
        $stmt->execute([$id_pais]);
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }
}
?>