<?php

namespace App\Logging\Processors;

use App\Models\ModelHasRole;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class LogRedactor implements ProcessorInterface
{

    protected array $redactKeys = [
        'x-api-key',
    ];

    public function __invoke(LogRecord $record): LogRecord
    {
        $context = $record->context;
        if (app()->bound('request') && $request = request()) {
            $userId = auth()->id() ?? 'guest';
            $role = 'none';

            if ($userId !== 'guest') {
                $role = ModelHasRole::where('model_id', $userId)->value('role_id') ?? 'none';
            }

            $context['security'] = [
                'ip' => $request->ip(),
                'user_id' => $userId,
                'role' => $role,
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'request_id' => $request->header('X-Request-ID') ?? str()->uuid()->toString(),
                'referrer' => $request->headers->get('referer') ?? 'direct',
                'env' => app()->environment(),
                'hostname' => gethostname(),

            ];
        }

        $emailKeys = ['usuario', 'user_email', 'email'];

        foreach ($emailKeys as $key) {
            if (isset($context[$key]) && is_string($context[$key]) && str_contains($context[$key], '@')) {
                $parts = explode('@', $context[$key]);
                $username = $parts[0];
                $domain = $parts[1];

                // Resultado: d***@gmail.com
                $context[$key] = substr($username, 0, 1) . '***@' . $domain;
            }
        }

        foreach ($this->redactKeys as $key) {
            if (array_key_exists($key, $context)) {
                $context[$key] = '[CENSURADO]';
            }
        }
        return $record->with(context: $context);
    }
}