<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;


/**
 * IKRA Banner Popup Content
 * 
 * This content show a banner and when click on it, a pupup open with details content.
 * 
 * @package    WordPress
 * @subpackage IKRA
 * @author     Sazzad Hu http://sazzadh.com
 */
class IKRA_Banner_Popup_content{
	
	public $base_id;
	public $d_class;
	public $css_settings;
	public $settings;
	
	
	
	/**
	 * 
	 * This function do all one time work fo the classs. 
	 * For example register script, add css and javascript to the theme or plugin.
	 *
	 * @param string $data  description
	 * @return boolean
	 */
	function __construct($data, $settings, $css_settings){
		$default = array(			
			'base_id' => 'IKRA_Banner_Popup_content',
			'd_class' => 'IKRA_Banner_Popup_content_1',
		);
		$data = array_merge($default, $data);
		
		$default_settings = array(
			'css_class' => NULL,		
			'img_w' => 300,
			'img_h' => 300,
			'img_placeholder' => false,
		);
		$settings = array_merge($default_settings, $settings);
		
		$default_css_settings = array(			
			'title_color' => NULL,
			'text_color' => NULL,
			'border_color' => NULL,
			'accent_color' => NULL,
		);
		$css_settings = array_merge($default_css_settings, $css_settings);
		
		$this->base_id = $data['base_id'];
		$this->d_class = $data['d_class'];
		$this->css_settings = $css_settings;
		$this->settings = $settings;
		
		
	}
	
	
	/**
	 * 
	 * the base html function
	 *
	 * @return string
	 */
	function html_base($content){
		$default = array(			
			'title' => '',
			'image' => NULL,
			'img_w' => '',
			'img_h' => '',
			'img_placeholder' => false,
			'text' => '',
			'media' => '',
		);
		$content = array_merge($default, $content);
		
		$image_w = ($content['img_w'] != '' ? $content['img_w'] : $this->settings['img_w']);
		$image_h = ($content['img_h'] != '' ? $content['img_h'] : $this->settings['img_h']);
		
		$image = ikra_image($content['image'], $image_w, $image_h, $content['img_placeholder']);
		
		$href = $this->base_id.'_ikbpc_'.rand();
		?>
        <div class="<?php echo $this->base_id; ?> <?php echo $this->d_class; ?> <?php echo $this->settings['css_class']; ?>">
        
        	<div class="IKBPC_image">
            	<a href="#<?php echo $href; ?>" class="ikra_magnificPopup_inline">
            		<img src="<?php echo $image; ?>" alt="<?php echo $content['title']; ?>" height="<?php echo $image_h; ?>" width="<?php echo $image_w; ?>" />
                </a>
				<a href="#<?php echo $href; ?>" class="IKBPC_caption ikra_magnificPopup_inline"><?php echo $content['title']; ?></a>
            </div>
            
            <div class="IKBPC_content">
            	<div class="<?php echo $this->base_id; ?>_holder" id="<?php echo $href; ?>">
                	
                	<div class="IKBPC_holder">
                         <h4 class="IKBPC_title"><?php echo $content['title']; ?></h4>
                         <?php if($content['media'] != NULL): ?><div class="IKBPC_media ikra_video"><?php echo $content['media']; ?></div><?php endif; ?>
                         <?php if($content['text'] != NULL): ?><div class="IKBPC_text ikra_video"><?php echo $content['text']; ?></div><?php endif; ?>
                     </div>
                     <button title="Close (Esc)" type="button" class="mfp-close">×</button>
                </div>
            </div>
            
        </div>
        <?php
	}
	
	
	
	/**
	 * 
	 * This functon will genetarte from array
	 *
	 * @return string
	 */
	function html_query_array($content){
		$this->html_base($content);
	}
	
	
	
	/**
	 * 
	 * This functon will genetarte from WP_Query
	 *
	 * @return string
	 */
	function html_query_wp($wp_query){
		$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
		$content = array(
			'title' => get_the_title(),
			'media' => get_post_meta(get_the_ID(), 'ikra_ikbpc__media', true),
			'image' => esc_url( $large_image_url[0] ),
			'text' => apply_filters('the_content', get_the_content()),
		);
		$this->html_base($content);
	}
	
	
	
	/**
	 * 
	 * This functon will genetarte from taxonomy
	 *
	 * @return string
	 */
	function html_query_tax($tax_term){
		
		$image = NULL;
		if (function_exists('z_taxonomy_image_url')) { $image = z_taxonomy_image_url($tax_term->term_id, 'full', NULL); }
		
		$content = array(
			'title' => $tax_term->name,
			'media' => NULL,
			'image' => $image,
			'text' => $tax_term->description,
		);
		$this->html_base($content);
	}
	
	
	
	/**
	 * 
	 * This functon will add dynamic CSS to wp_head
	 *
	 * @return string
	 */
	function dynamic_css_wpHead_action(){
		add_action('wp_head', array($this, 'dynamic_css_wpHead'));
	}
	
	
	
	/**
	 * 
	 * This functon will add dynamic CSS to wp_head
	 *
	 * @return string
	 */
	function dynamic_css_wpHead(){
		echo '<style type="text/css">';
			$this->dynamic_css();
		echo '</style>';
	}
	
	
	
	/**
	 * 
	 * This functon hold child and Masonry dynamic css
	 *
	 * @return string
	 */
	function dynamic_css(){
		
	}
	
}