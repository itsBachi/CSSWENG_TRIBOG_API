<?php

namespace App\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as BaseLogger;

abstract class Logger
{
  public static $disabled = false;

  abstract protected static function getLoggerName();
  abstract protected static function getLoggerPath();
  abstract protected static function getModule();

  protected static function addLog($logLevel, $action, $context)
  {
    if (static::$disabled) {
      return;
    }

    $logger = new BaseLogger(static::getLoggerName());
    $logger->pushHandler(new StreamHandler(storage_path(static::getLoggerPath())));
    $logTitle = '[' . strtoupper(str_replace(' ', '', static::getModule()) . ' - ' . $action) . ']';
    $logger->$logLevel($logTitle, $context);
  }

  public static function info($methodName, $parameters, $message)
  {
    self::addLog('info', $methodName, self::buildData($parameters, $message));
  }

  public static function error($methodName, $parameters, $message)
  {
    self::addLog('error', $methodName, self::buildData($parameters, $message));
  }

  public static function warning($methodName, $parameters, $message)
  {
    self::addLog('warning', $methodName, self::buildData($parameters, $message));
  }

  protected static function buildData($parameters, $message)
  {
    return [
      'message' => $message,
      'parameter' => $parameters
    ];
  }
}
