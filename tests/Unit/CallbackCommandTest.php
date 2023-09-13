<?php

use AlexStroganovRu\TelegramBotCallbackCommands\CallbackCommandsManager;
use Telegram\Bot\Api;

beforeEach(function () {
    $client = Mockery::mock(Api::class);
    $client->shouldReceive('answerCallbackQuery');

    $this->callbackCommandManager = new CallbackCommandsManager($client);
});

dataset('error_patterns', [
    ['first:{two}:three', 'first::three', [['two'], []]],
    ['first:{two}::three', 'first:aQaE:gRq4:three', [['two', 'four'], ['aQaE']]],
]);

dataset('patterns', [
    ['{two}:first:three', 'aQaE:first:three', [['two'], ['aQaE']]],
    ['first:{two}:three', 'first:aQaE:three', [['two'], ['aQaE']]],
    ['first:three:{two}', 'first:three:aQaE', [['two'], ['aQaE']]],

    ['{two}:{four}:first:three', 'aQaE:gRq4:first:three', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first:{two}:three:{four}', 'first:aQaE:three:gRq4', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first:{two}:{four}:three', 'first:aQaE:gRq4:three', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first:three:{two}:{four}', 'first:three:aQaE:gRq4', [['two', 'four'], ['aQaE', 'gRq4']]],

    ['{two}|{four}|first|three', 'aQaE|gRq4|first|three', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first|{two}|three|{four}', 'first|aQaE|three|gRq4', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first|{two}|{four}|three', 'first|aQaE|gRq4|three', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first|three|{two}|{four}', 'first|three|aQaE|gRq4', [['two', 'four'], ['aQaE', 'gRq4']]],

    ['{two}_{four}_first_three', 'aQaE_gRq4_first_three', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first_{two}_three_{four}', 'first_aQaE_three_gRq4', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first_{two}_{four}_three', 'first_aQaE_gRq4_three', [['two', 'four'], ['aQaE', 'gRq4']]],
    ['first_three_{two}_{four}', 'first_three_aQaE_gRq4', [['two', 'four'], ['aQaE', 'gRq4']]],

    [
        'All of {first} ferengis invade {three}, cold {two}.',
        'All of those ferengis invade remarkable, cold sensors.',
        [['first', 'three', 'two'], ['those', 'remarkable', 'sensors']],
    ],
]);

test('error output if the command is not found', function (string $pattern, string $data) {
    $command = makeCommand()->setName($pattern);
    $update = buildCallbackMessageUpdate($data);

    $this->callbackCommandManager->addCallbackCommand($command)
        ->callbackCommandsHandler($update);
})->throws(InvalidArgumentException::class)->with('error_patterns');

test('successful command processing', function (string $pattern, string $data) {
    $command = makeCommand()->setName($pattern);
    $update = buildCallbackMessageUpdate($data);

    $this->callbackCommandManager->addCallbackCommand($command)
        ->callbackCommandsHandler($update);

    expect(1)->toBeOne();
})->with('patterns');


