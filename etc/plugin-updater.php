<?php

class plugin_updater_pluginname {
	
	private $slug;
	private $update_license_user;
	private $update_license_key;
	private $update_endpoint;
	private $update_disable_sslverify;
	
	public function __construct($endpoint = null, $license_user = null, $license_key = null, $slug = null, $disable_sslverify = false)
	{
		add_filter('plugins_api', array(&$this, 'plugins_api'), 10, 3);
		add_filter('pre_set_site_transient_update_plugins', array(&$this, 'update_plugins'));
		
		$this->writeLog(basename(__DIR__), 'plugin_basename');
		
		$this->slug = basename(__DIR__);
		if (!empty($slug)) {
			$this->slug = $slug;
		}
		$options = get_option($this->slug);
		$this->update_endpoint = $options['update_endpoint'];
		$this->update_license_user = $options['update_license_user'];
		$this->update_license_key = $options['update_license_key'];
		$this->update_disable_sslverify = (bool)$options['update_disable_sslverify'];
		if (!empty($endpoint)) {
			$this->update_endpoint = $endpoint;
		}
		if (!empty($license_user) && !empty($license_key)) {
			$this->update_license_user = $license_user;
			$this->update_license_key = $license_key;
		}
		if ($disable_sslverify === true) {
			$this->update_disable_sslverify = true;
		}
	}
	
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
	
	public function plugins_api($false, $action, $args)
	{
		if (!isset($args->slug) || $args->slug !== $this->slug) {
			return $false;
		}
		
		$response = $this->api_request('get-plugin-information');
		// they may nor all be set
		@$response->ratings = (array)$response->ratings;
		@$response->sections = (array)$response->sections;
		@$response->banners = (array)$response->banners;
		
		$this->writeLog(print_r($response, true), 'get-plugin-information_response');
		
		return $response;
	}
	
	public function update_plugins($transient)
	{
		if (empty($transient->checked)) {
			return $transient;
		}
		
		#$plugin_path = plugin_basename(__FILE__);
		$plugin_path = $this->slug . '/' . $this->slug . '.php';
		
		$this->writeLog($plugin_path, 'plugin_path');
		
		$response = $this->api_request('check-latest-version');
		
		$this->writeLog(print_r($response, true), 'check-latest-version_response');
		
		if (is_object($response) && version_compare( $response->new_version, $transient->checked[ $plugin_path ], '>' )) {
			$transient->response[ $plugin_path ] = $response;
		}
		
		return $transient;
	}
	
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
	
	public static function trackInstallations($action)
	{
		$options = get_option(VERDICT_GRABBER_HANDLE);
		
		$url = $options['update_endpoint'] . '/api/plugins/track-installations';
		
		$fields = array(
			'slug' => VERDICT_GRABBER_HANDLE,
			'version' => VERDICT_GRABBER_VERSION,
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