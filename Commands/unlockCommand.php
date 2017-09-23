<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Hitmare\UnlockPTB\Unlock;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 */
class unlockCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'unlock';
    /**
     * @var string
     */
    protected $description = 'Unlocks the Bot with the given Auth-Key';
    /**
     * @var string
     */
    protected $usage = '/unlock <AuthKey>';
    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message    = $this->getMessage();
        $chat_id    = $message->getChat()->getId();
        $user_id    = $message->getFrom()->getId();
        $key = trim($message->getText(true));
        $data['chat_id'] = $chat_id;

        $chat_admins = array();
        $lockChat = $this->getConfig('lockChat');
        $thisChat = $message->getChat()->getType();
        //Check if the Command should executed in Groups or Private Chats
        if (!in_array($thisChat,$lockChat)) {
          $data['text'] = 'This Command is in this Chat not aviable';
          return Request::sendMessage($data);
        }


        //Check if Chat is private or not
        if ($thisChat != 'private'){
          // Check if user is admin

          $chat_admin = Request::getChatAdministrators(['chat_id' => $chat_id,])->getResult();
          foreach ($chat_admin as $chat_member) {
            $chat_admins[] = $chat_member->getUser()->getId();
          }

          if (!in_array($user_id,$chat_admins) OR !$this->telegram->isAdmin($user_id)){
            $data['text'] = 'Sorry, only the Bot Admin and the Chat Owner can execute this Command';
            return Request::sendMessage($data);
          }
        }


        if ($key == '') {
            $data['text'] = 'Please enter the given Key after the command `/unlock <key>`';
            $data['parse_mode'] = 'Markdown';
            return Request::sendMessage($data);
        }

        $isUnlocked = Unlock::isUnlocked($chat_id);
        if ($isUnlocked) {
            $text = 'The Bot is allready Unlocked here';
        } else {
            $unlock = Unlock::unlockChannel($chat_id, $key);
            if (is_bool($unlock)) {
                ($unlock) ? $text = 'Bot succsessfully unlocked' : $text = 'Could not unlock the Bot';
            } elseif (is_string($unlock)) {
                $text = $unlock;
            }

        }
        $data['text'] = $text;
        return Request::sendMessage($data);
    }
}
