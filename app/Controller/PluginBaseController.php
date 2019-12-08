<?php

class PluginBaseController extends Controller
{
    /**
     * @Route("/plugin/base/list", name=""plugin_base_list")
     * @Method(["GET"])
     */
    public function pluginBaseListAction()
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $db = new DBHelper();
        $base_plugins = $db->select('plugin', '*');
        $base_plugins_count = count($base_plugins);
        for ($i = 0; $i < $base_plugins_count; ++$i) {
            $base_plugins[$i]['entries'] = $db->select('plugin_version', '*', [
                'plugin_entry_id' => $base_plugins[$i]['id'],
                'ORDER' => [
                    'version' => 'DESC',
                ],
                'LIMIT' => 3,
            ]);
        }
    
        TemplateHelper::renderHTML('/plugin/list_base.tpl.php', [
            'base_plugins' => $base_plugins,
        ]);
    }
    
    /**
     * @Route("/plugin/base/[:id]/show", name="plugin_base_show")
     * @Method(["GET"])
     */
    public function pluginBaseShowAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $id = $request->id ?? null;
    
        if ($id !== null) {
            $db = new DBHelper();
        
            $base_plugin = $db->get('plugin', '*', [
                'id' => $id,
            ]);
        
            TemplateHelper::renderHTML('/plugin/show_base.tpl.php', [
                'base_plugin' => $base_plugin,
            ]);
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/plugin/list');
        }
    }
    
    /**
     * @Route("/plugin/base/add", name="plugin_base_add")
     * @Method(["GET", "POST"])
     */
    public function pluginBaseAddAction()
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        if (isset($_POST['btn_plugin_base_add'])) {
            AuthHelper::requireValidCSRFToken();
        
            $_plugin_base_add = $_POST['_plugin_base_add'];
            if (
                !empty($_plugin_base_add['plugin_name']) &&
                !empty($_plugin_base_add['slug']) &&
                !empty($_plugin_base_add['homepage']) &&
                !empty($_plugin_base_add['section_description'])
            ) {
                if (in_array($_FILES['_plugin_add_banner_low']['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
                    $parts = explode('.', $_FILES['_plugin_add_banner_low']['name']);
                    $end = $parts[count($parts)-1];
                    $dir = PROJECTDIR . '/public/banner_files/' . $_plugin_base_add['slug'] . '/';
                    $file_name = $_plugin_base_add['slug'] . '_banner_low.' . $end;
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0775, true);
                    }
                    move_uploaded_file($_FILES['_plugin_add_banner_low']['tmp_name'], $dir . $file_name);
                } else {
                    LoggerHelper::debug('banner low has incorrect file type', 'warn');
                }
            
                if (in_array($_FILES['_plugin_add_banner_high']['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
                    $parts = explode('.', $_FILES['_plugin_add_banner_high']['name']);
                    $end = $parts[count($parts)-1];
                    $dir = PROJECTDIR . '/public/banner_files/' . $_plugin_base_add['slug'] . '/';
                    $file_name = $_plugin_base_add['slug'] . '_banner_high.' . $end;
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0775, true);
                    }
                    move_uploaded_file($_FILES['_plugin_add_banner_high']['tmp_name'], $dir . $file_name);
                } else {
                    LoggerHelper::debug('banner high has incorrect file type', 'warn');
                }
            
                $allowable_tags = '<b><i><p><strong><ul><ol><li><em><a><img>';
            
                $db = new DBHelper();
                $db->insert('plugin', [
                    'plugin_name' => $_plugin_base_add['plugin_name'],
                    'slug' => $_plugin_base_add['slug'],
                    'homepage' => $_plugin_base_add['homepage'],
                    'section_description' => strip_tags($_plugin_base_add['section_description'], $allowable_tags),
                    'section_installation' => strip_tags($_plugin_base_add['section_installation'], $allowable_tags),
                    'section_faq' => strip_tags($_plugin_base_add['section_faq'], $allowable_tags),
                    'section_screenshots' => strip_tags($_plugin_base_add['section_screenshots'], $allowable_tags),
                    'section_changelog' => strip_tags($_plugin_base_add['section_changelog'], $allowable_tags),
                    'section_other_notes' => strip_tags($_plugin_base_add['section_other_notes'], $allowable_tags),
                    'last_updated' => date('Y-m-d H:i:s'),
                ]);
            
                Helper::setMessage('Base plugin added!', 'success');
                Helper::redirect('/plugin/base/list');
            } else {
                Helper::setMessage('Please fill in all required fields!', 'warning');
                Helper::redirect('/plugin/base/add');
            }
            Helper::redirect('/plugin/base/list');
        } else {
            TemplateHelper::renderHTML('/plugin/add_base.tpl.php');
        }
    }
    
    /**
     * @Route("/plugin/base/[:id]/edit", name="plugin_base_edit")
     * @Method(["GET", "POST"])
     */
    public function pluginBaseEditAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
        $id = (int)$params->id;
        $db = new DBHelper();
        if (isset($_POST['btn_plugin_base_edit'])) {
            AuthHelper::requireValidCSRFToken();
            $_plugin_base_edit = $_POST['_plugin_base_edit'];
            if (isset($_FILES['_plugin_base_edit_banner_low'])) {
                if (in_array($_FILES['_plugin_base_edit_banner_low']['type'], [
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                ])) {
                    $parts = explode('.', $_FILES['_plugin_base_edit_banner_low']['name']);
                    $end = $parts[count($parts) - 1];
                    $dir = PROJECTDIR . '/public/banner_files/' . $_plugin_base_edit['slug'] . '/';
                    $file_name = $file_name = $_plugin_base_edit['slug'] . '_banner_low.' . $end;
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0775, true);
                    }
                    move_uploaded_file($_FILES['_plugin_base_edit_banner_low']['tmp_name'], $dir . $file_name);
                } else {
                    LoggerHelper::debug('banner low has incorrect file type', 'warn');
                }
            }
            
            if (isset($_FILES['_plugin_base_edit_banner_high'])) {
                if (in_array($_FILES['_plugin_base_edit_banner_high']['type'], [
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                ])) {
                    $parts = explode('.', $_FILES['_plugin_base_edit_banner_high']['name']);
                    $end = $parts[count($parts) - 1];
                    $dir = PROJECTDIR . '/public/banner_files/' . $_plugin_base_edit['slug'] . '/';
                    $file_name = $file_name = $_plugin_base_edit['slug'] . '_banner_high.' . $end;
                    if (!is_dir($dir)) {
                        @mkdir($dir, 0775, true);
                    }
                    move_uploaded_file($_FILES['_plugin_base_edit_banner_high']['tmp_name'], $dir . $file_name);
                } else {
                    LoggerHelper::debug('banner high has incorrect file type', 'warn');
                }
            }
    
            $allowable_tags = '<b><i><p><strong><ul><ol><li><em><a><img>';
            
            $fields = [
                'plugin_name' => $_plugin_base_edit['plugin_name'],
                'slug' => $_plugin_base_edit['slug'],
                'homepage' => $_plugin_base_edit['homepage'],
                'section_description' => strip_tags($_plugin_base_edit['section_description'], $allowable_tags),
                'section_installation' => strip_tags($_plugin_base_edit['section_installation'], $allowable_tags),
                'section_faq' => strip_tags($_plugin_base_edit['section_faq'], $allowable_tags),
                'section_screenshots' => strip_tags($_plugin_base_edit['section_screenshots'], $allowable_tags),
                'section_changelog' => strip_tags($_plugin_base_edit['section_changelog'], $allowable_tags),
                'section_other_notes' => strip_tags($_plugin_base_edit['section_other_notes'], $allowable_tags),
                'last_updated' => date('Y-m-d H:i:s'),
            ];
            
            foreach ($_plugin_base_edit as $key => $value) {
                if (!empty($_plugin_base_edit[$key])) {
                    $fields[$key] = $_plugin_base_edit[$key];
                } else {
                    unset($fields[$key]);
                }
            }
        
            $db->update('plugin', $fields, [
                'id' => $id,
            ]);
            #die;
        
            Helper::setMessage('Changes saved!', 'success');
            Helper::redirect('/plugin/base/list');
        } else {
            $plugin = $db->get('plugin', '*', [
                'id' => $id,
            ]);
        
            TemplateHelper::renderHTML('/plugin/edit_base.tpl.php', [
                'plugin' => $plugin,
            ]);
        }
    }
    
    /**
     * @Route("/plugin/base/[:id]/remove", name="plugin_base_remove")
     * @Method(["GET"])
     */
    public function pluginBaseRemoveAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
        $id = (int)$params->id;
        $db = new DBHelper();
        
        $db->delete('plugin', [
            'id' => $id,
        ]);
        
        Helper::setMessage('Base plugin successfully removed.', 'success');
        Helper::redirect('/plugin/base/list');
    }
}
