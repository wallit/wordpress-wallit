# WordPress Plugin on GitHub

This is a drop-in library (or suggested composer install) to handle automatic updates for your GitHub hosted WordPress plugin.
This was highly influenced by [this article](http://code.tutsplus.com/tutorials/distributing-your-plugins-in-github-with-automatic-updates--wp-34817)

Please note that this does not support private repos at this time.

## Installation Instructions

Install the latest version with

```bash
$ composer require wpog/wordpress-plugin-on-github
```

## Usage as a Composer Library

There are three parameters to the update watcher.  The first is the full file path to the current plugin file.  PHP's 
magic constant of `__FILE__` works brilliantly here.  Next is the GitHub user name or organization name.  Finally, the 
GitHub project name is required.  (Please note, this is the project or repo name - it **does not** end with .git).

Add the following to your main plugin file.

```PHP
if (is_admin()) {
    new \WPOG\UpdateWatcher(__FILE__, 'githubUserName', 'projectName');
}
```   

## Usage as a Drop in Library

This usage is not recommended.  However, in order to do this, drop in the source files into a location in your plugin, and then
do a require to bring in the main UpdateWatcher file.  This project also has dependencies on Parsedown, so you'll need to 
install this as well.

## About

### Requirements

- WordPress 4.x
- PHP 5.4+

## FAQ

**Should I be versioning my composer vendor library in my WordPress plugin?  I thought you normally didn't do this.**

At this point, you need to version the composer vendor folder in your WordPress plugins.  In the future, hopefully it won't be like this. :)

**I get warnings when I do an update on a WordPress plugin with this file**

I know.  I don't know a good solution at this point - but it still works.  WordPress should be checking if a file exists before
opening it - but they don't.
