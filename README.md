HomeAI
======
Todo add some fancy text here...

General
-------
This software is for all the home-or-someother-way-made in engineers. Basically we want to provide
common API for "any kind" of 3th party devices with a minimum of effort.

Purpose of this "software" is connect 1-n host clients with one (in future many) server instance of
HomeAI server, clients can be example raspberry pi systems with 1-n sensors, relay controls or something
else. Basically we want to make system that can handle 1-n clients which provide different services.
These services can be simple rrd images, relay controls or something else.

We provide some default functionalities for default but you can write your own implementation if you
see that our free library isn't enough. Please remember to contribute our passion about this software.

### Demo
http://wunder.sytes.net/HomeAI/ - maybe down or not, may work or not, no guarantees.

### What are we using to do this all:
- PHP 5.4+, what else? :D (http://php.net/)
- Doctrine DBAL (http://www.doctrine-project.org/projects/dbal.html)
- Smarty Template Engine (http://www.smarty.net/)
- jQuery (http://www.jquery.com/)
- jQueryUI (http://www.jqueryui.com/)
- jQuery ctNotify Plugin (https://github.com/thecodecentral/ctNotify)
- Twitter Bootstrap (https://github.com/twitter/bootstrap)
- qTip2 (http://craigsworks.com/projects/qtip2/)
- YUI Compressor (http://developer.yahoo.com/yui/compressor/)
- <em>This list will be updated</em>

### Requirements
- PHP 5.4+
- <em>TODO: add specified requirement list here</em>

You can run system check script via following command in the root directory:
<pre>
php -q html/check.php
</pre>
or pointing your browser to following address after installation:
<pre>
http://your-host/check.php
</pre>

### Notes
We're always looking for some help to do this all, please contact if you are interested in this.

### Why to do this?
Don't know, some fucking passion to do something etc.

### License
The MIT License is simple and easy to understand and it places almost no restrictions on what you can do with this project.

You are free to use this project code in commercial projects as long as the copyright header is left intact.

Installation
---------------
Some installation docs here.
### Server
TODO, we may need some scripting...

### Client
TODO, we may need some scripting...

Development / Contributing
--------------------------
### Baseline
1. Fork it, please.
2. Create a branch (`git checkout -b xxx`)
3. Commit your changes (`git commit -am "some changes..."`)
4. Push to the branch (`git push origin xxx`)
5. Open a [Pull Request][1]
6. Take some Jaloviina (*) enjoy and wait

### IDE recommendation
Really, use PhpStorm (http://www.jetbrains.com/phpstorm/) it rocks!
