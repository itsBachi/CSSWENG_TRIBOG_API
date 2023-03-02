<?php

namespace App\Logger;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SystemLogger extends Logger
{
    protected static function getLoggerName()
    {
        return 'virtualid';
    }

    protected static function getLoggerPath()
    {
        return 'logs/laravel.log';
    }

    protected static function getModule()
    {
        return 'System';
    }

    protected static function buildData($parameters, $message)
    {
        return [
            'user_id' => Auth::user() ? Auth::user()->id : 0,
            'message' => $message,
            'parameter' => $parameters,
            'ip_address' => Request::ip()
        ];
    }
}
