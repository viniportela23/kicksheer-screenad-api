<?php 
// ambiente criado para fazer a busca ao banco voltada ao cardapio

require_once __DIR__ . '/BaseModel.php';

class Adicao extends BaseModel {


	public function InsertAnunciantes($data, $arquivo, $id_user) {
	    // Validação básica dos dados recebidos
	    if (empty($data['nome']) || empty($arquivo)) {
	        return [
	            'success' => false,
	            'message' => 'Nome e arquivo são obrigatórios'
	        ];
	    }

	    try {
	        $query = "INSERT INTO anuncios 
	                 (nome, arquivo, tempo, data_criacao, data_modificacao, data_finalizacao, status, id_user) 
	                 VALUES 
	                 (:nome, :arquivo, :tempo, NOW(), NOW(), :data_finalizacao, :status, :id_user)";
	        
	        $stmt = $this->executeQuery($query, [
	            ':nome' => $data['nome'],
	            ':arquivo' => $arquivo,
	            ':tempo' => $data['tempo'],
	            ':data_finalizacao' => $data['data_finalizacao'],
	            ':status' => $data['status'],
	            ':id_user' => $id_user
	        ]);
	        
	        return [
	            'success' => true,
	            'message' => 'Registro inserido com sucesso',
	            'id' => $this->db->lastInsertId() // Retorna o ID do registro inserido
	        ];
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => 'Erro ao inserir registro: ' . $e->getMessage()
	        ];
	    }
	}
	public function InsertProdCardapio($data, $id_user) {
	    // Validação básica dos dados recebidos
	    if (empty($data['nome']) || empty($data['preco'])) {
	        return [
	            'success' => false,
	            'message' => 'Nome e arquivo são obrigatórios'
	        ];
	    }

	    try {
	        $query = "INSERT INTO prod_Cardapio 
	                 (nome, preco, data_criacao, data_modificacao, status, id_user) 
	                 VALUES 
	                 (:nome, :preco, NOW(), NOW(), :status, :id_user)";
	        
	        $stmt = $this->executeQuery($query, [
	            ':nome' => $data['nome'],
	            ':preco' => $data['preco'],
	            ':status' => $data['status'],
	            ':id_user' => $id_user
	        ]);
	        
	        return [
	            'success' => true,
	            'message' => 'Registro inserido com sucesso',
	            'id' => $this->db->lastInsertId() // Retorna o ID do registro inserido
	        ];
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => 'Erro ao inserir registro: ' . $e->getMessage()
	        ];
	    }
	}

	public function InsertLayoutCardapio($data, $arquivo, $id_user) {
	    // Validação básica dos dados recebidos
	    if (empty($data['nome']) || empty($arquivo)) {
	        return [
	            'success' => false,
	            'message' => 'Nome e arquivo são obrigatórios'
	        ];
	    }

	    try {
	        $query = "INSERT INTO layout_Cardapio 
	                 (nome, imagem, data_criacao, data_update, status, tempo, cor_titulo, cor_fundo, cor_nome, cor_preco, id_user) 
	                 VALUES 
	                 (:nome, :arquivo, NOW(), NOW(), :status, :tempo, :cor_titulo, :cor_fundo, :cor_nome, :cor_preco, :id_user)";
	        
	        $stmt = $this->executeQuery($query, [
	            ':nome' => $data['nome'],
	            ':arquivo' => $arquivo,
	            ':status' => $data['status'],
	            ':tempo' => $data['tempo'],
	            ':cor_titulo' => $data['cor_titulo'],
	            ':cor_fundo' => $data['cor_fundo'],
	            ':cor_nome' => $data['cor_nome'],
	            ':cor_preco' => $data['cor_preco'],
	            ':id_user' => $id_user
	        ]);
	        
	        return [
	            'success' => true,
	            'message' => 'Registro inserido com sucesso',
	            'id' => $this->db->lastInsertId() // Retorna o ID do registro inserido
	        ];
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => 'Erro ao inserir registro: ' . $e->getMessage()
	        ];
	    }
	}


}
?>
