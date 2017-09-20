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
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $key = trim($message->getText(true));
		$isUnlocked = Unlock::isUnlocked($chat_id);
      if ($key == '') {
        $data = [
                'chat_id' => $chat_id,
                'text'    => 'Please enter the given Key after the command `/unlock <key
		'parse_mode' => 'Markdown',
            ];
        return Request::sendMessage($data);
      }
      
      if ($isUnlocked) {
          $text='The Bot is allready Unlocked here';
        }
        else{
         $unlock = Unlock::unlockChannel($chat_id, $key);
         ($unlock)?$text = 'Bot succsessfully unlocked':$text = 'Could not unlock the Bot';
        }
      
       
            $data = [
                'chat_id' => $chat_id,
                'text'    => $text,
            ];
        return Request::sendMessage($data);
        
		
        
    }
}
