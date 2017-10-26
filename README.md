moonmoon [![Build Status](https://travis-ci.org/moonmoon/moonmoon.svg?branch=master)](https://travis-ci.org/moonmoon/moonmoon)
========

[http://moonmoon.org/](http://moonmoon.org/)

Moonmoon is a web based aggregator similar to planetplanet.
It can be used to blend articles from different blogs with same interests into a single page.

Moonmoon is stupidly simple: it only aggregates feeds and spits them out in one single page.
It does not archive articles, it does not do comments nor votes.

Requirements
------------
You will need a web hosting with at least PHP 5.6 (PHP 7 is also supported).

If you are installing moonmoon on a Linux private server (VPS, dedicated host), 
please note that you will need to install the package `php-xml`.

Installing
----------

Installation steps (shared hosting or virtual / dedicated server) can be found 
[in the wiki](https://github.com/moonmoon/moonmoon/wiki/How-to-install).

Contributing
------------

You want to contribute to moonmoon? Perfect! [We wrote some guidelines to help you
craft the best Issue / Pull Request possible](https://github.com/moonmoon/moonmoon/blob/master/CONTRIBUTING.md),
don't hesitate to take a look at it :-)

License
-------

Moonmoon is free software and is released under BSD license.

Configuration options
---------------------
After installation, configuration is kept in a YAML formatted `custom/config.yml`:

```%yaml
url: http://planet.example.net  # your planet base URL
name: My Planet                 # your planet front page name
locale: en                      # front page locale
items: 10                       # how many items to show
refresh: 240                    # feeds cache timeout (in seconds)
cache: 10                       # front page cache timeout (in seconds)
cachedir: ./cache               # where is cache stored
postmaxlength: 0                # deprecated
shuffle: 0                      # deprecated
nohtml: 0                       # deprecated
categories:                     # only list posts that have one
                                # of these (tag or category)
debug: false                    # debug mode (dangerous in production!)
checkcerts: true                # check feeds certificates
```
