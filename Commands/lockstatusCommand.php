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
        $unlock  = Unlock::isUnlocked($chat_id);

        ($unlock) ? $text = 'The Bot is Unlocked here' : $text = 'The Bot is Locked here';
        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
