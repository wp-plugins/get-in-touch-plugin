<?php
/*
Plugin Name: Get-in-touch Widget
Plugin URI: 
Description: Adds a flipping widget in the sidebar. When clicked the box rotates and reveals configurable contact details (such as phone number).
Additionally it opens a feedback form where users can enter their name, email and message.
Author: Milena Dimitrova
Version: 0.5
Author URI: http://www.flatrocktech.com
*/



// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_get_in_touch_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_get_in_touch($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);
				
		$widget = '';
		$siteUrl = get_bloginfo('siteurl');
		$url = $siteUrl.'/wp-content/plugins/get-in-touch/send.php';
		$contactableDir = $siteUrl.'/wp-content/plugins/get-in-touch/assets';

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_get_in_touch');
		$name_label = $options['name_label'];
		$email_label = $options['email_label'];
		
		$widget.=' 
		<!--start get-in-touch script -->		
		<div id="contactable"><!-- form placeholder --></div>
		<script type="text/javascript" src="'.$contactableDir.'/jquery1.6.1.js"></script>
		<script type="text/javascript" src="'.$contactableDir.'/jquery-ui-1.8.2.custom.min.js"></script>
		<script type="text/javascript" src="'.$contactableDir.'/jquery.validate.pack.js"></script>
		<script type="text/javascript" src="'.$contactableDir.'/jquery.contactable.js"></script>
		<script type="text/javascript" src="'.$contactableDir.'/jquery.flip.js"></script>		
		<link rel="stylesheet" href="'.$contactableDir.'/get-in-touch.css" type="text/css" />
		<style type="text/css">
			#contactable form#contactForm .submit {
				background-color: '. $options['button_color'].';
			}			
			#contactable #contactForm {
				background-color: '. $options['form_color'].';
			}
			#contactable #contactable_inner {
				display: '. ($options['hide_floating_gadget']?'none':'block').';
			}
		</style>
		<script type="text/javascript">
			$(function(){$(\'#contactable\').contactable({
			url: "'.$url.'",
			name: "'. $options['name_label'].'",
			email: "'. $options['email_label'].'",
			message: "'. $options['message_label'].'",
			subject: "'. $options['subject_label'].'",
			submit: "'. $options['submit_button_label'].'",
			receivedMsg: "'. $options['received_message'].'",
			notRecievedMsg: "'. $options['not_received_message'].'",
			disclaimer: "'. ( $options['disclaimer_link'] ? '<a href=\"'.$options['disclaimer_link'].'\" target=\"_blank\">'.$options['disclaimer'].'</a>' : $options['disclaimer'] ).'",
			hideOnSubmit: "'. $options['hide_on_submit'].'"
			});});
		</script>

		<div id="contactable_handler" class="halo" onclick="if ( jQuery(this).html()==\''.$options['title'].'\') { jQuery(\'#contactable_handler\').flip({ direction:\'tb\', content:\''.$options['contact_details'].'\' })} else { $(\'#contactable_handler\').revertFlip() } "  style="background-color: '.$options['button_color'].'" >'.$options['title'].'</div>
		<span id="contact_details">'.$options['contact_details'].'</span>
		<!--end get-in-touch script -->
		';

		// this could be a linked title opening the get-in-touch profile in a new tab/window
		$hide_on_submit = ($options['hide_on_submit'] == '1') ? '&hide_on_submit=1' : '';
		$hide_sidebar_widget = ($options['hide_sidebar_widget'] == '1') ? true : false;
		
		$content = str_replace(
			array(
				"%title%",
				"%name_label%",
				"%email_label%"
			), 
			array(
				$title,
				$name_label,
				$email_label
			), 
			$widget
		);

		if (!$hide_sidebar_widget)
		{			
			echo $before_widget;
			echo $content;
			echo $after_widget;	
		} else {
			echo $content;
		}

	}



	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_get_in_touch_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_get_in_touch');
		if ( !is_array($options) )
			$options = array('title'=>'Get in touch', 
				'contact_details'=>'Call now: +44 (0) 20 7250 4778', 
				'name_label' => 'Your Name',
				'email_label' => 'Your Email',
				'subject_label' => 'Subject',
				'message_label' => 'Message',
				'submit_button_label' => 'Send feedback',
				'received_message' => 'Thank You for your feedback!',
				'not_received_message' => 'Sorry but your message could not be sent, try again later.',
				'disclaimer' => 'Please feel free to get in touch, we value your feedback.',
				'disclaimer_link' => 'http://www.flatrocktech.com',
				'hide_on_submit' => '0',
				'hide_sidebar_widget' => '0',
				'button_color' => '#0eb1e0', //  blue color by default
				'form_color' => '#666666' // dark grey color by default
			);


		if ( $_POST['get_in_touch-submit'] ) {
			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['get_in_touch-title']));
			$options['contact_details'] = strip_tags(stripslashes($_POST['get_in_touch-contact_details']));
			$options['name_label'] = strip_tags(stripslashes($_POST['get_in_touch-name_label']));
			$options['email_label'] = strip_tags(stripslashes($_POST['get_in_touch-email_label']));
			$options['subject_label'] = strip_tags(stripslashes($_POST['get_in_touch-subject_label']));
			$options['message_label'] = strip_tags(stripslashes($_POST['get_in_touch-message_label']));
			$options['submit_button_label'] = strip_tags(stripslashes($_POST['get_in_touch-submit_button_label']));
			$options['received_message'] = strip_tags(stripslashes($_POST['get_in_touch-received_message']));
			$options['not_received_message'] = strip_tags(stripslashes($_POST['get_in_touch-not_received_message']));
			$options['disclaimer'] = strip_tags(stripslashes($_POST['get_in_touch-disclaimer']));
			$options['disclaimer_link'] = strip_tags(stripslashes($_POST['get_in_touch-disclaimer_link']));
			$options['button_color'] = strip_tags(stripslashes($_POST['get_in_touch-button_color']));
			$options['form_color'] = strip_tags(stripslashes($_POST['get_in_touch-form_color']));
			$options['hide_on_submit'] = ($_POST['get-in-touch-hide_on_submit'] == "on" ) ? "1" : "0";			
			$options['hide_sidebar_widget'] = ($_POST['get-in-touch-hide_sidebar_widget'] == "on" ) ? "1" : "0";								
			$options['hide_floating_gadget'] = ($_POST['get-in-touch-hide_floating_gadget'] == "on" ) ? "1" : "0";							
			update_option('widget_get_in_touch', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		foreach ($options as $key=>$value)
		{
			$$key = htmlspecialchars($value, ENT_QUOTES);
		}

		// Here is the form segment. Notice that we don't need a
		// complete form as this will be embedded into the existing form.		
		echo '<p style="text-align:right;"><label for="get_in_touch-title">' . __('Box title (front):') . ' <input style="width: 180px;" id="get_in_touch-title" name="get_in_touch-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="get_in_touch-contact_details">' . __('Details (back):') . ' <input style="width: 180px;" id="get_in_touch-contact_details" name="get_in_touch-contact_details" type="text" value="'.$contact_details.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="get_in_touch-name_label">' . __('Name label:', 'widgets') . ' <input style="width: 180px;" id="get_in_touch-name_label" name="get_in_touch-name_label" type="text" value="'.$name_label.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="get_in_touch-email_label">' . __('Email label:', 'widgets') . ' <input style="width: 180px;" id="get_in_touch-email_label" name="get_in_touch-email_label" type="text" value="'.$email_label.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="get_in_touch-subject_label">' . __('Subject label:', 'widgets') . ' <input style="width: 180px;" id="get_in_touch-subject_label" name="get_in_touch-subject_label" type="text" value="'.$subject_label.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="get_in_touch-message_label">' . __('Message label:', 'widgets') . ' <input style="width: 180px;" id="get_in_touch-message_label" name="get_in_touch-message_label" type="text" value="'.$message_label.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="get_in_touch-submit_button_label">' . __('Submit button:', 'widgets') . ' <input style="width: 180px;" id="get_in_touch-submit_button_label" name="get_in_touch-submit_button_label" type="text" value="'.$submit_button_label.'" /></label></p>';
		echo '<p style="text-align:left;clear: both;"><label for="get_in_touch-disclaimer">' . __('Disclaimer:', 'widgets') . ' <input style="width: 320px;" id="get_in_touch-disclaimer" name="get_in_touch-disclaimer" type="text" value="'.$disclaimer.'" /></label></p>';
		echo '<p style="text-align:left;clear: both;"><label for="get_in_touch-disclaimer_link">' . __('Disclaimer link:', 'widgets') . ' <input style="width: 320px;" id="get_in_touch-disclaimer_link" name="get_in_touch-disclaimer_link" type="text" value="'.$disclaimer_link.'" /></label></p>';
		echo '<p style="text-align:left;"><label for="get_in_touch-received_message">' . __('Received Message:', 'widgets') . ' <input style="width: 320px;" id="get_in_touch-received_message" name="get_in_touch-received_message" type="text" value="'.$received_message.'" /></label></p>';
		echo '<p style="text-align:left;"><label for="get_in_touch-not_received_message">' . __('Not Received Message:', 'widgets') . ' <input style="width: 320px;" id="get_in_touch-not_received_message" name="get_in_touch-not_received_message" type="text" value="'.$not_received_message.'" /></label></p>';
		echo '<p style="text-align:left;clear: both;"><input style="width: 80px;" id="get_in_touch-button_color" name="get_in_touch-button_color" type="text" value="'.$button_color.'" /> <label for="get_in_touch-button_color">' . __('Button background color:', 'widgets') . '</label></p>';
		echo '<p style="text-align:left;clear: both;"><input style="width: 80px;" id="get_in_touch-form_color" name="get_in_touch-form_color" type="text" value="'.$form_color.'" /> <label for="get_in_touch-form_color">' . __('Form background color shade:', 'widgets') . '</label></p>';
		echo '<p style="text-align:left;"><input id="get-in-touch-hide_on_submit" name="get-in-touch-hide_on_submit" type="checkbox" ' . (  ($hide_on_submit == '1') ? "checked=\"checked\"" : "" ) . '<label for="get-in-touch-hide_on_submit">' . __('Hide on submit:', 'widgets') . ' </label></p>';
		echo '<p style="text-align:left;"><input id="get-in-touch-hide_sidebar_widget" name="get-in-touch-hide_sidebar_widget" type="checkbox" ' . (  ($hide_sidebar_widget == '1') ? "checked=\"checked\"" : "" ) . ' <label for="get-in-touch-hide_sidebar_widget">' . __('Hide sidebar widget:', 'widgets') . ' </label></p>';
		echo '<p style="text-align:left;"><input id="get-in-touch-hide_floating_gadget" name="get-in-touch-hide_floating_gadget" type="checkbox" ' . (  ($hide_floating_gadget == '1') ? "checked=\"checked\"" : "" ) . ' <label for="get-in-touch-hide_floating_gadget">' . __('Hide floating gadget:', 'widgets') . ' </label></p>';
		echo '<input type="hidden" id="get_in_touch-submit" name="get_in_touch-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Get In Touch', 'widgets'), 'widget_get_in_touch');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Get In Touch', 'widgets'), 'widget_get_in_touch_control', 300, 200);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_get_in_touch_init');

?>