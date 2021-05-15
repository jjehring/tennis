<?php

class Cream_Magazine_News_Widget_Eleven extends WP_Widget {
 
    function __construct() { 

        parent::__construct(
            'cream-magazine-news-widget-eleven',  // Base ID
            esc_html__( 'CM Half: News Widget 3', 'cream-magazine' ),   // Name
            array(
                'description' => esc_html__( 'Displays posts of selected category.', 'cream-magazine' ), 
            )
        );
 
    }
 
    public function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$post_cat = '';

        if( cream_magazine_get_option( 'cream_magazine_save_value_as' ) == 'slug' ) {

            $post_cat = !empty( $instance[ 'post_cat' ] ) ? $instance[ 'post_cat' ] : 'none';
        } else {

            $post_cat = !empty( $instance[ 'post_cat' ] ) ? $instance[ 'post_cat' ] : 0;
        }

		$post_no = !empty( $instance[ 'post_no' ] ) ? $instance[ 'post_no' ] : 4;

        $show_categories_meta = isset( $instance['show_categories_meta'] ) ? $instance['show_categories_meta'] : true;

        $show_author_meta = isset( $instance['show_author_meta'] ) ? $instance['show_author_meta'] : true;

        $show_date_meta = isset( $instance['show_date_meta'] ) ? $instance['show_date_meta'] : true;

        $show_cmnt_no_meta = isset( $instance['show_cmnt_no_meta'] ) ? $instance['show_cmnt_no_meta'] : true;

		$post_args = array(
			'post_type' => 'post',
            'ignore_sticky_posts' => true,
		);

        if( $post_cat != 'none' ) {

            if( cream_magazine_get_option( 'cream_magazine_save_value_as' ) == 'slug' ) {

                $post_args['category_name'] = sanitize_text_field( $post_cat );
            } else {

                $post_args['cat'] = absint( $post_cat );
            }
        }

        if( absint( $post_no ) > 0 ) {

            $post_args['posts_per_page'] = absint( $post_no );
        }

		$post_query = new WP_Query( $post_args );

		if( $post_query->have_posts() ) {
            ?>
            <section class="cm-post-widget-section cm_middle_post_widget_six">
                <div class="section_inner">
                    <?php 
                    if( !empty( $title ) ) {
                        ?>
                        <div class="section-title">
                            <h2><?php echo esc_html( $title ); ?></h2>
                        </div><!-- .section-title -->
                        <?php
                    } 
                    ?>                
                    <div class="owl-carousel middle_widget_six_carousel">
                        <?php
                        while( $post_query->have_posts() ) {
                            
                            $post_query->the_post();

                            $thumbnail_url = '';

                            if( has_post_thumbnail() ) {

                                $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'cream-magazine-thumbnail-4' );
                            }
                            ?>
                            <div class="item">
                                <?php 
                                if( !empty( $thumbnail_url ) ) { 
                                    ?>
                                    <div class="card post_thumb" style="background-image: url( <?php echo esc_url( $thumbnail_url ); ?> )" >
                                    <?php
                                } else { 
                                    ?>
                                    <div class="card">
                                    <?php 
                                } 
                                ?>
                                    <div class="card_content">
                                        <?php cream_magazine_post_categories_meta( $show_categories_meta ); ?>
                                        <div class="post_title">
                                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                        </div>
                                        <?php cream_magazine_post_meta( $show_date_meta, $show_author_meta, $show_cmnt_no_meta, false ); ?>
                                    </div><!-- .card_contents -->
                                </div><!-- .card -->
                            </div><!-- .item -->
                            <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </div><!-- .owl-carousel.widget_seven_carousel -->
                </div><!-- .section_inner -->
            </section><!-- .cm_post_widget_one -->
            <?php
        }
 
    }
 
    public function form( $instance ) {
        $defaults = array(
            'title'       => '',
            'post_no'	  => 4,
            'show_categories_meta' => true,
            'show_author_meta' => true,
            'show_date_meta' => true,
            'show_cmnt_no_meta' => true,
        );

        if( cream_magazine_get_option( 'cream_magazine_save_value_as' ) == 'slug' ) {

            $defaults['post_cat'] = 'none';
        } else {

            $defaults['post_cat'] = 0;
        }

        $instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
            <strong><?php esc_html_e( 'At frontend this widget looks like as below:', 'cream-magazine' ); ?></strong> 
            <img src="<?php echo esc_url( get_template_directory_uri() . '/admin/images/widget-placeholders/cm-half-widget-three.png' ); ?>" style="max-width: 100%; height: auto;"> 
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>">
                <strong><?php esc_html_e('Title', 'cream-magazine'); ?></strong>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />   
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) )?>"><strong><?php esc_html_e( 'Select Category', 'cream-magazine' ); ?></strong></label>
            <?php
            $categories = get_terms( 
                array( 
                    'taxonomy' => 'category', 
                    'hide_empty' => 0, 
                )
            );
            ?>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'post_cat' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'post_cat' ) ); ?>">
                <option value="none" <?php selected( $instance['post_cat'], 'none' ); ?>><?php esc_html_e( 'Select Category', 'cream-magazine' ); ?></option>
                <?php
                if( !empty( $categories ) ) {
                    foreach( $categories as $cat ) {
                        if( cream_magazine_get_option( 'cream_magazine_save_value_as' ) == 'slug' ) {
                            ?>
                            <option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $instance['post_cat'], $cat->slug ); ?>><?php echo esc_html( $cat->name ); ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo esc_attr( $cat->term_id ); ?>" <?php selected( $instance['post_cat'], $cat->term_id ); ?>><?php echo esc_html( $cat->name ); ?></option>
                            <?php
                        }
                    }
                }
                ?>
            </select>
            <small><?php echo esc_html__( 'If no category is selected, then recent posts will be displayed.', 'cream-magazine' ); ?></small>
        </p>

		<p>
            <label for="<?php echo esc_attr( $this->get_field_id('post_no') ); ?>">
                <strong><?php esc_html_e('No of Posts', 'cream-magazine'); ?></strong>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('post_no') ); ?>" name="<?php echo esc_attr( $this->get_field_name('post_no') ); ?>" type="number" value="<?php echo esc_attr( $instance['post_no'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('show_categories_meta') ); ?>">
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_categories_meta') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_categories_meta') ); ?>" <?php checked( $instance['show_categories_meta'], true ); ?>>
                <?php esc_html_e('Show Post Categories', 'cream-magazine'); ?>
            </label>
        </p> 

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('show_author_meta') ); ?>">
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_author_meta') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_author_meta') ); ?>" <?php checked( $instance['show_author_meta'], true ); ?>>
                <?php esc_html_e('Show Post Author', 'cream-magazine'); ?>
            </label>
        </p> 

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('show_date_meta') ); ?>">
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_date_meta') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_date_meta') ); ?>" <?php checked( $instance['show_date_meta'], true ); ?>>
                <?php esc_html_e('Show Posted Date', 'cream-magazine'); ?>
            </label>
        </p>  

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('show_cmnt_no_meta') ); ?>">
                <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_cmnt_no_meta') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_cmnt_no_meta') ); ?>" <?php checked( $instance['show_cmnt_no_meta'], true ); ?>>
                <?php esc_html_e('Show Post Comments Number', 'cream-magazine'); ?>
            </label>
        </p>  
		<?php
    }
 
    public function update( $new_instance, $old_instance ) {
 
        $instance                           = $old_instance;

        $instance['title']                  = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

        if( cream_magazine_get_option( 'cream_magazine_save_value_as' ) == 'slug' ) {

            $instance['post_cat']           = isset( $new_instance['post_cat'] ) ? sanitize_text_field( $new_instance['post_cat'] ) : 'none';
        } else {

            $instance['post_cat']           = isset( $new_instance['post_cat'] ) ? absint( $new_instance['post_cat'] ) : 0;
        }

        $instance['post_no']                = isset( $new_instance['post_no'] ) ? absint( $new_instance['post_no'] ) : 4;

        $instance['show_categories_meta']   = isset( $new_instance['show_categories_meta'] ) ? wp_validate_boolean( $new_instance['show_categories_meta'] ) : false;
        
        $instance['show_author_meta']       = isset( $new_instance['show_author_meta'] ) ? wp_validate_boolean( $new_instance['show_author_meta'] ) : false;

        $instance['show_date_meta']         = isset( $new_instance['show_date_meta'] ) ? wp_validate_boolean( $new_instance['show_date_meta'] ) : false;

        $instance['show_cmnt_no_meta']      = isset( $new_instance['show_cmnt_no_meta'] ) ? wp_validate_boolean( $new_instance['show_cmnt_no_meta'] ) : false;

        return $instance;
    } 
}