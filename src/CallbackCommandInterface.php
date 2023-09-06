<?php

namespace AlexStroganovRu\TelegramBotCallbackCommands;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Interface CallbackCommandInterface.
 */
interface CallbackCommandInterface
{
    public function getName(): string;

    public function getDescription(): string;

    public function getArguments(): array;

    public function make(Api $telegram, Update $update);
}
