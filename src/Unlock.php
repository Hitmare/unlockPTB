<?php

namespace Hitmare\UnlockPTB;

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Exception\TelegramException;
use PDO;
use PDOException;

class Unlock
{
    /**
     * Add a new row for this channel.
     *
     * @param string $chat_id
     *
     * @return bool
     * @throws TelegramException
     */
    private static function createRow($chat_id)
    {
        try {
            $pdo = DB::getPdo();
            $sql = 'INSERT INTO `chat_unlock` (chat) VALUES (:chat)';
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':chat', $chat_id);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Check if row exists for this channel.
     *
     * @param string $chat_id
     *
     * @return bool
     * @throws TelegramException
     */
    private static function rowExist($chat_id)
    {
        try {
            $pdo = DB::getPdo();
            $sql = 'SELECT COUNT(*) FROM `chat_unlock` WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':chat', $chat_id);
            $sth->execute();

            return $sth->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Check if this channel is unlocked.
     *
     * @param string $chat_id
     *
     * @return bool
     * @throws TelegramException
     */
    public static function isUnlocked($chat_id)
    {
        if (!self::rowExist($chat_id) && !self::createRow($chat_id)) {
            return false;
        }

        try {
            $pdo = DB::getPdo();
            $sql = 'SELECT `status` FROM `chat_unlock` WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':chat', $chat_id);
            $sth->execute();

            return (bool) $sth->fetchColumn();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Unlock the channel, if the key is correct.
     *
     * @param string $chat_id
     * @param string $key
     *
     * @return bool|string
     * @throws TelegramException
     */
    public static function unlockChannel($chat_id, $key)
    {
        if (!self::rowExist($chat_id) && !self::createRow($chat_id)) {
            return false;
        }

        try {
            $pdo = DB::getPdo();
            $sql = 'SELECT `key` FROM `chat_unlock` WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':chat', $chat_id);
            $sth->execute();
            $key_db = $sth->fetchColumn();

            if ($key_db !== $key) {
                return 'Wrong Key';
            }

            $sql = 'UPDATE `chat_unlock` SET `status` = :status WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':chat', $chat_id);
            $sth->bindValue(':status', 1, PDO::PARAM_INT);
            $sth->execute();

            return self::isUnlocked($chat_id);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Lock the channel.
     *
     * @param string $chat_id
     *
     * @return bool
     * @throws TelegramException
     */
    public static function lockChannel($chat_id)
    {
        try {
            $pdo = DB::getPdo();
            $sql = 'UPDATE `chat_unlock` SET `status` = :status WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':chat', $chat_id);
            $sth->bindValue(':status', 0, PDO::PARAM_INT);
            $sth->execute();

            return self::isUnlocked($chat_id);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Get the authorisation key.
     *
     * @param string $chat_id
     *
     * @return string
     * @throws TelegramException
     */
    public static function getAuthKey($chat_id)
    {
        if (!self::rowExist($chat_id) && !self::createRow($chat_id)) {
            return 'error creating SQL Table';
        }

        try {
            //generate and store key
            $key = uniqid();
            $pdo = DB::getPdo();
            $sql = 'UPDATE `chat_unlock` SET `key` = :key WHERE `chat` = :chat';
            $sth = $pdo->prepare($sql);
            $sth->bindValue(':chat', $chat_id);
            $sth->bindValue(':key', $key);
            $sth->execute();

            // check if key is stored properly

            $sql2 = 'SELECT `key` FROM `chat_unlock` WHERE `chat` = :chat';
            $sth2 = $pdo->prepare($sql2);
            $sth2->bindValue(':chat', $chat_id);
            $sth2->execute();
            $key_db = $sth2->fetch();
            // send key if the key is stored properly. Else send Error message
            if ($key_db === $key) {
                return $key;
            }

            return 'Error creating the Auth Key.';
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }
}
