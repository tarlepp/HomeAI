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
- noty (http://needim.github.com/noty/)
- jQuery dashboard plugin (https://connect.gxsoftware.com/dashboardplugin/demo/dashboard.html)
- jQuery jCounter plugin (http://devingredients.com/jcounter/)
- jQuery UI Touch Punch (https://github.com/furf/jquery-ui-touch-punch/)
- jQuery Timeago plugin (http://timeago.yarp.com/)
- Twitter Bootstrap (https://github.com/twitter/bootstrap)
- qTip2 (http://craigsworks.com/projects/qtip2/)
- YUI Compressor (http://developer.yahoo.com/yui/compressor/)
- SimplePie (http://www.simplepie.org/)
- <em>This list will be updated</em>

### Requirements
- PHP 5.4+
- <em>TODO: add specified requirement list here</em>

TODO: make this check.php script...

<strike>
You can run system check script via following command in the root directory:
<pre>
php -q html/check.php
</pre>
or pointing your browser to following address after installation:
<pre>
http://your-host/check.php
</pre>
</strike>

### Notes
We're always looking for some help to do this all, please contact if you are interested in this.

### Why to do this?
Don't know, some fucking passion to do something etc.

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

### Questions?
Contact me via IRC at da_wunder@IRCnet

### IDE recommendation
Really, use PhpStorm (http://www.jetbrains.com/phpstorm/) it rocks!

The MIT License (MIT)
---------------------
Copyright (c) 2012 Tarmo Leppänen

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

<strong>Note:</strong> libs and html/js/ -directories contains 3rd party software, which may have different license that MIT so check them also. Basically every 3rd party software is "freeware" for non-commercial use.

Read more from wikipedia, http://en.wikipedia.org/wiki/MIT_License