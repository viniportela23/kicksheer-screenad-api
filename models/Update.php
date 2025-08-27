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

	        if ($data['nome'] === 'null') {
	            $data['nome'] = null;
	            $data['tempo'] = null;
	            $data['cor_titulo'] = null;
	            $data['cor_fundo'] = null;
	            $data['cor_nome'] = null;
	            $data['cor_preco'] = null;
	        }

	        // Preenche os dados não fornecidos com os valores atuais
	        $fieldsToUpdate = [
	            'nome' => $data['nome'] ?? $ListaAnuncio['data']['nome'],
	            'status' => $data['status'] ?? $ListaAnuncio['data']['status'],
	            'tempo' => $data['tempo'] ?? $ListaAnuncio['data']['tempo'],
	            'cor_titulo' => $data['cor_titulo'] ?? $ListaAnuncio['data']['cor_titulo'],
	            'cor_fundo' => $data['cor_fundo'] ?? $ListaAnuncio['data']['cor_fundo'],
	            'cor_nome' => $data['cor_nome'] ?? $ListaAnuncio['data']['cor_nome'],
	            'cor_preco' => $data['cor_preco'] ?? $ListaAnuncio['data']['cor_preco'],            
	            'imagem' => ($arquivo !== null) ? $arquivo : $ListaAnuncio['data']['imagem']
	        ];

	        // Lógica para gerenciar status
	        $statusMudou = isset($data['status']) && $data['status'] != $ListaAnuncio['data']['status'];
	        $statusEstaAtivo = $fieldsToUpdate['status'] == 1;

	        // Se o status mudou para ativo (1), desativa todos os outros layouts
	        if ($statusMudou && $statusEstaAtivo) {
	            // Consulta todos os layouts do usuário
	            $todosLayouts = $userModel->ConsultaCompletaBaseIDUser($id_user, 'layout_Cardapio');
	            
	            if (!empty($todosLayouts['data'])) {
	                // Desativa todos os outros layouts (exceto o atual)
	                $queryDesativar = "UPDATE layout_Cardapio SET status = 0, data_modificacao = NOW() 
	                                  WHERE id_user = :id_user AND id != :id_atual AND status = 1";
	                
	                $paramsDesativar = [
	                    ':id_user' => $id_user,
	                    ':id_atual' => $data['id']
	                ];
	                
	                $this->executeQuery($queryDesativar, $paramsDesativar);
	            }
	        }
	        // Se o status mudou para inativo (0) e era o único ativo, verifica se precisa ativar outro
	        else if ($statusMudou && !$statusEstaAtivo) {
	            // Verifica se este era o único layout ativo
	            $queryVerificaAtivos = "SELECT COUNT(*) as total_ativos FROM layout_Cardapio 
	                                   WHERE id_user = :id_user AND status = 1 AND id != :id_atual";
	            
	            $paramsVerifica = [
	                ':id_user' => $id_user,
	                ':id_atual' => $data['id']
	            ];
	            
	            $stmt = $this->executeQuery($queryVerificaAtivos, $paramsVerifica);
	            $result = $stmt->fetch(PDO::FETCH_ASSOC);
	            
	        }

	        // Inicia a construção da query para atualizar o layout atual
	        $query = "UPDATE layout_Cardapio SET 
	                 nome = :nome, 
	                 status = :status,
	                 tempo = :tempo, 
	                 cor_titulo = :cor_titulo, 
	                 cor_fundo = :cor_fundo, 
	                 cor_nome = :cor_nome, 
	                 cor_preco = :cor_preco, 
	                 data_modificacao = NOW()";
	        
	        $params = [
	            ':id' => $data['id'],
	            ':nome' => $fieldsToUpdate['nome'],
	            ':status' => $fieldsToUpdate['status'],
	            ':tempo' => $fieldsToUpdate['tempo'],
	            ':cor_titulo' => $fieldsToUpdate['cor_titulo'],
	            ':cor_fundo' => $fieldsToUpdate['cor_fundo'],
	            ':cor_nome' => $fieldsToUpdate['cor_nome'],
	            ':cor_preco' => $fieldsToUpdate['cor_preco'],
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
	public function UpdateNotificacoes($data, $id_user) {
	    // Validação básica dos dados recebidos
	    if (empty($data['id'])) {
	        return [
	            'success' => false,
	            'message' => 'ID é obrigatório'
	        ];
	    }

	    try {


	        // Construção da query de atualização
	        $query = "UPDATE notificacoes SET 
	                 data_modificacao = NOW(), 
	                 status = :status
	                 WHERE id = :id AND id_user = :id_user";

	        $params = [
	            ':id' => $data['id'],
	            ':status' => $data['status'],
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
}
?>
