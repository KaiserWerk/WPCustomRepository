# WPCustomRepository (= WPCR)

Everyone who used Wordpress at least once or twice knows about the wordpress.org plugin/theme repository. It allows your Wordpress site to automatically pull updates for your plugins and themes (if you installed them from there).

I developed a plugin exclusive to one client to be installed on nine different sites. Very soon I was tired of manually updating the plugin on such a number of sites. Hence I started developing a custom Wordpress repository. It's intentionally held quite simple. All you can do is log in/out and upload new plugin versions. Currently I'm developing more features.

# Features

* Plugin updater (haha)
* Theme update (maybe in the future)
* Licensing system (yes, quite rudimentary)

# Installation
### Part I: On your server

* Step 1: Upload the WPCR (WPCustomRepository) files on your webserver, either by zip file or a ```git clone https://github.com/KaiserWerk/WPCustomRepository.git```.
* Step 2: Do the infamous ```composer install``` to get started.
* Step 3: Edit the values in the ```.env``` file according to your needs. Most of them are optional.
* Step 4: Import the sql dump file from the ```etc``` directory. It contains a standard admin user with the credentials ``admin/test``.

* Optional Step: If you have composer installed locally but not on your server, you can execute the whole installation process locally and upload everything via FTP.

### Part II: In your custom plugin

* Step 1: Add the code from the ```etc/plugin_code.txt``` to the end of the main file of your plugin. If your plugin directory is called ```test-plugin```, your main plugin file is ```test-plugin.php```.
* Step 2: Change the class name to something unique, ideally something like ```test_plugin_update``` or similar.
* Step 3: Change the class name in the add_filter() functions accordingly.
* Step 4: Change the ```private static $slug``` variable to the handle of your plugin, e.g. ```test-plugin```. Optimally use a defined constant.
* Step 5: In the plugin settings, set the ```Update Endpoint``` setting to the hostname you installed the WPCR at.
* Step 6: In case you are using the rudimentary license system, also enter the License User and Key you created at the WPCR.

### Part III: In Wordpress

* Step 1: Add the code from ```etc/wp-config.php.txt```to the end of your wordpress' wp-config.php file and add the hostname of your WPCR installation to the array, e.g. ```wpcr.yourdomain.com```. Without this snippet, your Wordpress installation would only accept automatic updates from the official wordpress.org repository.

# Contributing

Either branch it and create a pull request or open an issue. Also, feel free to fork and create your own, better version!

You can contact me via mail: m@r-k.mx

# Further Reading & Thoughts

In order for newly uploaded plugin versions to be recogized, the version string in the main file's comment **must** be higher than the currently installed version's. Naturally, it would make no sense to execute an automatic update to the same version that is already installed.

An automatic __installation__ would be reeeally great, but to my knowledge is not currently possible. You have to manually install your custom plugin first. If you set up everything correctly, the automatic updates will take over.
