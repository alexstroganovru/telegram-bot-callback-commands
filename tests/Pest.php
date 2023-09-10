<?php

use AlexStroganovRu\TelegramBotCallbackCommands\CallbackCommand;
use AlexStroganovRu\TelegramBotCallbackCommands\Tests\TestCase;
use Telegram\Bot\Objects\Update;

use function Pest\Faker\fake;

uses(TestCase::class)->in('Unit');

expect()->extend('toBeOne', fn () => $this->toBe(1));

function makeCommand(): CallbackCommand
{
    return (new class () extends CallbackCommand {
        protected string $name = 'test';

        public function handle(): void
        {
        }
    });
}

function buildTextMessageUpdate(string $text = ''): Update
{
    return new Update([
        'update_id' => random_int(100000000, 999999999),
        'message' => buildMessageInUpdate($text),
    ]);
}

function buildCallbackMessageUpdate(int|string $data = 0): Update
{
    return new Update([
        'update_id' => random_int(100000000, 999999999),
        'callback_query' => [
            'id' => (string)random_int(100000000000000, 999999999999999),
            'from' => buildFromInUpdate(),
            'message' => buildMessageInUpdate(),
            'chat_instance' => '-'.random_int(100000000000000, 999999999999999),
            'data' => is_int($data) ? generateData($data) : $data,
        ],
    ]);
}

function buildMessageInUpdate(string $text = '', string $chatType = 'private'): array
{
    return [
        'message_id' => random_int(1, 99999),
        'from' => buildFromInUpdate(),
        'chat' => buildChatInUpdate($chatType),
        'date' => fake()->dateTime()->timestamp,
        'text' => $text ?? fake()->text,
    ];
}

function buildFromInUpdate(): array
{
    return [
        'id' => random_int(1000000000, 9999999999),
        'is_bot' => (bool)random_int(0, 1),
        'first_name' => fake()->firstName,
        'last_name' => fake()->lastName,
        'username' => fake()->userName,
    ];
}

function buildChatInUpdate(string $type = 'private'): array
{
    $data = match ($type) {
        'private' => buildFromInUpdate(),
        default => [],
    };

    return [...$data, 'type' => $type];
}

function generateData(int $regexSegments = 0, int $segments = 1): string
{
    if ($regexSegments === 0) {
        return fake()->word;
    }

    if ($regexSegments >= $segments) {
        $segments = $regexSegments + 1;
    }

    $words = fake()->words($segments);

    $indexWords = [];

    while (count($indexWords) < $regexSegments) {
        $index = random_int(0, $segments - 1);
        if (! in_array($index, $indexWords, strict: true)) {
            $indexWords[] = $index;
            $words[$index] = sprintf('{%s}', $words[$index]);
        }
    }

    return implode(':', $words);
}