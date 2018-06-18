<?php

/**
 * Lists all email trackings with statistics
 */
$router->respond('GET', '/admin/tracking_mail/list', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::setMessage('Please login first!', 'warning');
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    
    if ((bool)getenv('EMAIL_TRACKING_ENABLED') === 'false') {
        die('<p>Email tracking is not enabled!</p>');
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
    #echo '<pre>';var_dump($mails);die;
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/admin/tracking_mail/list.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});
