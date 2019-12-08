<?php

class ThemeBaseController extends Controller
{
    /**
     * @Route("/theme/base/list", name="theme_base_list")
     * @Method(["GET"])
     */
    public function themeBaseListAction()
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $db = new DBHelper();
        $base_themes = $db->select('theme', '*', [
            'ORDER' => [
                'theme_name' => 'DESC',
            ]
        ]);
    
        TemplateHelper::renderHTML('/theme/list_base.tpl.php', [
            'base_themes' => $base_themes,
        ]);
    }
    
    /**
     * @Route("/theme/base/[:id]/show", name="theme_base_show")
     * @Method(["GET"])
     */
    public function themeBaseShowAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $id = $params->id ?? null;
        
        $db = new DBHelper();
        $base_theme = $db->get('theme', '*', [
            'id' => $id,
        ]);
    
        TemplateHelper::renderHTML('/theme/show_base.tpl.php', [
            'base_theme' => $base_theme,
        ]);
    }
    
    /**
     * @Route("/theme/base/add", name="theme_base_add")
     * @Method(["GET", "POST"])
     */
    public function themeBaseAddAction()
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
        if (isset($_POST['btn_theme_base_add'])) {
            AuthHelper::checkCSRFToken();
            $_theme_base_add = $_POST['_theme_base_add'];
        
            $fields = [
                'theme_name' => '',
                'slug' => '',
                'author' => '',
                'author_homepage' => '',
                'url' => '',
                'section_description' => '',
            ];
        
            foreach ($_theme_base_add as $key => $value) {
                if (!empty($_theme_base_add[$key])) {
                    $fields[$key] = $_theme_base_add[$key];
                } else {
                    unset($fields[$key]);
                }
            }
        
            #var_dump($fields);die;
        
            if (count($fields) === 0) {
                Helper::setMessage('No values were entered.');
                Helper::redirect('/theme/base/list');
            }
        
            $db = new DBHelper();
            $db->insert('theme', $fields);
        
            Helper::setMessage('Base theme added!', 'success');
            Helper::redirect('/theme/base/list');
        } else {
            TemplateHelper::renderHTML('/theme/add_base.tpl.php');
        }
    }
    
    /**
     * @Route("/theme/base/[:id]/edit", name="theme_base_edit")
     * @Method(["GET", "POST"])
     */
    public function themeBaseEditAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
        if (isset($_POST['btn_theme_base_edit'])) {
            AuthHelper::checkCSRFToken();
            $_theme_base_edit = $_POST['_theme_base_edit'];
        
            $fields = [
                'theme_name' => '',
                'slug' => '',
                'author' => '',
                'author_homepage' => '',
                'url' => '',
                'section_description' => '',
            ];
        
            foreach ($_theme_base_edit as $key => $value) {
                if (!empty($_theme_base_edit[$key])) {
                    $fields[$key] = $_theme_base_edit[$key];
                } else {
                    unset($fields[$key]);
                }
            }
        
            #var_dump($fields);die;
        
            if (count($fields) === 0) {
                Helper::setMessage('No changes were made.');
                Helper::redirect('/theme/base/list');
            }
        
            $db = new DBHelper();
            $db->update('theme', $fields, [
                'id' => $params->id,
            ]);
        
            Helper::setMessage('Base theme updated!', 'success');
            Helper::redirect('/theme/base/list');
        } else {
            $db = new DBHelper();
            $base_theme = $db->get('theme', '*', [
                'id' => $params->id,
            ]);
        
            if ($base_theme === false) {
                Helper::setMessage('Base theme not found!', 'danger');
                Helper::redirect('/theme/base/list');
            }
        
            TemplateHelper::renderHTML('/theme/edit_base.tpl.php', [
                'base_theme' => $base_theme,
            ]);
        }
    }
    
    /**
     * @Route("/theme/base/[:id]/remove", name="theme_base_remove")
     * @Method(["GET"])
     */
    public function themeBaseRemoveAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
        $db = new DBHelper();
        $db->delete('theme', [
            'id' => $params->id,
        ]);
    
        Helper::setMessage('Base theme removed!', 'success');
        Helper::redirect('/theme/base/list');
    }
}
