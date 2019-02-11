<?php

class LicenseController extends Controller
{
    /**
     * @Route("/license/list", name="license_list")
     * @Method(["GET"])
     */
    public function licenseListAction()
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $db = new DBHelper();
        $licenses = $db->select('license', '*', [
            'ORDER' => [
                'created_at' => 'DESC',
            ]
        ]);
    
        TemplateHelper::renderHTML('/license/list.tpl.php', [
            'licenses' => $licenses,
        ]);
    }
    
    /**
     * @Route("/license/add", name="license_add")
     * @Method(["GET", "POST"])
     */
    public function licenseAddAction()
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $db = new DBHelper();
        if (isset($_POST['license_button'])) {
            $_add = $_POST['_add'];
        
            AuthHelper::requireValidCSRFToken();
            AuthHelper::requireValidHonepot();
        
            if (empty($_add['license_user']) || empty($_add['license_key']) || empty($_add['valid_until'])) {
                Helper::setMessage('Please fill in all required fields!', 'warning');
                Helper::redirect('/license/add');
            }
        
            if ($db->has('license', [
                'license_key' => $_add['license_key'],
            ])) {
                Helper::setMessage('This license key is already in use!', 'danger');
                Helper::redirect('/license/add');
            }
        
            if ($db->has('license', [
                'AND' => [
                    'license_user' => $_add['license_user'],
                    'license_host' => $_add['license_host'],
                    'OR' => [
                        'plugin_entry_id' => $_add['plugin_entry_id'],
                        'theme_entry_id' => $_add['theme_entry_id'],
                    ]
                ]
            ])) {
                Helper::setMessage('This license already exists! Please renew as needed.', 'danger');
                Helper::redirect('/license/add');
            }
        
            $bool = $db->insert('license', [
                'plugin_entry_id' => $_add['plugin_entry_id'],
                'theme_entry_id' => $_add['theme_entry_id'],
                'license_user' => strtoupper($_add['license_user']),
                'license_key' => $_add['license_key'],
                'license_host' => $_add['license_host'],
                'valid_until' => $_add['valid_until'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        
            if ($bool === false) {
                Helper::setMessage('Database error!', 'danger');
                Helper::redirect('/license/list');
            }
        
            Helper::setMessage('License added!', 'success');
            Helper::redirect('/license/list');
        } else {
            /** get base plugins */
            $base_plugins = $db->select('plugin', [
                'plugin_name',
                'slug',
            ]);
        
            /** get base themes */
            $base_themes = $db->select('theme', [
                'theme_name',
                'slug',
            ]);
        
            $key = AuthHelper::generateToken(200);
        
            TemplateHelper::renderHTML('/license/add.tpl.php', [
                'base_plugins' => $base_plugins,
                'base_themes' => $base_themes,
                'key' => $key,
            ]);
        }
    }
    
    /**
     * @Route("/license/[:id]/renew", name="license_renew")
     * @Method(["GET"])
     */
    public function licenseRenewAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $db = new DBHelper();
        $id = $params->id ?? null;
    
        if ($id !== null) {
            $license = $db->get('license', [
                'id',
                'valid_until',
                'renewals',
            ], [
                'id' => $id,
            ]);
        
            if (new \DateTime($license['valid_until']) <= new \DateTime('+12 month')) {
                // renew until end of next year
                $db->update('license', [
                    'valid_until' => date('Y') + 1 . '-12-31 23:59:59',
                    'renewals' => $license['renewals'] + 1,
                ], [
                    'id' => $id,
                ]);
            
                Helper::setMessage('License renewed!', 'success');
                Helper::redirect('/license/list');
            } else {
                Helper::setMessage('No changes were made.');
                Helper::redirect('/license/list');
            }
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/license/list');
        }
    }
    
    /**
     * @Route("/license/[:id]/edit", name="license_edit")
     * @Method(["GET", "POST"])
     */
    public function licenseEditAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $id = $params->id ?? null;
        $db = new DBHelper();
        if ($id !== null) {
            if (isset($_POST['btn_license_edit'])) {
                $_edit = $_POST['_edit'];
            
                AuthHelper::requireValidCSRFToken();
                AuthHelper::requireValidHonepot();
            
                if (empty($_edit['license_user']) || empty($_edit['license_key']) || empty($_edit['valid_until'])) {
                    Helper::setMessage('Please fill in all required fields!', 'warning');
                    Helper::redirect('/license/'.$id.'/edit');
                }
            
                if ($db->has('license', [
                    'license_key' => $_edit['license_key'],
                    'id[!]' => $id,
                ])) {
                    Helper::setMessage('This license key is already in use!', 'danger');
                    Helper::redirect('/license/'.$id.'/edit');
                }
            
                if ($db->has('license', [
                    'AND' => [
                        'license_user' => $_edit['license_user'],
                        'license_host' => $_edit['license_host'],
                        'plugin_slug' => $_edit['plugin_slug'],
                        'id[!]' => $id,
                    ]
                ])) {
                    Helper::setMessage('This license already exists! Please renew as needed!', 'danger');
                    Helper::redirect('/license/' . $id . '/edit');
                }
            
                $db->update('license', [
                    'license_user' => $_edit['license_user'],
                    'license_key' => $_edit['license_key'],
                    'license_host' => $_edit['license_host'],
                    'plugin_slug' => $_edit['plugin_slug'],
                    'valid_until' => $_edit['valid_until'],
                ], [
                    'id' => $id,
                ]);
            
                Helper::setMessage('Changes saved!', 'success');
                Helper::redirect('/license/list');
            } else {
                /** get base plugins */
                $base_plugins = $db->select('plugin', [
                    'plugin_name',
                    'slug',
                ]);
            
                /** get base themes */
                $base_themes = $db->select('theme', [
                    'theme_name',
                    'slug',
                ]);
            
                /** get data for license */
                $license = $db->get('license', '*', [
                    'id' => $id,
                ]);
            
                TemplateHelper::renderHTML('/license/edit.tpl.php', [
                    'base_plugins' => $base_plugins,
                    'base_themes' => $base_themes,
                    'license' => $license,
                ]);
            }
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/license/list');
        }
    }
    
    /**
     * @Route("/license/[:id]/remove", name="license_remove")
     * @Method(["GET"])
     */
    public function licenseRemoveAction($params)
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
    
        $id = $params->id;
    
        $db = new DBHelper();
        $bool = $db->delete('license', [
            'id' => $id,
        ]);
    
        if ($bool !== false) {
            Helper::setMessage('License removed!', 'success');
            Helper::redirect('/license/list');
        } else {
            Helper::setMessage('You are trying to access an invalid entry!', 'danger');
            Helper::redirect('/license/list');
        }
    }
}
