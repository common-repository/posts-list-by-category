<?php
/*
 * Plugin Name:   Posts list by Category
 * Version:       1.0
 * Plugin URI:    http://wordpress.org/extend/plugins/posts-list-by-category/
 * Description:   This plugin gives you the flexibility to display all your wordpress posts in a categorically manner under a single post or a page.From the plugin configuration page you can also list your category and posts as per the order you want. Adjust your settings <a href="options-general.php?page=posts-list-by-category/posts-list-by-category.php">here</a>.
 * Author:        MaxBlogPress
 * Author URI:    http://www.maxblogpress.com
 *
 * License:       GNU General Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * Copyright (C) 2007 www.maxblogpress.com
 *
 */
$mbpplbc_path      = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', __FILE__);
$mbpplbc_path      = str_replace('\\','/',$mbpplbc_path);
$mbpplbc_dir       = substr($mban_path,0,strrpos($mbpplbc_path,'/'));
$mbpplbc_siteurl   = get_bloginfo('wpurl');
$mbpplbc_siteurl   = (strpos($mbpplbc_siteurl,'http://') === false) ? get_bloginfo('siteurl') : $mbpplbc_siteurl;
$mbpplbc_fullpath  = $mbpplbc_siteurl.'/wp-content/plugins/'.$mbpplbc_dir.'';
$mbpplbc_fullpath  = $mbpio_fullpath.'posts-list-by-category/';
$mbpplbc_abspath   = str_replace("\\","/",ABSPATH); 

define('MBP_PLBC_ABSPATH', $mbpplbc_path);
define('MBP_PLBC_LIBPATH', $mbpplbc_fullpath);
define('MBP_PLBC_SITEURL', $mbpplbc_siteurl);
define('MBP_PLBC_NAME', 'Posts list by Category');
define('MBP_PLBC_VERSION', '1.0');  
define('MBP_PBC_LIBPATH', $mbpplbc_fullpath);

add_option('mbpplbc_header', '<h2>Posts list by Category</h2>');
add_filter('the_content', 'mbpplbc_generate');
add_action('admin_menu', 'mbpplbc_add_option_pages'); 
	
function mbpplbc_add_option_pages() {
	if (function_exists('add_options_page')) {
		add_options_page('Posts list by Category', 'Posts list by Category', 8, __FILE__, 'mbpplbc_options_page');
	}		
}

function mbpplbc_options_page() {

	$mbp_plbc_activate = get_option('mbp_plbc_activate');
	$reg_msg = '';
	$mbp_plbc_msg = '';
	$form_1 = 'mbp_plbc_reg_form_1';
	$form_2 = 'mbp_plbc_reg_form_2';
		// Activate the plugin if email already on list
	if ( trim($_GET['mbp_onlist']) == 1 ) {
		$mbp_plbc_activate = 2;
		update_option('mbp_plbc_activate', $mbp_plbc_activate);
		$reg_msg = 'Thank you for registering the plugin. It has been activated'; 
	} 
	// If registration form is successfully submitted
	if ( ((trim($_GET['submit']) != '' && trim($_GET['from']) != '') || trim($_GET['submit_again']) != '') && $mbp_plbc_activate != 2 ) { 
		update_option('mbp_plbc_name', $_GET['name']);
		update_option('mbp_plbc_email', $_GET['from']);
		$mbp_plbc_activate = 1;
		update_option('mbp_plbc_activate', $mbp_plbc_activate);
	}
	if ( intval($mbp_plbc_activate) == 0 ) { // First step of plugin registration
		global $userdata;
		mbp_plbcRegisterStep1($form_1,$userdata);
	} else if ( intval($mbp_plbc_activate) == 1 ) { // Second step of plugin registration
		$name  = get_option('mbp_plbc_name');
		$email = get_option('mbp_plbc_email');
		mbp_plbcRegisterStep2($form_2,$name,$email);
	} else if ( intval($mbp_plbc_activate) == 2 ) { // Options page
		if ( trim($reg_msg) != '' ) {
			echo '<div id="message" class="updated fade"><p><strong>'.$reg_msg.'</strong></p></div>';
		}






	if (isset($_POST['info_update'])) {

		?>
<div id="message" class="updated fade"><p><strong><?php 

		update_option('cat_order', (string) $_POST["cat_order"]);
		update_option('post_order', (string) $_POST["post_order"]);
		update_option('mbpplbc_header', (string) $_POST["mbpplbc_header"]);
		echo "Configuration Updated!";

	    ?></strong></p></div><?php

	} ?>

	<div class=wrap>

	<h2><?php echo MBP_PLBC_NAME.' '.MBP_PLBC_VERSION; ?></h2><br/>
<strong><img src="<?php echo MBP_AIT_LIBPATH;?>image/how.gif" border="0" align="absmiddle" /> <a href="http://wordpress.org/extend/plugins/posts-list-by-category/other_notes/" target="_blank">How to use it</a>&nbsp;&nbsp;&nbsp;
		<img src="<?php echo MBP_AIT_LIBPATH;?>image/comment.gif" border="0" align="absmiddle" /> <a href="http://www.maxblogpress.com/forum/forumdisplay.php?f=30" target="_blank">Community</a></strong>
<br/><br/>			

	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="info_update" id="info_update" value="true" />


	<fieldset class="options"> 
	  <legend>Options</legend>
	  <table width="100%" border="0" cellspacing="0" cellpadding="6">

	  <tr valign="top">
	    <td width="35%" align="right">
		  Header title </td>
	    <td align="left">
		  <input name="mbpplbc_header" type="text" size="50" value="<?php echo get_option('mbpplbc_header') ?>"/>
	  </td></tr>

<?php 
global $wp_version;
if ($wp_version > '2.2') { ?>	  
	  <tr valign="top">
	    <td align="right">Order Catgory name by </td>
	    <td align="left">
			<select name="cat_order">
				<option <?php if (get_option('cat_order') == 'ASC'){ echo 'selected'; }?> value="ASC">ASC</option>
				<option <?php if (get_option('cat_order') == 'DESC') { echo 'selected'; }?> value="DESC">DESC</option>
			</select>
		</td>
	    </tr>
	  <tr valign="top">
	    <td align="right">Order Posts by </td>
	    <td align="left">
			<select name="post_order">
				<option <?php if (get_option('post_order') == 'ASC') { echo 'selected'; }?> value="ASC">ASC</option>
				<option <?php if (get_option('post_order') == 'DESC') { echo 'selected'; }?> value="DESC">DESC</option>
			</select>		
		</td>
	    </tr>
<?php } ?>
	  </table>
	</fieldset>

	<div class="submit">
		<input type="submit" name="info_update" value="<?php _e('Update options'); ?> &raquo;" />
	</div>
	</form>
	
<div align="center" style="background-color:#f1f1f1; padding:5px 0px 5px 0px" >
<p align="center"><strong><?php echo MBP_PLBC_NAME.' '.MBP_PLBC_VERSION; ?> by <a href="http://www.maxblogpress.com" target="_blank">MaxBlogPress</a></strong></p>
<p align="center">This plugin is the result of <a href="http://www.maxblogpress.com/blog/219/maxblogpress-revived/" target="_blank">MaxBlogPress Revived</a> project.</p>
</div>	
	
	</div>
    <?php
	}
}


function posts_by_category() {

	global $wpdb, $post, $wp_version;
	
	$tp = $wpdb->prefix;

	$mbpplbc_header = get_option('mbpplbc_header');
	
	if ($wp_version < 2.3) {
		// for 2.2 and less compatibility
		$sort_code = 'ORDER BY cat_name ASC, post_date DESC';
		$the_output = NULL;
	
	
		$last_posts = (array)$wpdb->get_results("
			SELECT post_date, ID, post_title, cat_name, cat_ID
			FROM {$tp}posts, {$tp}post2cat, {$tp}categories 
			WHERE {$tp}posts.ID = {$tp}post2cat.post_id 
			AND {$tp}categories.cat_ID = {$tp}post2cat.category_id
			AND post_status = 'publish' 
			AND post_type != 'page' 
			AND post_date < NOW() 
			{$hide_check} 
			{$sort_code}
		");
	
		if (empty($last_posts)) {
			return NULL;
		}
	
		$the_output .= stripslashes($ddle_header); 
	
		$used_cats = array();;
		$i = 0;
		foreach ($last_posts as $posts) {
			if (in_array($posts->cat_name, $used_cats)) {
				unset($last_posts[$i]);
			} else {
				$used_cats[] = $posts->cat_name;
			}
			$i++;
		}
		$last_posts = array_values($last_posts);
	
		$the_output .= '<ul>';
		foreach ($last_posts as $posts) {
	
		  $the_output .= '<li><strong><a href="' . get_category_link($posts->cat_ID) . '">' . apply_filters('list_cats', $posts->cat_name, $posts) . '</a></strong></li>';
	
		$where = apply_filters('getarchives_where', "WHERE post_type = 'post' AND post_status = 'publish' AND post_category=5" , $r );
		
		$arcresults = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND ID IN (Select post_id FROM $wpdb->post2cat WHERE category_id =$posts->cat_ID) ORDER BY post_date DESC");
			
			foreach ( $arcresults as $arcresult ) {
			$the_output .= '<li><a href="' . get_permalink($arcresult->ID) . '">' . apply_filters('the_title', $arcresult->post_title) . '</a></li>';
			}
		
		$the_output .= '';
		}
		$the_output .= '</ul>';
	} else {
			//codes for 2.2+ compatible
			$the_output = '';
			$the_output.= get_option('mbpplbc_header') . '<br/>';
			$cat_order_by = (get_option('cat_order') == 'DESC')? ' ORDER BY name DESC' : ' ORDER BY name ASC';
			
			$query_cat = "SELECT
								a.name,
								b.term_taxonomy_id
						FROM 
							wp_terms a
						INNER JOIN wp_term_taxonomy b ON(a.term_id=b.term_id)
						WHERE
							b.taxonomy='category'";
			$query_cat.= $cat_order_by;
										
			$sql_cat = mysql_query($query_cat);
			while($rs_cat = mysql_fetch_array($sql_cat)) {
				$the_output.=  '<div>' . '<a href=' . get_category_link($rs_cat['term_taxonomy_id']) . '>' . '<strong>' . $rs_cat['name'] . '</strong>' . '</a>';
			
			$post_order_by = (get_option('post_order') == 'DESC')? ' ORDER BY post_date DESC' : ' ORDER BY post_date ASC';			
			$query_post = "SELECT 
								a.ID,
								a.post_title
						   FROM
								wp_posts a
						   INNER JOIN wp_term_relationships b ON(a.ID = b.object_id)
						   INNER JOIN wp_term_taxonomy c ON(b.term_taxonomy_id = c.term_taxonomy_id)
						   WHERE
								a.post_status='publish'
								AND a.post_type='post'
								AND c.term_taxonomy_id='" .$rs_cat['term_taxonomy_id'] . "'";
			$query_post.= $post_order_by;
			$sql_post  = mysql_query($query_post);
			if (mysql_num_rows($sql_post) > 0) {
				$the_output.= '<ul>';
				while($rs_post = mysql_fetch_array($sql_post)) {
					$the_output.= '<li>' . '<a href=' . get_permalink($rs_post['ID']) . '>' . $rs_post['post_title'] .  '</a>' . '</li>';
				}
				$the_output.= '</ul>';	
			}
			$the_output.= '</div>';										
		}				
	}
	return $the_output;
}


function mbpplbc_generate($content) {
	$content = str_replace("<!-- postslistbycategory -->", posts_by_category(), $content);
	return $content;
}


// Srart Registration.

/**
 * Plugin registration form
 */
function mbp_plbcRegistrationForm($form_name, $submit_btn_txt='Register', $name, $email, $hide=0, $submit_again='') {
	$wp_url = get_bloginfo('wpurl');
	$wp_url = (strpos($wp_url,'http://') === false) ? get_bloginfo('siteurl') : $wp_url;
	$plugin_pg    = 'options-general.php';
	$thankyou_url = $wp_url.'/wp-admin/'.$plugin_pg.'?page='.$_GET['page'];
	$onlist_url   = $wp_url.'/wp-admin/'.$plugin_pg.'?page='.$_GET['page'].'&amp;mbp_onlist=1';
	if ( $hide == 1 ) $align_tbl = 'left';
	else $align_tbl = 'center';
	?>
	
	<?php if ( $submit_again != 1 ) { ?>
	<script><!--
	function trim(str){
		var n = str;
		while ( n.length>0 && n.charAt(0)==' ' ) 
			n = n.substring(1,n.length);
		while( n.length>0 && n.charAt(n.length-1)==' ' )	
			n = n.substring(0,n.length-1);
		return n;
	}
	function mbp_plbcValidateForm_0() {
		var name = document.<?php echo $form_name;?>.name;
		var email = document.<?php echo $form_name;?>.from;
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var err = ''
		if ( trim(name.value) == '' )
			err += '- Name Required\n';
		if ( reg.test(email.value) == false )
			err += '- Valid Email Required\n';
		if ( err != '' ) {
			alert(err);
			return false;
		}
		return true;
	}
	//-->
	</script>
	<?php } ?>
	<table align="<?php echo $align_tbl;?>">
	<form name="<?php echo $form_name;?>" method="post" action="http://www.aweber.com/scripts/addlead.pl" <?php if($submit_again!=1){;?>onsubmit="return mbp_plbcValidateForm_0()"<?php }?>>
	 <input type="hidden" name="unit" value="maxbp-activate">
	 <input type="hidden" name="redirect" value="<?php echo $thankyou_url;?>">
	 <input type="hidden" name="meta_redirect_onlist" value="<?php echo $onlist_url;?>">
	 <input type="hidden" name="meta_adtracking" value="mr-posts-list-by-category">
	 <input type="hidden" name="meta_message" value="1">
	 <input type="hidden" name="meta_required" value="from,name">
	 <input type="hidden" name="meta_forward_vars" value="1">	
	 <?php if ( $submit_again == 1 ) { ?> 	
	 <input type="hidden" name="submit_again" value="1">
	 <?php } ?>		 
	 <?php if ( $hide == 1 ) { ?> 
	 <input type="hidden" name="name" value="<?php echo $name;?>">
	 <input type="hidden" name="from" value="<?php echo $email;?>">
	 <?php } else { ?>
	 <tr><td>Name: </td><td><input type="text" name="name" value="<?php echo $name;?>" size="25" maxlength="150" /></td></tr>
	 <tr><td>Email: </td><td><input type="text" name="from" value="<?php echo $email;?>" size="25" maxlength="150" /></td></tr>
	 <?php } ?>
	 <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="<?php echo $submit_btn_txt;?>" class="button" /></td></tr>
	 </form>
	</table>
	<?php
}

/**
 * Register Plugin - Step 2
 */
function mbp_plbcRegisterStep2($form_name='frm2',$name,$email) {
	$msg = 'You have not clicked on the confirmation link yet. A confirmation email has been sent to you again. Please check your email and click on the confirmation link to activate the plugin.';
	if ( trim($_GET['submit_again']) != '' && $msg != '' ) {
		echo '<div id="message" class="updated fade"><p><strong>'.$msg.'</strong></p></div>';
	}
	?>
	<style type="text/css">
	table, tbody, tfoot, thead {
		padding: 8px;
	}
	tr, th, td {
		padding: 0 8px 0 8px;
	}
	</style>
	<div class="wrap"><h2> <?php echo MBP_PLBC_NAME.' '.MBP_PLBC_VERSION; ?></h2>
	 <center>
	 <table width="100%" cellpadding="3" cellspacing="1" style="border:1px solid #e3e3e3; padding: 8px; background-color:#f1f1f1;">
	 <tr><td align="center">
	 <table width="650" cellpadding="5" cellspacing="1" style="border:1px solid #e9e9e9; padding: 8px; background-color:#ffffff; text-align:left;">
	  <tr><td align="center"><h3>Almost Done....</h3></td></tr>
	  <tr><td><h3>Step 1:</h3></td></tr>
	  <tr><td>A confirmation email has been sent to your email "<?php echo $email;?>". You must click on the link inside the email to activate the plugin.</td></tr>
	  <tr><td><strong>The confirmation email will look like:</strong><br /><img src="http://www.maxblogpress.com/images/activate-plugin-email.jpg" vspace="4" border="0" /></td></tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr><td><h3>Step 2:</h3></td></tr>
	  <tr><td>Click on the button below to Verify and Activate the plugin.</td></tr>
	  <tr><td><?php mbp_plbcRegistrationForm($form_name.'_0','Verify and Activate',$name,$email,$hide=1,$submit_again=1);?></td></tr>
	 </table>
	 </td></tr></table><br />
	 <table width="100%" cellpadding="3" cellspacing="1" style="border:1px solid #e3e3e3; padding:8px; background-color:#f1f1f1;">
	 <tr><td align="center">
	 <table width="650" cellpadding="5" cellspacing="1" style="border:1px solid #e9e9e9; padding:8px; background-color:#ffffff; text-align:left;">
	   <tr><td><h3>Troubleshooting</h3></td></tr>
	   <tr><td><strong>The confirmation email is not there in my inbox!</strong></td></tr>
	   <tr><td>Dont panic! CHECK THE JUNK, spam or bulk folder of your email.</td></tr>
	   <tr><td>&nbsp;</td></tr>
	   <tr><td><strong>It's not there in the junk folder either.</strong></td></tr>
	   <tr><td>Sometimes the confirmation email takes time to arrive. Please be patient. WAIT FOR 6 HOURS AT MOST. The confirmation email should be there by then.</td></tr>
	   <tr><td>&nbsp;</td></tr>
	   <tr><td><strong>6 hours and yet no sign of a confirmation email!</strong></td></tr>
	   <tr><td>Please register again from below:</td></tr>
	   <tr><td><?php mbp_plbcRegistrationForm($form_name,'Register Again',$name,$email,$hide=0,$submit_again=2);?></td></tr>
	   <tr><td><strong>Help! Still no confirmation email and I have already registered twice</strong></td></tr>
	   <tr><td>Okay, please register again from the form above using a DIFFERENT EMAIL ADDRESS this time.</td></tr>
	   <tr><td>&nbsp;</td></tr>
	   <tr>
		 <td><strong>Why am I receiving an error similar to the one shown below?</strong><br />
			 <img src="http://www.maxblogpress.com/images/no-verification-error.jpg" border="0" vspace="8" /><br />
		   You get that kind of error when you click on &quot;Verify and Activate&quot; button or try to register again.<br />
		   <br />
		   This error means that you have already subscribed but have not yet clicked on the link inside confirmation email. In order to  avoid any spam complain we don't send repeated confirmation emails. If you have not recieved the confirmation email then you need to wait for 12 hours at least before requesting another confirmation email. </td>
	   </tr>
	   <tr><td>&nbsp;</td></tr>
	   <tr><td><strong>But I've still got problems.</strong></td></tr>
	   <tr><td>Stay calm. <strong><a href="http://www.maxblogpress.com/contact-us/" target="_blank">Contact us</a></strong> about it and we will get to you ASAP.</td></tr>
	 </table>
	 </td></tr></table>
	 </center>		
	<p style="text-align:center;margin-top:3em;"><strong><?php echo MBP_PLBC_NAME.' '.MBP_PLBC_VERSION; ?> by <a href="http://www.maxblogpress.com/" target="_blank" >MaxBlogPress</a></strong></p>
	</div>
	<?php
}

/**
 * Register Plugin - Step 1
 */
function mbp_plbcRegisterStep1($form_name='frm1',$userdata) {
	$name  = trim($userdata->first_name.' '.$userdata->last_name);
	$email = trim($userdata->user_email);
	?>
	<style type="text/css">
	tabled , tbody, tfoot, thead {
		padding: 8px;
	}
	tr, th, td {
		padding: 0 8px 0 8px;
	}
	</style>
	<div class="wrap"><h2> <?php echo MBP_PLBC_NAME.' '.MBP_PLBC_VERSION; ?></h2>
	 <center>
	 <table width="100%" cellpadding="3" cellspacing="1" style="border:2px solid #e3e3e3; padding: 8px; background-color:#f1f1f1;">
	  <tr><td align="center">
		<table width="548" align="center" cellpadding="3" cellspacing="1" style="border:1px solid #e9e9e9; padding: 8px; background-color:#ffffff;">
		  <tr><td align="center"><h3>Please register the plugin to activate it. (Registration is free)</h3></td></tr>
		  <tr><td align="left">In addition you'll receive complimentary subscription to MaxBlogPress Newsletter which will give you many tips and tricks to attract lots of visitors to your blog.</td></tr>
		  <tr><td align="center"><strong>Fill the form below to register the plugin:</strong></td></tr>
		  <tr><td align="center"><?php mbp_plbcRegistrationForm($form_name,'Register',$name,$email);?></td></tr>
		  <tr><td align="center"><font size="1">[ Your contact information will be handled with the strictest confidence <br />and will never be sold or shared with third parties ]</font></td></tr>
		</table>
	  </td></tr></table>
	 </center>
	<p style="text-align:center;margin-top:3em;"><strong><?php echo MBP_PLBC_NAME.' '.MBP_PLBC_VERSION; ?> by <a href="http://www.maxblogpress.com/" target="_blank" >MaxBlogPress</a></strong></p>
	</div>
	<?php
}
?>
