### WPCustomRepository (= WPCR)

Everyone who used Wordpress at least once or twice knows about the wordpress.org plugin/theme repository. It allows your Wordpress site to automatically pull updates for your plugins and themes (if you installed them from there).

I developed a plugin exclusive to one client to be installed on nine different sites. Very soon I was tired of manually updating the plugin on such a number of sites. Hence I started developing a custom Wordpress repository. It's intentionally held quite simple. All you can do is log in/out and upload new plugin versions. Currently I'm developing more features.

There is still a lot to do. That means that not everything will work 100%.

Edit: Broken since WordPress version 5. :(
Edit: Appears to work properly again (tested Nov. 12, 2019)

### Features

* Automatic plugin updates
* Automatic theme updates
* Tracking of active installations (?)

### Requirements
* PHP 7
* A MySQL/MariaDB database
* Some MB of webspace (remember the uploaded plugin/theme files also take up some space)
* Apache (or Nginx) with rewrite module

### Installation
##### Part I: On your server

* Step 0: Create a (sub)domain and a vHost.
* Step 1: Upload the WPCR (WPCustomRepository) files on your webserver, either by zip file or a `git clone https://github.com/KaiserWerk/WPCustomRepository.git`.
* Step 2: Do the infamous `composer install` to get started.
* Step 3: Edit the values in the `config.yml` file according to your needs. Most of them are optional.
* Step 4: Import the sql dump file from the `etc` directory.
* Step 5: Create an administrative user with the following command: `php bin/console --create-user --admin` and enter the details as asked.

* Optional Step 6: If you have composer installed locally but not on your server or if it's just a basic webspace, you can do the whole installation locally and upload everything to your webspace/server. Just remember to edit the `config.yml` file.

##### Part II: In your custom plugin

* Step 1: Add the file `etc/plugin-updater.php` to your plugin directory.
* Step 2: Change the class name in the file to something unique, ideally add the name of your plugin as prefix or suffix , e.g. `plugin_updater_pluginname` or similar. (If you forget to do this it will still work but you cannot use another custom plugin with the updater code.)
* Step 3: include the plugin-updater.php file and create a new instance of it, e.g. `new plugin_updater_pluginname();`

* Optional Step 4: Use the parameters to hardcode specific values: `new plugin_updater_pluginname(string $endpoint, string $license_user, string $license_key, string $slug, bool $disable_sslverify)`

### Contributing
First, make a feature request/open an issue. If I don't have the time to take care of it, feel free to create a pull request. Also, feel free to fork and create your own, better version!

### Support

* Open an issue
* Send me an electronic letter: m@r-k.mx

### Further Reading & Thoughts

In order for newly uploaded plugin versions to be recognized, the version string in the main file's header comment **must** be higher than the currently installed version's. Naturally, it would make no sense to execute an automatic update to the same version that is already installed.

An **automatic** installation would be reeeally great, but to my knowledge this is not currently possible. You have to manually install your custom plugin first. If you set up everything correctly, the automatic updates will take over.

### TODO
* Add SQLite support (check)
* Pull new plugin versions direct from Git (work in progress)
