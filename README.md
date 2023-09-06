<p align="center"><img src="#" alt="CallbackCommands" width="400"></p>

# Callback Commands plugin for Telegram Bot API PHP SDK

The extension for [Telegram Bot API PHP SDK](https://github.com/irazasyed/telegram-bot-sdk) v3.1+ that allows to
implement processing callback data for telegram bots.

## Installation

You can easily install the package using Composer:

```shell
composer require alexstroganovru/telegram-bot-callback-commands
```

Package requires PHP >= 8.0

## Usage

1. Create a Callback Command class
2. Add ```'callback_commands' => []``` in telegram.php
3. Setup your controller class to proceed CallbackCommands on income webhook request.

### 1. Create a Callback Command class

Each CallbackCommand should be implemented as class that extends basic `CallbackCommand` as you can see in
[HelloExampleCallbackCommand](https://github.com/alexstroganovru/telegram-bot-callback-data/blob/master/src/Commands/HelloExampleCallbackCommand.php)
or the code bellow:

```php
use AlexStroganovRu\TelegramBotCallbackCommands\CallbackCommand;

final class HelloCallbackCommand extends CallbackCommand
{
    protected string $pattern = '';

    public function handle(): void
    {
        $this->getTelegram()->sendMessage([
            'chat_id' => $this->getChatId(),
            'text' => 'Hello! How are you?',
        ]);
    }
}
```

### 2. Add ```'callback_commands' => []``` in telegram.php

```php
use AlexStroganovRu\TelegramBotCallbackCommands\Commands\HelloExampleCallbackCommand;

'callback_commands' => [
    HelloExampleCallbackCommand::class,
],
```

### 3. Setup your controller

Process request inside your Laravel webhook controller:

```php
use Telegram\Bot\BotsManager;
use AlexStroganovRu\TelegramBotCallbackCommands\CallbackCommandsManager;

final class TelegramWebhookController
{
    public function handle(CallbackCommandsManager $callbackManager, BotsManager $botsManager): void
    {
        $bot = $botsManager->bot('your-bot-name');
        $update = $bot->commandsHandler(true);
        
        $callbackManager->setTelegram($bot)
            ->callbackCommandsHandler($update);
    }
}
```

### `CallbackCommand` class API

- `handle()` - Handle callback data

### `CallbackCommandsManager` class API

ℹ️ `CallbackCommands` [Facade](https://laravel.com/docs/master/facades) proxies calls to `CallbackCommandsManager`
class.

- `setTelegram(\Telegram\Bot\Api $bot)` - Use non-default Bot for API calls
- `setCallbackCommands(array $commands)` - Change commands list ащк Bot
- `callbackCommandsHandler(Telegram\Bot\Objects\Update $update)` - Callback command handler

## ToDo

- Improve documentation and examples
- Improve test coverage

## Backward compatibility promise

CallbackCommands package uses [Semver 2.0](https://semver.org/). This means that versions are tagged with MAJOR.MINOR.
PATCH.
Only a new major version will be allowed to break backward compatibility (BC).

Classes marked as `@experimental` or `@internal` are not included in our backward compatibility promise.
You are also not guaranteed that the value returned from a method is always the same.
You are guaranteed that the data type will not change.

PHP 8 introduced [named arguments](https://wiki.php.net/rfc/named_params), which increased the cost and reduces
flexibility for package maintainers.
The names of the arguments for methods in CallbackCommands is not included in our BC promise.
