<?php
class sacf_simple_ajax_contact_form{
	public $settings;
	public $rand;
	
	function __construct($settings = array()){
		$default = array(
			'id' => 'contact',			
			'div_class' => '',
			'submit_text' => 'Send Now',
			'email' => 'hello@tallythemes.com',
			'subject' => 'Message From Contact form',
			'fields' => array(
				array(
					'title' => 'Name',
					'type' => 'text',
					'class' => '',
					'des' => '',
					'required' => 'on',
					'placeholder' => 'on',
					'width' => '2',
				),
				array(
					'title' => 'Email',
					'type' => 'email',
					'class' => '',
					'des' => '',
					'required' => 'on',
					'placeholder' => 'on',
					'width' => '2',
				),
				array(
					'title' => 'Phone',
					'type' => 'tel',
					'class' => '',
					'des' => '',
					'required' => 'off',
					'placeholder' => 'on',
					'width' => '2',
				),
				array(
					'title' => 'Subject',
					'type' => 'text',
					'class' => '',
					'des' => '',
					'required' => 'off',
					'placeholder' => 'on',
					'width' => '2',
				),
				array(
					'title' => 'Message',
					'type' => 'textarea',
					'class' => '',
					'des' => '',
					'required' => 'on',
					'placeholder' => 'on',
					'width' => '1',
				),
			),
		);
		$this->settings = array_merge($default, $settings);
		$this->rand = rand();
		
		
		add_action('wp_enqueue_scripts', array($this, 'script_register'));
		
		
		
		$this->form();
	}
	
	
	function ID(){
		$settings = $this->settings;
		
		return $settings['id'].'_'.$this->rand;
	}
	
	
	
	function script_register(){
		wp_localize_script( 'sacf', 'postsacf', array('ajax_url' => admin_url( 'admin-ajax.php' )));
	}
	
	
	
	function form(){
		$settings = $this->settings;
		if(is_array($settings)){
			if(is_array($settings['fields'])){
				echo '<form class="sacf_simple_ajax_contact_form" action="" method="post" name="'.$this->ID().'_form'.'" id="'.$this->ID().'_form'.'">';
					echo '<div class="cl_holder">';
						foreach($settings['fields'] as $key => $field){
							echo '<div class="cl cl_'.$field['width'].'">';
								if($field['placeholder'] == 'off'){ echo '<label for="'.$this->ID().$key.'">'.$field['title'].'</label>'; }
								$placeholder = '';
								$required = '';
								if($field['placeholder'] == 'on'){ $placeholder = ' placeholder="'.$field['title'].'" '; }
								if($field['required'] == 'on'){ $required = ' required '; }
								
								if($field['type'] == 'textarea'){
									echo '<textarea name="'.$this->ID().$key.'" class="'.$field['class'].' '.$required.'" id="'.$this->ID().$key.'" '.$placeholder.'></textarea>';
									
								}else{
									echo '<input type="'.$field['type'].'" name="'.$this->ID().$key.'" class="'.$field['class'].' '.$required.'" id="'.$this->ID().$key.'" value="" '.$placeholder.'>';
								}
								if($field['des'] != ''){ echo '<span class="sacf_form_des">'.$field['des'].'</span>'; }
							echo '</div>';
						}
					echo '</div>';
					echo '<input type="button" name="'.$this->ID().'_submit'.'" id="'.$this->ID().'_submit'.'" value="'.$settings['submit_text'].'" class="submit">';
					echo '<p class="'.$this->ID().'_formOK" style="display:none;">Thank you for your attention. Your message has been sent.</p>';
					echo '<p class="'.$this->ID().'_sending" style="display:none;"><span>Sending...</span></p>';
				echo '</form>';
				
				?>
                <script type="text/javascript">
					jQuery(document).ready(function($) {
						jQuery("#<?php echo $this->ID(); ?>_submit").click(function() {

								$( '.<?php echo $this->ID(); ?>_formOK' ).css( "display", 'none' );
								
								jQuery.ajax({
									url: "<?php echo admin_url('admin-ajax.php'); ?>",
									data: {
										'action':'sacf_simple_ajax_contact_form',
										'settings':'<?php echo $this->encode( serialize( $settings ) ); ?>',
										'formid':'<?php echo $this->ID(); ?>',
										<?php
										if(is_array($settings)){
											if(is_array($settings['fields'])){
												foreach($settings['fields'] as $key => $field){
													?>
													'<?php echo $this->ID().$key; ?>' : $( "#<?php echo $this->ID().$key; ?>" ).val(),
													<?php
												}
											}
										}
										?>
									},
									beforeSend:function(data) {
										// This outputs the result of the ajax request
										$( '.<?php echo $this->ID(); ?>_sending' ).css( "display", 'block' );
										console.log(data);
									},
									success:function(data) {
										// This outputs the result of the ajax request
										$( '.<?php echo $this->ID(); ?>_formOK' ).css( "display", 'block' );
										$( '.<?php echo $this->ID(); ?>_sending' ).css( "display", 'none' );
										$( '.<?php echo $this->ID(); ?>_formOK' ).html( data );
										console.log(data);
									},
									error: function(errorThrown){
										$( '.<?php echo $this->ID(); ?>_formNOK' ).css( "display", 'block' );
										$( '.<?php echo $this->ID(); ?>_sending' ).css( "display", 'none' );
										console.log(errorThrown);
									}
								});
								return false;
							
						})
					});
				</script>
                <?php
			}
		}
	}
	
	
	static function encode( $value ) {

	  $func = 'base64' . '_encode';
	  return $func( $value );
	  
	}
	
	static function decode( $value ) {
	
	  $func = 'base64' . '_decode';
	  return $func( $value );
	  
	}
	
}

add_action('wp_ajax_sacf_simple_ajax_contact_form', 'sacf_simple_ajax_contact_form');
add_action('wp_ajax_nopriv_sacf_simple_ajax_contact_form',  'sacf_simple_ajax_contact_form');

function sacf_simple_ajax_contact_form(){
	$get_settings = $_REQUEST['settings'];
	$ID = $_REQUEST['formid'];
	$settings = unserialize(sacf_simple_ajax_contact_form::decode( $get_settings ));
	$content = array();
	$error = 0;
	$message = '';
	
	if(is_array($settings)){
		if(is_array($settings['fields'])){
			foreach($settings['fields'] as $key => $field){
				
				$content[] = array( 
					'title' => $field['title'], 
					'value' => $_REQUEST[$ID.$key],
					'type' => $field['type'],
					'required' => $field['required'],
				);
				
				if(( $field['required'] == 'on') && ( $_REQUEST[$ID.$key] == '' )){ $error++; $message .= '<p class="sacf_form_error"><strong>'.$field['title'].'</strong> is required.</p>'; }
				if($field['type'] == 'email'){
					if(!filter_var($_REQUEST[$ID.$key], FILTER_VALIDATE_EMAIL)){
						$error++; $message .= '<p class="sacf_form_error">Enter a Valid email address for <strong>'.$field['title'].'</strong>.</p>';
					}
				}
			}
		}
	}
	
	if( $error == 0 ){
		$to = $settings['email'];
		$subject = $settings['subject'];
		$headers = "From: " . strip_tags($settings['email']) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($settings['email']) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$body = '<html><body>';
		$body .= '<h1>'.$subject.'</h1>';
		$body .= '<table rules="all" style="border:1px solid #666; width:100%;" cellpadding="10">';
			$body .= "<tr style='background: #eee;'>
						<td style='padding: 10px;border-bottom: solid 1px; border-right: 1px solid; width: 20%;'><strong>Labels:</strong> </td>
						<td style='padding: 10px;border-bottom: solid 1px; border-right: 1px solid;'>Data</td>
					  </tr>";
			foreach( $content as $con_item ){
				$body .= "<tr>
							<td style='padding: 10px;border-bottom: solid 1px; border-right: 1px solid;'><strong>".$con_item['title'].":</strong> </td>
							<td style='padding: 10px;border-bottom: solid 1px; border-right: 1px solid;'>" . strip_tags($con_item['value']) . "</td>
						  </tr>";
			}
			
		$body .= "</table>";
		$body .= '</body></html>';
		
		if(wp_mail($to, $subject, $body, $headers)){
			$message .= '<p class="sacf_form_success">Thank you for your attention. Your message has been sent.</p>';
			?>
            <script type="text/javascript">
				jQuery(document).ready(function($) {
					$("#<?php echo $ID; ?>_form").get(0).reset();
				});
			</script>	
            <?php
		}else{
			$message .= '<p class="sacf_form_error">There was an error when sending your message, please try again later.</p>';
			
		}
	}
	
	if($error == 0){ echo '<div class="sacf_form_message_success">'; }else{ echo '<div class="sacf_form_message_error">'; }
		echo $message;
	echo '</div>';
	
	
	exit(); 
}