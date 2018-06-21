<?php

class LicenseHelper
{
    /**
     * Checks the request headers for valid license data
     *
     * @param $headers
     * @return bool
     */
    public static function checkLicenseValidity()
    {
        $headers = getallheaders();
        $licenseUser = $headers['X-License-User'] ?? null;
        $licenseKey  = $headers['X-License-Key'] ?? null;
        $licenseHost = $headers['Host'] ?? gethostbyname(Helper::getIP());
    
        if ($licenseUser === null || $licenseKey === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'missing license data',
            ]);
        }
    
        $db = new DBHelper();
        $license = $db->get('license', '*', [
            'license_key' => $licenseKey,
        ]);
        if ($license !== false) {
            // @TODO proper JSON error messages
            if ($license['license_user'] !== $licenseUser) {
                HTTPHelper::jsonResponse([
                    'status' => 'error',
                    'message' => 'Invalid license user',
                ], 401);
            }
            
            if (!empty($license['license_host']) && $license['license_host'] !== $licenseHost) {
                HTTPHelper::jsonResponse([
                    'status' => 'error',
                    'message' => 'Invalid host',
                ], 401);
            }
            
            $valid = new \DateTime($license['valid_until']);
            $now = new \DateTime();
            if ($valid <= $now) {
                HTTPHelper::jsonResponse([
                    'status' => 'error',
                    'message' => 'License expired',
                ], 410);
            }
        } else {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'License not found',
            ], 404);
        }
        return true;
    }
}