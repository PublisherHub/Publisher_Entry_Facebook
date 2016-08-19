# FacebookEntry
Provides Entries for publisher/publisher to post to Facebook.

FacebookUserEntry: post a status message as a user.
-> implements publisher/recommendation

FacebookPageEntry: post a status message as a page admin.
-> implements publisher/recommendation
    -> offers scheduled publishing


# Installation
The recommended way to install this is through [composer](http://getcomposer.org).

Edit your `composer.json` and add:

```json
{
    "require": {
        "publisher/facebook-entry": "dev-master"
    }
}
```

And install dependencies:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```