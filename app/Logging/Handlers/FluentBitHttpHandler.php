<?php

namespace App\Logging\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Illuminate\Support\Facades\Http;
use Monolog\Level;

class FluentBitHttpHandler extends AbstractProcessingHandler
{
    protected string $url;

    public function __construct(string $url, $level = Level::Info, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->url = $url;
    }

    protected function write(LogRecord $record): void
    {
        Http::withBody($record->formatted, 'application/json')->post($this->url);
    }
}