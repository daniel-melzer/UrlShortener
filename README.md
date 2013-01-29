UrlShortener is based on the Flight PHP micro-framework, SQLite 3 and Twitter Bootstrap.

# System requirements:

Make sure your server meets the following requirements

* Apache (nginx could work, but isn't tested)
* Enabled mod_rewrite
* PHP 5.3.2+
* SQLite 3

# Installation

## Via Composer

Create project
```bash
$ composer create-project damel/urlshortener <path>
```
Check the directory and file permissions of app/resource/sqlite/urlshortener.sqlite.

## Manually
1. Download a .zip file of the UrlShortener
2. Upload the contents of the .zip file to your web server
3. Check the directory and file permissions of app/resource/sqlite/urlshortener.sqlite

# Configuration

To configure your installation of UrlShortener, edit the values in app/config/config.ini.

## url
Set the URL of your installation, e.g.:

## name
Set the name in the title, navbar and on the frontpage.

## list_default_limit
Set the number of elements in the list.

# Contributors

*  Marc Löhe
    https://github.com/boundaryfunctions