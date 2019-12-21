<?php

class Theme_Updater
{
    private $update_endpoint = 'http://wpcr.local';
    private $slug;
    
    public function __construct()
    {
        $this->slug = strtolower(basename(__DIR__));
        add_filter('pre_set_site_transient_update_themes', [$this, 'check_for_updates']);
    }
    
    public function check_for_updates($transient)
    {
        $this->writeLog(print_r($transient, true), 'transient');

        if (empty($transient->checked)) {
            return $transient;
        }
        
        if (!array_key_exists($this->slug, $transient->checked)) {
            return $transient;
        }
        
        $plugin_info = $this->api_request('theme-information');
        if ($plugin_info === false) {
            return $transient;
        }
        
        if (version_compare($plugin_info->new_version, $transient->checked[$this->slug], '>')) {
            $transient->response[$this->slug] = (array) $plugin_info;
        }
        $this->writeLog(print_r($transient, true), 'transient_modified');
        return $transient;
    }
    
    /**
     * @param string $action
     *
     * @return object|bool
     */
    private function api_request($action)
    {
        $url     = $this->update_endpoint . '/api/themes/' . $action . '/' . $this->slug;
        $params  = [
            'timeout'   => 10,
            'sslverify' => false,
        ];
        $request = wp_remote_get($url, $params);
        if (is_wp_error($request)) {
            return false;
        }
        $code = wp_remote_retrieve_response_code($request);
        if ($code !== 200) {
            return false;
        }
        $response = json_decode(wp_remote_retrieve_body($request));
        if ($response instanceof WP_Error) {
            return false;
        }
        if (!is_object($response)) {
            return false;
        }
        
        $this->writeLog(print_r($response, true), 'action_' . $action . '_response');
        
        return $response;
    }
    
    /**
     * @param string $cont
     * @param string $filename
     * @param bool $append
     */
    public function writeLog($cont, $filename = 'default', $append = false)
    {
        if (WP_DEBUG === true) {
            if (!is_dir(__DIR__ . '/logs')) {
                @mkdir(__DIR__ . '/logs', 0775, true);
            }
            if (!$append) {
                file_put_contents(__DIR__ . '/logs/' . $filename . '.log', $cont);
            } else {
                file_put_contents(__DIR__ . '/logs/' . $filename . '.log', $cont . PHP_EOL, FILE_APPEND);
            }
        }
    }
}