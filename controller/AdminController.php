<?php

$router->respond('GET', '/admin', function () {
    // just redirect to dashboard
    Helper::redirect('/admin/dashboard');
});

/**
 * Main page; the admin dashboard
 *
 * I don't know yet what to display here, but I think
 * it's gonna be some statistics
 */

$router->respond('GET', '/admin/dashboard', function ($request) {
    $db = new DBHelper();
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    
    $user_count = $db->count('user');
    $plugin_count = $db->count('plugin');
    $plugin_distinct_count = count(array_unique($db->select('plugin', 'id')));
    $theme_count = $db->count('theme');
    $theme_distinct_count = count(array_unique($db->select('theme', 'id')));
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/admin/dashboard.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});


/**
 * send a test email
 */
$router->respond('GET', '/admin/tools/test_mail_message', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
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
$router->respond('GET', '/admin/tools/test_stride_message', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    CommunicationHelper::sendStrideMessage('Dies ist eine Stride Test-Nachricht.');
    LoggerHelper::debug('Tries so send a Stride test message.');
    Helper::setMessage('You sent a test Stride message.');
    Helper::redirect('/admin/dashboard');
});


/**
 * send a test hipchat message
 */
$router->respond('GET', '/admin/tools/test_hipchat_message', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    CommunicationHelper::sendHipChatMessage('Dies ist eine HipChat Test-Nachricht.');
    LoggerHelper::debug('Tries so send a HipChat test message.');
    Helper::setMessage('You sent a test HipChat message.');
    Helper::redirect('/admin/dashboard');
});

/**
 * send a test Slack message
 */
$router->respond('GET', '/admin/tools/test_slack_message', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    CommunicationHelper::sendSlackMessage('Dies ist eine Slack Test-Nachricht.');
    LoggerHelper::debug('Tries so send a Slack test message.');
    Helper::setMessage('You sent a test Slack message.');
    Helper::redirect('/admin/dashboard');
});
