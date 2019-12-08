<?php

class ThemeVersionController extends Controller
{
    /**
     * @Route("/theme/version/[:id]/list", name="theme_version_list")
     * @Method(["GET"])
     */
    public function themeVersionListAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $id = $params->id;
    
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
    
        TemplateHelper::renderHTML('/theme/list_version.tpl.php', [
            'base_theme' => $base_theme,
            'theme_versions' => $theme_versions,
        ]);
    }
    
    /**
     * @Route("/theme/version/[:id]/show", name="theme_version_show")
     * @Method(["GET"])
     */
    public function themeVersionShowAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $db = new DBHelper();
        $theme_version = $db->get('theme_version', '*', [
            'id' => $params->id,
        ]);
    
        $base_theme = $db->get('theme', '*', [
            'id' => $theme_version['theme_entry_id'],
        ]);
    
        TemplateHelper::renderHTML('/theme/show_version.tpl.php', [
            'theme_version' => $theme_version,
            'base_theme' => $base_theme,
        ]);
    }
    
    /**
     * @Route("/theme/version/add", name="theme_version_add")
     * @Method(["GET", "POST"])
     */
    public function themeVersionAddAction()
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
        $db = new DBHelper();
        if (isset($_POST['btn_theme_version_add'])) {
            AuthHelper::requireValidCSRFToken();
            $_theme_version_add = $_POST['_theme_version_add'];
        
            $fields = [
                'theme_entry_id' => null,
                'version' => null,
                'requires_php' => null,
                'requires' => null,
                'tested' => null,
            ];
        
            foreach ($_theme_version_add as $key => $value) {
                if (!empty($_theme_version_add[$key])) {
                    $fields[$key] = $_theme_version_add[$key];
                } else {
                    if ($fields[$key] === null) {
                        unset($fields[$key]);
                    }
                }
            }
        
            $db->insert('theme_version', $fields);
        
            $base_theme = $db->get('theme', '*', [
                'id' => $_theme_version_add['theme_entry_id'],
            ]);
        
            /** upload theme version file */
            $uploaded_file = $_FILES['_theme_version_add_theme_file'];
            if (!in_array($uploaded_file['type'], ['application/zip', 'application/octet-stream',
                'application/x-zip-compressed', 'multipart/x-zip'])) {
                Helper::setMessage('Theme file has an invalid mime type!', 'danger');
                Helper::redirect('/theme/version/add');
            }
            $dir = PROJECTDIR . '/var/theme_files/' . $base_theme['slug'] . '/';
            $file_name = $file_name = $base_theme['slug'] . '_v' . $_theme_version_add['version'] . '.zip';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            move_uploaded_file($uploaded_file['tmp_name'], $dir . $file_name);
        
            Helper::setMessage('Theme version added!', 'success');
            Helper::redirect('/theme/version/' . $_theme_version_add['theme_entry_id'] . '/list');
        
        } else {
            $base_themes = $db->select('theme', [
                'id',
                'theme_name',
            ], [
                'ORDER' => [
                    'theme_name' => 'DESC',
                ]
            ]);
        
            TemplateHelper::renderHTML('/theme/add_version.tpl.php', [
                'base_themes' => $base_themes,
            ]);
        }
    }
    
    /**
     * @Route("/theme/version/[:id]/edit", name="theme_version_edit")
     * @Method(["GET", "POST"])
     */
    public function themeVersionEditAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
        $db = new DBHelper();
        if (isset($_POST['btn_theme_version_edit'])) {
            $_theme_version_edit = $_POST['_theme_version_edit'];
        
            $fields = [
                'version' => null,
                'requires_php' => null,
                'requires' => null,
            ];
        
            foreach ($_theme_version_edit as $key => $value) {
                if (array_key_exists($key, $fields)) {
                    $fields[$key] = $_theme_version_edit[$key];
                } else {
                    unset($fields[$key]);
                }
            }
        
            $db->update('theme_version', $fields, [
                'id' => $params->id,
            ]);
        
            Helper::setMessage('Changes saved!');
            Helper::redirect('/theme/base/list');
        } else {
            $theme_version = $db->get('theme_version', '*', [
                'id' => $params->id,
            ]);
        
            $base_theme = $db->get('theme', '*', [
                'id' => $theme_version['theme_entry_id'],
            ]);
        
            TemplateHelper::renderHTML('/theme/edit_version.tpl.php', [
                'base_theme' => $base_theme,
                'theme_version' => $theme_version,
            ]);
        }
    }
    
    /**
     * @Route("/theme/version/[:id]/remove", name="theme_version_remove")
     * @Method(["GET"])
     */
    public function themeVersionRemoveAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    }
}
