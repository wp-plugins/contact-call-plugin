<?php
/*
Plugin Name: Contact Call Plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Contact Call Plugin allows you to receive calls from browser and local access numbers in more than 40 countries.
Version: 1.0
Author: PBXPlus - Phone Company
Author URI: http://push2call.invox.com
License: GPL2
*/

// Constants - Define Push2Call (Voicelo definitions)
define('VOICELO_TEST_URL', "http://cdn-social.invox.com/live-call/standalone_v1.html?invoxid=");
define('VOICELO_VOICEMAIL_URL', "http://cdn-social.invox.com/live-call/standalone_v1.html?invoxid=");
define('VOICELO_LOGS_URL', "http://cdn-social.invox.com/live-call/standalone_v1.html?invoxid=");
define('VOICELO_BUSINESS_URL', "http://cdn-social.invox.com/live-call/standalone_v1.html?invoxid=");
define('VOICELO_ICON_URL', "http://cdn-site.invox.com/images/wp_fav.ico");
define('VOICELO_NUMBERS_URL', "http://phone.invox.com/widgetconfig/publishWidget/voicelo_dids.html?ext=");

// Wordpress DB
define('VOICELO_DB_SIDE', "contact_call_widget_side");
define('VOICELO_DB_POSITION', "contact_call_widget_position");
define('VOICELO_DB_COLOR', "contact_call_widget_color");
define('VOICELO_DB_FORWARD_TYPE', "contact_call_widget_forward_type");
define('VOICELO_DB_FORWARD_VALUE', "contact_call_widget_forward_value");
define('VOICELO_DB_USER_EMAIL', "contact_call_widget_user_email");
define('VOICELO_DB_USER_BASE_URL', "contact_call_widget_user_base_url");
define('VOICELO_DB_INVOX_USER_ID', "contact_call_widget_invox_user_id");
define('VOICELO_DB_INVOX_SHARED_EXTENSION', "contact_call_widget_invox_shared_extension");


// Init Contact Call Widget - Add Widget js at client side and admin.js at server side
function contact_call_widget_admin_init() {

	// Load Widget.js at user end and admin.js for admin
	$uri = $_SERVER['REQUEST_URI'];
	if(!strstr($uri,'wp-admin') && !strstr($uri,'wp-login'))
	{	
		// echo 'Do not touch it at admin side';
	}
	else
		wp_enqueue_script('push2call_script_client', plugins_url('/js/admin.js', __FILE__), array('jquery'), '1.0.1',true);	   	
}	


function contact_call_widget_init()
{

	// Load Widget.js at user end and admin.js for admin
	$uri = $_SERVER['REQUEST_URI'];
	if(!strstr($uri,'wp-admin') && !strstr($uri,'wp-login'))
	{					
		$forward_type = get_option(VOICELO_DB_FORWARD_TYPE);
		$forward_value = get_option(VOICELO_DB_FORWARD_VALUE);
		$position = get_option(VOICELO_DB_POSITION);
		$side = get_option(VOICELO_DB_SIDE);
		$color = get_option(VOICELO_DB_COLOR);
		$user_email  = get_option(VOICELO_DB_USER_EMAIL);
		$user_url = get_option(VOICELO_DB_USER_BASE_URL);
		$invoxuserid = get_option(VOICELO_DB_INVOX_USER_ID);
		$invoxsharedextension = get_option(VOICELO_DB_INVOX_SHARED_EXTENSION);

		if($invoxuserid != '')
		{
	
		?>
		
		<!-- Start of InVox.com Widget -->
	<script  type="text/javascript">
	var _inv = {};
	_inv.invoxid = '<?php echo $invoxuserid ?>';
	_inv.label = 'Call';
	_inv.side= '<?php echo $side ?>';
	_inv.color= '#<?php echo $color ?>';
	_inv.position = '<?php echo $position ?>%';
	</script>
	<!-- End of InVox.com Widget -->
	
	<?php
	
		 wp_enqueue_script('push2call_script_client', plugins_url('/js/widget.js', __FILE__), array('jquery'), '1.0.1',true);	   	
	
	
		}
	}
}


// Remove dbs while Uninstalling 
function contact_call_widget_uninstall() {
   
	// Delete all options for db
	delete_option(VOICELO_DB_SIDE);
	delete_option(VOICELO_DB_POSITION);
	delete_option(VOICELO_DB_COLOR);
	delete_option(VOICELO_DB_FORWARD_TYPE);
	delete_option(VOICELO_DB_FORWARD_VALUE);
	delete_option(VOICELO_DB_USER_EMAIL);
	delete_option(VOICELO_DB_USER_BASE_URL);
	delete_option(VOICELO_DB_INVOX_USER_ID);
	delete_option(VOICELO_DB_INVOX_SHARED_EXTENSION);
}

/******************* Contact Call Widget Install *******************************************/

add_action("init", "contact_call_widget_admin_init");
add_action("wp_print_scripts", "contact_call_widget_init");
register_deactivation_hook(__FILE__, 'contact_call_widget_uninstall');

/******************* End of Contact Call Widget Install ************************************/


/********************* Start of Menu Options ***********/				

// create custom plugin menu
add_action('admin_menu', 'voicelo_create_menu');   

//create admin menu
function voicelo_create_menu() {
  
   //create new top-level menu
   add_menu_page('Account Configuration', 'Contact-Call Widget', 'administrator', 'voicelo_account_config', 'voicelo_account_config',VOICELO_ICON_URL);
   
   // Setup menu
   add_submenu_page('voicelo_account_config', 'Account Configuration', 'Setup', 'administrator', 'voicelo_account_config', 'voicelo_account_config');
   
   // Preview menu
   //add_submenu_page('voicelo_account_config', 'Voicelo Preview', 'Preview', 'administrator', 'voicelo_test', 'test_voicelo');

   // Voicemails menu
  // add_submenu_page('voicelo_account_config', 'Voicelo Voicemails', 'Voicemails', 'administrator', 'voicelo_voicemails', 'voicemail_voicelo');
   
   // Logs menu
   //add_submenu_page('voicelo_account_config', 'Voicelo Logs', 'Logs', 'administrator', 'voicelo_logs', 'logs_voicelo');
   
   // Business menu
  // add_submenu_page('voicelo_account_config', 'Voicelo Businesses', 'Businesses', 'administrator', 'voicelo_businesses', 'business_voicelo');

   // Numbers
  // add_submenu_page('voicelo_account_config', 'Voicelo Global Countries', 'Global Countries', 'administrator', 'voicelo_global', 'global_voicelo');

}

// Test
function test_voicelo() {

  $guid = get_option('invoxuserid');

   if(strlen($guid)!=0){
	   echo '<div id="dashboarddiv"><iframe id="dashboardiframe" src='.VOICELO_TEST_URL.''.get_option('invoxuserid').' height=700 width=98% scrolling="no"></iframe></div>';
   }else{
		echo '<div >Please setup your voicelo before checking the preview.</div>';
   }

}
// Voicemails
function voicemail_voicelo() {
	
   $guid = get_option('invoxuserid');

   if(strlen($guid)!=0){
      echo '<div id="dashboarddiv"><iframe id="dashboardiframe" src='.VOICELO_VOICEMAIL_URL.''.get_option('invoxuserid').' height=700 width=98% scrolling="no"></iframe></div>';
   }else{
		echo '<div >Please setup your voicelo before checking the voicemails.</div>';
   }

}
// Logs
function logs_voicelo() {
  $guid = get_option('invoxuserid');

   if(strlen($guid)!=0){
	   echo '<div id="dashboarddiv"><iframe id="dashboardiframe" src='.VOICELO_LOGS_URL.''.get_option('invoxuserid').' height=700 width=98% scrolling="no"></iframe></div>';
   }else{
		echo '<div >Please setup your voicelo before checking the logs.</div>';
   }
}

// Numbers
function global_voicelo() {
  
   $ext = get_option('invoxsharedextension');

   if(strlen($ext)!=0){
	   echo '<div id="dashboarddiv"><iframe id="dashboardiframe" src='.VOICELO_NUMBERS_URL.''.get_option('invoxsharedextension').' height=700 width=98% scrolling="no"></iframe></div>';
   }else{
        echo '<div >Please setup your voicelo first to access global countries.</div>';
   }
}
//Business
function business_voicelo() {
 
 ?>
  <div  style="margin-top:30px;">
   <div class="invbody">
   This plugin provides you with a free 'Call Us' button for your wordpress site. This button helps your web visitors to instantly call you free from their PC; Thus, making it easier for your web visitor to contact you instantly and conveniently! <a href="http://www.invox.com/" target="_blank;">website</a>
   </div>
  </div>
  
 
  <?php
}   


/********************* End of Menu Options ***********/				



/********************* Configure settings for invox api ***********/				
function voicelo_account_config() {
	
	$task=$_POST['task'];
	if($task == 'add_submit'){
		
		
		$forward_type = $_POST['forward_type'];
		$forward_value = $_POST['forward_value'];
		$position = $_POST['position'];
		$color = $_POST['color'];
		$side = $_POST['side'];
		$user_email  = $_POST['user_email'];
		$user_url  = $_POST['user_url'];
        $invoxuserid = $_POST['invoxuserid'];
		$invoxsharedextension = $_POST['invoxsharedextension'];
		if($forward_value == ''){
			$msg = 'Please enter your number.';
		}
		if($color==''){
			if($msg!='')$msg .= '<br />';
			$msg .= 'Please provide color code.';
		}
		if($user_email==''){
			if($msg!='')$msg .= '<br />';
			$msg .= 'Please provide Email ID.';
		}
		if($user_url==''){
			if($msg!='')$msg .= '<br />';
			$msg .= 'Please provide your website URL.';
		}
		
		if($msg==''){
			
			update_option(VOICELO_DB_FORWARD_TYPE, $forward_type );
			update_option(VOICELO_DB_FORWARD_VALUE, $forward_value );			
			update_option(VOICELO_DB_POSITION, $position);				
			update_option(VOICELO_DB_SIDE, $side);
			update_option(VOICELO_DB_COLOR, $color);
			update_option(VOICELO_DB_USER_EMAIL, $user_email );
			update_option(VOICELO_DB_USER_BASE_URL, $user_url );
			update_option(VOICELO_DB_INVOX_USER_ID, $invoxuserid );
			update_option(VOICELO_DB_INVOX_SHARED_EXTENSION, $invoxsharedextension );
			
			$success = 'Successfully saved';
		}
	}else{
				
		$forward_type = get_option(VOICELO_DB_FORWARD_TYPE);
		$forward_value = get_option(VOICELO_DB_FORWARD_VALUE);
		$position = get_option(VOICELO_DB_POSITION);
		$side = get_option(VOICELO_DB_SIDE);
		$color = get_option(VOICELO_DB_COLOR);
		$user_email  = get_option(VOICELO_DB_USER_EMAIL);
		$user_url = get_option(VOICELO_DB_USER_BASE_URL);
		$invoxuserid = get_option(VOICELO_DB_INVOX_USER_ID);
		$invoxsharedextension = get_option(VOICELO_DB_INVOX_SHARED_EXTENSION);
	}

	 if($position=='') $position = '50';
	 if($side=='') $side = 'left';
	// if($button_label=='') $button_label = 'Contact us';
	if($color=='') $color = '565656';	
	
	
    ?>

<style type="text/css">

label{  float: left;  width: 120px;  margin-right: 5px; margin-bottom: 5px;margin-left:25px; text-transform: capitalize;}
.invheader{background: #DFDFDF url("http://cdn-site.invox.com/images/wp_gray_grad.png") repeat-x left top;padding:8px;}
.invbody{-moz-border-radius: 6px;-khtml-border-radius: 6px;-webkit-border-radius: 6px;border-radius: 6px;background-color: white;border: 1px solid #DFDFDF;}
h3{font-size:16px;height:20px;margin:0px;}
.invinput{margin-bottom:5px;}
</style>	
<div class="wrap">	
	<h2 style="margin-bottom:20px;">Configure Push2Call widget</h2>		
	<?php 	
	
	if($msg!=''){
		echo '<div class="error">'.$msg.'</div>';		
	}
	if($success!=''){
		echo '<div class="updated message">'.$success.'</div>';		
	}
	?>
	<div class="invbody">
	<h3 class="invheader">Setup</h3>
	
	<div style="padding:20px 10px 20px 45px;">
	
	<p>In just few minutes, your web visitors can call you directly from browser (web-based phone) or <a href='http://www.pbxplus.com/sharednumbers.jsp'>local access numbers</a> in more than 40 different countries. <br/>Eg: customer in Japan can from Browser or a local number in Tokyo, Japan to reach your business.</p>
	
	<br/>
	
	<center><img src='http://push2call.invox.com/img/push2call-new.png'/></center>
	
	<br/>
	<p>Don't worry - if you miss your calls, we will send you an email with voicemail attachment.</p>
	
	<br/>
	<form action="" method="post" id="push2call" name="push2call" >

	    <b>Where do you wish to answer calls made by your web visitors?</b>
		<br /><br />
		<!-- Forward Type -->
		<label for="invoxforwardtype">Forward Type:</label>	
			<select name="forward_type" id="forward_type" onchange = "return forwardOption();" class="invinput">
				<option value="Skype" <?php if($forward_type == 'Skype') echo 'selected="selected"';?> >Skype</option>
				<option value="Google-Talk" <?php if($forward_type == 'Google-Talk') echo 'selected="selected"';?> >Google Talk </option>
				<option value="Toll-free" <?php if($forward_type == 'Toll-free') echo 'selected="selected"';?> >Toll-free</option>
				<option value="Landline/Mobile" <?php if($forward_type == 'Landline/Mobile') echo 'selected="selected"';?> >Landline/Mobile</option>	
				<option value="SIP" <?php if($forward_type == 'SIP') echo 'selected="selected"';?> >SIP</option>	
			</select>	
		<br />
		
		<!-- Forward Value -->
		<label for="invoxforwardvalue" id="invoxforwardvalue">Forward Value:</label>	
		<input type="text" class="invinput" name="forward_value" id="forward_value" value="<?php echo $forward_value;?>" size="35"/>
		<span class="description">Your Skype ID or Google Talk or SIP or Toll-free/US number.</span>	
		<br/>
		
		<!-- Button Style -->
		<br /><b>Button Style</b>
		<br /><br />
		<!-- side -->
		<label for="position">Side:</label>			 
			<select name="side" id='side' class="invinput">
				<option value="top" <?php if($side=='top') echo 'selected="selected"';?>>Top</option>
				<option value="bottom" <?php if($side=='bottom') echo 'selected="selected"';?>>Bottom</option>
				<option value="left" <?php if($side=='left') echo 'selected="selected"';?>>Left</option>
				<option value="right" <?php if($side=='right') echo 'selected="selected"';?>>Right</option>		
				
			</select>
		<br />
		<!-- position -->
		<label for="position">Position:</label>			 
			<select name="position" id='position' class="invinput">
				<option value="0" <?php if($position=='0') echo 'selected="selected"';?>>0%</option>
				<option value="30" <?php if($position=='30') echo 'selected="selected"';?>>30%</option>
				<option value="45" <?php if($position=='45') echo 'selected="selected"';?>>45%</option>
				<option value="50" <?php if($position=='50') echo 'selected="selected"';?>>50%</option>			
				<option value="75" <?php if($position=='75') echo 'selected="selected"';?>>75%</option>	
				
			</select>
		<br />
		
		<!-- color -->
		<label for="color">Color #: </label>	
		<input type="text" class="invinput" name="color" id='color' value="<?php echo $color;?>" maxlength="6" size="6" /> 
		<span class="description">Please provide valid color code to get effected. (Eg. FF00FF).</span>	
		<br />
		
		<!-- Customer Details -->
		<br /><b>Your Details</b><br /><br />
         <label for="user_email">Email ID:</label>	
			<input type="text" class="invinput" name="user_email" id='user_email' value="<?php echo $user_email;?>" size="35"/>
			<span class="description">for voicemail attachments and statistics</span>
		<br />
		
		<!-- <label for="user_url">Base URL:</label>-->	
		<input type="hidden" class="invinput" id="user_url" name="user_url" value="<?php echo get_bloginfo('url')?>" readonly="readonly" />
			
		

		 <br /><br />
		<!--hidden invox userid text filed-->
		<input type="hidden" class="invinput" id = "invoxuserid" name="invoxuserid" value="<?php echo $invoxuserid?>" />
		
		<input type="text" class="invinput" id = "invoxsharedextension" name="invoxsharedextension" value="<?php echo $invoxsharedextension?>" />
		<!--hidden task filed-->
		<input type="hidden" class="invinput" name="task" value="add_submit" />
		<p class="submit">
			<input type="button" id='save' value="Save Changes" class="button-primary" style="margin:-30px 0px 0px 0px;"/><span id="voicelo_loading" style="display:none;margin-left:10px;"><img src='http://cdn-site.invox.com/images/wp_voicelo_loader.gif' style="vertical-align:middle;padding-bottom:5px;"/><b style="padding:5px;">Please wait...</b></span>
		</p>	
		
		<div id="error_mssg" style="display:hidden">	
		<font size="3" color="red"></font><br/>
		</div>

	</form>
	
	<br/>
	
	<p>The following is the <b>demonstration</b> - this will call our business in Sunnyvale, CA, US.</p>
	
	<!-- Add this span tag inside body -->
	<span class = 'socialinvox'></span>
	<!-- End of span tag -->
	
	<!-- Start of InVox.com Widget -->
	<!-- Add this script code before end of the body tag. -->
	<script  type="text/javascript">
	var _inv = {};_inv.invoxid = '9fc572e7-6adf-8648-cfd1-1878fe731af8';_inv.label = 'Call';_inv.side='normal';_inv.color='#F88017';_inv.position = '45%';var invoxPath = (('https:' == document.location.protocol) ? 'https://d2i4snaizu6ojm.cloudfront.net/live-call/' : 'http://cdn-social.invox.com/live-call/');var invoxProtocol = (('https:' == document.location.protocol) ? 'https://' : 'http://');
	var invox = document.createElement('script'); invox.type = 'text/javascript'; invox.async = true;invox.src = invoxPath + 'widget/invox.js';var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(invox, s);
	</script>
	<!-- End of InVox.com Widget -->
	
	
	<br/><br/>
	<p>If you need a full fledged phone system for your business to sound professional, please visit <a href='http://www.pbxplus.com'>PBX+</a>. <p>
	<br/>
	<b>What is PBX+</b>
	<p>PBX+ is a next-generation phone system that works with your current setup. There is no software or hardware to install or buy. PBX+ answers calls and greets your callers by your customized business greeting - "Thanks for calling xyz corp. Please enter the extension you are trying to reach - Press 1 for sales, 2 for support, 3 for John.. .". Then PBX+ routes the calls based on caller's preference for extension. Know your caller plugin a feature of PBX+ enables you to know the callers, when was the last time they called and you can leave notes on their calls. Also you can pull in caller data from CRMs such as Salesforce, ZOHO and Sugar.</p>
	
	
	<?php

	if($invoxuserid != '')
	{
	?>
	
	
	
	
	<?php
	}
	
	?>

	
	
	</div>
	</div>
</div>		
	<?php				
}
 
?>