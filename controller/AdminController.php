<?php
AuthHelper::init();
$router->with('/admin', function () use ($router) {
    
    /** just to redirect to dashboard */
    $router->respond('GET', '/', function () {
        Helper::redirect('/admin/dashboard');
    });
    
    /**
     * Main page; the admin dashboard
     *
     * I don't know yet what to display here, but I think
     * it's gonna be some statistics
     */
    
    $router->respond('GET', '/dashboard', function ($request) {
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        $db = new DBHelper();
        $user_count = $db->count('user');
        $plugin_base_count = $db->count('plugin');
        $plugin_version_count = $db->count('plugin_version');
        $theme_base_count = $db->count('theme');
        $theme_version_count = $db->count('theme_version');
        
        Helper::renderPage('/admin/dashboard.tpl.php', [
            'user_count' => $user_count,
            'plugin_base_count' => $plugin_base_count,
            'plugin_version_count' => $plugin_version_count,
            'theme_base_count' => $theme_base_count,
            'theme_version_count' => $theme_version_count,
        ]);
    });
    
    
    /**
     * send a test email
     */
    $router->respond('GET', '/tools/mail-message', function ($request) {
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        CommunicationHelper::sendMail(
            'This is a Test-Mail.',
            'WPCustomRepository Test-Mail',
            getenv('MAILER_USER'),
            getenv('MAILER_USER_NAME'),
            getenv('MAILER_USER'),
            getenv('MAILER_USER_NAME')
        );
        LoggerHelper::debug('Tries so send a mail test message.');
        Helper::setMessage('You sent a test email message.');
        Helper::redirect('/admin/dashboard');
    });
    
    /**
     * send a test stride message
     */
    $router->respond('GET', '/tools/stride-message', function ($request) {
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        CommunicationHelper::sendStrideMessage('Dies ist eine Stride Test-Nachricht.');
        LoggerHelper::debug('Tries so send a Stride test message.');
        Helper::setMessage('You sent a test Stride message.');
        Helper::redirect('/admin/dashboard');
    });
    
    
    /**
     * send a test hipchat message
     */
    $router->respond('GET', '/tools/hipchat-message', function ($request) {
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        CommunicationHelper::sendHipChatMessage('Dies ist eine HipChat Test-Nachricht.');
        LoggerHelper::debug('Tries so send a HipChat test message.');
        Helper::setMessage('You sent a test HipChat message.');
        Helper::redirect('/admin/dashboard');
    });
    
    /**
     * send a test Slack message
     */
    $router->respond('GET', '/tools/slack-message', function ($request) {
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        CommunicationHelper::sendSlackMessage('Dies ist eine Slack Test-Nachricht.');
        LoggerHelper::debug('Tries so send a Slack test message.');
        Helper::setMessage('You sent a test Slack message.');
        Helper::redirect('/admin/dashboard');
    });
    
});