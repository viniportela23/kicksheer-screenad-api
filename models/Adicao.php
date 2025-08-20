<?php 
// ambiente criado para fazer a busca ao banco voltada ao cardapio

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Busca.php';

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
	        // Se o status for 1, verificar se já existe algum layout ativo
	        if ($data['status'] == 1) {
	            $userModel = new Buscas($this->db);
	            $ListaAnuncio = $userModel->ConsultaCompletaBaseIDUser($id_user, 'layout_Cardapio');
	            
	            // Verificar se existe algum layout com status 1
	            $hasActiveLayout = false;
	            foreach ($ListaAnuncio['data'] as $layout) {
	                if ($layout['status'] == 1) {
	                    $hasActiveLayout = true;
	                    break;
	                }
	            }
	            
	            // Se já existe um layout ativo, desativar todos os outros
	            if ($hasActiveLayout) {
	                $updateQuery = "UPDATE layout_Cardapio SET status = 0, data_update = NOW() 
	                               WHERE id_user = :id_user AND status = 1";
	                $this->executeQuery($updateQuery, [':id_user' => $id_user]);
	            }
	        }
	        
	        // Inserir o novo registro
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
