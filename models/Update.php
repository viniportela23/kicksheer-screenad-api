<?php 
// ambiente criado para fazer a busca ao banco voltada ao cardapio

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Busca.php';

class Update extends BaseModel {


	public function UpdateAnunciantes($data, $id_user, $arquivo = null) {
	    // Validação básica dos dados recebidos
	    if (empty($data['id'])) {
	        return [
	            'success' => false,
	            'message' => 'Id é obrigatório'
	        ];
	    }

	    try {
	    	$userModel = new Buscas($this->db);
	    	$ListaAnuncio = $userModel->ConsultaCompletaBaseIDUserIDTabela($data['id'], $id_user, 'anuncios');

	        if (empty($ListaAnuncio['data'])) {
	            return [
	                'success' => false,
	                'message' => 'Anúncio não encontrado ou não pertence ao usuário'
	            ];
	        }

	        if ($data['nome'] === 'null' && $data['tempo'] === 'null' && $data['data_finalizacao'] === 'null') {
	        	$data['nome'] = null;
				$data['tempo'] = null;
				$data['data_finalizacao'] = null;
	        }

	        // Preenche os dados não fornecidos com os valores atuais
	        $fieldsToUpdate = [
	            'nome' => $data['nome'] ?? $ListaAnuncio['data']['nome'],
	            'tempo' => $data['tempo'] ?? $ListaAnuncio['data']['tempo'],
	            'data_finalizacao' => $data['data_finalizacao'] ?? $ListaAnuncio['data']['data_finalizacao'],
	            'status' => $data['status'] ?? $ListaAnuncio['data']['status']
	        ];

	        // Inicia a construção da query
	        $query = "UPDATE anuncios SET 
	                 nome = :nome, 
	                 tempo = :tempo, 
	                 data_modificacao = NOW(), 
	                 data_finalizacao = :data_finalizacao, 
	                 status = :status";
	        
	        // Adiciona o campo arquivo somente se foi fornecido
	        $params = [
	            ':id' => $data['id'],
	            ':nome' => $fieldsToUpdate['nome'],
	            ':tempo' => $fieldsToUpdate['tempo'],
	            ':data_finalizacao' => $fieldsToUpdate['data_finalizacao'],
	            ':status' => $fieldsToUpdate['status'],
	            ':id_user' => $id_user
	        ];
	        
	        if ($arquivo !== null) {
	            $query .= ", arquivo = :arquivo";
	            $params[':arquivo'] = $arquivo;
	        }
	        
	        // Adiciona a condição WHERE
	        $query .= " WHERE id = :id AND id_user = :id_user";
	        
	        $stmt = $this->executeQuery($query, $params);
	        
	        // Verifica se alguma linha foi afetada
	        if ($stmt->rowCount() > 0) {
	            return [
	                'success' => true,
	                'message' => 'Registro atualizado com sucesso',
	                'affected_rows' => $stmt->rowCount()
	            ];
	        } else {
	            return [
	                'success' => false,
	                'message' => 'Nenhum registro foi atualizado'
	            ];
	        }
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => 'Erro ao atualizar registro: ' . $e->getMessage()
	        ];
	    }
	}
	public function UpdateProdCardapio($data, $id_user) {
	    // Validação básica dos dados recebidos
	    if (empty($data['id'])) {
	        return [
	            'success' => false,
	            'message' => 'ID é obrigatório'
	        ];
	    }

	    try {
	    	$userModel = new Buscas($this->db);
	    	$ListaAnuncio = $userModel->ConsultaCompletaBaseIDUserIDTabela($data['id'], $id_user, 'prod_Cardapio');

	        if (empty($ListaAnuncio['data'])) {
	            return [
	                'success' => false,
	                'message' => 'Produto não encontrado ou não pertence ao usuário'
	            ];
	        }

	        if ($data['nome'] === 'null' && $data['preco'] === 'null') {
	        	$data['nome'] = null;
				$data['preco'] = null;
	        }

	        // Preenche os dados não fornecidos com os valores atuais
	        $fieldsToUpdate = [
	            'nome' => $data['nome'] ?? $ListaAnuncio['data']['nome'],
	            'preco' => $data['preco'] ?? $ListaAnuncio['data']['preco'],
	            'status' => $data['status'] ?? $ListaAnuncio['data']['status']
	        ];

	        // Construção da query de atualização
	        $query = "UPDATE prod_Cardapio SET 
	                 nome = :nome, 
	                 preco = :preco, 
	                 data_modificacao = NOW(), 
	                 status = :status
	                 WHERE id = :id AND id_user = :id_user";

	        $params = [
	            ':id' => $data['id'],
	            ':nome' => $fieldsToUpdate['nome'],
	            ':preco' => $fieldsToUpdate['preco'],
	            ':status' => $fieldsToUpdate['status'],
	            ':id_user' => $id_user            
	        ];
	        
	        $stmt = $this->executeQuery($query, $params);
	        
	        // Verifica se alguma linha foi afetada
	        if ($stmt->rowCount() > 0) {
	            return [
	                'success' => true,
	                'message' => 'Registro atualizado com sucesso',
	                'affected_rows' => $stmt->rowCount()
	            ];
	        } else {
	            return [
	                'success' => false,
	                'message' => 'Nenhum registro foi atualizado'
	            ];
	        }
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => 'Erro ao atualizar registro: ' . $e->getMessage()
	        ];
	    }
	}
	public function UpdateLayoutCardapio($data, $id_user, $arquivo = null) {
	    // Validação básica dos dados recebidos
	    if (empty($data['id'])) {
	        return [
	            'success' => false,
	            'message' => 'ID é obrigatório'
	        ];
	    }

	    try {
	    	$userModel = new Buscas($this->db);
	    	$ListaAnuncio = $userModel->ConsultaCompletaBaseIDUserIDTabela($data['id'], $id_user, 'layout_Cardapio');

	        if (empty($ListaAnuncio['data'])) {
	            return [
	                'success' => false,
	                'message' => 'Layout não encontrado ou não pertence ao usuário'
	            ];
	        }

	        if ($data['nome'] === 'null') {$data['nome'] = null;}

	        // Preenche os dados não fornecidos com os valores atuais
	        $fieldsToUpdate = [
	            'nome' => $data['nome'] ?? $ListaAnuncio['data']['nome'],
	            'status' => $data['status'] ?? $ListaAnuncio['data']['status'],
	            'imagem' => ($arquivo !== null) ? $arquivo : $ListaAnuncio['data']['imagem']
	        ];

	        // Inicia a construção da query
	        $query = "UPDATE layout_Cardapio SET 
	                 nome = :nome, 
	                 status = :status, 
	                 data_update = NOW()";
	        
	        $params = [
	            ':id' => $data['id'],
	            ':nome' => $fieldsToUpdate['nome'],
	            ':status' => $fieldsToUpdate['status'],
	            ':id_user' => $id_user
	        ];
	        
	        // Adiciona o campo imagem somente se foi fornecido ou se já existia
	        if ($arquivo !== null) {
	            $query .= ", imagem = :imagem";
	            $params[':imagem'] = $fieldsToUpdate['imagem'];
	        }
	        
	        // Adiciona a condição WHERE
	        $query .= " WHERE id = :id AND id_user = :id_user";
	        
	        $stmt = $this->executeQuery($query, $params);
	        
	        // Verifica se alguma linha foi afetada
	        if ($stmt->rowCount() > 0) {
	            return [
	                'success' => true,
	                'message' => 'Registro atualizado com sucesso',
	                'affected_rows' => $stmt->rowCount()
	            ];
	        } else {
	            return [
	                'success' => false,
	                'message' => 'Nenhum registro foi atualizado'
	            ];
	        }
	    } catch (Exception $e) {
	        return [
	            'success' => false,
	            'message' => 'Erro ao atualizar registro: ' . $e->getMessage()
	        ];
	    }
	}
}
?>
