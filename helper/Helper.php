<?php


class Helper
{
    public static function setMessage($message, $type = 'info')
    {
        $_SESSION['X-Message'] = $message;
        $_SESSION['X-Message-Type'] = $type;
    }
    
    public static function getMessage()
    {
        if (array_key_exists('X-Message', $_SESSION) && array_key_exists('X-Message-Type', $_SESSION)) {
            $output = '<br>
            <div class="alert alert-%s alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                %s
            </div>';
            $message = $_SESSION['X-Message'];
            $type = $_SESSION['X-Message-Type'];
            unset($_SESSION['X-Message'], $_SESSION['X-Message-Type']);
            echo sprintf($output, $type, $message);
        } else {
            echo '';
        }
    }
    
    public static function createZip($files = array(), $destination = '', $overwrite = false) {
        
        if(file_exists($destination) && !$overwrite) {
            return false;
        }

        $valid_files = array();
        if(is_array($files)) {
            foreach($files as $file) {
                if(file_exists($file['file'])) {
                    $valid_files[] = array(
                        'file' => $file['file'],
                        'name' => $file['name'],
                    );
                }
            }
        }

        #echo "<pre>"; var_dump($valid_files);die;

        if(count($valid_files) > 0) {

            $zip = new ZipArchive();
            if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }

            foreach($valid_files as $file) {
                $zip->addFile($file['file'], $file['name']);
            }
            $zip->close();

            return file_exists($destination);
        }
        return false;
    }
    
    /**
     * Returns the current host name
     *
     * @return string
     */
    public static function getHost()
    {
        $possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
        $sourceTransformations = array(
            'HTTP_X_FORWARDED_HOST' => function($value) {
                $elements = explode(',', $value);
                return trim(end($elements));
            }
        );
        $host = '';
        foreach ($possibleHostSources as $source)
        {
            if (!empty($host)) break;
            if (empty($_SERVER[$source])) continue;
            $host = $_SERVER[$source];
            if (array_key_exists($source, $sourceTransformations))
            {
                $host = $sourceTransformations[$source]($host);
            }
        }
    
        // Remove port number from host
        $host = preg_replace('/:\d+$/', '', $host);
    
        return 'http' . (self::isSSL() ? 's' : '') . '://' . trim($host);
    }

    /**
     * Return a slug for a given string. Optionally, can be used for
     * an URL safe filename
     *
     * @param $string
     * @param bool $is_filename
     * @return null|string
     */
    public static function sluggify($string, $is_filename = false) {
        $pattern = '/[^A-Za-z0-9]+/';
        if ($is_filename === true) {
            $pattern = '/[^A-Za-z0-9\.]+/';
        }
        return preg_replace($pattern, '-', $string);
    }

    /**
     * Determines whether the supplied username is already
     * in use or not
     *
     * @param $username
     * @return bool
     */
    public static function isUsernameInUse($username)
    {
        $db = new DBHelper();
        return $db->has('user', [
            'username' => $username,
        ]);
    }

    /**
     * Determines whether the supplied e-mail address is
     * already in use or not
     *
     * @param string $email
     * @return bool
     */
    public static function isEmailInUse($email)
    {
        $db = new DBHelper();
        return $db->has('user', [
            'email' => $email,
        ]);
    }

    /**
     * Redirects the user to an error page
     *
     * @param int $code
     */
    public static function errorPage($code)
    {
        self::redirect('/e/' . $code);
    }

    /**
     * Generates an email tracking entry and returns a tracking token
     *
     * @param $confirmation_token
     * @param $recipient
     * @return string
     */
    public static function generateEmailTrackingToken($recipient, $confirmation_token = null)
    {
        $db = new DBHelper();
        $db->insert('mail_sent', [
            'confirmation_token' => $confirmation_token,
            'recipient' => $recipient,
            'sent_at' => date('Y-m-d H:i:s'),
        ]);
        return $db->id();
    }

    /**
     * Fetches data for a certain user
     *
     * @param int $user_id
     * @return array
     */
    public static function getUserData($user_id)
    {
        $db = new DBHelper();
        return $db->get('user', '*', [
            'id' => (int)$user_id,
        ]);
    }

    /**
     * Return the client's IP address in IPV4 format
     *
     * @return mixed
     */
    public static function getIP()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if(isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = false;
        }
        
        if ($ipaddress !== false) {
            return filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        return false;
    }

    /**
     * Returns a transparent 1x1 pixel image used as a tracking pixel in e-mails
     *
     * @return resource
     */
    public static function generateTrackingPixel()
    {
        $img = imagecreatetruecolor(1, 1);
        imagesavealpha($img, true);
        $color = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $color);
        return $img;
    }

    /**
     * Redirects the user to the supplied url
     * (either internal or external)
     *
     * @param $url
     */
    public static function redirect($url)
    {
        #die("Location: ".$url); // only for debugging
        header('Location: ' . $url);
        die;
    }

    /**
     * Returns the client's OS and Browser
     *
     * @return string
     */
    public static function getOSAndBrowser()
    {
        
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform = 'Unknown OS Platform';
        
        $os_array = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );
        
        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform    =   $value;
            }
        }
        
        $browser  = 'Unknown Browser';
        $browser_array = array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/edge/i'       =>  'Edge',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );
        
        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $browser    =   $value;
            }
        }
        
        return $os_platform . '/' . $browser;
    }

    /**
     * Checks whether the connection was established over SSL
     * or not
     *
     * @return bool
     */
    public static function isSSL()
    {
        if (isset($_SERVER['HTTPS']) ) {
            if (strtolower($_SERVER['HTTPS']) === 'on')
                return true;
            if ($_SERVER['HTTPS'] == '1')
                return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }
    
    /**
     * Insert variables into an email template and
     * returns the complete code.
     *
     * @param $body
     * @param array $params
     * @return string
     */
    public static function insertValues($body, $params = array())
    {
        $body = str_replace('{support_email}', getenv('MAILER_REPLYTO'), $body);
        $body = str_replace('{support_user}', getenv('MAILER_REPLYTO_NAME'), $body);
        $body = str_replace('{host}', 'http'.(Helper::isSSL() ? 's' : '').'://'.$_SERVER['HTTP_HOST'], $body);
        $body = str_replace('{os_and_browser}', Helper::getOSAndBrowser(), $body);
        $body = str_replace('{ip_address}', Helper::getIP(), $body);
        
        if (!isset($params['tracking_token'])) {
            $body = preg_replace('#<!--et-->.*<!--/et-->#m', '', $body);
        }
        
        foreach ($params as $key => $value) {
            $body = str_replace('{'.$key.'}', $value, $body);
        }
        return $body;
    }
    
    public static function getCurrentWPVersion($return = false)
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 30,
            ]
        ]);
        
        $url = 'https://api.wordpress.org/core/version-check/1.7/';
    
        $contents = file_get_contents($url, false, $context);
        #$contents = utf8_encode($contents);
        $obj = json_decode($contents);
        $version = $obj->offers[0]->version;
        if ($return) {
            return $version;
        }
        echo $version;
    }
}
