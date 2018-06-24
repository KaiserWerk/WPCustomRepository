<?php
AuthHelper::init();
$router->with('/admin/tracking-mail', function () use ($router) {
    
    /**
     * Lists all email trackings with statistics
     */
    $router->respond('GET', '/list', function ($request) {
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
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
        
        Helper::renderPage('/admin/tracking_mail/list.tpl.php', [
            'mails' => $mails,
        ]);
    });
    
});