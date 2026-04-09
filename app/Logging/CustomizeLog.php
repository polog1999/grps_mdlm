<?php
namespace App\Logging;

use App\Logging\Processors\LogRedactor;
use Illuminate\Log\Logger;

class CustomizeLog
{
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor(new LogRedactor());
        }
    }
}