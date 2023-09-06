<?php

declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackCommands;

use AlexStroganovRu\TelegramBotCallbackCommands\Traits\CallbackCommandsHandler;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Traits\Telegram;

final class CallbackCommandsManager
{
    use Telegram;
    use CallbackCommandsHandler;

    private CallbackCommandBus $callbackCommandBus;

    public function __construct(Api $telegram, array $commands = [])
    {
        $this->setTelegram($telegram)
            ->setCallbackCommandBus(new CallbackCommandBus($telegram));

        if (! empty($commands)) {
            $this->setCallbackCommands($commands);
        }
    }

    public function setBot(Api $bot): self
    {
        return $this->setTelegram($bot);
    }

    /**
     * Magically pass methods to the default bot.
     *
     * @return mixed
     *
     * @throws TelegramSDKException
     */
    public function __call(string $method, array $parameters)
    {
        return $this->getTelegram()->$method(...$parameters);
    }
}
