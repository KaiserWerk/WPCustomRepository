<?php

class LicenseHelper
{
    /**
     * Checks the request headers for valid license data
     *
     * @param $headers
     * @return bool
     */
    public static function checkLicenseValidity($headers)
    {
        if ((bool)getenv('LICENSE_SYSTEM_ENABLED') === false) {
            return true;
        }
        
        $licenseUser = $headers['X-License-User'] ?? null;
        $licenseKey = $headers['X-License-Key'] ?? null;
        $licenseHost = $headers['Host'] ?? gethostbyname(Helper::getIP());
    
        if ($licenseUser === null || $licenseKey === null) {
            die('missing license data');
        }
    
        $db = new DBHelper();
        $license = $db->get('license', '*', [
            'license_key' => $licenseKey,
        ]);
        if ($license !== false) {
            // @TODO proper JSON error messages
            if ($license['license_user'] !== $licenseUser) {
                die('Invalid license user.');
            }
            
            if (!empty($license['license_host']) && $license['license_host'] !== $licenseHost) {
                die('invalid host.');
            }
            
            $valid = new \DateTime($license['valid_until']);
            $now = new \DateTime();
            if ($valid <= $now) {
                die('license expired.');
            }
        } else {
            die('invalid license data.');
        }
        return true;
    }
}