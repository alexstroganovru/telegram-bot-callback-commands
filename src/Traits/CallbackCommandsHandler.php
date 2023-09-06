<?php

namespace AlexStroganovRu\TelegramBotCallbackCommands\Traits;

use AlexStroganovRu\TelegramBotCallbackCommands\CallbackCommandBus;
use Telegram\Bot\Objects\Update;

/**
 * CallbackCommandsHandler.
 */
trait CallbackCommandsHandler
{
    /**
     * Return Callback Command Bus.
     */
    public function getCallbackCommandBus(): CallbackCommandBus
    {
        return $this->callbackCommandBus;
    }

    public function setCallbackCommandBus(CallbackCommandBus $callbackCommandBus): static
    {
        $this->callbackCommandBus = $callbackCommandBus;

        return $this;
    }

    /**
     * Return Callback Commands.
     */
    public function getCallbackCommands(): array
    {
        return $this->callbackCommandBus->getCommands();
    }

    public function setCallbackCommands(array $commands): self
    {
        $this->callbackCommandBus->addCommands($commands);

        return $this;
    }

    /**
     * Processes Inbound Callback Commands.
     */
    public function callbackCommandsHandler(Update $update): void
    {
        $this->callbackCommandBus->handler($update);
    }
}
