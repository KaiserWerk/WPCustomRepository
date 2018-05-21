<?php


class Helper
{
    
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
        return 'http' . (self::isSSL() ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
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
        $bool = $db->has('user', 'id', [
            'username' => $username,
        ]);
        
        if ($bool) {
            return true;
        }
        return false;
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
        $bool = $db->has('user', 'id', [
            'email' => $email,
        ]);
        if ($bool) {
            return true;
        }
        return false;
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
            'sent_at' => 'NOW()',
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
        $row = $db->get('user', '*', [
            'id' => (int)$user_id,
        ]);
        
        return $row;
    }

    /**
     * Return the client's IP address in IPV4 format
     *
     * @return mixed
     */
    public static function getIP()
    {
        return filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
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
        #imagepng($img, 'test.png');
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
        header("Location: ".$url);
        die;
    }

    /**
     * Returns the client's OS and Browser
     *
     * @return string
     */
    public static function getOSAndBrowser() {
        
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform = "Unknown OS Platform";
        
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
        
        $browser  = "Unknown Browser";
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
        
        return $os_platform . '/'.$browser;
    }

    /**
     * Checks whether the connection was established over SSL
     * or not
     *
     * @return bool
     */
    public static function isSSL() {
        if (isset($_SERVER['HTTPS']) ) {
            if ('on' == strtolower($_SERVER['HTTPS']))
                return true;
            if ('1' == $_SERVER['HTTPS'])
                return true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }
}
