<p align="center"><img src="#" alt="CallbackData" width="400"></p>

# Callback Data plugin for Telegram Bot API PHP SDK

The extension for [Telegram Bot API PHP SDK](https://github.com/irazasyed/telegram-bot-sdk) v3.1+ that allows to 
implement processing callback data for telegram bots.


## About this fork

Original package is not maintained anymore and does not support Telegram Bot API PHP SDK v3.
The goal of the fork is to maintain the package compatible with the latest [Telegram Bot API PHP SDK](https://github.com/irazasyed/telegram-bot-sdk),
PHP 8+ and Laravel features, focus on stability, better DX and readability.


## Installation

You can easily install the package using Composer:

```shell
composer require alexstroganovru/telegram-bot-callback-data
```
Package requires PHP >= 8.0


## Usage

1. Create a Callback Command class
2. Setup your controller class to proceed CallbackData on income webhook request.


### 1. Create a Callback Command class

Each CallbackCommand should be implemented as class that extends basic `CallbackCommand` as you can see in 
[HelloExampleCallbackCommand](https://github.com/alexstroganovru/telegram-bot-callback-data/blob/master/src/CallbackData/HelloExampleCallbackCommand.php)
or the code bellow:

```php
use AlexStroganovRu\TelegramBotCallbackData\CallbackCommand;
use Telegram\Bot\Objects\Update;

final class HelloCallbackCommand extends CallbackCommand
{
    protected string $pattern = '';

    public function handle(): void
    {
        $this->bot->sendMessage([
            'chat_id' => $this->getChatId(),
            'text' => 'Hello! How are you?',
        ]);
    }
}
```


### 2. Setup your controller

Process request inside your Laravel webhook controller:

```php
use Telegram\Bot\BotsManager;
use AlexStroganovRu\TelegramBotCallbackData\CallbackDataManager;

final class TelegramWebhookController
{
    public function handle(CallbackDataManager $callbackDataManager, BotsManager $botsManager): void
    {
        $bot = $botsManager->bot('your-bot-name');
        $update = $bot->commandsHandler(true);
        
        $callbackDataManager->setBot($bot);
    }
}
```


### `CallbackCommand` class API

- `handle()` - Handle callback data

### `CallbackDataManager` class API

ℹ️ `CallbackData` [Facade](https://laravel.com/docs/master/facades) proxies calls to `CallbackDataManager` class.

- `setBot(\Telegram\Bot\Api $bot)` - Use non-default Bot for API calls


## ToDo

- Improve documentation and examples
- Improve test coverage


## Backward compatibility promise

CallbackData package uses [Semver 2.0](https://semver.org/). This means that versions are tagged with MAJOR.MINOR.PATCH.
Only a new major version will be allowed to break backward compatibility (BC).

Classes marked as `@experimental` or `@internal` are not included in our backward compatibility promise.
You are also not guaranteed that the value returned from a method is always the same.
You are guaranteed that the data type will not change.

PHP 8 introduced [named arguments](https://wiki.php.net/rfc/named_params), which increased the cost and reduces flexibility for package maintainers.
The names of the arguments for methods in CallbackData is not included in our BC promise.
