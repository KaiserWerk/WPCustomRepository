<?php

class LicenseHelper
{
    /**
     * Checks the request headers for valid license data
     * @return bool
     * @throws Exception
     */
    public static function checkLicenseValidity()
    {
        $headers = getallheaders();
        $licenseUser = $headers['X-License-User'] ?? null;
        $licenseKey  = $headers['X-License-Key'] ?? null;
        $licenseObject  = $headers['X-License-Object'] ?? null;
        $licenseHost = Helper::getIP();
    
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
        if ($license === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'License not found',
            ], 404);
        }
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
        
        if ($license['plugin_entry_id'] === null && $license['theme_entry_id'] === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'License not valid for any object',
            ], 400);
        }
        
        if ($license['plugin_entry_id'] !== null || $license['theme_entry_id'] !== null) {
            $objectParts = explode('_', $licenseObject);
            // 0 = pluginversion|themeversion
            // 1 = slug
            if ($objectParts[0] === 'pluginversion') {
                
                $base_plugin = $db->get('plugin', 'id', [
                    'slug' => $objectParts[1],
                ]);
                
                if ($base_plugin['id'] !== $license['plugin_entry_id']) {
                    HTTPHelper::jsonResponse([
                        'status' => 'error',
                        'message' => 'License not valid for object (1)',
                    ], 400);
                }
            } elseif ($objectParts[0] === 'themeversion') {

                $base_theme = $db->get('theme', 'id', [
                    'slug' => $objectParts[1],
                ]);
                
                if ($base_theme['id'] !== $license['theme_entry_id']) {
                    HTTPHelper::jsonResponse([
                        'status' => 'error',
                        'message' => 'License not valid for object (2)',
                    ], 400);
                }
            }
        }
        
        return true; // a formality
    }
}