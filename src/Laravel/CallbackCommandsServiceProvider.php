<?php

declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackCommands\Laravel;

use AlexStroganovRu\TelegramBotCallbackCommands\CallbackCommandsManager;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class CallbackCommandsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /** @inheritDoc */
    public function register(): void
    {
        $this->registerBindings();
    }

    private function registerBindings(): void
    {
        $this->app->singleton(
            CallbackCommandsManager::class,
            static function (Container $app): CallbackCommandsManager {
                return new CallbackCommandsManager(
                    $app->make('telegram.bot'),
                    config('telegram.callback_commands', [])
                );
            }
        );

        $this->app->alias(CallbackCommandsManager::class, 'telegram.callback_commands');
    }

    /**
     * @inheritDoc
     * @return list<string>
     */
    public function provides(): array
    {
        return [
            'telegram.callback_commands',
            CallbackCommandsManager::class,
        ];
    }
}
