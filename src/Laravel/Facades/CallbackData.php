<?php declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackData\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/** @mixin \AlexStroganovRu\TelegramBotCallbackData\CallbackDataManager */
final class CallbackData extends Facade
{
    /** Get the registered name of the component. */
    protected static function getFacadeAccessor(): string
    {
        return 'telegram.callback_data';
    }
}
