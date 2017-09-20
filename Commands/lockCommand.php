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
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
/**
 * User "/help" command
 */
class lockCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'lock';
    /**
     * @var string
     */
    protected $description = 'Locks the Bot';
    /**
     * @var string
     */
    protected $usage = '/lock';
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


      if (!$isUnlocked) {
          $text='The Bot is allready Locked here';
      }
      else{
        $unlock = Unlock::lockChannel($chat_id);
		if (is_bool($unlock)) {
			(!$unlock)?$text = 'Bot succsessfully locked':$text = 'Could not lock the Bot';
		}
		elseif(is_string($unlock)) {
			$text = $unlock;
        }

       }
            $data = [
                'chat_id' => $chat_id,
                'text'    => $text,
            ];
        return Request::sendMessage($data);



    }
}
