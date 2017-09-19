<?php
namespace Hitmare\UnlockPTB;
use Longman\TelegramBot\DB;
use PDO;
use PDOException;

class Unlock {
    private function createRow($chat_id) {
        try {
            $pdo = DB::getPdo();
            $sql = 'INSERT INTO `chat_unlock` (status, chat) VALUES (:status, :chat)';
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id, PDO::PARAM_INT);
            $sth->bindParam(':status', 0, PDO::PARAM_INT);
            $sth->execute();
            
            return self::rowExist($chat_id);
            
            
        }
         catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }
    private function rowExist($chat_id) {
        try {
            $pdo = DB::getPdo();
            $sql = 'SELECT * FROM `chat_unlock` WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id, PDO::PARAM_INT);
            $sth->execute();
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            if(!$row){
                return FALSE;
            }
            elseif($row){
                return TRUE;
            }
        }
         catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
        
    }
    public function isUnlocked($chat_id) {
        if(!self::rowExist($chat_id)){
            if(!self::createRow($chat_id)) {
                return 'ERROR';
            }
        }
        try {
            
            $pdo = DB::getPdo();
            $sql = 'SELECT `status` FROM `chat_unlock` WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id, PDO::PARAM_INT);
            $sth->execute();
            $row = $sth->fetch();

            return boolval($row['status']);
            
        }
         catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }
    
    public function unlockChannel($chat_id) {
                   
        
        try {
            $pdo = DB::getPdo();
            if(!self:rowExist($chat_id){
                $sql = 'INSERT INTO `chat_unlock` (status, chat) VALUES (:status, :chat)';
            }
            else {
               $sql = 'UPDATE `chat_unlock` SET `status` = :status WHERE `chat` = :chat'; 
            }
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id, PDO::PARAM_INT);
            $sth->bindParam(':status', 1, PDO::PARAM_INT);
            $sth->execute();
            
            return self::isUnlocked($chat_id);
            
            
        }
         catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }
    public function lockChannel($chat_id) {
        
        try {
            $pdo = DB::getPdo();
            $sql = 'UPDATE `chat_unlock` SET `status` = :status WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id, PDO::PARAM_INT);
            $sth->bindParam(':status', 0, PDO::PARAM_INT);
            $sth->execute();
            
            return self::isUnlocked($chat_id);
            
            
        }
         catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }
    
    
    
    
    
    
}

?>
