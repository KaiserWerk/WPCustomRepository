<?php

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     * @Method(["GET"])
     */
    public function adminDashboardAction()
    {
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        $db = new DBHelper();
        $user_count = $db->count('user');
        $plugin_base_count = $db->count('plugin');
        $plugin_version_count = $db->count('plugin_version');
        $theme_base_count = $db->count('theme');
        $theme_version_count = $db->count('theme_version');
    
        TemplateHelper::renderHTML('/admin/dashboard', [
            'user_count' => $user_count,
            'plugin_base_count' => $plugin_base_count,
            'plugin_version_count' => $plugin_version_count,
            'theme_base_count' => $theme_base_count,
            'theme_version_count' => $theme_version_count,
        ]);
    }
    
    /**
     * @Route("/admin/tools/mail_message", name="admin_tools_mail_message")
     * @Method(["GET"])
     */
    public function adminToolsMailmessageAction()
    {
        global $config;
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
    
        Helper::sendMail(
            'This is an email test message.',
            'WPCustomRepository Test-Mail',
            $config['mailer']['username'],
            $config['mailer']['fullname'],
            $config['mailer']['username'],
            $config['mailer']['fullname']
        );
        LoggerHelper::debug('Tries to send a mail test message.');
        Helper::setMessage('You sent a test email message.');
        Helper::redirect('/admin/dashboard');
    }
}