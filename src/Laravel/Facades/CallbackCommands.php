<?php

declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackCommands\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/** @mixin \AlexStroganovRu\TelegramBotCallbackCommands\CallbackCommandBus */
final class CallbackCommands extends Facade
{
    /** Get the registered name of the component. */
    protected static function getFacadeAccessor(): string
    {
        return 'telegram.callback_commands';
    }
}
