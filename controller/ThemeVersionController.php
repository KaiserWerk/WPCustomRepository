<?php

$router->with('/theme/version', function () use ($router) {
    
    /**
     * List all versions for base theme ID
     */
    $router->respond('GET', '/[:id]/list', function ($request) {
        AuthHelper::requireLogin();
    });
    
    /**
     * Add a theme version
     */
    $router->respond('GET', '/add', function ($request) {
        AuthHelper::requireLogin();
    });
    
    /**
     * Edit a theme version
     */
    $router->respond('GET', '/[:id]/edit', function ($request) {
        AuthHelper::requireLogin();
    });
    
    /**
     * Remove a theme version
     */
    $router->respond('GET', '/[:id]/remove', function ($request) {
        AuthHelper::requireLogin();
    });
    
    /**
     * Show details of a theme version
     */
    $router->respond('GET', '/[:id]/show', function ($request) {
        AuthHelper::requireLogin();
    });
});