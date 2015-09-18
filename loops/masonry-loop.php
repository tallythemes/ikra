<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;


/**
 * IKRA Masonry Loop 
 * 
 * 
 * @package    WordPress
 * @subpackage IKRA
 * @author     Sazzad Hu http://sazzadh.com
 */
class IKRA_Masonry_Loop{
	
	public $base_id;
	public $d_class;
	public $css_class;
	public $css_id;
	public $column;
	public $margin;
	public $pagination;
	public $query_type;
	public $query_array;
	public $query_wp;
	public $query_tax;
	public $css_settings;
	public $child_css_settings;
	public $child_settings;
	public $child_php_class;
	public $child_php_class_call;
	
	/**
	 * 
	 * This function do all one time work fo the classs. 
	 * For example register script, add css and javascript to the theme or plugin.
	 *
	 * @param string $data  description
	 * @return boolean
	 */
	function __construct($data){
		$default = array(			
			'base_id' => 'IKRA_Masonry_Loop',
			'd_class' => 'IKRA_Masonry_Loop1',
			'css_class' => '',
			'child_php_class' => NULL,
			'css_id' => '',
			'column' => '3',
			'margin' => '30px',
			'pagination' => TRUE,
			'query_type' => 'wp', //array, wp, tax
			'query_array' => array(
				array(
					'title'=> 'sample title',
					'text'=> 'sample text',
					'link'=> 'http://sazzadh.com',
					'image'=> '',
				),
			),
			'query_wp' => array(
				'post_type' => array( 'post' ),
				'posts_per_page' => 10,
				'ignore_sticky_posts' => 1,
			),
			'query_tax' => array(
				'taxonomy' => 'post_tag', 
				'args' => array(
					'orderby'           => 'name', 
					'order'             => 'ASC',
					'hide_empty'        => true, 
				)
			),
			'css_settings' => array(
				'paginate_text_color' => NULL,
				'paginate_bg_color' => NULL,
				'paginate_border_color' => NULL,
				'paginate_text_h_color' => NULL,
				'paginate_bg_h_color' => NULL,
				'paginate_border_h_color' => NULL,
				
				'filter_text_color' => NULL,
				'filter_bg_color' => NULL,
				'filter_border_color' => NULL,
				'filter_text_h_color' => NULL,
				'filter_bg_h_color' => NULL,
				'filter_border_h_color' => NULL,
			),
			'child_css_settings' => array(),
			'child_settings' => array(),
		);
		$data = array_merge($default, $data);
		
		$this->base_id = $data['base_id'];
		$this->d_class = $data['d_class'];
		$this->css_class = $data['css_class'];
		$this->css_id = $data['css_id'];
		$this->column = $data['column'];
		$this->margin = $data['margin'];
		$this->pagination = $data['pagination'];
		$this->query_type = $data['query_type'];
		$this->query_array = $data['query_array'];
		$this->query_wp = $data['query_wp'];
		$this->query_tax = $data['query_tax'];
		$this->css_settings = $data['css_settings'];
		$this->child_css_settings = $data['child_css_settings'];
		$this->child_settings = $data['child_settings'];
		$this->child_php_class = $data['child_php_class'];
		
		$child_data = array(
			'd_class' => $this->d_class.'sid',
		);
		$this->child_php_class_call = new $this->child_php_class($child_data, $this->child_settings, $this->child_css_settings);
	}
	
	
	
	/**
	 * 
	 * Render the HTML
	 *
	 * @return string
	 */
	function html(){
		if(class_exists($this->child_php_class)){
			echo '<div class="'.$this->base_id.' IKML_Column_'.$this->column.'">';
				echo '<div class="'.$this->base_id.'_inner">';
					echo '<div class="'.$this->base_id.'_masonry">';
						if(($this->query_type == 'array') && is_array($this->query_array)){
							$this->html_loop_array();
						}elseif(($this->query_type == 'wp') && is_array($this->query_wp)){
							$this->html_loop_WpQuery();
						}elseif(($this->query_type == 'tax') && is_array($this->query_tax)){
							$this->html_loop_TaxQuery();
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';
			?>
            <script type="text/javascript">
				jQuery(document).ready(function($) {
					var $<?php echo $this->base_id; ?>_container = $('.<?php echo $this->base_id; ?>_masonry');
					$<?php echo $this->base_id; ?>_container.imagesLoaded( function(){
						$<?php echo $this->base_id; ?>_container.masonry({
							itemSelector : '.<?php echo $this->base_id; ?>_child',
							columWidth: 200,
						});
					});
				});
			</script>
            <?php
		}else{
			echo 'Child class is not found';	
		}
	}
	
	
	
	/**
	 * 
	 * This functon will genetarte from array
	 *
	 * @return string
	 */
	function html_loop_array(){
		foreach($this->query_array as $array_content){
			echo '<div class="'.$this->base_id.'_child">';
				echo '<div class="'.$this->base_id.'_child_inner" style="margin:'.$this->margin.';">';
					$this->child_php_class_call->html_query_array($array_content);
				echo '</div>';
			echo '</div>';
		}
	}
	
	
	
	/**
	 * 
	 * This functon will genetarte from WP_Query
	 *
	 * @return string
	 */
	function html_loop_WpQuery(){
		
		$query_args = $this->query_wp;
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$query_args['paged'] = $paged;
		
		wp_reset_postdata();
		$query = new WP_Query( $query_args );
		
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) { $query->the_post();
				echo '<div class="'.$this->base_id.'_child">';
					echo '<div class="'.$this->base_id.'_child_inner" style="margin:'.$this->margin.';">';
						$this->child_php_class_call->html_query_wp($query);
					echo '</div>';
				echo '</div>';
			}	
			if($this->pagination == true){ $this->pafination(); }
		}
		wp_reset_postdata();
	}
	
	
	
	/**
	 * 
	 * This functon will genetarte from taxonomy
	 *
	 * @return string
	 */
	function html_loop_TaxQuery(){
		$terms = get_terms( $this->query_tax['taxonomy'], $this->query_tax['args'] );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			foreach ( $terms as $tax_term ) {
				echo '<div class="'.$this->base_id.'_child">';
					echo '<div class="'.$this->base_id.'_child_inner" style="margin:'.$this->margin.';">';
						$this->child_php_class_call->html_query_tax($tax_term);
					echo '</div>';
				echo '</div>';
			} 
		}
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
		$this->masonry_dynamic_css();
		$this->child_dynamic_css();
	}
	
	
	
	/**
	 * 
	 * This functon hold Masonry dynamic css
	 *
	 * @return string
	 */
	function masonry_dynamic_css(){
		
	}
	
	
	
	/**
	 * 
	 * This functon hold Child dynamic css
	 *
	 * @return string
	 */
	function child_dynamic_css(){
		$this->child_php_class_call->dynamic_css();
	}
	
	
	
	/**
	 * 
	 * This function generate pagination of WP_Query
	 *
	 * @param string Array
	 * @return string
	 */
	function pafination(){
		global $wp_query; 
		$query = $wp_query;
		$output = null;
		
		if ($query->max_num_pages > 1) {
			$output .= '<div class="IKRAM_pagenav">';
				$big = 999999999; // need an unlikely integer		
				$output .= paginate_links( array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var('paged') ),
					'total' => $query->max_num_pages
				));
			$output .= '</div>';
		}
		
		echo $output;
	}
	
	
}