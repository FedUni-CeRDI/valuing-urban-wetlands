<?php
/**
 * User: Scott Limmer
 * Date: 3/04/2023
 */

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait PrefixedLogger
{
    private string $loggerPrefix;

    private function setLoggerPrefix(string $prefix): void
    {
        $this->loggerPrefix = $prefix;
    }

    private function getLoggerPrefix(): string
    {
        return $this->loggerPrefix;
    }
    private function log(string $level, string $message, array $context = []):void
    {
        $message = sprintf('%s: %s', $this->getLoggerPrefix(), $message);
        Log::{$level}($message, $context);
    }

}
