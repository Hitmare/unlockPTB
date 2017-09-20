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
use Longman\TelegramBot\Commands\Command;
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
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $key = trim($message->getText(true));
		$isUnlocked = Unlock::isUnlocked($chat_id);




        $key = Unlock::getAuthkey($chat_id);
		    $text = 'The Authkey for the Channel ' . $chat_id . ': ' . $key;


            $data = [
                'chat_id' => $chat_id,
                'text'    => $text,
            ];
        return Request::sendMessage($data);



    }
}
