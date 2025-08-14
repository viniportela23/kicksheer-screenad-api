<?php

class SalvaArquivos {
    private $baseUploadDir = 'uploads/';

    public function generateRandomName($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }

    /**
     * Salva um arquivo no diretório especificado
     * @param mixed $file - O arquivo a ser salvo (pode ser binário ou array do $_FILES)
     * @param string $folder - Subpasta dentro de uploads/
     * @return array - Retorna status e informações do arquivo
     */
    public function saveFile($file, $folder) {
        // Verifica se o diretório base existe, se não, cria
        if (!file_exists($this->baseUploadDir)) {
            mkdir($this->baseUploadDir, 0777, true);
        }

        // Cria o diretório específico se não existir
        $targetDir = $this->baseUploadDir . $folder . '/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Gera um nome aleatório para o arquivo
        $fileName = $this->generateRandomName();
        
        // Verifica se é um array (como do $_FILES) ou dados binários diretos
        if (is_array($file)) {
            // Trata upload via formulário ($_FILES)
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fullPath = $targetDir . $fileName . '.' . $extension;
            
            if (move_uploaded_file($file['tmp_name'], $fullPath)) {
                return [
                    'success' => true,
                    'file_name' => $fileName . '.' . $extension,
                    'file_path' => $fullPath
                ];
            }
        } else {
            // Trata binário direto (assumindo que é uma string)
            $extension = 'dat'; // ou você pode detectar o tipo
            $fullPath = $targetDir . $fileName . '.' . $extension;
            
            if (file_put_contents($fullPath, $file) !== false) {
                return [
                    'success' => true,
                    'file_name' => $fileName . '.' . $extension,
                    'file_path' => $fullPath
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Falha ao salvar o arquivo'
        ];
    }
}
?>