<?php

class ThemeAPIController extends Controller
{
    /**
     * @Route("/api/themes/theme-update/[:slug]", name="api_themes_themeupdate")
     * @Method(["GET"])
     */
    public function apiThemesThemeupdateAction($params)
    {
        $slug = $params->slug ?? null;
    
        if ($slug === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'invalid slug',
            ], 400);
        }
    
        #LicenseHelper::checkLicenseValidity();
    
        $db = new DBHelper();
    
        $base_theme = $db->get('theme', [
            'id',
            'url'
        ], [
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
            ],
        ]);
    
        if ($latest_version === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Theme version not found',
            ], 404);
        }
    
        $response = array();
        $response['package'] = Helper::getHost() . '/download/theme/' . $slug . '/latest';
        $response['new_version'] = $latest_version['version'];
        $response['url'] = $base_theme['url'];
        $response['tags'] = array(
            'one' => 'ins',
            'two' => 'tzwi',
        );
    
        HTTPHelper::jsonResponse($response);
    }
    
    /**
     * @Route("/api/themes/theme-information/[:slug]", name="api_themes_themeinformation")
     * @Method(["GET"])
     */
    public function apiThemesThemeinformationAction($params)
    {
        $slug = $params->slug ?? null;
    
        if ($slug === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'invalid slug',
            ], 400);
        }
    
        #LicenseHelper::checkLicenseValidity();
    
        $db = new DBHelper();
    
        $base_theme = $db->get('theme', '*', [
            'slug' => $slug,
        ]);
    
        if ($base_theme === false || $base_theme === null) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Base theme not found',
            ], 404);
        }
    
        $latest_version = $db->get('theme_version', '*', [
            'theme_entry_id' => $base_theme['id'],
            'ORDER' => [
                'version' => 'DESC',
            ],
        ]);
    
        if ($latest_version === false) {
            HTTPHelper::jsonResponse([
                'status' => 'error',
                'message' => 'Theme version not found',
            ], 404);
        }
    
        $data = new stdClass;
        $data->theme = $slug; // SLUG
        #$data->name = $base_theme['theme_name'];
        $data->new_version = $latest_version['version'];
        $data->last_updated = $base_theme['updated_at'];
        $data->package = Helper::getHost() . '/download/theme/' . $slug . '/latest';
        $data->url = $base_theme['url'];
        $data->requires = $latest_version['requires'];
        $data->requires_php = $latest_version['requires_php'];
        
        HTTPHelper::jsonResponse($data);
    }
}

