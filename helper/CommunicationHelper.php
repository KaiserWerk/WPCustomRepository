<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class CommunicationHelper
{
    /**
     * Bundles all notification functions
     */
    public static function sendNotification($message)
    {
        self::sendStrideMessage($message);
        self::sendSlackMessage($message);
    }
    
    /**
     * Sends a message to a Stride conversation (room)
     *
     * @param string $message
     */
    public static function sendStrideMessage($message)
    {
        $params = array(
            'body' => array(
                'version' => 1,
                'type' => 'doc',
                'content' => array(
                    array(
                        'type' => 'paragraph',
                        'content' => array(
                            array(
                                'type' => 'text',
                                'text' => $message,
                            )
                        )
                    )
                )
            )
        );

        $url = sprintf('https://api.atlassian.com/site/%s/conversation/%s/message',
            getenv('STRIDE_CLOUD_ID'), getenv('STRIDE_CONVERSATION_ID'));

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.getenv('STRIDE_BEARER_TOKEN'),
        ));

        $res = curl_exec($ch);
        LoggerHelper::debug($res);
    }
    
    /**
     * Sends a Message to a Slack Channel.
     *
     * @param string $message
     * @return mixed
     */
    public static function sendSlackMessage($message)
    {
       
        $ch = curl_init("https://slack.com/api/chat.postMessage");
        $data = http_build_query([
            'token' => getenv('SLACK_APIKEY'),
            'channel' => getenv('SLACK_CHANNEL'), // prefix with a '#'
            'text' => $message,
            'username' => getenv('SLACK_BOTNAME'), // freely name the sender
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // doesn't work otherwise
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
    
    /**
     * Sends an email
     *
     * @param $body
     * @param $subject
     * @param $recipient_address
     * @param $recipient_name
     * @param $sender_address
     * @param $sender_name
     * @param null $replyto_address
     * @param null $replyto_name
     * @param null $attachments
     */
    public static function sendMail(
        $body,
        $subject,
        $recipient_address,
        $recipient_name,
        $sender_address,
        $sender_name,
        $replyto_address = null,
        $replyto_name = null,
        $attachments = null
    )
    {
        LoggerHelper::debug(print_r(func_get_args(), true), 'info');
        
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

        //Server settings
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = getenv('MAILER_HOST');  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = getenv('MAILER_USER');                 // SMTP username
        $mail->Password = getenv('MAILER_PASSWORD');                           // SMTP password
        $mail->SMTPSecure = getenv('MAILER_ENCRYPTION');                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = getenv('MAILER_PORT');                                    // TCP port to connect to

        //Recipients
        try {
            $mail->setFrom($sender_address, $sender_name);
            $mail->addAddress($recipient_address, $recipient_name);     // Add a recipient
            #$mail->addAddress('ellen@example.com');               // Name is optional
            if ($replyto_address !== null && $replyto_name !== null) {
                $mail->addReplyTo($replyto_address, $replyto_name);
            }
            #$mail->addCC('cc@example.com');
            #$mail->addBCC('bcc@example.com');

            //Attachments
            if (is_array($attachments)) {
                foreach ($attachments as $attachment) {
                    if (file_exists($attachment['path'])) {
                        $mail->addAttachment($attachment['path'], $attachment['name']);
                    }
                }
            }
            #$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            #    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = utf8_decode($subject);
            $mail->Body = utf8_decode($body);
            #$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            
            LoggerHelper::debug($mail->ErrorInfo);
        } catch (Exception $e) {
            LoggerHelper::debug($e->getMessage(), 'error');
        }
    }
}