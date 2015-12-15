CodeIgniter Authme
==================

A lightweight, flexible and secure CodeIgniter authentication library. It uses PHPass for secure password hashing and aims to be a solid base on which to build an authentication system for your CodeIgniter project. It comes packaged with an example `Auth` class and related views so that you can get your CI project up and running in minutes.

Requirements
------------

* CodeIgniter 2.1+
* PHP 5.2+
* MySQL

Installation
------------

1. Download and unpack the contents of the application folder to your CodeIgniter project.
2. That's it! Visit `/auth` to signup and login.
3. If you want you can edit `application/config/authme.php` to change some settings, but the defaults are fine.

Usage
-----

For an example on how to use Authme see the [example Auth controller](application/controllers/auth.php) which provides a functioning example of login, sign up, logout and forget/reset password. The Authme library provides several API methods:

`logged_in()` - Returns `true` if the current user logged in, `false` otherwise.

`login($email, $password)` - Attempts to login a user with a given `$email` and `$password`. Returns `true` if successful and `false` otherwise.

`logout([$redirect = false])` - Logs out the current user (by destorying the session). Accepts an optional `$redirect` parameter to redirect to a given URI after logout.

`signup($email, $password)` - Attempts to create a user with a given `$email` and `$password`. Returns `true` if successful and `false` otherwise.

`reset_password($user_id, $new_password)` - Resets the password of the user with the given `$user_id`.

The [Authme helper](application/helpers/authme_helper.php) includes the following helper functions:

`logged_in()` - Returns `true` if the current user logged in, `false` otherwise (shortcut to the Authme library `logged_in()` function).

`user([$key = ''])` - Returns the session data for the currently logged in user. If you specifiy a `$key` you can retrieve specific info, for exmaple `user('id')` returns the currently logged in user ID.

Credits
-------

The CodeIgniter Authme library was created by [Gilbert Pellegrom](http://gilbert.pellegrom.me) from [Dev7studios](http://dev7studios.com). Released under the MIT license.

Please contribute by [reporting bugs](https://github.com/gilbitron/CodeIgniter-Authme/issues) and submitting [pull requests](https://github.com/gilbitron/CodeIgniter-Authme/pulls).
