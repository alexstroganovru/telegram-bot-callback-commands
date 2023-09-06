<?php

declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackCommands\Commands;

use AlexStroganovRu\TelegramBotCallbackCommands\CallbackCommand;

/**
 * An example of CallbackCommand class for demo purposes.
 * @internal
 */
final class HelloExampleCallbackCommand extends CallbackCommand
{
    protected string $name = 'hello';

    public function handle(): void
    {
        $this->replyWithMessage(['text' => 'Hello! How are you?']);
    }
}
