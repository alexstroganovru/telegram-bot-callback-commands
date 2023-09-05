<?php declare(strict_types=1);

namespace AlexStroganovRu\TelegramBotCallbackData;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

final class CallbackDataManager
{
    /** Bot instance to use for all API calls. */
    private Api $bot;

    public function __construct(Api $bot)
    {
        $this->bot = $bot;
    }

    /** Use non-default Bot for API calls */
    public function setBot(Api $bot): void
    {
        $this->bot = $bot;
    }
}
