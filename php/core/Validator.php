<?php
class Validator {
    private $errors = [];
    private $data;
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || empty(trim($this->data[$field]))) {
            $this->errors[$field] = $message ?? "The {$field} field is required";
        }
        return $this;
    }
    
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "The {$field} must be a valid email address";
        }
        return $this;
    }
    
    public function min($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? "The {$field} must be at least {$length} characters";
        }
        return $this;
    }
    
    public function max($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? "The {$field} may not be greater than {$length} characters";
        }
        return $this;
    }
    
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? "The {$field} must be a number";
        }
        return $this;
    }
    
    public function phone($field, $message = null) {
        if (isset($this->data[$field]) && !preg_match("/^[0-9]{10}$/", $this->data[$field])) {
            $this->errors[$field] = $message ?? "The {$field} must be a valid 10-digit phone number";
        }
        return $this;
    }
    
    public function unique($field, $table, $column, $except = null, $message = null) {
        if (!isset($this->data[$field])) return $this;
        
        $db = Database::getInstance()->getConnection();
        $value = $this->data[$field];
        
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = :value";
        $params = [':value' => $value];
        
        if ($except) {
            $sql .= " AND id != :id";
            $params[':id'] = $except;
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        if ($stmt->fetchColumn() > 0) {
            $this->errors[$field] = $message ?? "The {$field} has already been taken";
        }
        
        return $this;
    }
    
    public function passes() {
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
}
?>
