<?php
/**
 * Main page; the admin dashboard
 *
 * I don't know yet what to display here, but I think
 * it's gonna be some statistics
 */

$klein->respond('GET', '/admin/dashboard', function ($request) {
    $db = new DBHelper();
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    
    $user_count = $db->count('user');
    $plugin_count = $db->count('plugin');
    $plugin_distinct_count = count(array_unique($db->select('plugin', 'id')));
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/admin/dashboard.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

/**
 * Lists all email trackings with statistics
 */
$klein->respond('GET', '/admin/tracking_mail/list', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    
    if ((bool)getenv('EMAIL_TRACKING_ENABLED') === 'false') {
        echo '<p>Email tracking is not enabled!</p>';
        die;
    }
    
    $db = new DBHelper();
    $stm = $db->pdo->prepare("
        SELECT
            s.id as id,
            s.confirmation_token as confirmation_token,
            s.recipient as recipient,
            s.sent_at as sent_at,
            s.token_used_at as token_used_at,
            COUNT(t.id) as tracking_count
        FROM
            mail_sent s,
            mail_tracked t
        WHERE
            t.mail_entry_id = s.id
        GROUP BY s.id
        HAVING tracking_count > 0
        ORDER BY
            s.sent_at
        DESC
    ");
    $stm->execute();
    $mails = $stm->fetchAll(PDO::FETCH_ASSOC);
   # echo '<pre>';var_dump($mails);die;
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/admin/tracking_mail/list.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

/**
 * send a test email
 */
$klein->respond('GET', '/admin/tools/test_mail_message', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
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
    Helper::redirect('/admin/dashboard');
});

/**
 * send a test stride message
 */
$klein->respond('GET', '/admin/tools/test_stride_message', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    CommunicationHelper::sendStrideMessage('Dies ist eine Stride Test-Nachricht.');
    LoggerHelper::debug('Tries so send a Stride test message.');
    Helper::redirect('/admin/dashboard');
});


/**
 * send a test hipchat message
 */
$klein->respond('GET', '/admin/tools/test_hipchat_message', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    CommunicationHelper::sendHipChatMessage('Dies ist eine HipChat Test-Nachricht.');
    LoggerHelper::debug('Tries so send a HipChat test message.');
    Helper::redirect('/admin/dashboard');
});

/**
 * send a test Slack message
 */
$klein->respond('GET', '/admin/tools/test_slack_message', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    CommunicationHelper::sendSlackMessage('Dies ist eine Slack Test-Nachricht.');
    LoggerHelper::debug('Tries so send a Slack test message.');
    Helper::redirect('/admin/dashboard');
});
