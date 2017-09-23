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
class lockstatusCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'lockstatus';

    /**
     * @var string
     */
    protected $description = 'Check if the Bot is locked or unlocked in this Channel';

    /**
     * @var string
     */
    protected $usage = '/lockstatus';

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
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id    = $message->getFrom()->getId();
        $chat_admins = array();
        $data['chat_id'] = $chat_id;
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

        $unlock  = Unlock::isUnlocked($chat_id);

        ($unlock) ? $text = 'The Bot is Unlocked here' : $text = 'The Bot is Locked here';
        $data['text'] = $text;

        return Request::sendMessage($data);
    }
}
