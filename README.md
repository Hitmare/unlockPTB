# unlockPTB

## Table of Contents
- [Introduction](#introduction)
- [Instructions](#instructions)
    - [Installation](#installation)
    - [Add the Lockstatus Check to your Files](#add-the-lockstatus-check-to-your-files)
    - [How to use](#how-to-use)
    - [Aviable Commands](#aviable-commands)

## Introduction

This is unlock Class for the PHP Telegram Bot Libary to lock an unlock the Bot in Private and Group Channels

## Instructions
### Installation

Install this package through [Composer][composer].
Edit your project's `composer.json` file to require `hitmare/unlockptb`.

Edit *composer.json* file and add `hitmare/unlockptb` under require
```json
{
    "name": "yourproject/yourproject",
    "type": "project",
    "require": {
        "php": ">=5.5",
        "longman/telegram-bot": "*",
        "hitmare/unlockptb": "*"
    }
}
```
and run `composer update`

**or**

run this command in your command line:

```bash
composer require hitmare/unlockptb
```

### Add the Lockstatus Check to your Files

To use the Libary you have to add the Code for checking the Lockstatus in every Command File where you want to include the Lock function.
At the Moment it is, as far as i know, the only way to implement this without editing the Main Code of the Bot

- Add the required Namespace
```php
    use Hitmare\UnlockPTB\Unlock;
```

- Add the Lockstatus Check as first inside the `execute()` funciton
```php
    $message    = $this->getMessage();
    $chat_id    = $message->getChat()->getId();
    $isUnlocked = Unlock::isUnlocked($chat_id);
    if (!isUnlocked) {
      $data = ['chat_id' = $chat_id, 'text' = 'This Command is locked inside this Chat'];
      return Request::sendMessage($data);
    }
    // Your Code down here
```

- To lock only eg. Group Chats but no Privat Chats you can use the Code like this

```php
    $message    = $this->getMessage();
    $chat_id    = $message->getChat()->getId();
    $isUnlocked = Unlock::isUnlocked($chat_id);
    if (!$message->getChat()->getTye() === 'privat' && !isUnlocked) {
      $data = ['chat_id' = $chat_id, 'text' = 'This Command is locked inside this Chat'];
      return Request::sendMessage($data);
    }
    // Your Code down here
```

### How to use

1. Generate the Authkey

To generate the Authkey there are two ways:   
Get the wanted Chat or User ID through the `/chats *` Command and execute `/getAutchkey <id>` directly to the Bot

**OR**

*(Only for Group Chats)* Execute `/getAutchkey` inside of the Group Chat and the Bot will send the Botadmin who send the Command the Authkey

2. Unlock the Bot
For Private Chat:
    - Execute `/unlock <Authkey>` and the Bot will be unlocked.
For Group Chats:
    - Execute `/unlock <Authkey>` as an **Bot Admin** or as an **Channel Owner** and the Bot will be unlocked.  

To Lock the Bot execute `/lock`. In Group Chats only the Bot Admin and the Group Chat Owner can use it   
To Show the Lockstatus execute `/lockstatus`. In Group Chats only the Bot Admin and the Group Chat Owner can use it   

### Aviable Commands

This Library includes four (4) Commands
- `/getAuthkey` - Generates the Unlock Authkey. Can only be used from Bot Admin
- `/unlock` - Unlocks the Bot. In Group Chats only the Bot Admin and the Group Chat Owner can use it
- `/lock` - Locks the Bot. In Group Chats only the Bot Admin and the Group Chat Owner can use it
- `/lockstatus` - Shows the Lockstatus of the Bot. In Group Chats only the Bot Admin and the Group Chat Owner can use it
