<?php

$router->with('/theme/version', function () use ($router) {
    
    /**
     * List all versions for base theme ID
     */
    $router->respond('GET', '/[:id]/list', function ($request) {
        AuthHelper::requireLogin();
        
        $id = $request->id;
        
        $db = new DBHelper();
        
        $base_theme = $db->get('theme', '*', [
            'id' => $id,
        ]);
        
        if ($base_theme === false) {
            Helper::setMessage('Base theme not found!', 'danger');
            Helper::redirect('/theme/base/list');
        }
        
        $theme_versions = $db->select('theme_version', '*', [
            'theme_entry_id' => $id,
        ]);
    
        if ($theme_versions === false) {
            Helper::setMessage('No theme versions found!', 'danger');
            Helper::redirect('/theme/base/list');
        }
        
        Helper::renderPage('/theme/list_version.tpl.php', [
            'base_theme' => $base_theme,
            'theme_versions' => $theme_versions,
        ]);
    });
    
    /**
     * Show details of a theme version
     */
    $router->respond('GET', '/[:id]/show', function ($request) {
        AuthHelper::requireLogin();
    });
    
    /**
     * Add a theme version
     */
    $router->respond('GET', '/add', function ($request) {
        AuthHelper::requireLogin();
        $db = new DBHelper();
        if (isset($_POST['btn_theme_version_add'])) {
        
        } else {
            $base_themes = $db->select('theme', [
                'id',
                'theme_name',
            ], [
                'ORDER' => [
                    'theme_name' => 'DESC',
                ]
            ]);
            
            Helper::renderPage('/theme/add_version.tpl.php', [
                'base_themes' => $base_themes,
            ]);
        }
    });
    
    /**
     * Edit a theme version
     */
    $router->respond('GET', '/[:id]/edit', function ($request) {
        AuthHelper::requireLogin();
        $db = new DBHelper();
        if (isset($_POST['btn_theme_version_edit'])) {
        
        } else {
            $base_themes = $db->select('theme', [
                'id',
                'theme_name',
            ], [
                'ORDER' => [
                    'theme_name' => 'DESC',
                ]
            ]);
        
            Helper::renderPage('/theme/edit_version.tpl.php', [
                'base_themes' => $base_themes,
            ]);
        }
    });
    
    /**
     * Remove a theme version
     */
    $router->respond('GET', '/[:id]/remove', function ($request) {
        AuthHelper::requireLogin();
    });
    
});