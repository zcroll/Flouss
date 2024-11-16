<?php

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routes, $output = 'active')
    {
        if (is_array($routes)) {
            return in_array(Route::currentRouteName(), $routes) ? $output : '';
        }
        return Route::currentRouteName() == $routes ? $output : '';
    }
}
