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
     * Parse Command Arguments.
     */
    public function parseArguments(string $command): array
    {
        return $this->compareStrings($this->getName(), $command);
    }

    /**
     * Process Inbound Command.
     */
    public function make(Api $telegram, Update $update): void
    {
        $this->telegram = $telegram;
        $this->update = $update;
        $this->arguments = $this->parseCommandArguments();

        $this->handle();
    }

    /**
     * Parse Command Arguments.
     */
    protected function parseCommandArguments(): array
    {
        if (empty($this->update->callbackQuery->data)) {
            return [];
        }

        $result = $this->parseArguments(command: $this->update->callbackQuery->data);

        if (! empty($result[0])) {
            return array_combine($result[0], $result[1]);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    abstract public function handle(): void;


    private function compareStrings(string $pattern, string $value): array
    {
        preg_match_all('/\{([a-zA-Z0-9]+)\}/', $pattern, $matches);

        $regex_pattern = preg_quote($pattern, '/');
        foreach ($matches[1] as $match) {
            $regex_pattern = str_replace(preg_quote('{'.$match.'}'), '([a-zA-Z0-9_]+)', $regex_pattern);
        }

        preg_match_all('/^'.$regex_pattern.'$/', $value, $values);

        if (! empty($values) && ! empty($values[0])) {
            array_shift($values);
            return [
                $matches[1],
                array_map(static function ($value) {
                    return reset($value);
                }, $values),
            ];
        }

        return [[], []];
    }
}
