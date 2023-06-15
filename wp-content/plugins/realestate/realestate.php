<?php
/**
 * @package Real Estate
 * @version 1.0.0
 */
/*
Plugin Name: Real Estate
Plugin URI: http://wordpress.org/plugins/<plugin_name>/
Description: Test Work
Author: Mykyta P
Version: 1.0.0
Author URI: http://localhost/
*/


add_action( 'init', 'realestate_custom_post_type' );
function realestate_custom_post_type() {
	register_post_type( 'realestate',
		array(
			'labels'      => array(
				'name'          => __('Объекты недвижимости', 'realestate'),
				'singular_name' => __('Объект недвижимости', 'realestate'),
			),
			'public'      => true,
			'has_archive' => true,
		)
	);
}


add_action( 'init', 'realestate_custom_taxonomy' );
function realestate_custom_taxonomy() {
	register_taxonomy( 'district', 'realestate', array(
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Район', 'taxonomy general name' ),
			'singular_name' => _x( 'Район', 'taxonomy singular name' ),
			'search_items' =>  __( 'Поиск по району', 'realestate' ),
			'all_items' => __( 'Все районы', 'realestate' ),
			// 'parent_item' => __( 'Весь Киев', 'realestate' ),
			// 'parent_item_colon' => __( 'Весь Киев:', 'realestate' ),
			'edit_item' => __( 'Редактировать район', 'realestate' ),
			'update_item' => __( 'Обновить район', 'realestate' ),
			'add_new_item' => __( 'Добавить новый район', 'realestate' ),
			'new_item_name' => __( 'Имя нового района', 'realestate' ),
			'menu_name' => __( 'Районы' ),
		),
		'meta_box_cb' => 'district_meta_box',
		'show_in_rest' => true,
	) );
}


function district_meta_box( $post ) {
	$terms = get_terms( 'district', array( 'hide_empty' => false ) );

	$post  = get_post();
	$district = wp_get_object_terms( $post->ID, 'district', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
	$name  = '';

    if ( ! is_wp_error( $district ) ) {
    	if ( isset( $district[0] ) && isset( $district[0]->name ) ) {
			$name = $district[0]->name;
	    }
    }

	foreach ( $terms as $term ) {
?>
		<label title='<?php esc_attr_e( $term->name ); ?>'>
		    <input type="radio" name="district" value="<?php esc_attr_e( $term->name ); ?>" <?php checked( $term->name, $name ); ?>>
			<span><?php esc_html_e( $term->name ); ?></span>
		</label><br>
<?php
    }
}


add_action( 'save_post_realestate', 'save_realestate_district_meta_box' );
function save_realestate_district_meta_box( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['district'] ) ) {
		return;
	}

	$district = sanitize_text_field( $_POST['district'] );
	
	if ( empty( $district ) ) {
		// unhook this function so it doesn't loop infinitely
		remove_action( 'save_post_realestate', 'save_realestate_district_meta_box' );

		$postdata = array(
			'ID'          => $post_id,
			'post_status' => 'draft',
		);
		wp_update_post( $postdata );
	} else {
		$term = get_term_by( 'name', $district, 'district' );
		if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
			wp_set_object_terms( $post_id, $term->term_id, 'district', false );
		}
	}
}


add_action( 'wp_enqueue_scripts', 'load_scripts' );
function load_scripts(){
    wp_enqueue_script( 'jquery' );
}


// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_get_ajax_posts', 'get_ajax_posts');
add_action('wp_ajax_nopriv_get_ajax_posts', 'get_ajax_posts');
function get_ajax_posts() {

    $args = array(
        'post_type' => array('realestate'),
        'post_status' => array('publish'),
        'posts_per_page' => -1,
        // 'nopaging' => true,
        'order' => 'DESC',
        'orderby' => 'date',
	    'meta_query'    => array(
	        'relation'      => 'AND',
	        array(
		        'relation'      => 'OR',
		        array(
		            'key'       => 'number_floors',
		            'value'     => '18',
		            'compare'   => '='
		        ),
		        /*array(
		            'key'       => 'number_floors',
		            'value'     => '6',
		            'compare'   => '='
		        ),*/
		    ),
	        array(
		        'relation'      => 'OR',
		        array(
		            'key'       => 'building_type',
		            'value'     => 'Панель1',
		            'compare'   => '='
		        ),
		        array(
		            'key'       => 'building_type',
		            'value'     => 'Пеноблок',
		            'compare'   => '='
		        ),
		    )
	        // array(
	        //     'key'       => 'attendees',
	        //     'value'     => 100,
	        //     'type'      => 'NUMERIC',
	        //     'compare'   => '>'
	        // )
	    )
    );
print_r("<pre>");
print_r($_REQUEST);

	foreach ( $_REQUEST['number_floors'] as $number_floors ) {
		if ( $number_floors[1] == 'true' ) {
			print_r($number_floors[0]);
			print_r("///");
		}
	}

	foreach ( $_REQUEST['building_type'] as $building_type ) {
		if ( $building_type[1] == 'true' ) {
			print_r($building_type[0]);
			print_r("///");
		}
	}
print_r("</pre>");


	$q = new WP_Query( $args );

	if( $q->have_posts() ) :

		while( $q->have_posts() ) : $q->the_post();
			ob_start();
			get_template_part( 'template-parts/content/content-excerpt', get_post_format() );
			$data .= ob_get_clean();
		endwhile;

	endif;
	 
	wp_reset_postdata();

	$resp = array(
	    'success' => true,
	    'data'    => $data
	);

    echo json_encode( $resp );

    exit;
}


add_shortcode( 'search_attributes_shortcode', 'search_attributes_shortcode_function', 999 );
function search_attributes_shortcode_function( $query ) {
	$fields = get_field_objects();
?>
	<script>
		jQuery(document).ready(function($) {
		    $('input[name*=number_floors]').prop('checked', '');

		    var number_floors = [];
		    var building_type = [];

	        $("#apply").click(function() {
		    	$('input[name*=number_floors]').each(function(index) {
  					number_floors[index] = [$(this).val(), $(this).prop('checked')];
				});

		    	$('input[name*=building_type]').each(function(index) {
  					building_type[index] = [$(this).val(), $(this).prop('checked')];
				});

console.log(number_floors)
console.log(building_type)
				$.ajax({
				    type: 'POST',
				    url: '<?php echo admin_url('admin-ajax.php');?>',
				    dataType: "json",
				    data: {
				    	action : 'get_ajax_posts',
				    	number_floors: number_floors,
				    	building_type: building_type,
				    },
				    success: function( response ) {
		          		$("#search_results").html(response.data);
				    }
				});
	        })
		});
	</script>

	<div style="background: transparent">
<?php
		foreach ( $fields as $key => $field ) {

			if ( isset($field['choices']) ) {
?>
				<div><?php echo $field['label']; ?></div>
<?php
				foreach ( $field['choices'] as $k => $choice ) {
					if ($choice != '-') {
?>
					    <span style="padding-right:10px">
							<input type="checkbox" name="<?php echo $key; ?>" value="<?php echo $choice; ?>">
							<label style="padding-left:0" for="<?php echo $key; ?>"><?php echo $choice; ?></label>
					    </span>
<?php
					}
				}
			}
		}
?>
		<button id="apply">Apply</button>
	</div>
<?php
}


add_shortcode( 'add_district_shortcode', 'add_district_shortcode_function', 999 );
function add_district_shortcode_function( $query ) {
	$terms = wp_get_post_terms( get_the_ID(), array( 'district' ) );

	if( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
	    $taxonomy = $terms[0]->name;
	} else {
		$taxonomy = "не указан";
	}

    echo "<div>Район: ".$taxonomy."</div>";
}


add_shortcode( 'add_attributes_shortcode', 'add_attributes_shortcode_function', 999 );
function add_attributes_shortcode_function( $query ) {
	$house_name = get_field_object( 'house_name' );
	$location_coordinates = get_field_object( 'location_coordinates' );
	$number_floors = get_field_object( 'number_floors' );
	$building_type = get_field_object( 'building_type' );
?>
	<div style="background: white">
		<div>
			<i>
				<?php $house_name_value = ($house_name['value'] == "") ? "не указано" : $house_name['value'];?>
				<?php echo $house_name['label']; ?>: <?php echo $house_name_value; ?>
			</i>
		</div>

		<div>
			<i>
				<?php $location_coordinates_value = ($location_coordinates['value'] == "") ? "не указано" : $location_coordinates['value'];?>
				<?php echo $location_coordinates['label']; ?>: <?php echo $location_coordinates_value; ?>
			</i>
		</div>

		<div>
			<i>
				<?php $number_floors_value = ($number_floors['value'] == "-") ? "не указано" : $number_floors['value'];?>
				<?php echo $number_floors['label']; ?>: <?php echo $number_floors_value; ?>
			</i>
		</div>

		<div>
			<i>
				<?php $building_type_value = ($building_type['value'] == "") ? "не указано" : $building_type['value'];?>
				<?php echo $building_type['label']; ?>: <?php echo $building_type_value; ?>
			</i>
		</div>
	</div>
<?php
}


add_action( 'widgets_init', 'attributes_widget_init' );
function attributes_widget_init() {
	register_sidebar( array(
		'name'          => __( 'Attributes', 'realestate' ),
		'id'            => 'attributes-single-post-widgets',
		'description'   => __( 'Widgets in this area will be shown under your single posts, before comments.', 'textdomain' ),
		'before_widget'	=> '',
		'after_widget'	=> '',
		'before_title'	=> '',
		'after_title'	=> '',
    ) );
}


// add_filter( 'single_template', 'override_single_template' );
// function override_single_template( $single_template ){
//     global $post;

//     $file = dirname(__FILE__) .'/templates/single-'. $post->post_type .'.php';

//     if( file_exists( $file ) ) $single_template = $file;

//     return $single_template;
// }

