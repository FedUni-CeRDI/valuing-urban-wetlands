<?php
/**
 * User: Scott Limmer
 * Date: 3/04/2023
 */

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait PrefixedLogger
{
    private function getPrefix(): string
    {
        return $this->logPrefix;
    }
    private function log(string $level, string $message, array $context = []):void
    {
        $message = sprintf('%s: %s', $this->getPrefix(), $message);
        Log::{$level}($message, $context);
    }

}
