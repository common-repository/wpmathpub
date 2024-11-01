<?php
/***************************************************************************************
Plugin Name: WP Math Publisher - wpmathpub
Plugin URI: https://wordpress.org/plugins/wpmathpub/
Description: Put your plain text <a href="https://www.biophysicslab.com/wp-content/plugins/wpmathpub/phpmathpublisher/doc/help.html">mathematical expressions</a> between [pmath size=xx]...[/pmath] shortcode tags. Useful xx size integer values range from 8 to 24 (default is 12). An updated version of Pascal Brachet's MathPublisher.php included, now maintained by BiophysicsLab.com (version 0.5). 
Version: 2.0.3
Date: Nov, 22, 2022
Author: Ron Fredericks, BiophysicsLab.com
Support: https://www.biophysicslab.com/wordpress-math-publisher-plugin/

Easy install notes:
	Copy the wpmathpub directory and all its contents into your WordPress plugins directory.
	Activate plugin.
	Verify plugin installation status from wordpress dashboard > tools > wpmathpub (review table). 

Platforms tested over the years:
	1) Linux Apache web server, php 4.4.4, WordPress 2.0.4, default theme, installed in subdirectory, 
	2) Linux Apache web server, php 4.4.4, WordPress 2.3.3, clasic theme, installed in root directory,
	3) Linux Apache web server, php 4.4.4, WordPress 2.5.1, default theme, installed in subdirectory. 
	4) Linux Apache web server, php 5.2.14, WordPress 3.0.4, TwentyTen theme, installed in root directory. 
    5) Linux Apache web server, php 7.2.22, WordPress 5.2.3, High Responsive theme by Catch Themes, installed in root directory. 
	6) Linux Apache web server, php 7.4.24, WordPress 6.1, High Responsive theme by Catch Themes, installed in root directory. 
	
References:
	Origin: Pascal Brachet's phpmathpublisher and Matteo Bertini's WordPress plugin called PHP Math Publisher [both code bases not available anymore]
	
	BiophysicsLab.com now manages an up-to-date version of PHPMathPublisher bundled with this plugin for WordPress.org users.
	

	
***************************************************************************************/
/***************************************************************************************
 
    Copyright 2008-22  Ron Fredericks, BiophysicsLab.com (email : ron [at] BiophysicsLab.com)
	
	GNU General Public License
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
***************************************************************************************/
// 									** Begin wpmathpub Code **
define("WPMATHPUBVERSION", '2.0.0');

//
// Control automatic test and set feature for write access to 'img' directory.
// 		Automatic test and set feature when define("AUTOCHMOD", true); 
// 		Manual automatic test and set feature when define("AUTOCHMOD", false);
define("AUTOCHMOD", true);

// Control support for [pmath] tag in blog comments
//		Engage the [pmath] tag support in blog comments when define("ENGAGECOMMENTS", true);
//		Disengage the [pmath] tag support in blog comments when define("ENGAGECOMMENTS", false);
define("ENGAGECOMMENTS", true);

// Include Pascal's php math publisher library.
define("PHPMATHLIB", 'phpmathpublisher', true);
require_once(PHPMATHLIB.'/mathpublisher.php') ;

// Determine depth of relative addressing based on location of current running script: default value is that of WordPress install directory.
$tmparray = explode("/",getcwd());
$depth=(end($tmparray) == "wp-admin") ? "..": ".";

// Overwrite mathpublisher.php's default pointers to /img and /fonts subdirectories with a flexible relative addressing scheme.
$basedir = '/wp-content/plugins/'.basename(dirname(__FILE__), ".php").'/'.PHPMATHLIB;
$dirfonts=$depth.$basedir.'/fonts';
$dirimg=$depth.$basedir.'/img';

// Create a seperate absolute pointer to the phpmathpublisher /img/ subdirectory because our relative address scheme won't work when called from the "apply filter" php module within the WordPress Loop.
$abs_dirimg = get_bloginfo('url').$basedir.'/img/';

// test for proper installation and a known run-time environment
$abs_dirimg_readable = is_readable($dirimg);

// Attempt to make $dirimg writable if it is not writable already.
$abs_use_mathfilter = true;
if (AUTOCHMOD && $abs_dirimg_readable) {
	if (!is_writable($dirimg)) {
		if (!chmod($dirimg, 0755)) {
			$abs_use_mathfilter = false;
		}
		clearstatcache();
	}
}

// Returns with <img src=http://www.yoursite.com/yourblog/wp-content/pluglins/wpmathpub/phpmathpublisher/img/some_unique_image.png> HTML tag.
// Makes reference to mathfilter function included from PHPMATHLIB/mathpublisher.php code.
// Update parameter passing method from preg_replace to preg_replace_callback
function wpmathfilter($m)
{
	global $abs_dirimg;
	global $abs_use_mathfilter;
	global $abs_dirimg_readable;

    $ascii_math = $m[4];
    $size_math = $m[2];
		
	// Define the default font size.
	if (empty($size_math)) $size_math = '12';
	
	if ($abs_use_mathfilter && $abs_dirimg_readable) {
		// html_entity_decode() converts HTML entities like "&gt;" back to standard text like ">", when present.
		$ascii_math = html_entity_decode($ascii_math);
		// str_replace() supports the new ge gt ne math symbols in phpmathpublisher as of BiophysicsLab fork version 0.5
		$ascii_math = str_replace("<>", "ne", $ascii_math);
		$ascii_math = str_replace(">=", "ge", $ascii_math);
		$ascii_math = str_replace(">", "gt", $ascii_math);
		$phpmath = mathfilter("<m>".$ascii_math."</m>", $size_math, $abs_dirimg);
	} else if ($abs_dirimg_readable) {
		$phpmath = '<span style="color: red">Error:</span>'." $abs_dirimg must have write access".' <a href="http://wordpress.org/extend/plugins/wpmathpub/faq/" title="use '."'chmod 755 img'".' to attempt to manually fix this problem on your server">Read the official wpmathpub plugin FAQ for more details</a>';
	} else {
		$phpmath = '<span style="color: red">Error:</span>'." wpmathpub plugin not usable under these conditions: $abs_dirimg";
	}
	return $phpmath;
}

// Create a WordPress math text filter 
function to_phpmath($content)
{
	// Add an optional font size attribute size=xx
	// Use preg_replace_callback instead of preg_replace /e in support of php 7
    $content = preg_replace_callback('#\[pmath(\s+size=|\s?)(\d*)(\])(.*?)\[/pmath\]#si', 'wpmathfilter', $content);
	return $content;
}

// action function for above hook
function mt_add_pages() {
    // Add a new submenu under Manage:
    add_management_page('wpmathpub', 'wpmathpub', 8, 'wpmathpubmanage', 'wpmathpub_manage_page');
}

// wpmathpub_manage_page() displays the page content for the Test Manage submenu
function wpmathpub_manage_page() {
	global $abs_dirimg;
	global $dirimg;	
	global $abs_use_mathfilter;
	global $abs_dirimg_readable;
	global $abs_addfilter_test;

	$abs_dirimg_executable = is_executable($dirimg);
	if ($abs_use_mathfilter)
		$abs_use_mathfilter = is_writable($dirimg);	// make sure img directory really is writable
	
	$arraytemp = gd_info(); // collect details on server's support of GD graphics library
	
	$tabcolor = array('#dddddd', '#ffffcc');
	$tabcnt = 0;	
	echo "<table width='700' border='0' cellspacing='1' cellpadding='1'>";
	
  	echo "<tr>";
    echo "<th scope='col'> </th><th scope='col'><span style='color: blue'><h3>wpmathpub plugin status: ".WPMATHPUBVERSION."</h3></span></th>";
  	echo "</tr>";
	
	echo "<tr>";
 	echo "<th scope='row' width='240' align='right'><span style='color: blue'>Operating system:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".PHP_OS."</td>";
  	echo "</tr>";	
		
	echo "<tr>";
 	echo "<th scope='row' width='240' align='right'><span style='color: blue'>PHP version:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".phpversion()."</td>";
  	echo "</tr>";		
	
	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>PHP GD library:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".((strlen($arraytemp["GD Version"])>1) ? ("version ".$arraytemp["GD Version"]) : "<span style='color: red'> ERROR: GD library not found on this server</span>") .(($arraytemp["PNG Support"]===true) ? " with PNG format supported" : "<span style='color: red'> ERROR: PNG format not supported</span>")."</td>";
  	echo "</tr>";
	
  	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>Ownership:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".((getmyuid()==fileowner($dirimg)) ? "" : "<span style='color: red'>WARNING </span>")." script owner=".getmyuid().", img file owner=".fileowner($dirimg)."</td>";
  	echo "</tr>";	
	
	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>Blog's url:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".get_bloginfo('url')."</td>";
  	echo "</tr>";	
	
	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>WordPress version:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".get_bloginfo('version')."</td>";
  	echo "</tr>";	
	
	echo "<tr>";	
	echo "<th scope='row' width='240' align='right'><span style='color: blue'>WordPress plugin name:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".basename(dirname(__FILE__), ".php")."</td>";
  	echo "</tr>";		

	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>Relative img path:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".$dirimg."</td>";
  	echo "</tr>";	
	
	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>Working directory:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".getcwd()."</td>";
  	echo "</tr>";		

  	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>Absolute img path:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">$abs_dirimg</td>";
  	echo "</tr>";
	
  	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>img directory readable:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".(($abs_dirimg_readable) ? "yes" : "<span style='color: red'>error</span>")."</td>";
  	echo "</tr>";
	
  	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>img directory writable:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".(($abs_use_mathfilter) ? "yes" : "<span style='color: red'>error</span>")."</td>";
  	echo "</tr>";
	
  	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>img directory executable:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".(($abs_dirimg_executable) ? "yes" : "<span style='color: red'>error</span>")."</td>";
  	echo "</tr>";
		
  	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>Content filter added:</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".(($abs_addfilter_test) ? "yes" : "<span style='color: red'>error</span>")."</td>";
  	echo "</tr>";
		
  	echo "<tr>";
    echo "<th scope='row' width='240' align='right'><span style='color: blue'>mathfilter(y=mx^2+b):</span></th>";
    echo "<td bgcolor=".$tabcolor[$tabcnt++ %2].">".mathfilter("<m>".html_entity_decode("y=mx^2+b")."</m>", '12', $abs_dirimg)."</td>";
  	echo "</tr>";	
	
	echo "</table>";
}

// Register our WordPress text filter, to_phpmath, into the two hook routines, the_content and comment_text.
if (!ENGAGECOMMENTS) {
// 		Register comment_text updates after all priorty comment processing filters.
//		Note: calling the comment filter first, before the content filter, fixed comment_RSS feed errors.
	remove_filter('comment_text', 'to_phpmath'); 
} else {
	add_filter('comment_text', 'to_phpmath'); 
}
// 		Register the_content updates after all priorty content processing filters.
$abs_addfilter_test = add_filter('the_content', 'to_phpmath', 5);

// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');
//
// 									** End wpmathpub Code **
?>
