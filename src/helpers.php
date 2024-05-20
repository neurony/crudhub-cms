<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (!function_exists('get_menuable_routes')) {
    /**
     * @return array
     */
    function get_menuable_routes(): array
    {
        $routes = [];

        foreach (Route::getRoutes() as $route) {
            if (!$route->getName()) {
                continue;
            }

            if (!in_array('get', array_map('strtolower', $route->methods()))) {
                continue;
            }

            if (!in_array('web', array_map('strtolower', $route->middleware()))) {
                continue;
            }

            if (Str::startsWith($route->uri(), config('crudhub.admin.prefix', 'admin'))) {
                continue;
            }

            if (Str::startsWith($route->uri(), 'sanctum')) {
                continue;
            }

            if (Str::contains($route->uri(), ['{', '}'])) {
                continue;
            }

            $routes[] = $route;
        }

        return $routes;
    }
}