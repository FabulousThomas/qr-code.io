<?php
/*
 * Barcode and QR Code Model
 * Handles saving and retrieving barcodes and QR codes from database
 */
class BarcodeQr
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Save barcode or QR code to database
    public function save($data)
    {
        $type = $data['type'] ?? 'barcode'; // 'barcode' or 'qrcode'
        $value = $data['value'] ?? '';
        $format = $data['format'] ?? '';
        $imageData = $data['imageData'] ?? '';
        $imagePath = $data['imagePath'] ?? '';
        $customization = isset($data['customization']) && !empty($data['customization']) ? json_encode($data['customization']) : null;
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $sessionId = session_id();

        // Save image to file if imageData is provided
        if (!empty($imageData) && empty($imagePath)) {
            $imagePath = $this->saveImageToFile($imageData, $type, $value);
        }

        $this->db->query('INSERT INTO codes (type, value, format, image_path, customization_options, user_id, session_id, created_at) 
                         VALUES (:type, :value, :format, :image_path, :customization, :user_id, :session_id, NOW())');

        $this->db->bind(':type', $type);
        $this->db->bind(':value', $value);
        $this->db->bind(':format', $format);
        $this->db->bind(':image_path', $imagePath);
        $this->db->bind(':customization', $customization);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':session_id', $sessionId);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Save multiple barcodes
    public function saveMultiple($items)
    {
        $savedIds = [];
        foreach ($items as $item) {
            $id = $this->save($item);
            if ($id) {
                $savedIds[] = $id;
            }
        }
        return $savedIds;
    }

    // Save image to file
    private function saveImageToFile($imageData, $type, $value)
    {
        // Determine file extension from data URI
        $extension = 'png'; // default
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
            $extension = $matches[1] ?? 'png';
        }

        // Remove data URL prefix if present
        $imageDataClean = preg_replace('/^data:image\/(\w+);base64,/', '', $imageData);
        $imageDataClean = str_replace(' ', '+', $imageDataClean);
        $decoded = base64_decode($imageDataClean);

        if (!$decoded) {
            return null;
        }

        // Create directory if it doesn't exist
        $dir = APPROOT . '/images/' . $type . 's/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Generate filename
        $filename = $type . '_' . time() . '_' . md5($value . time()) . '.' . $extension;
        $filepath = $dir . $filename;

        // Save file
        if (file_put_contents($filepath, $decoded)) {
            // Return relative path
            return APPROOT . '/images/' . $type . 's/' . $filename;
        }

        return null;
    }

    // Get codes by session
    public function getBySession($sessionId)
    {
        $this->db->query('SELECT * FROM codes WHERE session_id = :session_id ORDER BY created_at DESC');
        $this->db->bind(':session_id', $sessionId);
        return $this->db->resultSet();
    }

    // Get codes by user
    public function getByUser($userId)
    {
        $this->db->query('SELECT * FROM codes WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Get code by ID
    public function getById($id)
    {
        $this->db->query('SELECT * FROM codes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->singleSet();
    }

    // Delete code
    public function delete($id)
    {
        // Get the code first to delete associated image file
        $code = $this->getById($id);
        if ($code && $code->image_path && file_exists($code->image_path)) {
            unlink($code->image_path);
        }

        $this->db->query('DELETE FROM codes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
