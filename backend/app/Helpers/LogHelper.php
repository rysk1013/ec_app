<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (!function_exists('appLog')) {
    /**
     * output log helper function
     *
     * @param string $level   log level(info, error, debug)
     * @param string $message log message
     * @param array  $context context data
     * @param string $channel log channel
     * @return void
     *
     * @return void
     */
    function appLog(string $level, string $message, array $context = [], string $channel = 'daily')
    {
        Log::channel($channel)->log($level, $message, $context);
    }
}

if (!function_exists('logInfo')) {
    /**
     * output info level log
     *
     * @param  string $message
     * @param  array  $context
     * @param  string $channel
     * @return void
     */
    function logInfo(string $message, $context = [], $channel = 'daily')
    {
        appLog('info', $message, $context, $channel);
    }
}

if (!function_exists('logError')) {
    /**
     * output error level log
     *
     * @param  string $message
     * @param  array  $context
     * @param  string $channel
     * @return void
     */
    function logError(string $message, array $context = [], string $channel = 'daily')
    {
        appLog('error', $message, $context, $channel);
    }
}

if (!function_exists('logDebug')) {
    /**
     * output debug level log
     *
     * @param  string $message
     * @param  array  $context
     * @param  string $channel
     * @return void
     */
    function logDebug(string $message, array $context = [], $channel = 'daily')
    {
        appLog('debug', $message, $context, $channel);
    }
}
