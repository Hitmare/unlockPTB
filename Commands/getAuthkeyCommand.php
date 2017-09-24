<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\AdminCommands;

use Hitmare\UnlockPTB\Unlock;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 */
class getAuthkeyCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'getAuthkey';
    /**
     * @var string
     */
    protected $description = 'Creates a Authkey to unlock the Bot';
    /**
     * @var string
     */
    protected $usage = '/getAuthkey <channel-id>';
    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */

    protected $private_only = false;

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
        $extchat    = trim($message->getText(true));
        $isUnlocked = Unlock::isUnlocked($chat_id);

        $data['chat_id'] = $user_id;

        if ($message->getChat()->getTye() != 'private' && $extchat == ''){
          $extchat = $chat_id;
        }
        elseif ($message->getChat()->getTye() === 'private' && $extchat == ''){
          $text = 'Please use the Command with a Chat ID in the private Chat or use the Command in the Group where you want to generate the Authkey for the Chat';
        }

        $key  = Unlock::getAuthKey($extchat);
        $text = 'The Authkey for the Channel ' . $chat_id . ': ' . $key;

        $data['text'] = $text;

        return Request::sendMessage($data);
    }
}
