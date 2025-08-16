<?php 
// ambiente criado para fazer a busca ao banco voltada ao cardapio

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Busca.php';

class Deletar extends BaseModel {


	public function DeletarBaseIDUserIDTabel($id_tabela, $id_user, $table) {

    	$userModel = new Buscas($this->db);
    	$ListaAnuncio = $userModel->ConsultaCompletaBaseIDUserIDTabela($id_tabela, $id_user, $table);
	    
        if (empty($ListaAnuncio['data'])) {
	        return [
	            'success' => false,
	            'message' => 'Nenhum dado encontrado para exclusão'
	        ];
	    }
	    
	    // Se o registro existe, procede com a exclusão
	    $deleteQuery = "DELETE FROM " . $table . " WHERE id = :id AND id_user = :id_user";
	    $deleteStmt = $this->executeQuery($deleteQuery, [':id' => $id_tabela, ':id_user' => $id_user]);
	    
	    // Verifica se a exclusão foi bem-sucedida
	    if ($deleteStmt->rowCount() > 0) {
	        return [
	            'success' => true,
	            'message' => 'Registro deletado com sucesso'
	        ];
	    } else {
	        return [
	            'success' => false,
	            'message' => 'Falha ao deletar o registro'
	        ];
	    }
	}	
}
?>