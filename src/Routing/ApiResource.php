<?php

namespace OZiTAG\Tager\Backend\Core\Routing;

use Illuminate\Routing\Router;

class ApiResource
{
    public static function register()
    {
        if (!Router::hasMacro('apiResourceWithMove')) {
            Router::macro('apiResourceWithMove', function ($name, $controller, $options = []) {
                $only = ['index', 'show', 'store', 'update', 'destroy', 'move'];
                if (isset($options['except'])) {
                    $only = array_diff($only, (array)$options['except']);
                }
                Router::resource($name, $controller, array_merge(['only' => $only], $options));
            });
        }

        if (!Router::hasMacro('apiResourceWithMoveCount')) {
            Router::macro('apiResourceWithMoveCount', function ($name, $controller, $options = []) {
                $only = ['index', 'show', 'store', 'update', 'destroy', 'move', 'count'];
                if (isset($options['except'])) {
                    $only = array_diff($only, (array)$options['except']);
                }
                Router::resource($name, $controller, array_merge(['only' => $only], $options));
            });
        }

        if (!Router::hasMacro('apiResourceWithCount')) {
            Router::macro('apiResourceWithCount', function ($name, $controller, $options = []) {
                $only = ['index', 'show', 'store', 'update', 'destroy', 'count'];
                if (isset($options['except'])) {
                    $only = array_diff($only, (array)$options['except']);
                }
                Router::resource($name, $controller, array_merge(['only' => $only], $options));
            });
        }
    }
}
