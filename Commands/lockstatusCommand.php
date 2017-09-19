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

use Hitmare\Unlock-PTB\Unlock;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 */
class lockstatusCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'lockstatus';

    /**
     * @var string
     */
    protected $description = 'Bot im Chat entsperren';

    /**
     * @var string
     */
    protected $usage = '/lockstatus';

    /**
     * @var string
     */
    protected $version = '1.1.0';

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
		$unlock = Unlock::isUnlocked($chat_id);
        
       
            $data = [
                'chat_id' => $chat_id,
                'text'    => $unlock,
            ];

        return Request::sendMessage($data);
        
		
        
    }
}
