<?php

require_once 'db.php';
class Contacts extends DB {
    public function insert($name, $email, $subject, $message) {
        $this->connect();

        $sql = "INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            $stmt->close();
            $this->closeConnection();
            return "Success";
        } else {
            $error = "Error" . $stmt->error;
            $stmt->close();
            $this->closeConnection();
            return $error;
        }
        
    }

    public function getContacts(){
        $this->connect();

        $sql = "SELECT * FROM contacts";
        $result = $this->conn->query($sql);
        $contacts = [];

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }
        }

        $this->closeConnection();
        return $contacts;
    }
}

?>