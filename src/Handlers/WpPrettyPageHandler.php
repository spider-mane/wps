<?php

namespace Spidermane\WpOops\Handlers;

use WP;
use WP_Post;
use WP_Query;
use Whoops\Handler\PrettyPageHandler;

class WpPrettyPageHandler extends PrettyPageHandler
{
    public function __construct()
    {
        parent::__construct();
        array_map([$this, 'addDataTableCallback'], $this->getDefaultDataTableCallbacks());
    }

    protected function getDefaultDataTableCallbacks(): array
    {
        return [
            '$wp' => function () {
                global $wp;

                if (!$wp instanceof WP) {
                    return [];
                }

                $output = get_object_vars($wp);
                unset($output['private_query_vars']);
                unset($output['public_query_vars']);

                return array_filter($output);
            },
            '$wp_query' => function () {
                global $wp_query;

                if (!$wp_query instanceof WP_Query) {
                    return [];
                }

                $output = get_object_vars($wp_query);
                $output['query_vars'] = array_filter($output['query_vars']);
                unset($output['posts']);
                unset($output['post']);

                return array_filter($output);
            },
            '$post' => function () {
                $post = get_post();

                if (!$post instanceof WP_Post) {
                    return [];
                }

                return get_object_vars($post);
            },
        ];
    }
}
