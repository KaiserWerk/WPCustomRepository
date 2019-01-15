<?php

class plugin_updater_%%pluginname%% {
    
    /**
     * @var string
     */
    protected $plugin_data = array();
    protected $plugin_name;
    protected $slug;
    protected $version;
    protected $update_endpoint;
    protected $update_license_user;
    protected $update_license_key;
    protected $update_disable_sslverify;
    /**
     * plugin_updater constructor.
     *
     * @param array $plugin_data
     */
    public function __construct($plugin_data)
    {
        add_filter('plugins_api', array(&$this, 'plugins_api'), 10, 3);
        add_filter('pre_set_site_transient_update_plugins', array(&$this, 'update_plugins'));
        
        #$this->plugin_data = $plugin_data;
        $this->plugin_name = $plugin_data['name'];
        $this->slug = $plugin_data['slug'];
        $this->version = $plugin_data['version'];
        
        $options = get_option($this->slug);
        // you can set hard-coded values here!
        $this->update_endpoint = isset($options['update_endpoint']) ? sanitize_text_field($options['update_endpoint']) : '';
        $this->update_license_user = isset($options['update_license_user']) ? sanitize_text_field($options['update_license_user']) : '';
        $this->update_license_key = isset($options['update_license_key']) ? sanitize_text_field($options['update_license_key']) : '';
        $this->update_disable_sslverify = isset($options['update_disable_sslverify']) ? (bool)$options['update_disable_sslverify'] : false;
    }
    
    /**
     * @param string $cont
     * @param string $filename
     * @param bool $append
     */
    public function writeLog($cont, $filename = 'default', $append = false) {
        if (WP_DEBUG === true) {
            if (!is_dir(__DIR__.'/logs')) {
                @mkdir( __DIR__ . '/logs', 0775, true );
            }
            if (!$append) {
                file_put_contents( __DIR__ . '/logs/' . $filename . '.log', $cont );
            } else {
                file_put_contents( __DIR__ . '/logs/' . $filename . '.log', $cont.PHP_EOL, FILE_APPEND );
            }
        }
    }
    
    /**
     * @param mixed $false
     * @param mixed $action
     * @param object $args
     *
     * @return object
     */
    public function plugins_api($false, $action, $args)
    {
        if (!isset($args->slug) || $args->slug !== $this->slug) {
            return $false;
        }
        
        $response = $this->api_request('get-plugin-information');
        // they may or may not be all set
        @$response->ratings = (array)$response->ratings;
        @$response->sections = (array)$response->sections;
        @$response->banners = (array)$response->banners;
        
        $this->writeLog(print_r($response, true), 'get-plugin-information_response');
        
        return $response;
    }
    
    /**
     * @param object $transient
     *
     * @return object
     */
    public function update_plugins($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        $plugin_path = $this->slug . '/' . $this->slug . '.php';
        
        $this->writeLog($plugin_path, 'plugin_path');
        
        $response = $this->api_request('check-latest-version');
        
        $this->writeLog(print_r($response, true), 'check-latest-version_response');
        
        if (is_object($response) && version_compare( $response->new_version, $transient->checked[ $plugin_path ], '>' )) {
            $transient->response[ $plugin_path ] = $response;
        }
        
        return $transient;
    }
    
    /**
     * @param string $action
     *
     * @return object|bool
     */
    private function api_request($action)
    {
        $url = $this->update_endpoint . '/api/plugins/' . $action . '/' . $this->slug;
        
        $params = array('timeout' => 10);
        if (!empty($this->update_license_user) && !empty($this->update_license_key)) {
            $params['headers'] = array(
                'X-License-User' => $this->update_license_user,
                'X-License-Key'=> $this->update_license_key,
            );
        }
        
        if ($this->update_disable_sslverify === true) {
            $params['sslverify'] = false;
        }
        
        $this->writeLog(print_r($params, true), 'api_request_params', true);
        
        $request = wp_remote_get($url, $params);
        
        $this->writeLog(print_r($request['body'], true), 'api_request_response', true);
        
        if (is_wp_error( $request )) {
            return false;
        }
        
        $code = wp_remote_retrieve_response_code( $request );
        if ($code !== 200) {
            return false;
        }
        
        $response = json_decode(wp_remote_retrieve_body($request));
        
        if (is_object($response)) {
            return $response;
        }
        
        return false;
    }
    
    /**
     * @param string $action
     *
     * @return bool
     */
    public function trackInstallations($action)
    {
        $options = get_option($this->slug);
        
        $url = $options['update_endpoint'] . '/api/plugins/track-installations';
        
        $fields = array(
            'slug' => $this->slug,
            'version' => $this->version,
            'action' => $action,
        );
        
        $response = wp_remote_request($url, array(
            'method' => 'POST',
            'body' => $fields,
            'timeout' => 10,
        ));
        
        if (!$response instanceof WP_Error) {
            return true;
        }
        return false;
    }
}