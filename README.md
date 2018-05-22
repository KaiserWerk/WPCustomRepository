# WPCustomRepository

Everyone who used Wordpress at least once or twice knows about the wordpress.org plugin/theme repository. It allows your Wordpress site to automatically pull updates for your plugins and themes (if you installed them from there).

I developed a plugin exclusive to one client to be installed on nine different sites. Very soon I was tired of manually updating the plugin on such a number of sites. Hence I started developing a custom Wordpress repository. It's intentionally held quite simple. All you can do is log in/out and upload new plugin versions. Currently I'm developing more features.

# Features

* Plugin updater (haha)
* Theme update (maybe in the future)
* Licensing system (not yet)

# Installation
### Part I: On your server

* Step 1: Upload the WPCustomRepository files on your webserver, either by zip file or a ``git pull https://github.com/KaiserWerk/WPCustomRepository.git``
* Step 2: do the infamous ``composer install``
* Step 3: Edit the values in the .env file according to your needs

### Part II: In your custom plugin
### Part III: In Wordpress
