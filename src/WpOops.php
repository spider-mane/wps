<?php

namespace Spidermane\WpOops;

use Spidermane\WpOops\Handlers\AdminAjaxHandler;
use Spidermane\WpOops\Handlers\RestApiHandler;
use Spidermane\WpOops\Handlers\WpPrettyPageHandler;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PlainTextHandler;
use Whoops\Run;
use Whoops\RunInterface;

class WpOops
{
    public static function register(): RunInterface
    {
        $run = new Run();

        $run->prependHandler(static::getDefaultHtmlHandler())
            ->pushHandler(static::getDefaultAdminAjaxHandler())
            ->pushHandler(static::getDefaultRestApiHandler())
            ->pushHandler(static::getDefaultCommandLineHandler())
            ->register();

        ob_start();

        return $run;
    }

    protected static function getDefaultHtmlHandler(): HandlerInterface
    {
        $handler = new WpPrettyPageHandler();
        $handler->addEditor('phpstorm-remote-call', 'http://localhost:8091?message=%file:%line');

        return $handler;
    }

    protected static function getDefaultAdminAjaxHandler(): HandlerInterface
    {
        $handler = new AdminAjaxHandler();
        $handler->addTraceToOutput(true);

        return $handler;
    }

    protected static function getDefaultRestApiHandler(): HandlerInterface
    {
        $handler = new RestApiHandler();
        $handler->addTraceToOutput(true);

        return $handler;
    }

    protected static function getDefaultCommandLineHandler(): HandlerInterface
    {
        return new PlainTextHandler();
    }
}
