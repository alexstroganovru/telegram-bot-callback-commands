<?php

declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackCommands;

use Telegram\Bot\Answers\Answerable;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Class CallbackCommand.
 */
abstract class CallbackCommand implements CallbackCommandInterface
{
    use Answerable;

    /**
     * The name of the Telegram command.
     */
    protected string $name;

    /** @var string The Telegram command description. */
    protected string $description;

    /** @var array Holds parsed command arguments */
    protected array $arguments = [];

    /**
     * Get the Command Name.
     *
     * The name of the Telegram command.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Command Name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Command Description.
     *
     * The Telegram command description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set Command Description.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function argument(string $name, mixed $default = null): mixed
    {
        return $this->arguments[$name] ?? $default;
    }

    /**
     * Get Command Arguments.
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Set Command Arguments.
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Process Inbound Command.
     */
    public function make(Api $telegram, Update $update): mixed
    {
        $this->telegram = $telegram;
        $this->update = $update;
        $this->arguments = $this->parseCommandArguments();

        return $this->handle();
    }

    /**
     * Parse Command Arguments.
     */
    protected function parseCommandArguments(): array
    {
        $arguments = [];

        $this->update->callbackQuery->data;

        return $arguments;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function handle();
}
