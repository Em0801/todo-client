<?php

class ApiConnection {
    private $baseUrl;
    private $headers;

    public function __construct($baseUrl = null) {
        // Si no se proporciona una URL base, construirla dinámicamente
        if ($baseUrl === null) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            // Ajustamos la ruta para incluir /api/
            $baseUrl = $protocol . $host . '/API-TO-DO/';
        }
        
        $this->baseUrl = $baseUrl;
        $this->headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: testapikey1234567890'  // Agregamos la API Key que configuramos
        );
    }

    /**
     * Obtiene todas las tareas
     * @return array Lista de tareas
     */
    public function getAllTasks() {
        return $this->makeRequest('GET', 'tasks');
    }

    /**
     * Obtiene una tarea específica por ID
     * @param int $id ID de la tarea
     * @return array Datos de la tarea
     */
    public function getTask($id) {
        return $this->makeRequest('GET', "tasks/$id");
    }

    /**
     * Crea una nueva tarea
     * @param array $taskData Datos de la tarea (title, description, status)
     * @return array Respuesta de la creación
     */
    public function createTask($taskData) {
        return $this->makeRequest('POST', 'tasks', $taskData);
    }

    /**
     * Actualiza una tarea existente
     * @param int $id ID de la tarea
     * @param array $taskData Datos actualizados de la tarea
     * @return array Respuesta de la actualización
     */
    public function updateTask($id, $taskData) {
        return $this->makeRequest('PUT', "tasks/$id", $taskData);
    }

    /**
     * Elimina una tarea
     * @param int $id ID de la tarea
     * @return array Respuesta de la eliminación
     */
    public function deleteTask($id) {
        return $this->makeRequest('DELETE', "tasks/$id");
    }

    /**
     * Realiza la petición HTTP a la API
     * @param string $method Método HTTP
     * @param string $endpoint Endpoint de la API
     * @param array $data Datos a enviar (opcional)
     * @return array Respuesta de la API
     */
    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        
        $curl = curl_init();
        
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        if ($data !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            throw new Exception('Error en la petición cURL: ' . curl_error($curl));
        }

        curl_close($curl);

        if ($response === false) {
            throw new Exception('No se recibió respuesta de la API');
        }

        $result = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error al decodificar JSON: ' . json_last_error_msg() . '. Respuesta: ' . $response);
        }

        if ($httpCode >= 400) {
            throw new Exception('Error en la API (Código ' . $httpCode . '): ' . ($result['message'] ?? $response));
        }

        return $result;
    }
}

// Ejemplo de uso:
/*
try {
    $api = new ApiConnection();
    
    // Obtener todas las tareas
    $tasks = $api->getAllTasks();
    
    // Crear una nueva tarea
    $newTask = $api->createTask([
        'title' => 'Nueva tarea',
        'description' => 'Descripción de la tarea',
        'status' => 'pending'
    ]);
    
    // Actualizar una tarea
    $updatedTask = $api->updateTask(1, [
        'status' => 'completed'
    ]);
    
    // Eliminar una tarea
    $api->deleteTask(1);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/
?>
