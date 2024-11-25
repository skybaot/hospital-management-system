<?php
class Response {
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public static function success($data = null, $message = 'Success') {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }
    
    public static function error($message = 'An error occurred', $statusCode = 400) {
        self::json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }
    
    public static function notFound($message = 'Resource not found') {
        self::error($message, 404);
    }
    
    public static function validationError($errors) {
        self::json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422);
    }
}
?>
