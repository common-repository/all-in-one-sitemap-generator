<?php
	/*
	Plugin Name: All In One Sitemap Generator
	Description: Create sitemap in XML and txt format and robots editor.
	Version: 1.0
	Author: Techsurvi
	Author URI: https://techsurvi.com
	Text Domain: All In One Sitemap Generator
    Domain Path: /languages/
	*/
	
	// Disable direct access
	defined('ABSPATH') or die("Hey you can't access this file!!!!");
	
	// Load translations
	function aiositemap_load_translations() {
		load_plugin_textdomain( 'all-in-one-sitemap-genrator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
	}
	add_action( 'init', 'aiositemap_load_translations' );
	
	// Insert Data
	function aiositemap_install()
	{
		if (! wp_next_scheduled ( 'aiositemap_create' )) wp_schedule_event( time(), 'hourly', 'aiositemap_create '); //Set schedule
	
		// Get files needed for this plugin to work
		$aiositemap_url 	= get_home_path();
		$aiositemap_file 	= $aiositemap_root_file.'/sitemap.xml';
		$aiositemap_robots_file = $aiositemap_root_file.'/robots.txt';
		
		// Create sitemap file
		if ( !file_exists( $aiositemap_file ) ) $aiositemap_myfile = fopen( $aiositemap_file, "w" );
	
		// Create robots file
		if ( !file_exists( $aiositemap_robots_file ) ) $aiositemap_myfile = fopen( $aiositemap_robots_file, "w" );
	}
	add_action('aiositemap_create', 'aio_sitemap');
	register_activation_hook( __FILE__, 'aiositemap_install' );
	
	// Clear everything about plugin
	function aiositemap_uninstall()
	{
		//deactivation
		flush_rewrite_rules();
	}
	register_deactivation_hook(  __FILE__, 'aiositemap_uninstall' );
	
	//add menu option
	add_action("admin_menu", "addMenuaiositemap");
	function addMenuaiositemap()
	{
		add_menu_page("All in one sitemap", "All in one sitemap", "edit_pages", "all in one sitemap", "dashboard_aiositemap");
	}
	 
	  // Sitemap dashboard
	function dashboard_aiositemap() {
		include_once('dashboard_sitemap.php');
	}
	//create sitemap in xml
	function create_xml_aiositemap()
	{
		global $aio_sitemap,$url;
		
		if (str_replace('-', '', get_option('aio_gmt_offset')) < 10)
			$get_offset = '-0' . str_replace('-', '', get_option('aio_gmt_offset'));			
		else 
			$get_offset = get_option('aio_gmt_offset');
		
		if (strlen($get_offset) == 3) 
			 $get_offset = $get_offset . ':00';
		
		$postsForAllinoneSitemap = get_posts(array('numberposts' => -1, 
		'orderby' => 'modified', 
		'post_type' => array('post', 'page', 'product', 'attachment'), 
		'post_status' => 'publish', 
		'order' => 'DESC'));
		
		$aio_sitemap .= '<?xml version="1.0" encoding="UTF-8"?>';
		
		$aio_sitemap .= "\n" . '<urlset
				  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
				  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
				  http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";
		
		$aio_sitemap .= "\t" . '<url>' . "\n" . "\t\t" . '<loc>' . esc_url(home_url('/')) . '</loc>' . "\n\t\t" . '<lastmod>' . date("Y-m-d\TH:i:s", current_time('timestamp', 0)) . $get_offset . '</lastmod>' . "\n\t\t" . '<changefreq>hourly</changefreq>' . "\n\t\t" . '<priority>1.0</priority>' . "\n\t" . '</url>' . "\n";
		foreach ($postsForAllinoneSitemap as $aiopost) 
		{
			setup_postdata($aiopost);
			$postdate = explode(" ", $aiopost -> post_modified);
			
			$aio_sitemap .= "\t" . '<url>' . "\n" . "\t\t" . '<loc>' . get_permalink($aiopost -> ID) . '</loc>' . "\n\t\t" . '<lastmod>' . $postdate[0] . 'T' . $postdate[1] . $get_offset . '</lastmod>' . "\n\t\t" . '<changefreq>Weekly</changefreq>' . "\n\t\t" . '<priority>0.5</priority>' . "\n\t" . '</url>' . "\n";
		
		}
		$aio_sitemap .= '</urlset>';
		$filepath = fopen(ABSPATH . "allinonesitemap.xml", 'w');
		fwrite($filepath, $aio_sitemap);
		fclose($filepath);
		
		$url .= "VIEW SITEMAP URL IN XML FORMAT" . '<a href="' . get_site_url() . '/allinonesitemap.xml" target="_blank"  target="All in one Sitemap" >Click Here</a><br />';
		echo $url;
	}
	function create_txt_aiositemap()
	{
		global $siteurl , $siteurl;
		$filepath = get_site_url();
		$sitemaplines = $filepath.'/allinonesitemap.xml';
		$allsitemapMatches = array();
		
		foreach ($allsitemapMatches as $line_number => $url_line ) 
		{
			 $url_line = trim($url_line);
			 preg_match_all('/(?<=\<loc\>)(.*?)(?=\<\/loc\>)/U', $url_line, $matches,PREG_SET_ORDER);
			 if($matches){
			  if ( $matches[0][0] != '' ) {
			   $allsitemapMatches[] = $matches[0][0];
			  };
			 };
		};
		
		$list = '';
		foreach ( $allsitemapMatches as $sitemaplines ) {
		 $list .= $sitemaplines."\n";
		};
		$fh = fopen('allurllist.txt', "w+");
		fwrite($fh, $list);
		fclose($fh);
		
		$siteurl .= "View URL IN .txt FORMAT" . '<a href="' . get_site_url() . '/allurllist.txt" target="_blank"  target="All in one Sitemap" >Click Here</a><br />' ;
			
		echo $siteurl;
	}
	// function to read robots.txt
	function aiositemap_read_robots_file()
	{
		$aiositemap_path = get_home_path();
		$aiorobots_file = $aiositemap_path .'/robots.txt';
		
		$robotsfilecontent = array();
		
		if($robotsfile = fopen($aiorobots_file, "r")){
			while (($robotsgetcontent = fgets($robotsfile)) !== false) {
				array_push( $robotsfilecontent, $robotsgetcontent );
			}	
			fclose($robotsfile);
		}
		foreach($robotsfilecontent as $robots_file_line)
			echo $robots_file_line; 
			echo '';
	}
	//function to write content in robots.txt
	function aiositemap_write_robots_file($content_to_write = '')
	{
		$aiositemap_path = get_home_path();
		$aiorobots_file = $aiositemap_path .'/robots.txt';
		
		if (is_writable($aiorobots_file)) {
	
			if (!$openfile = fopen($aiorobots_file, 'w'))
				 echo "robots.txt can not open";
			
			if (fwrite($openfile, $content_to_write) === FALSE) 
			   echo "Something goes wrong.";
					  
			echo "Your robots file has been updated succesfully<br />";
	
			fclose($openfile);
	
		} 
		else {
			echo "The file robots.txt is not writable";
		}
	}
	
	//function to ping google automatically
	 function ping_to_google()
	 {
		$aiositemapUrl = get_site_url() .'/allinonesitemap.xml';
		$aiositemap_ping_url = '';
		$aiositemap_ping_url = "http://www.google.com/webmasters/tools/ping?sitemap=" .$aiositemapUrl;
		$aiositemap_response = wp_remote_get( $aiositemap_ping_url );
		if($aiositemap_response['response']['code']=200)
			echo "Pinged google succesfully";
		else 
			echo "Failed to Pinged google";
		
	  }
	 
?>
