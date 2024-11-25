<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Validator.php';

class Doctor {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function validateDoctor($data, $isUpdate = false) {
        $validator = new Validator($data);
        
        $validator->required('id', 'Doctor ID is required')
                 ->required('name', 'Name is required')
                 ->required('specialization', 'Specialization is required')
                 ->required('experience', 'Experience is required')
                 ->required('phone', 'Phone number is required')
                 ->required('email', 'Email is required')
                 ->email('email')
                 ->phone('phone')
                 ->numeric('experience');
        
        if (!$isUpdate) {
            $validator->unique('id', 'doctors', 'doctor_id');
        }
        
        return $validator;
    }
    
    public function getAllDoctors() {
        try {
            $stmt = $this->db->query("SELECT * FROM doctors WHERE status = 'Active' ORDER BY name ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching doctors: " . $e->getMessage());
        }
    }
    
    public function getDoctorById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching doctor: " . $e->getMessage());
        }
    }
    
    public function createDoctor($data) {
        $validator = $this->validateDoctor($data);
        
        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }
        
        try {
            $stmt = $this->db->prepare("
                INSERT INTO doctors (doctor_id, name, specialization, experience, phone, email, status)
                VALUES (:id, :name, :specialization, :experience, :phone, :email, 'Active')
            ");
            
            $stmt->execute([
                ':id' => $data['id'],
                ':name' => $data['name'],
                ':specialization' => $data['specialization'],
                ':experience' => $data['experience'],
                ':phone' => $data['phone'],
                ':email' => $data['email']
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error creating doctor: " . $e->getMessage());
        }
    }
    
    public function updateDoctor($id, $data) {
        $validator = $this->validateDoctor($data, true);
        
        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }
        
        try {
            $stmt = $this->db->prepare("
                UPDATE doctors 
                SET name = :name,
                    specialization = :specialization,
                    experience = :experience,
                    phone = :phone,
                    email = :email
                WHERE doctor_id = :id
            ");
            
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':specialization' => $data['specialization'],
                ':experience' => $data['experience'],
                ':phone' => $data['phone'],
                ':email' => $data['email']
            ]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error updating doctor: " . $e->getMessage());
        }
    }
    
    public function deleteDoctor($id) {
        try {
            // Check for active appointments
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM appointments 
                WHERE doctor_id = ? AND status != 'Cancelled'
            ");
            $stmt->execute([$id]);
            
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Cannot delete doctor with active appointments");
            }
            
            // Soft delete
            $stmt = $this->db->prepare("
                UPDATE doctors 
                SET status = 'Inactive' 
                WHERE doctor_id = ?
            ");
            
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error deleting doctor: " . $e->getMessage());
        }
    }
    
    public function searchDoctors($query) {
        try {
            $query = "%$query%";
            $stmt = $this->db->prepare("
                SELECT * FROM doctors 
                WHERE status = 'Active' 
                AND (name LIKE ? OR specialization LIKE ? OR doctor_id LIKE ?)
                ORDER BY name ASC
            ");
            
            $stmt->execute([$query, $query, $query]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error searching doctors: " . $e->getMessage());
        }
    }
}
?>
