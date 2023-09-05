<?php declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackData\Laravel;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use AlexStroganovRu\TelegramBotCallbackData\CallbackDataManager;

final class CallbackDataServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /** @inheritDoc */
    public function register(): void
    {
        $this->registerBindings();
    }

    private function registerBindings(): void
    {
        $this->app->singleton(CallbackDataManager::class, static function (Container $app): CallbackDataManager {
            return new CallbackDataManager($app->make('telegram.bot'));
        });

        $this->app->alias(CallbackDataManager::class, 'telegram.callback_data');
    }

    /**
     * @inheritDoc
     * @return list<string>
     */
    public function provides(): array
    {
        return [
            'telegram.callback_data',
            CallbackDataManager::class,
        ];
    }
}
