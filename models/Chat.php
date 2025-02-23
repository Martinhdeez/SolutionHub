<?php
    class Chat{
        private $table = 'chats';

        private $conn;

        public $user_id1;
        public $user_id2;

        public function __construct($db){
            $this->conn = $db->conn;
        }

        public function createChat(){
            $query = "INSERT INTO $this->table (user_id1, user_id2) VALUES (:user_id1, :user_id2)";
            $stmt = $this->conn->prepare($query);

            $this->user_id1 = htmlspecialchars(strip_tags($this->user_id1));
            $this->user_id2 = htmlspecialchars(strip_tags($this->user_id2));

            $stmt->bindParam(':user_id1', $this->user_id1);
            $stmt->bindParam(':user_id2', $this->user_id2);

            if($stmt->execute()){
                return true;
            }

            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        public function getChatsByUser($user_id){
            $query = "SELECT * FROM $this->table 
            WHERE user_id1 = :user_id OR user_id2 = :user_id 
            ORDER BY updated_at DESC";
             $stmt = $this->conn->prepare($query);
  
            $user_id = htmlspecialchars(strip_tags($user_id));
  
            $stmt->bindParam(':user_id', $user_id);
  
            $stmt->execute();
  
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
  
        }
        public function countUnreadMessagesByChat($chat_id, $user_id) {
            $query = "SELECT COUNT(*) as unread_count FROM messages 
                      WHERE chat_id = :chat_id AND receiver_id = :user_id AND is_read = 0";
        
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":chat_id", $chat_id, PDO::PARAM_INT);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $result['unread_count'] ?? 0;
        }
        

        public function countUnreadMessages($user_id) {
            $query = "SELECT COUNT(*) as unread_count FROM messages 
                      WHERE receiver_id = :user_id AND is_read = 0";
        
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $result['unread_count'] ?? 0;
        }
        
        

        public function hasUnreadMessages($user_id) {
            $query = "SELECT COUNT(*) as unread FROM messages 
                      WHERE receiver_id = ? AND is_read = 0"; 
                      
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            return $result['unread'] > 0; // Devuelve true si hay mensajes sin leer
        }
        

        public function sendMessage($chat_id, $sender_id, $receiver_id, $message) {
            $chat_id = (int) $chat_id;
            $receiver_id = (int) $receiver_id;  
            $sql = "INSERT INTO messages (chat_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$chat_id, $sender_id, $receiver_id, $message]);
        }

        public function getMessages($chat_id) {
            $sql = "SELECT * FROM messages WHERE chat_id = ? ORDER BY updated_at ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$chat_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

}
