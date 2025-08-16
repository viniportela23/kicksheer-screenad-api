<?php 
// ambiente criado para fazer a busca ao banco voltada ao cardapio

require_once __DIR__ . '/BaseModel.php';

class Buscas extends BaseModel {

	public function ConsultaCompletaBaseIDUser($id_user, $table) {
	    $query = "SELECT * FROM " . $table . " WHERE id_user = :id_user";
	    $stmt = $this->executeQuery($query, [':id_user' => $id_user]);
	    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
	    
	    if (empty($resultados)) {
	        return [
	            'message' => 'Nenhum dado encontrado'
	        ];
	    }
	    
	    return [
	        'data' => $resultados
	    ];
	}

	public function ConsultaCompletaBaseIDUserIDTabela($id_tabela, $id_user, $table) {
	    $query = "SELECT * FROM " . $table . " WHERE id = :id AND id_user = :id_user";
	    $stmt = $this->executeQuery($query, [':id' => $id_tabela, ':id_user' => $id_user]);
	    $resultados = $stmt->fetch(PDO::FETCH_ASSOC);
	    
	    if (empty($resultados)) {
	        return [
	            'message' => 'Nenhum dado encontrado'
	        ];
	    }
	    
	    return [
	        'data' => $resultados
	    ];
	}

	public function ConsultaCompleta($table) {
	    $query = "SELECT * FROM " . $table;
	    $stmt = $this->executeQuery($query);
	    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
	    
	    if (empty($resultados)) {
	        return [
	            'message' => 'Nenhum dado encontrado'
	        ];
	    }
	    
	    return [
	        'data' => $resultados
	    ];
	}
	public function ConsultaCompletaBaseStatus($status, $table) {
	    $query = "SELECT * FROM " . $table . " WHERE status = :status";
	    $stmt = $this->executeQuery($query, [':status' => $status]);
	    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
	    
	    if (empty($resultados)) {
	        return [
	            'message' => 'Nenhum dado encontrado'
	        ];
	    }
	    
	    return [
	        'data' => $resultados
	    ];
	}

}
?>