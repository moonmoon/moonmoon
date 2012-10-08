moonmoon
========

[http://moonmoon.org/](http://moonmoon.org/)

Moonmoon is a web based aggregator similar to planetplanet. 
It can be used to blend articles from different blogs with same interests into a single page.

Moonmoon is stupidly simple: it only aggregates feeds and spits them out in one single page. 
It does not archive articles, it does not do comments nor votes.


Requirements
------------
Web hosting with PHP5.3 (PHP4 & 5.2 will not work).

License
-------
Moonmoon is free software and is released under BSD license.


Configuration options
---------------------
After installation, configuration is kept in a YAML formatted ```custom/config.yml```:

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
```

---