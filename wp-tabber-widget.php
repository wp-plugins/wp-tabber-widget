<?php
/*
Plugin Name: Wp tabber widget
Description: This is a jquery based lightweight plugin to create tab in the wordpress widget.
Author: Gopi.R
Version: 2.1
Plugin URI: http://www.gopiplus.com/work/2012/11/10/tabber-widget-plugin-for-wordpress/
Author URI: http://www.gopiplus.com/work/2012/11/10/tabber-widget-plugin-for-wordpress/
Donate link: http://www.gopiplus.com/work/2012/11/10/tabber-widget-plugin-for-wordpress/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("GTabberTable", $wpdb->prefix . "gtabber");
define('WP_gtabber_FAV', 'http://www.gopiplus.com/work/2012/11/10/tabber-widget-plugin-for-wordpress/');

if ( ! defined( 'WP_gtabber_BASENAME' ) )
	define( 'WP_gtabber_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_gtabber_PLUGIN_NAME' ) )
	define( 'WP_gtabber_PLUGIN_NAME', trim( dirname( WP_gtabber_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_gtabber_PLUGIN_URL' ) )
	define( 'WP_gtabber_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_gtabber_PLUGIN_NAME );
	
if ( ! defined( 'WP_gtabber_ADMIN_URL' ) )
	define( 'WP_gtabber_ADMIN_URL', get_option('siteurl') . '/wp-admin/options-general.php?page=wp-tabber-widget' );

// Main method to load tabber widget
function GTabber()
{
	global $wpdb;
	$gtabber_left = get_option('GTabber_Left');
	$gtabber_right = get_option('GTabber_Right');
	
	if($gtabber_left == "" && $gtabber_right == "")
	{
		echo 'Tabber widget: Please enter correct group value<br /><br />';
		return;
	}
	
	$sSql = "select gtabber_text, gtabber_link, gtabber_group, gtabber_target from ".GTabberTable." where 1=1";
	if($gtabber_left == "" || $gtabber_right == "")
	{
		if($gtabber_right <> "")
		{
			$sSql = $sSql . " and `gtabber_group` = '".$gtabber_right."'";
		}
		if($gtabber_left <> "")
		{
			$sSql = $sSql . " and `gtabber_group` = '".$gtabber_left."'";
		}
	}
	else
	{
		$sSql = $sSql . " and (`gtabber_group` = '".$gtabber_right."' or `gtabber_group` = '".$gtabber_left."')";
	}
	$data = $wpdb->get_results($sSql);
	$left = "";
	$right = "";
	if ( ! empty($data) ) 
	{
		foreach ( $data as $data ) 
		{
			if($data->gtabber_group == $gtabber_left)
			{
				$left = $left. "<div>";
				if($data->gtabber_link <> "") 
				{ 
					$left = $left. "<a href='".$data->gtabber_link."' target='".$data->gtabber_target."'>";
				}
				$left = $left. stripslashes($data->gtabber_text);
				if($data->gtabber_link <> "") 
				{ 
					$left = $left. "</a>";
				}
				$left = $left. "</div>";
			}
			elseif($data->gtabber_group == $gtabber_right)
			{
				$right = $right. "<div>";
				if($data->gtabber_link <> "") 
				{ 
					$right = $right. "<a href='".$data->gtabber_link."' target='".$data->gtabber_target."'>";
				}
				$right = $right. stripslashes($data->gtabber_text);
				if($data->gtabber_link <> "") 
				{ 
					$right = $right. "</a>";
				}
				$right = $right. "</div>";
			}
		}
		?>
		<div id="GTabberTabber">
		  <ul class="GTabberTabs">
			<li><a href="#GTabberLeft"><?php echo stripslashes($gtabber_left); ?></a></li>
			<li><a href="#GTabberRight"><?php echo stripslashes($gtabber_right); ?></a></li>
		  </ul>
		  <!--end .GTabberTabs-->
		  <div class="clear"></div>
		  <div class="GTabberInside">
			<div id="GTabberLeft">
				<?php echo $left; ?>
			</div>
			<!--end #popular-posts-->
			<div id="GTabberRight">
				<?php echo $right; ?>    
			</div>
			<!--end #recent-posts-->
			<div class="clear" style="display: none;"></div>
		  </div>
		  <!--end .GTabberInside -->
		  <div class="clear"></div>
		</div>
		<?php
	}
}

/*Function to call when plugin get activated*/
function GTabber_install() 
{
	global $wpdb, $wp_version;
	if($wpdb->get_var("show tables like '". GTabberTable . "'") != GTabberTable) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". GTabberTable . "` (";
		$sSql = $sSql . "`gtabber_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`gtabber_text` VARCHAR( 255 ) NOT NULL ,";
		$sSql = $sSql . "`gtabber_link` VARCHAR( 255 ) NOT NULL default '#' ,";
		$sSql = $sSql . "`gtabber_group` VARCHAR( 25 ) NOT NULL default 'left' ,";
		$sSql = $sSql . "`gtabber_target` VARCHAR( 10 ) NOT NULL default '_blank',";
		$sSql = $sSql . "`gtabber_extra1` VARCHAR( 255 ) NOT NULL default 'No' ,";
		$sSql = $sSql . "`gtabber_extra2` int( 11 ) NOT NULL default '0' ,";
		$sSql = $sSql . "`gtabber_expiration` datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "PRIMARY KEY ( `gtabber_id` )";
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		$wpdb->query($sSql);
		
		$IsSql = "INSERT INTO `". GTabberTable . "` (`gtabber_text`, `gtabber_group`)"; 
		for($i = 1; $i <= 10; $i++)
		{
			if($i <= 5 )
			{
				$group = "left";
				$text = "Sample text " . $i ;
			}
			else
			{
				$group = "right";
				$text = "Sample text " . $i ;
			}
			$sSql = $IsSql . " VALUES ('$text', '$group');";
			$wpdb->query($sSql);
		}
	}
	add_option('GTabber_Left', "left");
	add_option('GTabber_Right', "right");
}

/*Function to Call when plugin get deactivated*/
function GTabber_deactivation() 
{
	// No action on plugin deactivation
}

/*Admin tabber text management*/
function GTabber_admin()
{
	//include_once("content-management.php");
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-edit.php');
			break;
		case 'add':
			include('pages/content-add.php');
			break;
		case 'set':
			include('pages/content-setting.php');
			break;
		default:
			include('pages/content-show.php');
			break;
	}
}

/*Admin menu options*/
function GTabber_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page( __('Wp tabber widget', 'wp-tabber-widget'), 
				__('Wp tabber widget', 'wp-tabber-widget'), 'manage_options', 'wp-tabber-widget', 'GTabber_admin' );
	}
}

/*Load javascript files for plugins*/
function GTabber_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'Gtabber', WP_gtabber_PLUGIN_URL.'/inc/Gtabber.css');
		wp_enqueue_script( 'tabber', WP_gtabber_PLUGIN_URL.'/inc/Gtabber.js', '', '1.0', true);
	}
}   

/*Tabber plugin widget control*/
function GTabber_control() 
{
	$GTabber_Left = get_option('GTabber_Left');
	$GTabber_Right = get_option('GTabber_Right');
	if (isset($_POST['GTabber_Submit'])) 
	{
		$GTabber_Left = $_POST['GTabber_Left'];
		$GTabber_Right = $_POST['GTabber_Right'];
		update_option('GTabber_Left', $GTabber_Left );
		update_option('GTabber_Right', $GTabber_Right );
	}
	echo '<p>'.__('Group 1:', 'wp-tabber-widget').'<br><input  style="width: 200px;" type="text" value="';
	echo $GTabber_Left . '" name="GTabber_Left" id="GTabber_Left" /></p>';
	echo '<p>'.__('Group 2:', 'wp-tabber-widget').'<br><input  style="width: 200px;" type="text" value="';
	echo $GTabber_Right . '" name="GTabber_Right" id="GTabber_Right" /></p>';
	echo '<input type="hidden" id="GTabber_Submit" name="GTabber_Submit" value="1" />';
}

/*Method to load tabber widget*/
function GTabber_widget($args) 
{
	//extract($args);
	//echo $before_widget . $before_title;
	//echo "Widget Title";
	//echo $after_title;
	GTabber();
	//echo $after_widget;
}

/*Method to initiate sidebar widget & control*/
function GTabber_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget( __('Wp tabber widget', 'wp-tabber-widget'), __('Wp tabber widget', 'wp-tabber-widget'), 'GTabber_widget');
	}
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control( __('Wp tabber widget', 'wp-tabber-widget'), array( __('Wp tabber widget', 'wp-tabber-widget'), 'widgets'), 'GTabber_control');
	} 
}

function GTabber_textdomain() 
{
	  load_plugin_textdomain( 'wp-tabber-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'GTabber_textdomain');
add_action('admin_menu', 'GTabber_add_to_menu');
add_action("plugins_loaded", "GTabber_init");
add_action('wp_enqueue_scripts', 'GTabber_add_javascript_files');
register_activation_hook(__FILE__, 'GTabber_install');
register_deactivation_hook(__FILE__, 'GTabber_deactivation');
?>