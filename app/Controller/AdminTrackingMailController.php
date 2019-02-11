<?php

class AdminTrackingMailController extends Controller
{
    /**
     * @Route("/admin/tracking-mail/list", name="admin_trackingmail_list")
     * @Method(["GET"])
     */
    public function adminTrackingMailListAction()
    {
        global $config;
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
    
        if ($config['site']['email_tracking'] === false) {
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
    
        TemplateHelper::renderHTML('/admin/tracking_mail/list.tpl.php', [
            'mails' => $mails,
        ]);
    }
}
