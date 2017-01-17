<?php
/*
Plugin Name: Share-In
Plugin URI: https://github.com/nauvalazhar/share-in
Description: Share-in is a plugin to display social media sharing buttons on a single post page and this is still a beta version, this plugin is bad? Your one thought with me.
Version: 1.0 (beta)
Author: Muhamad Nauval Azhar
Author URI: https://kodinger.com
License: GPLv3 or later
Text Domain: share-in
*/

$plugin_dir = plugin_dir_url(__FILE__);
$share_in_data = array(
	'description'=> 'Share-in is a plugin to display social media sharing buttons on a single post page and this is still a beta version, this plugin is bad? Your one thought with me.');

$share_in_default = array(
	'facebook' => 1,
	'twitter' => 1,
	'google_plus' => 1,
	'pinterest' => 1,
	'linkedin' => 1,
	'button_text' => 'Share',
	'button_icon' => 'fa fa-share-alt',
	'button_color' => '#fff',
	'button_bg' => '#333',
	'button_position' => 'fixed',
	'button_left_right' => 'left',
	'button_left_right_px' => '120',
	'button_offset_top' => '10',
	'button_display_scroll' => 0,
	'button_scroll_offset' => 0,
	'fa_load_theme' => 0,
	'fa_load_cdn' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css');

$share_in = array(
		array(
			'id' => 'facebook',
			'title' => 'Facebook',
			'url' => 'https://www.facebook.com/sharer.php?u=%1$s',
			'icon' => 'fa fa-facebook'
		),
		array(
			'id' => 'twitter',
			'title' => 'Twitter',
			'url' => 'https://twitter.com/share?url=%1$s&text=%2$s',
			'icon' => 'fa fa-twitter'
		),
		array(
			'id' => 'google_plus',
			'title' => 'Google+',
			'url' => 'https://plus.google.com/share?url=%1$s',
			'icon' => 'fa fa-google-plus'
		),
		array(
			'id' => 'pinterest',
			'title' => 'Pinterest',
			'url' => 'https://pinterest.com/pin/create/button/?url=%1$s',
			'icon' => 'fa fa-pinterest'
		),
		array(
			'id' => 'linkedin',
			'title' => 'linkedin',
			'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=%1$S&title=%2$s',
			'icon' => 'fa fa-linkedin'
		)
	);


add_filter('the_content', 'share_in');

function share_in($content) {
	global $share_in;
	global $share_in_default;
	$content .= '<div class="share-in '.((get_option('share_in_button')['left_right']) ? get_option('share_in_button')['left_right'] : $share_in_button['left_right']).'" style="position: '.((get_option('share_in_button')['position']) ? get_option('share_in_button')['position'] : $share_in_default['position']).'; '.((get_option('share_in_button')['left_right']) ? get_option('share_in_button')['left_right'] : $share_in_default['button_left_right']).': '.((get_option('share_in_button')['left_right_px']) ? get_option('share_in_button')['left_right_px'] : $share_in_default['left_right_px']).'px; top: '.((get_option('share_in_button')['top_px']) ? get_option('share_in_button')['top_px'] : $share_in_default['button_offset_top']).'px; '.((get_option('share_in_button')['display_scroll']) ? 'display:none' : '').';">';
	$content .= '<div class="share-in-group">';
	$content .= '<a href="#" class="share-in-toggle" style="background-color: '.((get_option('share_in_button')['bg']) ? get_option('share_in_button')['bg'] : $share_in_default['button_bg']).'; color: '.((get_option('share_in_button')['color']) ? get_option('share_in_button')['color'] : $share_in_default['button_color']).'"><i class="fa fa-share-alt fa-fw"></i> '.((get_option('share_in_button')['text']) ? get_option('share_in_button')['text'] : $share_in_default['button_text']).'</a>';
	$content .= '<ul class="share-in-ul">';
	foreach($share_in as $row => $val) {
		if(get_option('share_in_status')[$val['id']] == 1) {
		$content .= '<li class="'.$val['id'].'"><a href="'.sprintf($val['url'], urlencode(get_the_permalink()), (get_the_title())).'" title="'.$val['title'].'"><i class="'.$val['icon'].'"></i></a></li>';			
		}
	}
	$content .= '</ul>';
	$content .= '</div>';
	$content .= '</div>';
	return $content;
}

if(get_option('share_in_button')['display_scroll']) {
function share_in_display_scroll() {
	echo '
	<script>
	$(function(){
		$(window).on("scroll", function(){
			if($(this).scrollTop() > '.get_option('share_in_button')['offset_top_scroll'].') {
				$(".share-in").fadeIn();
			}else{
				$(".share-in").fadeOut();
			}
		});
	});
	</script>
	';
}
add_action('wp_head', 'share_in_display_scroll');
}

wp_enqueue_style( 'share-in-css', $plugin_dir . 'share-in.css', array(), '1.0' );
wp_enqueue_script( 'share-in-js', $plugin_dir . 'share-in.js', array('jquery'), '1.0' );

if(!get_option('share_in_fa')['load']) {
	wp_enqueue_style( 'font-awesome-css', get_option('share_in_fa')['cdn'], array(), '1.0' );
}

add_action('admin_menu', 'share_in_menu');
function share_in_menu() {
	add_menu_page('Share-In', 'Share-In', 'administrator', __FILE__, 'share_in_settings_page' , plugins_url('/icon.png', __FILE__) );

	add_action( 'admin_init', 'share_in_settings' );
}

function share_in_setting_button($links) { 
  $settings_link = '<a href="admin.php?page=share-in/share-in.php" class="badge">Settings</a>'; 
  $settings_link .= ' | <a href="plugin-editor.php?file=share-in/share-in.php">Edit</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'share_in_setting_button' );

function share_in_settings() {
	register_setting( 'share-in-settings-group', 'share_in_status' );
	register_setting( 'share-in-settings-group', 'share_in_button' );
	register_setting( 'share-in-settings-group', 'share_in_fa' );
	if($_POST['submit']) {
	echo '<div class="updated notice is-dismissable"><p>Share-in has been updated, awesome!</p>
	<button type="button" class="notice-dismiss">
		<span class="screen-reader-text">Dismiss this notice.</span>
	</button>
	</div>';
}
}

function share_in_settings_page() {
	global $share_in_default;
	global $share_in_data;
	if(get_option('share_in_status')['facebook'] == 1) {
		$share_in_status['facebook'] = 'checked';
	}else{
		$share_in_status['facebook'] = '';
	}

	if(get_option('share_in_status')['twitter'] == 1) {
		$share_in_status['twitter'] = 'checked';
	}else{
		$share_in_status['twitter'] = '';
	}

	if(get_option('share_in_status')['google_plus'] == 1) {
		$share_in_status['google_plus'] = 'checked';
	}else{
		$share_in_status['google_plus'] = '';
	}

	if(get_option('share_in_status')['pinterest'] == 1) {
		$share_in_status['pinterest'] = 'checked';
	}else{
		$share_in_status['pinterest'] = '';
	}

	if(get_option('share_in_status')['linkedin'] == 1) {
		$share_in_status['linkedin'] = 'checked';
	}else{
		$share_in_status['linkedin'] = '';
	}

	if(!get_option('share_in_button')['text']) {
		$share_in_button['text'] = $share_in_default['button_text'];
	}else{
		$share_in_button['text'] = get_option('share_in_button')['text'];
	}

	if(!get_option('share_in_button')['icon']) {
		$share_in_button['icon'] = $share_in_default['button_icon'];
	}else{
		$share_in_button['icon'] = get_option('share_in_button')['icon'];
	}

	if(!get_option('share_in_button')['color']) {
		$share_in_button['color'] = $share_in_default['button_color'];
	}else{
		$share_in_button['color'] = get_option('share_in_button')['color'];
	}

	if(!get_option('share_in_button')['bg']) {
		$share_in_button['bg'] = $share_in_default['button_bg'];
	}else{
		$share_in_button['bg'] = get_option('share_in_button')['bg'];
	}
	
	if(!get_option('share_in_button')['position']) {
		$share_in_button['position'] = $share_in_default['button_position'];
	}else{
		$share_in_button['position'] = get_option('share_in_button')['position'];
	}	

	if(!get_option('share_in_button')['left_right']) {
		$share_in_button['left_right'] = $share_in_default['button_left_right'];
	}else{
		$share_in_button['left_right'] = get_option('share_in_button')['left_right'];
	}	

	if(!get_option('share_in_button')['left_right_px']) {
		$share_in_button['left_right_px'] = $share_in_default['button_left_right_px'];
	}else{
		$share_in_button['left_right_px'] = get_option('share_in_button')['left_right_px'];
	}

	if(!get_option('share_in_button')['top_px']) {
		$share_in_button['top_px'] = $share_in_default['button_top_px'];
	}else{
		$share_in_button['top_px'] = get_option('share_in_button')['top_px'];
	}	

	if(get_option('share_in_button')['display_scroll']) {
		$share_in_button['display_scroll'] = 'checked';
	}else{
		$share_in_button['display_scroll'] = '';
	}	

	if(!get_option('share_in_button')['offset_top_scroll']) {
		$share_in_button['scroll_offset'] = $share_in_default['button_scroll_offset'];
	}else{
		$share_in_button['scroll_offset'] = get_option('share_in_button')['offset_top_scroll'];
	}	

	if(get_option('share_in_fa')['load'] == 1) {
		$share_in_fa['load'] = 'checked';
	}else{
		$share_in_fa['load'] = '';
	}

	if(!get_option('share_in_fa')['cdn']) {
		$share_in_fa['cdn'] = $share_in_default['fa_load_cdn'];
	}else{
		$share_in_fa['cdn'] = get_option('share_in_fa')['cdn'];
	}	
?>
<div class="wrap">
<h2><img style="margin-bottom: -7px;" src="<?=plugins_url('/share-in.png', __FILE__);?>" width="30"> Share-In Settings</h2>
<p><?=$share_in_data['description'];?></p>
<form method="post" action="options.php">
<?php settings_fields( 'share-in-settings-group' ); ?>
<?php do_settings_sections( 'share-in-settings-group' ); ?>
<?php settings_errors(); ?>
<table>
	<tr>
		<td width="150" valign="top">
			<h3>Share Button</h3>
			<label><input type="checkbox" name="share_in_status[facebook]" value="1" <?=$share_in_status['facebook']?>>Facebook</label><br>
			<label><input type="checkbox" name="share_in_status[twitter]" value="1" <?=$share_in_status['twitter']?>>Twitter</label><br>
			<label><input type="checkbox" name="share_in_status[google_plus]" value="1" <?=$share_in_status['google_plus']?>>Google+</label><br>
			<label><input type="checkbox" name="share_in_status[pinterest]" value="1" <?=$share_in_status['pinterest']?>>Pinterest</label><br>
			<label><input type="checkbox" name="share_in_status[linkedin]" value="1" <?=$share_in_status['linkedin']?>>Linkedin</label>
		</td>
		<td valign="top" width="280">
			<h3>Toggle Button</h3>
			<label for="button-text">Button Text</label><br>
			<input type="text" name="share_in_button[text]" id="button-text" class="regular-text" value="<?=$share_in_button['text'];?>" style="width:250px"><br><br>
			<label for="button-icon">Button Icon</label><br>
			<input type="text" name="share_in_button[icon]" id="button-icon" class="regular-text" value="<?=$share_in_button['icon'];?>" style="width:250px"><br><a href="http://fontawesome.io/icons" target="_blank">Click here</a> for more icons<br><br>
			<label for="button-color">Button Color</label><br>
			<input type="text" name="share_in_button[color]" id="button-color" class="regular-text" value="<?=$share_in_button['color'];?>" style="width:250px">
			<br><br>
			<label for="button-bg">Button Background</label><br>
			<input type="text" name="share_in_button[bg]" id="button-bg" class="regular-text" value="<?=$share_in_button['bg'];?>" style="width:250px">
		</td>
		<td valign="top" width="220">
			<h3>Toggle Position</h3>
			<label>Position</label><br>
			<select name="share_in_button[position]" class="regular-text" style="width:170px">
				<option value="fixed" <?=(($share_in_button['position'] =='fixed') ? 'selected' : '')?>>Fixed</option>
				<option value="absolute" <?=(($share_in_button['position'] =='absolute') ? 'selected' : '')?>>Absolute</option>
			</select><br><br>

			<label>Left/Right</label><br>
			<select name="share_in_button[left_right]" class="regular-text" style="width:100px">
				<option value="left" <?=(($share_in_button['left_right'] =='left') ? 'selected' : '')?>>Left</option>
				<option value="right" <?=(($share_in_button['left_right'] =='right') ? 'selected' : '')?>>Right</option>
			</select>
			<input type="text" name="share_in_button[left_right_px]" value="<?=$share_in_button['left_right_px'];?>" class="regular-text" style="width:50px" placeholder="0">px<br><br>

			<label>Offset Top</label><br>
			<input type="text" name="share_in_button[top_px]" class="regular-text" value="<?=$share_in_button['top_px'];?>" style="width:50px" placeholder="0">px
		</td>
		<td valign="top">
		<h3>Show/Hide Toggle</h3>
		<label><input type="checkbox" name="share_in_button[display_scroll]" value="1" <?=$share_in_button['display_scroll'];?>>Show toggle when scroll</label>
		<br><br>
		<label>Offset Top</label><br>
		<input type="text" name="share_in_button[offset_top_scroll]" class="regular-text" value="<?=$share_in_button['scroll_offset'];?>" style="width:50px" placeholder="0">px<br><br>
		<h3>Font Awesome</h3>
		<label><input type="checkbox" name="share_in_fa[load]" value="1" <?=$share_in_fa['load'];?>> Load from my themes</label><br><br>
		<label>Load from CDN</label><br>
		<input type="text" name="share_in_fa[cdn]" class="regular-text" style="width:250px" value="<?=$share_in_fa['cdn'];?>">
		</td>
	</tr>
</table>
<?php submit_button(); ?>
<h2>Need help? <a href="https://kodinger.com/?utm_source=<?=bloginfo('title');?>&amp;utm_medium=share-in&amp;http=<?=home_url();?>" target="_blank">visit us</a>.</h2>
</form>
</div>
<?php } ?>