<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;


/**
 * IKRA Banner Content
 * 
 * 
 * @package    WordPress
 * @subpackage IKRA
 * @author     Sazzad Hu http://sazzadh.com
 */
class IKRA_Banner_content{
	
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
			'base_id' => 'IKRA_Banner_content',
			'd_class' => 'IKRA_Banner_content1',
		);
		$data = array_merge($default, $data);
		
		$default_settings = array(
			'css_class' => NULL,		
			'img_w' => 300,
			'img_h' => 300,
			'img_placeholder' => false,
			'more_text' => 'Read More',
			'target' => '_self',
			'text_limit' => false,
		);
		$settings = array_merge($default_settings, $settings);
		
		$default_css_settings = array(			
			'title_color' => NULL,
			'subtitle_color' => NULL,
			'text_color' => NULL,
			'bg_color' => NULL,
			'border_color' => NULL,
			'button_text_color' => NULL,
			'button_border_color' => NULL,
			'button_bg_color' => NULL,
			'button_text_h_color' => NULL,
			'button_border_h_color' => NULL,
			'button_bg_h_color' => NULL,
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
			'subtitle' => '',
			'image' => NULL,
			'img_w' => '',
			'img_h' => '',
			'img_placeholder' => false,
			'more_text' => 'Read More',
			'target' => '_self',
			'link' => '#',
			'text' => '',
			'text_limit' => false,
		);
		$content = array_merge($default, $content);
		
		$image_w = ($content['img_w'] != '' ? $content['img_w'] : $this->settings['img_w']);
		$image_h = ($content['img_h'] != '' ? $content['img_h'] : $this->settings['img_h']);
		$text_limit = ($content['text_limit'] != '' ? $content['text_limit'] : $this->settings['text_limit']);
		
		$image = ikra_image($content['image'], $image_w, $image_h);
		?>
        <div class="<?php echo $this->base_id; ?> <?php echo $this->d_class; ?> <?php echo $this->settings['css_class']; ?>">
        	<div class="<?php echo $this->base_id; ?>_inner">
            	<div class="IKBAC_caption">
                	<span></span>
                    <div class="IKBAC_caption_inner">
                    	<?php if($content['title'] != NULL): ?>
                            <h4 class="IKBAC_title">
                                <a href="<?php echo $content['link']; ?>" target="<?php echo $content['target']; ?>" title="<?php echo $content['title']; ?>">
                                    <?php echo $content['title']; ?>
                                </a>
                            </h4>
                        <?php endif; ?>
                        <?php if($content['subtitle'] != NULL): ?><h5 class="IKBAC_subtitle"><?php echo $content['subtitle']; ?></h5><?php endif; ?>
                        <?php if($content['text'] != NULL): ?><p class="IKBAC_text"><?php echo ikra_string_limit($content['text'], $text_limit); ?></p><?php endif; ?>
                        <?php if($content['link'] != NULL): ?>
                            <a class="IKBAC_more" href="<?php echo $content['link']; ?>" target="<?php echo $content['target']; ?>" title="<?php echo $content['title']; ?>">
                                <?php echo $content['more_text']; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <img src="<?php echo $image; ?>" alt="<?php echo $content['title']; ?>" height="<?php echo $image_h; ?>" width="<?php echo $image_w; ?>" />
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
			'subtitle' => get_post_meta(get_the_ID(), 'ikra_subtitle', true),
			'image' => esc_url( $large_image_url[0] ),
			'link' => get_permalink(),
			'text' => get_the_content(),
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
			'subtitle' => NULL,
			'image' => $image,
			'link' => get_term_link( $tax_term ),
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