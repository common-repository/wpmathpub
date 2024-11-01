=== wpmathpub ===
Contributors: RonF
Donate link: https://www.paypal.com/donate/?hosted_button_id=S22G8VVL9YAVE
Tags: math, publish, equation, mathematics, MathML, LaTeX, Texmaker, symbol, axiom, operator, delimiter, matrix, constructor, sum, integral, derivative, calculus, vector, gradient, phpmathpublisher, mathpublisher, math publisher, wpmathpub, biophysicslab
Requires at least: 5.2.3
Requires PHP: 7.0
Tested up to: 6.1.1
Stable tag: 2.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin uses shortcode tags to display mathematical equations within your WordPress posts, pages, and comments.

== Description ==
Display mathematical equations within your posts, pages, and comments.

Put your plain text <a href="https://www.biophysicslab.com/wp-content/plugins/wpmathpub/phpmathpublisher/doc/help.html">mathematical expressions</a> between [pmath size=xx]...[/pmath] shortcode tags. Useful xx size integer values range from 8 to 24 (default is 12). 

Get more information [WordPress Math Publisher Plugin](https://www.biophysicslab.com/wordpress-math-publisher-plugin/ "WpMathPub discussion page") 

== Installation ==

1. Unzip into your `/wp-content/plugins/` directory. If you're uploading it make sure to upload
the top-level folder wpmathpub. 
2. Make sure the newly installed ./wpmathpub/phpmathpublisher/img directory is readable and writable on your web server (See FAQ for more details) 
3. Activate the plugin through the usual 'Plugins' menu in WordPress
4. Look for installation issues using the WordPress Dashboard > Tools > wpmathpub table

== Upgrade Notice ==
= 2.0.3 =
Minor documentation fixes

== Changelog ==

= 2.0.3 =
* get svn to delete unused files from repository
* sorry for so many small changes!

= 2.0.2 =
* get svn to copy all required files to repository

= 2.0.1 =
* Remove obsolete files for improved security
* Minor documentation fixes

= 2.0.0 =
* Fix bug using greater-than ">" symbol in Gutenberg block editor. 
* Add new vector math symbol "del".
* Update documentation now located at BiophysicsLab.com. 
* Verified plugin works with latest WordPress release (6.1.1) 
* Tested plugin on PHP 7.4
* Remove obsolete files for improved security

= 1.3.0 =
* Update phpmathpublisher code for fatal errors and test plugin on PHP 7

= 1.2.0 =
* Update wpmathpub code for PHP 7

= 1.1.0 =
* Improved documentation

= 1.0.9 =
* Improved documentation

= 1.0.8 =
* Improved documentation

= 1.0.7 =
* Fixed a few bugs, improved documentation.

== Upgrade Notice ==

= 1.1.0 =
Just a documentation update

= 1.0.9 =
Just a documentation update

= 1.0.8 =
Just a documentation update

= 1.0.7 =
Stable version

= 1.0.6 =
Upgrade if you have this version


== Frequently Asked Questions == 

= Where can I learn more about wpmathpub's graphics library? =

wpmathpub (aka WordPress Math Publisher) is based on Pascal Brachet's PhpMathPublisher library. 

Unfortunately Pascal's links to phpMathPublisher are now gone. Instead BiophysicsLab has picked up support for this library for WordPress use. Support includes porting the original PHP 5 code to PHP 7.x, and the addition of the gradient symbol (Del) for display of vector field equations.

A quick set of examples and list of all shortcode tags with their associated LaTeX font symbols are included with the plugin in the Doc section. An online link to this file (https://www.biophysicslab.com/wp-content/plugins/wpmathpub/phpmathpublisher/doc/help.html)

= The [pmath] tag doesn't seem to work. How can I solve this problem? =

Starting with version 1.0.7, use the wpmathpub plugin status display table from your blog's admin site's "Manage" or "Tools" menu. See screenshot #5 (in the screenshots tab) for details. The status display will:

* Check your system for correct access to required directories, 
* Determine if required libraries are available, and 
* Show a sample math conversion from text to image format.

Use the results within the table to help troubleshoot installation issues. 

= Do some plugins interfere with the wpmathpub plugin? =

Starting with version 1.0.7, an enhanced priority scheme was implemented to improve reliability and better cooperation with some high bandwidth video streaming plugins. 

At this time, only one plugin is known to play havoc with display of math images from within comments called: Live Comment Preview.  Blog posts are not affected. This plugin causes the [pmath] start tag to get out of sync with the [/pmath] end tag.  

If you suspect plugin interference, a simple test is to disable all of your plugins except wpmathpub. If wpmathpub works without other plugins, start turning on your plugins one by one to see which one(s) are interfering with [pmath] tag filtering.  If you find one, let me know - I may be able to find a solution.

= During installation how can I make sure the 'img' directory has write access? =

Use the wpmathpub plugin status display table from your blog's admin site's "Manage" or "Tools" menu

The 'img' directory needs write access to create new math images from your blog's math text. Starting with version 1.0.5, the wpmathpub plugin automatically assigns the correct access rights to the 'img' directory on Linux/Unix installations. This auto-assignment feature can be turned off in wpmathpub.php by setting AUTOCHMOD to false:

define("AUTOCHMOD", false);

Below is a sample bash shell session demonstrating how to manually locate the 'img' directory, change its mode to include write access, and verify the change was made:

<br/>-bash-3.00$ cd wp-content
<br/>-bash-3.00$ cd plugins
<br/>-bash-3.00$ cd wpmathpub
<br/>-bash-3.00$ cd phpmathpublisher
<br/>-bash-3.00$ chmod 755 img
<br/>-bash-3.00$ stat -c %a img
<br/>755
<br/>-bash-3.00$ stat -c %A img
<br/>drwxr-xr-x

= How can I disable the use of [pmath] tags within blog comments? =

By default, the wpmathpub plugin supports user generated math equations in comments. Starting with wpmathpub plugin version 1.0.6, you can disable the use of [pmath] tags in comments by changing ENGAGECOMMENTS flag to false in wpmathpub.php:

define("ENGAGECOMMENTS", false);

This setting will not affect the display of math equations in blog posts and pages.


= Can I use HTML entities like "& gt;" (for ">") in my math text equations? =

Starting in wpmathpub version 2.0.0, new symbols are recommended to replace the >, <, >=, <=, and <> test operators with gt, lt, ge, le, and ne. 

Specifically, the ">" symbol and its HTML entity "& gt;" will create unusual results in the Gutenberg block editor.

= Can I use pmath tags in blog posts, pages, AND blog comments? =

Starting with wpmathpub version 1.0.5 both blog posts, pages, and comments support pmath tags.

= I have a new Q =
You may go to the WordPress wpmathpub support page to ask questions to the community:
[WordPress wpmathpub Support Page](https://www.biophysicslab.com/wordpress-math-publisher-plugin/ "WordPress wpmathpub Plugin Community Support Page")

== Screenshots ==

1. WordPress post with [pmath] tags mixed with plain text
2. WordPress comments with [pmath] tags mixed with plain text (as shown from WP v:2.5.1 admin tool's detail view)
3. WordPress plugin management page after upload and activation
4. Sample directory structure of this plugin within a WordPress installation
5. status display from the author's blog > Manage > wpmathpub menu

== How To ==

To toggle to the math mode within your blog's content, you must use the [pmath size=xx]...[/pmath] markdown tag. The plugin automatically replaces your math text commands into HTML image tags that look sort of like this: 
&lt;img src="MathFileName.png" style="vertical-align:-xxpx; display: inline-block ;" alt="your math text command" title="your math text command"/&gt;.

Use the shortcode block to enter math equations from the Gutenberg WordPress block editor.

The math commands must be separated by a space character or surrounded by {}.

Examples: 

* [pmath size=12]S(f)(t)=a_{0}+sum{n=1}{+infty}{a_{n} cos(n omega t)+b_{n} sin(n omega t)}[/pmath] 
* [pmath size=24]delim{lbrace}{matrix{3}{1}{{3x-5y+z=0} {sqrt{2}x-7y+8z=0} {x-8y+9z=0}}}{ }[/pmath] 
* [pmath]delim{|}{{1/N} sum{n=1}{N}{gamma(u_n)} - 1/{2 pi} int{0}{2 pi}{gamma(t) dt}}{|} le epsilon/3[/pmath]
* [pmath size=16]vec{Del}f(x,y) ~ = ~ {partial{f}}/{partial{x}}hat{i} ~ + ~ {partial{f}}/{partial{y}} hat{j}[/pmath]
* [pmath size=16]{Del}f(x,y) ~ = ~ {partial{f}}/{partial{x}}i ~ + ~ {partial{f}}/{partial{y}} j[/pmath]


Math elements supported:

* Usual commands
* Parenthesis
* Math space
* Greek letters
* Symbols
* Arrows
* Sets
* Roots
* Limits
* Big operators
* Delimiters
* Matrix
* Constructions

[pmath syntax](https://www.biophysicslab.com/wp-content/plugins/wpmathpub/phpmathpublisher/doc/help.html "See complete list of elements and the symbols they generate here")

== Credits ==
* Thanx to [Pascal Brachet](https://www.xm1math.net/) for the original PhpMathPublisher library.