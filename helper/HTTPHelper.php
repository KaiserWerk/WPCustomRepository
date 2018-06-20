<?php

class HTTPHelper
{
    /**
     * @param string $message
     * @param int $code
     * @param null|array $additional_headers
     */
    public static function textResponse($message, $code = 200, $additional_headers = null)
    {
        http_response_code($code);
        if (!empty($message)) {
            echo $message;
        }
        if ($additional_headers !== null && is_array($additional_headers)) {
            foreach ($additional_headers as $key => $value) {
                header($key . ': ' . $value);
            }
        }
        die;
    }
    
    /**
     * @param array|object $message
     * @param int $code
     * @param null|array $additional_headers
     */
    public static function jsonResponse($message, $code = 200, $additional_headers = null)
    {
        http_response_code($code);
        if (is_array($message)) {
            $message['created_at'] = (new \DateTime())->format('Y-m-d H:i:s');
        } elseif (is_object($message)) {
            $message->created_at = (new \DateTime())->format('Y-m-d H:i:s');
        }
        
        $message = (array)$message;
        
        if ($additional_headers !== null && is_array($additional_headers)) {
            foreach ($additional_headers as $key => $value) {
                header($key . ': ' . $value);
            }
        }
        header('Content-Type: application/json');
        echo \json_encode($message, JSON_PRETTY_PRINT);
        die;
    }
}