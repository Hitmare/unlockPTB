<?php
namespace Hitmare\UnlockPTB;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Exception\TelegramException;
use PDO;
use PDOException;

class Unlock {
    /**
     * @param $chat_id1
     * @return bool
     * @throws TelegramException
     */
    private function createRow($chat_id1) {
        try {
            $val = 0;
            $pdo = DB::getPdo();
            $sql = 'INSERT INTO `chat_unlock` (status, chat) VALUES (:status, :chat)';
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id1, PDO::PARAM_INT);
            $sth->bindParam(':status', $val, PDO::PARAM_INT);
            $sth->execute();
            
            return self::rowExist($chat_id1);
            
            
        }
         catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * @param $chat_id2
     * @return bool
     * @throws TelegramException
     */
    private function rowExist($chat_id2) {
        try {
            $val = '';
            $pdo = DB::getPdo();
            $sql = 'SELECT * FROM `chat_unlock` WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id2, PDO::PARAM_INT);
            $sth->execute();
            $row = $sth->fetch(PDO::FETCH_ASSOC);
                if(!$row){
                    $val = FALSE;
                }
                elseif($row){
                    $val = TRUE;
                }
                return $val;
            }
        catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
        
    }

    /**
     * @param $chat_id3
     * @return bool|string
     */
    public static function isUnlocked($chat_id3) {
        if(!self::rowExist($chat_id3)){
            if(!self::createRow($chat_id3)) {
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

    /**
     * @param $chat_id4
     * @return bool|string
     */
    public static function unlockChannel($chat_id4) {
                   
        
        try {
            $val = 1;
            $pdo = DB::getPdo();
            if(!self::rowExist($chat_id4){
                $sql = 'INSERT INTO `chat_unlock` (status, chat) VALUES (:status, :chat)';
            }
            else {
               $sql = 'UPDATE `chat_unlock` SET `status` = :status WHERE `chat` = :chat'; 
            }
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id4, PDO::PARAM_INT);
            $sth->bindParam(':status', $val, PDO::PARAM_INT);
            $sth->execute();
            
            return self::isUnlocked($chat_id4);
            
            
        }
         catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * @param $chat_id5
     * @return bool|string
     */
    public static function lockChannel($chat_id5) {
        
        try {
            $val = 0;
            $pdo = DB::getPdo();
            $sql = 'UPDATE `chat_unlock` SET `status` = :status WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindParam(':chat', $chat_id5, PDO::PARAM_INT);
            $sth->bindParam(':status', $val, PDO::PARAM_INT);
            $sth->execute();
            
            return self::isUnlocked($chat_id5);
            
            
        }
         catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }
    
    
    
    
    
    
}

?>