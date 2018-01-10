<?php
namespace HotelElements\Widgets;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Hotel_Luxury_Elements_Rooms extends Widget_Base {
	public function get_name() {
		return 'hotel-luxury-rooms';
	}
	public function get_title() {
		return __( 'Rooms', 'hotel-luxury' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'fa fa-hotel';
	}
	public function get_categories() {
		return [ 'hotel-elements' ];
	}
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Rooms', 'hotel-luxury' ),
			]
		);
		$this->add_control(
			'room_title',
			[
				'label' => __( 'Title', 'hotel-luxury' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Rooms', 'hotel-luxury' )
			]
		);
		$this->add_control(
			'number_posts',
			[
				'label' => __( 'Number of rooms to show', 'hotel-luxury' ),
				'type' => Controls_Manager::NUMBER,
				'default' => ''
			]
		);
		$this->add_control(
			'room_category',
			[
				'label' => __( 'Select Room Category', 'hotel-luxury' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => hotel_luxury_get_taxonomy('category')
			]
		);
		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'hotel-luxury' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'hotel-luxury' ),
					'desc' => __( 'DESC', 'hotel-luxury' )
				]
			]
		);
		$this->add_control(
			'orderby',
			[
				'label' => __( 'Orderby', 'hotel-luxury' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'ID' => __( 'ID', 'hotel-luxury' ),
					'title' => __( 'Title', 'hotel-luxury' ),
					'date' => __( 'Date', 'hotel-luxury' ),
					'rand' => __( 'Random', 'hotel-luxury' )
				]
			]
		);
		$this->add_control(
			'column_layout',
			[
				'label' => __( 'Column Layout', 'hotel-luxury' ),
				'type' => Controls_Manager::SELECT,
				'default' => '6',
				'options' => [
					'4' => __( '3', 'hotel-luxury' ),
					'6' => __( '2', 'hotel-luxury' )
				]
			]
		);
		$this->add_control(
			'enable_filter',
			[
				'label' => __( 'Enable Filter By Tags', 'hotel-luxury' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Show', 'hotel-luxury' ),
				'label_off' => __( 'Hide', 'hotel-luxury' ),
				'return_value' => 'yes',
			]
		);
		$this->end_controls_section();
	}
	protected function render( $instance = [] ) {
		// get our input from the widget settings.
		global $post;
		$settings = $this->get_settings();
		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['number_posts'],
			'cat'            => $settings['room_category'],
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
		);
		$rooms = get_posts( $args );
		?>
		<div class="builder-item-wrapper builder-rooms ">
			<div class="builder-title-wrapper clearfix has_filter">
				<h3 class="builder-item-title"><?php echo !empty( $settings['room_title'] ) ? esc_attr( $settings['room_title'] ) : esc_html__('Rooms', 'hotel-luxury') ?></h3>
				<!-- filter tag -->
				<?php
				if ( $settings['enable_filter'] == 'yes' ) {
					$filter = '';
					$current_tag = '';
                    $tags = array();
					$filter .= '<ul data-option-key="filter" class="cpt-filters right">
                    <li><button type="button" data-filter="all">' . esc_html__( 'All', 'hotel-luxury' ) . '</button></li>';
					foreach ( $rooms as $post ) {
						$term_objects = get_the_terms( $post->ID,  'post_tag' );
						if ( $term_objects ) :
						foreach ( $term_objects as $term_object ) {
							$tags[ $term_object->slug ] = $term_object->name;
						}
						endif;
					}
					$tags = array_unique($tags);
					foreach ( $tags as $slug => $name ) {
						$filter .= '<li><button data-filter=".' . esc_attr( $slug ) . '">' . esc_html( stripslashes( $name ) ) . '</button></li>';
                    }
					$filter .= '</ul>';
					echo $filter;
				}
				?>
			</div>
			<!-- room layout -->
			<?php
			if ( $rooms ) {
				echo  '<div class="room-items row clearfix">';
				foreach ( $rooms as $post ) {
					setup_postdata( $post );
					$room_id = get_the_ID();
					$term_list = wp_get_post_terms( $room_id, 'post_tag', array("fields" => "all") );
					$filter_class = array();
					foreach($term_list as $term){
						$filter_class[]= $term->slug;
					}
					?>
						<div class="mix col-md-<?php echo $settings['column_layout'] . ' ' . esc_attr( join(' ', $filter_class ) ); ?> column">
                            <div class="hover">
                                <a class="first-gallery-thumb" href="<?php the_permalink() ?>">
									<?php the_post_thumbnail('hotel_luxury_medium') ; ?>
								</a>
                                <div class="overlay">                                    
                                    <a href="<?php the_permalink() ?>" class="info"><i class="fa fa-link" aria-hidden="true"></i></a>
                                </div>
                            </div>                            
							<div class="cpt-detail clearfix">
								<h2 class="cpt-title">
									<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
								</h2>
								<div class="cpt-desc"><?php the_excerpt() ?></div>
							</div>
						</div>
					<?php
				}
				echo '</div>';
			}
			wp_reset_postdata();
			?>
		</div>
		<?php if ( $settings['enable_filter'] == 'yes' ) { ?>
		<script type="text/javascript">
            jQuery( function ($) {
                var mixer = mixitup('.room-items', {
                    selectors: {
                        target: '.mix'
                    },
                    animation: {
                        duration: 300
                    }
                });
            });
		</script>
		<?php } ?>
		<?php
	}
	protected function _content_template() {}
}