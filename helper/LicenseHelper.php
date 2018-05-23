<?php

class LicenseHelper
{
    /**
     * @param $request
     */
    public static function checkLicenseValidity($request)
    {
        $slug = $request->slug;
        $headers = $request->headers();
        $licenseUser = $headers['X-License-User'] ?? null;
        $licenseKey = $headers['X-License-Key'] ?? null;
        $licenseHost = $headers['Host'] ?? gethostbyname(Helper::getIP());
    
        if ($licenseUser === null || $licenseKey === null) {
            die('missing license data');
        }
    
        $db = new DBHelper();
        $license = $db->get('license', 'id', [
            'license_fullname' => $licenseUser,
            'license_key' => $licenseKey,
            'license_host' => $licenseHost,
            'plugin_slug' => $slug,
        ]);
    
        if ($license !== false) {
            $renewal = $db->get('license_renewal', '*', [
                'license_entry_id' => $license['id'],
            ]);
            $valid = new \DateTime($renewal['valid_until']);
            $now = new \DateTime();
            if ($valid <= $now) {
                die('license expired');
            }
        } else {
            die('invalid license data.');
        }
    }
}