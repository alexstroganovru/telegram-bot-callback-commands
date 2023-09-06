<?php

declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackCommands;

use InvalidArgumentException;
use Telegram\Bot\Answers\AnswerBus;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;

/**
 * Class CallbackCommandBus.
 */
class CallbackCommandBus extends AnswerBus
{
    /**
     * @var array<string, CallbackCommand> Holds all commands. Keys are command names.
     */
    private array $commands = [];

    /**
     * Instantiate Callback Command Bus.
     */
    public function __construct(Api $telegram = null)
    {
        $this->telegram = $telegram;
    }

    /**
     * Returns the list of callback commands.
     *
     * @return array<string, CallbackCommand>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Add a list of commands.
     *
     * @param  list<CallbackCommandInterface|class-string<CallbackCommandInterface>>  $commands
     *
     * @throws TelegramSDKException
     */
    public function addCommands(array $commands): self
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }

        return $this;
    }

    /**
     * Add a command to the commands list.
     *
     * @param  CallbackCommandInterface|class-string<CallbackCommandInterface>  $command  Either an object or fully qualified class name (FQCN) of the command class.
     *
     * @throws TelegramSDKException
     */
    public function addCommand(CallbackCommandInterface|string $command): self
    {
        $command = $this->resolveCommand($command);

        /**
         * At this stage we definitely have a proper command to use.
         *
         * @var CallbackCommand $command
         */
        $this->commands[$command->getName()] = $command;

        return $this;
    }

    /**
     * Remove a command from the list.
     *
     * @param  string  $name  Command's name
     */
    public function removeCommand(string $name): self
    {
        unset($this->commands[$name]);

        return $this;
    }

    /**
     * Removes a list of commands.
     *
     * @param  list<string>  $names  Command names
     */
    public function removeCommands(array $names): self
    {
        foreach ($names as $name) {
            $this->removeCommand($name);
        }

        return $this;
    }

    /**
     * Parse a Command for a Match.
     *
     * @param  string  $data  Command data
     *
     * @return string Telegram command name
     */
    protected function parseCommand(string $data): string
    {
        if (trim($data) === '') {
            throw new InvalidArgumentException('Data is empty, Cannot parse for command');
        }

        return $data;
    }

    /**
     * Handles Inbound Messages and Executes Appropriate Command.
     */
    protected function handler(Update $update): Update
    {
        if ($update->callbackQuery?->data) {
            $this->process($update);
        }

        return $update;
    }

    /**
     * Execute a bot callback command from the update text.
     */
    private function process(Update $update): void
    {
        $command = $this->parseCommand($update->callbackQuery->data);

        $this->execute($command, $update);
    }

    /**
     * Execute the command.
     *
     * @param  string  $name  Telegram command name
     */
    protected function execute(string $name, Update $update): mixed
    {
        $command = $this->commands[$name]
            ?? collect($this->commands)->first(fn ($command): bool => $command instanceof $name);

        return $command?->make($this->telegram, $update) ?? false;
    }

    /**
     * @param  CallbackCommandInterface|class-string<CallbackCommandInterface>  $command
     *
     * @throws TelegramSDKException
     */
    private function resolveCommand(CallbackCommandInterface|string $command): CallbackCommandInterface
    {
        if (! is_a($command, CallbackCommandInterface::class, true)) {
            throw new TelegramSDKException(
                sprintf(
                    'Callback Command class "%s" should be an instance of "%s"',
                    is_object($command) ? $command::class : $command,
                    CallbackCommandInterface::class
                )
            );
        }

        $commandInstance = $this->buildDependencyInjectedClass($command);

        if ($commandInstance instanceof CallbackCommand && $this->telegram) {
            $commandInstance->setTelegram($this->getTelegram());
        }

        return $commandInstance;
    }
}
