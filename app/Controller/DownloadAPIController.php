<?php

class DownloadAPIController extends Controller
{
    /**
     * @Route("/download/plugin/[:slug]/[:version]", name="api_download_plugin")
     * @Method(["GET"])
     */
    public function apiDownloadPluginAction($params)
    {
        $slug = $params->slug ?? null;
        $version = $params->version ?? null;
    
        $db = new DBHelper();
    
        $base_plugin = $db->get('plugin', [
            'id',
        ], [
            'slug' => $slug,
        ]);
    
        if ($base_plugin === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Base plugin not found',
            ], 404);
        }
    
        $latest_version = $db->max('plugin_version', 'version', [
            'plugin_entry_id' => $base_plugin['id'],
        ]);
    
        if ($latest_version === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Plugin version not found.',
            ], 404);
        }
    
        if ($slug !== null && $version !== null) {
        
            if ($version === 'latest') {
                $download_version = $latest_version;
            } else {
                $download_version = $version;
            }
        
            $db->update('plugin_version', [
                'downloaded[+]' => 1,
            ], [
                'plugin_entry_id' => $base_plugin['id'],
                'version' => $download_version,
            ]);
        
            $file_name = $slug . '_v' . $download_version . '.zip';
            $dir = VARDIR . '/plugin_files/' . $slug . '/';
        
            if (file_exists($dir . $file_name)) {
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $file_name . '"');
                readfile($dir . $file_name);
                die;
            } else {
                HTTPHelper::jsonResponse([
                    'status' => 'error',
                    'message' => 'Version file does not exist',
                ], 404);
            }
        } else {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Invalid plugin slug or version string',
            ], 400);
        }
    }
    
    /**
     * @Route("/download/theme/[:slug]/[:version]", name="apiDownloadTheme")
     * @Method(["GET"])
     */
    public function apiDownloadThemeAction($params)
    {
        $slug = $params->slug;
        $version = $params->version;
    
        if ($slug === null || $version === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Invalid theme slug or version string',
            ], 400);
        }
        
        $db = new DBHelper();
    
        $base_theme = $db->get('theme', '*', [
            'slug' => $slug,
        ]);
    
        if ($base_theme === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Base theme not found',
            ], 404);
        }
    
        $latest_version = $db->get('theme_version', '*', [
            'theme_entry_id' => $base_theme['id'],
            'ORDER' => [
                'version' => 'DESC',
            ]
        ]);
    
        if ($latest_version === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Theme version not found',
            ], 404);
        }
    
        if ($version === 'latest') {
            $download_version = $latest_version['version'];
        } else {
            $download_version = $version;
        }
    
        $db->update('theme_version', [
            'downloads[+]' => 1,
        ], [
            'theme_entry_id' => $base_theme['id'],
            'version' => $download_version,
        ]);
    
        $file_name = $slug . '_v' . $download_version . '.zip';
        $dir = VARDIR . '/theme_files/' . $slug . '/';
    
        #die($dir . $file_name);
        
        if (!file_exists($dir . $file_name)) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Version file does not exist.',
            ], 404);
        }
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        readfile($dir . $file_name);
        die;
    }
}