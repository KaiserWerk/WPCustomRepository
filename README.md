# WPCustomRepository (= WPCR)

Everyone who used Wordpress at least once or twice knows about the wordpress.org plugin/theme repository. It allows your Wordpress site to automatically pull updates for your plugins and themes (if you installed them from there).

I developed a plugin exclusive to one client to be installed on nine different sites. Very soon I was tired of manually updating the plugin on such a number of sites. Hence I started developing a custom Wordpress repository. It's intentionally held quite simple. All you can do is log in/out and upload new plugin versions. Currently I'm developing more features.

# Features

* Plugin updater (haha)
* Theme update (maybe in the future)
* Licensing system (not yet)

# Installation
### Part I: On your server

* Step 1: Upload the WPCR (WPCustomRepository) files on your webserver, either by zip file or a ```git pull https://github.com/KaiserWerk/WPCustomRepository.git```.
* Step 2: Do the infamous ```composer install``` to get started.
* Step 3: Edit the values in the ```.env``` file according to your needs.
* Step 4: Import the sql dump file from the ```etc``` directory. It contains a standard admin user with the credentials ``admin/test``.

* Optional Step: If you have composer installed locally but not on your server, you can execute the whole installation process locally and upload everything via FTP.

### Part II: In your custom plugin

* Step 1: Add the code from the ```etc/plugin_code.txt``` to the end of the main file of your plugin. If your plugin directory is called ```test-plugin```, then your main plugin file is ```test-plugin.php```.
* Step 2: Change the class name to something unique, ideally something like ```test_plugin_*update*``` or similar.
* Step 3: Change the class name in the add_filter() functions accordingly.
* Step 4: Change the ```private static $endpoint``` to the address of your WPCR Installation.

### Part III: In Wordpress

* Step 1: Add the code from ```etc/wp-config.txt```to the end of your wordpress' wp-config.php file and add the hostname of your WPCR installation to the array, e.g. ```wpcr.yourdomain.com```. Without this snippet, your Wordpress installation would only accept automatic updates from the official wordpress.org repository - which is not what you want.

# Contributing

Either branch it and create a pull request open an issue.
ou can contact me via mail: m@r-k.mx

# Further Reading & Thoughts

In order for newly uploaded plugin versions to be recogized, the version string the main file's comment **must** be higher than the currently installed version's. Naturally, it would make no sense to execute an automatic update to the same version that is already installed.

An automatic __installation__ would be reeeally great, but to my knowledge is not currently possible. You have to manually install your custom plugin first. If you set up everything correctly, the automatic updates will take over.
