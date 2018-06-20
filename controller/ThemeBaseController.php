<?php

$router->with('/theme/base', function () use ($router) {
    
    /**
     * List all base themes
     */
    $router->respond('GET', '/list', function () {
        AuthHelper::requireLogin();
        
        $db = new DBHelper();
        $base_themes = $db->select('theme', [
            'id',
            'theme_name',
            'slug',
        ], [
            'ORDER' => [
                'theme_name' => 'DESC',
            ]
        ]);
        
        Helper::renderPage('/theme/list_base.tpl.php', [
            'base_themes' => $base_themes,
        ]);
    });
    
    /**
     * Add a base theme
     */
    $router->respond(['GET', 'POST'], '/add', function () {
        AuthHelper::requireLogin();
        if (isset($_POST['btn_theme_base_add'])) {
        
        } else {
            Helper::renderPage('/theme/add_base.tpl.php');
        }
    });
    
    /**
     * Edit a base theme
     */
    $router->respond(['GET', 'POST'], '/[:id]/edit', function ($request) {
        AuthHelper::requireLogin();
        if (isset($_POST['btn_theme_base_edit'])) {
            AuthHelper::checkCSRFToken();
            $_theme_base_edit = $_POST['_theme_base_edit'];
            
            $fields = [
                'theme_name',
                'slug',
                'author',
                'author_homepage',
                'url',
                'section_description',
            ];
            
            foreach ($_theme_base_edit as $key => $value) {
                if (!empty($fields[$key])) {
                    $fields[$key] = $value;
                } else {
                    unset($fields[$key]);
                }
            }
            
            if (count($fields) === 0) {
                Helper::setMessage('No changes were made.');
                Helper::redirect('/theme/base/list');
            }
            
            $db = new DBHelper();
            $db->update('theme', $fields, [
                'id' => $request->id,
            ]);
            
            Helper::setMessage('Base theme updated!', 'success');
            Helper::redirect('/theme/base/list');
        } else {
            $db = new DBHelper();
            $base_theme = $db->get('theme', '*', [
                'id' => $request->id,
            ]);
            
            if ($base_theme === false) {
                Helper::setMessage('Base theme not found!', 'danger');
                Helper::redirect('/theme/base/list');
            }
            
            Helper::renderPage('/theme/edit_base.tpl.php', [
                'base_theme' => $base_theme,
            ]);
        }
    });
    
    /**
     * Remove a base theme
     */
    $router->respond('GET', '/[:id]/remove', function () {
        AuthHelper::requireLogin();
        
    });
    
});