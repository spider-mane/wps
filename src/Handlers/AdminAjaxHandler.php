<?php

namespace Spidermane\WpOops\Handlers;

use Whoops\Exception\Formatter;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Util\Misc;

class AdminAjaxHandler extends JsonResponseHandler
{
    /**
     * @return bool
     */
    protected function isAjaxRequest()
    {
        return defined('DOING_AJAX') && DOING_AJAX;
    }

    /**
     * @return int
     */
    public function handle()
    {
        if (!$this->isAjaxRequest()) {
            return Handler::DONE;
        }

        $response = array(
            'success' => false,
            'data' => Formatter::formatExceptionAsDataArray($this->getInspector(), $this->addTraceToOutput()),
        );

        if (Misc::canSendHeaders()) {
            header('Content-Type: application/json; charset=' . get_option('blog_charset'));
        }

        echo wp_json_encode($response, JSON_PRETTY_PRINT);

        return Handler::QUIT;
    }
}
