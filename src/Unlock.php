<?php
namespace Hitmare\unlock-PTB;

use Longman\TelegramBot\DB;

class unlock {
    function checkunlock($chat_id) {
        try {
           $statement = self::$pdo->prepare("SELECT status FROM chat_unlock WHERE chat = ?");
        $statement->execute(array($chat_id));
        $row = $statement->fetch();
            if ($row['unlock'] == 1 ) {
                $result = TRUE;
            }
            else {
                $result = FALSE;
            }
        }
         catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
        return $result;
    }
    
    function unlock($chat_id) {
        try {            
        $statement = self::$pdo->prepare("INSERT INTO chat_unlock (status) VALUES (?)");
        $result = $statement->execute(array($chat_id));
        }
         catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
        return $result;
        
    }
    
    function lock($chat_id) {
        try {
        $statement = self::$pdo->prepare("DELETE FROM chat_unlock WHERE chat = ?");
            if($statement->execute(array($chat_id))) {
                $result = TRUE;
            }
            else{
               $result 'SQL Error ' . $statement->queryString.' ' . $statement->errorInfo()[2];
        
            }
        }
         catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
        return $result;
    } 
    
}

?>