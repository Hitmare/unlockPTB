# unlockPTB

## Table of Contents
- [Introduction](#introduction)
- [Instructions](#instructions)
    - [Installation](#installation)
    - [Add the Lockstatus Check to your Files](#add-the-lockstatus-check-to-your-files)

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
