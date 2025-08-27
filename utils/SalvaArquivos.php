<?php

class SalvaArquivos {
    private $baseUploadDir = 'uploads/';
    private $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'bmp', // Imagens
        'pdf', 'doc', 'docx', 'txt', 'rtf', // Documentos
        'mp4', 'avi', 'mov', 'wmv', 'mkv',  // Vídeos (com verificação adicional)
        'mp3', 'wav', 'ogg'                 // Áudios
    ];
    private $maxFileSize = 10485760; // 10MB

    public function generateRandomName($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }

    /**
     * Verifica se o arquivo pode conter scripts maliciosos
     */
    private function isFileSafe($filePath, $extension) {
        // Verifica arquivos de vídeo (AVI, MP4, etc.) para scripts injetados
        if (in_array(strtolower($extension), ['avi', 'mp4', 'mov', 'wmv', 'mkv'])) {
            return $this->scanVideoForScripts($filePath);
        }
        
        // Verifica se é um arquivo executável disfarçado
        if ($this->isExecutableDisguised($filePath, $extension)) {
            return false;
        }
        
        // Verifica conteúdo do arquivo em busca de scripts PHP/JavaScript
        return $this->scanForMaliciousContent($filePath);
    }

    /**
     * Escaneia arquivos de vídeo em busca de scripts injetados
     */
    private function scanVideoForScripts($filePath) {
        $fileSize = filesize($filePath);
        
        // Arquivos de vídeo muito pequenos podem ser suspeitos
        if ($fileSize < 1024) {
            return false;
        }
        
        // Lê os primeiros e últimos bytes do arquivo em busca de assinaturas suspeitas
        $handle = fopen($filePath, 'rb');
        
        // Verifica os primeiros 2KB
        $header = fread($handle, 2048);
        
        // Verifica os últimos 2KB (para scripts injetados no final)
        fseek($handle, -2048, SEEK_END);
        $footer = fread($handle, 2048);
        
        fclose($handle);
        
        // Procura por padrões suspeitos
        $suspiciousPatterns = [
            '<?php', '<?=', '<? ', 'javascript:', 'eval(', 'base64_decode',
            'document.cookie', 'window.location', 'script', 'onload='
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($header, $pattern) !== false || stripos($footer, $pattern) !== false) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Verifica se é um executável disfarçado
     */
    private function isExecutableDisguised($filePath, $extension) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        
        // Lista de MIME types perigosos
        $dangerousMimes = [
            'application/x-msdownload', // EXE
            'application/x-dosexec',    // EXE
            'application/x-executable',
            'application/x-sharedlib',
            'application/x-shellscript'
        ];
        
        // Se o MIME type não corresponde à extensão ou é perigoso
        $expectedMimes = [
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png', 'gif' => 'image/gif',
            'pdf' => 'application/pdf', 'avi' => 'video/x-msvideo',
            'mp4' => 'video/mp4', 'mp3' => 'audio/mpeg'
        ];
        
        if (in_array($mimeType, $dangerousMimes)) {
            return true;
        }
        
        if (isset($expectedMimes[$extension]) && $expectedMimes[$extension] !== $mimeType) {
            return true;
        }
        
        return false;
    }

    /**
     * Escaneia o conteúdo do arquivo em busca de código malicioso
     */
    private function scanForMaliciousContent($filePath) {
        $content = file_get_contents($filePath);
        
        if ($content === false) {
            return false;
        }
        
        // Padrões maliciosos comuns
        $maliciousPatterns = [
            '/<\?php\s.*?\?>/is',    // Tags PHP
            '/<script.*?>.*?<\/script>/is', // Tags JavaScript
            '/eval\(.*?\)/is',       // Função eval
            '/base64_decode\(.*?\)/is', // Decodificação base64
            '/system\(.*?\)/is',     // Chamadas de sistema
            '/shell_exec\(.*?\)/is', // Execução de shell
            '/passthru\(.*?\)/is',   // Passthru
            '/exec\(.*?\)/is',       // Exec
            '/proc_open\(.*?\)/is',  // Proc open
        ];
        
        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Salva um arquivo no diretório especificado com verificações de segurança
     */
    public function saveFile($file, $folder) {
        // Verifica se o diretório base existe
        if (!file_exists($this->baseUploadDir)) {
            mkdir($this->baseUploadDir, 0777, true);
        }

        // Cria o diretório específico
        $targetDir = $this->baseUploadDir . $folder . '/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Trata upload via formulário ($_FILES)
        if (is_array($file)) {
            // Verifica erros de upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'message' => 'Erro no upload do arquivo'
                ];
            }
            
            // Verifica tamanho do arquivo
            if ($file['size'] > $this->maxFileSize) {
                return [
                    'success' => false,
                    'message' => 'Arquivo muito grande'
                ];
            }
            
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            // Verifica extensão permitida
            if (!in_array($extension, $this->allowedExtensions)) {
                return [
                    'success' => false,
                    'message' => 'Tipo de arquivo não permitido'
                ];
            }
            
            // Gera nome aleatório
            $fileName = $this->generateRandomName();
            $fullPath = $targetDir . $fileName . '.' . $extension;
            
            // Move arquivo para diretório temporário para análise
            $tempPath = $targetDir . 'temp_' . $fileName . '.' . $extension;
            
            if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
                return [
                    'success' => false,
                    'message' => 'Falha ao mover arquivo'
                ];
            }
            
            // Verifica segurança do arquivo
            if (!$this->isFileSafe($tempPath, $extension)) {
                unlink($tempPath); // Remove arquivo suspeito
                return [
                    'success' => false,
                    'message' => 'Arquivo potencialmente perigoso detectado'
                ];
            }
            
            // Move para localização final
            rename($tempPath, $fullPath);
            
            return [
                'success' => true,
                'file_name' => $fileName . '.' . $extension,
                'file_path' => $fullPath
            ];
            
        } else {
            // Trata binário direto (com verificações adicionais)
            $extension = 'dat';
            $fileName = $this->generateRandomName();
            $fullPath = $targetDir . $fileName . '.' . $extension;
            $tempPath = $targetDir . 'temp_' . $fileName . '.' . $extension;
            
            // Salva temporariamente para análise
            if (file_put_contents($tempPath, $file) === false) {
                return [
                    'success' => false,
                    'message' => 'Falha ao salvar arquivo temporário'
                ];
            }
            
            // Verifica segurança
            if (!$this->isFileSafe($tempPath, $extension)) {
                unlink($tempPath);
                return [
                    'success' => false,
                    'message' => 'Conteúdo potencialmente perigoso detectado'
                ];
            }
            
            // Move para localização final
            rename($tempPath, $fullPath);
            
            return [
                'success' => true,
                'file_name' => $fileName . '.' . $extension,
                'file_path' => $fullPath
            ];
        }
    }
}
?>