<?php

//edit 4

function pinar_child_latest_enqueue()
{
	wp_register_style( 'child-theme-style', get_stylesheet_directory_uri() . '/style.css','1.0.0');
	wp_enqueue_style( 'child-theme-style');

	wp_register_style( 'font-awesome4.5', get_stylesheet_directory_uri() . '/css/font-awesome.min.css');
	wp_enqueue_style( 'font-awesome4.5');

	//add your scripts
	//wp_register_script( 'my-child-script', get_stylesheet_directory_uri() . '/js/my-scripts.js', '1.0', true );
	//wp_enqueue_script('my-child-script');

}
add_action("wp_enqueue_scripts", "pinar_child_latest_enqueue", 10000);

function child_enqueue() {

    wp_enqueue_style( 'bootstrap_css', 'http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css');
    /*wp_enqueue_script( 'room_accordion_js', get_stylesheet_directory_uri() . '/js/room-accordion.js', array( 'jquery' ), '1.0', true );*/
    wp_enqueue_script( 'bootstrap_js', 'http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js', array( 'jquery' ), '1.0', true );
}

add_action('wp_enqueue_scripts', 'child_enqueue');

		/**----------------------------------------------------------------------------------------------------**/
		/**----------------------------------------------------------------------------------------------------**/

add_shortcode('sudamala-availability-form', 'sudamala_availability_form');

function sudamala_availability_form($attr)
	{
		/**
		 * Availability form's Attribute
		 */
		$pinar_availability_form_attr = shortcode_atts(
			array(
				/**
				 * Values that this attribute can handle are:
				 * "vertical" , "horizontal" and "simple"
				 * Default value : 'vertical'
				 */
				'type'  => 'vertical',
				/**
				 * For using the Circular form leave this attribute blank like : 'style' => '',
				 * if you want to use the simple form add the "style-2" value for this attribute
				 * Default value : 'style-2'
				 */
				'style' => 'style-2',
				'title' => 'Find A <b>Room</b>'
			), $attr );

		$select_options ='';
		for ($i=1; $i < 7; $i++)
	    {
	    	$select_options .= '<option value="'. esc_attr( $i ) .'">'. esc_html( $i ) .'</option>';
	    }
		switch ($pinar_availability_form_attr['type']) {
			case 'vertical':

				$pinar_availability_form_str = '
					<div class="booking-form-container container">
						<div id="main-booking-form" class="'.esc_attr($pinar_availability_form_attr['style']).' sudamala-booking">

							<form class="booking-form clearfix" action="'. esc_url( RAVIS_BOOKING_PAGE_URL ) .'" method="post" autocomplete="off">
							<div class="input-daterange clearfix col-md-6">
						            <div class="booking-fields col-xs-6 col-md-6">
						                <input placeholder="'.esc_attr__('Choose check in date', 'ravis').'" class="datepicker-fields check-in" type="text" name="start" />
						                <i class="fa fa-calendar"></i>
						            </div>
						            <div class="booking-fields col-xs-6 col-md-6">
						                <input placeholder="'.esc_attr__('Choose check out date', 'ravis').'" class="datepicker-fields check-out" type="text" name="end" />
						                <i class="fa fa-calendar"></i>
						            </div>
						        </div>
					            <div class="booking-fields col-xs-6 col-md-3">

					                <select name="adult">
					                    <option value="">'.esc_attr__('How Many Adult?', 'ravis').'</option>
					                    '.balancetags($select_options).'
					                </select>
					            </div>
					            <div class="booking-fields col-xs-6 col-md-3">
					                <select name="child">
					                    <option value="">'.esc_attr__('How Many Children ?', 'ravis').'</option>
					                    '.balancetags($select_options).'
					                </select>
					            </div>
					            <div class="booking-button-container">
					                <input class="btn btn-default" value="'.esc_attr__('Check Availability', 'ravis').'" type="submit"/>
					            </div>
					        </form>
						</div>
					</div>';
				break;
		}
		return $pinar_availability_form_str;
	}

		/**----------------------------------------------------------------------------------------------------**/
		/**----------------------------------------------------------------------------------------------------**/

add_shortcode('sudamala-service-slider', 'sudamala_service_slider');

/**

	 * ------------------------------------------------------------------------------------------

	 * Generate the service slider

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_service_slider($attr)

	{

		global $post;



		/**

		 * Service Slider's Attribute

		 */

		$pinar_service_slider_attr = shortcode_atts(

			array(

				'title' => esc_html__('Our Services', 'ravis'),

				'class' => '',

				'id' => '75, 76, 77'

			), $attr );

		$service_id = explode(', ', $pinar_service_slider_attr['id']);

		//echo ' ' . $service_id[1];

		/**

		 * Generate the Query for getting the posts

		 * @var array 	$args

		 */

		$args = array(

			'post_type'   => 'service',

			'post_status' => 'publish',

			'post__in'    => $service_id,

			'order'       => 'DESC',

			'orderby'     => 'date',

			//Custom Field Parameters

			'meta_query'     => array(

				array(

					'key' => 'services_in_slider',

					'value' => 'on',

				),

			)

		);

		$pinar_service_slider  = new WP_Query( $args );



		/**

		 * Loading post for making the service_slider

		 */

		if ( $pinar_service_slider->have_posts() )

		{

			/**

			 * Generating the service_slider HTML codes

			 * @var string 	 pinar_service_slider_code

			 */



			$pinar_service_slider_code = '

			<div id="our-services" class="services-container '. esc_attr( $pinar_service_slider_attr['class'] ) .'">

	            <div class="heading-box"><h2>'. ravis_fn_title_effect(esc_html($pinar_service_slider_attr['title'] )) .'</h2></div>

					<div id="services-box" class="owl-carousel owl-theme">';

			    /**

			     * Loop for getting post data

			     */

				while ( $pinar_service_slider->have_posts() )

				{

					$pinar_service_slider->the_post();

					$thumb_size  = array('600', '300');

					$service_img = ( get_the_post_thumbnail( $post->ID, $thumb_size, 'alt='. get_the_title()) ? get_the_post_thumbnail( $post->ID, $thumb_size, 'alt='. get_the_title()) : '<img src="'. PINAR_IMG_PATH.'service-placeholder.jpg' .'" alt="'.__('No Image','ravis').'" />');

					$pinar_service_slider_code .= '

					<div class="item">

		                '.balancetags($service_img).'

		                <div class="title">'. esc_html( get_the_title() ) .'</div>

		                <div class="short-desc">'. wp_kses_post( get_the_content() ) .'</div>

		            </div>';

				}

			$pinar_service_slider_code .= '

				</div>

	        </div>';

		}

		else

		{

			// no posts found

			$pinar_service_slider_code = esc_html__('There is not any services','ravis');

		}



		/**

		 * Restore original Post Data

		 */

		wp_reset_postdata();

		return balancetags( $pinar_service_slider_code );

	}

		/**----------------------------------------------------------------------------------------------------**/
		/**----------------------------------------------------------------------------------------------------**/

add_shortcode('sudamala-grid-gallery', 'sudamala_grid_gallery');

/**

	 * ------------------------------------------------------------------------------------------

	 * Generate Sudamala Grid Gallery

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_grid_gallery($attr)

	{

		$sudamala_grid = shortcode_atts(

			array(

				'title' => esc_html__('Our Services', 'ravis'),

				'class' => '',

				'link' => '',

				'caption' => ''

			), $attr );

		$links = explode(', ', $sudamala_grid['link']);

		$captions = explode(', ', $sudamala_grid['caption']);

		//echo '' . $links[0];


		$sudamala_gallery_grid_code = '
			<div class="gallery-container container gallery-grid '. esc_attr( $sudamala_grid['class'] ) .'">
				<div class="gallery-container">
					<div class="sort-section">
						<div class="sort-section-container">
							<div class="sort-handle"></div>
							<ul class="list-inline">
								<li><a href="#" data-filter="."></a></li>
							</ul>
						</div>
					</div>

					<ul class="image-main-box clearfix">';

		for($i=0; $i<count($links); $i++){

			$sudamala_gallery_grid_code .= '
				<li class="item col-xs-6 col-md-4">
					<figure>
						<img src="'. $links[$i] .'">
						<a href="'. $links[$i] .'" class="more-details" data-title=""></a>
						<figcaption>
							<h4><b>'. $captions[$i] .'</b></h4>
						</figcaption>
					</figure>
				</li>
			';

		}

		$sudamala_gallery_grid_code .= '
					</ul>
				</div>
			</div>
		';

		return balancetags( $sudamala_gallery_grid_code );

	}

	add_shortcode('sudamala-main-gallery', 'sudamala_main_gallery');

/**

	 * ------------------------------------------------------------------------------------------

	 * Generate the main Gallery

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_main_gallery($attr)

	{

		global $pinar_opt;



		/**

		 * Main Gallery's Attribute

		 */

		$pinar_main_gallery_attr = shortcode_atts(

			array(

				'title'       => esc_html__('Pinar Gallery', 'ravis'),

				'sort_option' => $pinar_opt['pinar-gallery-sort'],

				'img_count'   => 8,

				'more_link'	  => FALSE

			), $attr );





		$pinar_main_gallery_code = $sort_options = $sort_options_li = $img_list_class = '';

		$gallery_items_id        = isset($pinar_opt["pinar-main-gallery"] ) ? explode(',', $pinar_opt["pinar-main-gallery"]) : '';



		if (isset($pinar_opt["pinar-main-gallery"]))

		{

			$pinar_main_gallery_code .='

				<div id="gallery">';

				if(isset($pinar_main_gallery_attr['title']) && $pinar_main_gallery_attr['title'] !='')

				{

					$pinar_main_gallery_code .='

					<div class="heading-box">

						<h2>'.ravis_fn_title_effect($pinar_main_gallery_attr['title']).'</h2>

					</div>';

				}



				if($gallery_items_id[0] !=='')

				{

					$sort_options = explode(',', $pinar_main_gallery_attr['sort_option']);

					foreach ($sort_options as $sort_options_list) {

						if($sort_options_list === 'All')

						{

							$sort_options_li .='<li><a href="#" data-filter="*" class="active">'.esc_html__('All', 'ravis').'</a></li>';

						}

						else

						{

							$sort_options_li .='<li><a href="#" data-filter=".'.esc_attr(strtolower($sort_options_list)).'">'.esc_html($sort_options_list).'</a></li>';

						}

					}



					$pinar_main_gallery_code .='<div class="gallery-container gallery-grid sudamala-main-gallery">

						<div class="sort-section">

							<div class="sort-section-container">

								<div class="sort-handle">'.esc_html__('Filters', 'ravis').'</div>

								<ul class="list-inline">

									'.$sort_options_li.'

								</ul>

							</div>

						</div>

						<ul class="image-main-box clearfix">';



					$img_i = 1;

					foreach ($gallery_items_id as $gallery_item_id) {

						if(isset($pinar_main_gallery_attr['img_count']) && $pinar_main_gallery_attr['img_count']!='')

						{

							if( $img_i > $pinar_main_gallery_attr['img_count'] ) continue;

						}

						$image = get_post( intval( $gallery_item_id ) );

						$pinar_main_gallery_code .='

							<li class="item '.( (isset($image->post_title) && $image->post_title !=='' && strpos($image->post_title, 'col-') !== false ) ? esc_attr($image->post_title) : 'col-xs-6 col-md-3').' '.(isset($image->post_content) && $image->post_content !=='' ? esc_attr($image->post_content) : '').'">

								<figure>

									<img src="'.esc_url( $image->guid ).'" alt="11"/>

									<a href="'.esc_url( $image->guid ).'" class="more-details" data-title="'.esc_attr($image->post_excerpt).'"></a>

									<figcaption>

										<h4>'.ravis_fn_title_effect(esc_html($image->post_excerpt )).'</h4>

									</figcaption>

								</figure>

							</li>';

						$img_i ++;

					}

					$pinar_main_gallery_code .='</ul>';



					if($pinar_main_gallery_attr['more_link'] === TRUE)

					{

						$pinar_main_gallery_code .='<a href="'.esc_url( RAVIS_GALLERY_PAGE_URL ).'" class="btn btn-default btn-sm">'.esc_html__('More ...', 'ravis').'</a>';

					}



					$pinar_main_gallery_code .='</div>';

				}

				else

				{

					$pinar_main_gallery_code .='

						<div class="items">

				            <img src="'.PINAR_IMG_PATH.'slider-placeholder.png" alt="'.esc_attr__( 'No Image',  'ravis').'"/>

				        </div>';

				}



			$pinar_main_gallery_code .='</div>';

		}

		else

		{

			esc_html_e('There is not any slides', 'ravis');

		}



		/**

		 * Restore original Post Data

		 */

		wp_reset_postdata();

		return balancetags( $pinar_main_gallery_code );

	}

		/**----------------------------------------------------------------------------------------------------**/
		/**----------------------------------------------------------------------------------------------------**/

	add_shortcode('sudamala-art-space-head', 'sudamala_art_space_head');

/**

	 * ------------------------------------------------------------------------------------------

	 * Generate Sudamala Art Space

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_art_space_head($attr, $content = null)

	{

		$art_space_head = shortcode_atts(

			array(

				'title' => esc_html__('Sudakara Art Space Exhibitions', 'ravis')

			), $attr );

		$art_space_head_code = '
			<div id="luxury-rooms" class="container art-space">
				<div class="heading-box">
					<h2>'. esc_attr( $art_space_head['title'] ) .'</h2>
				</div>
			<div class="room-container">
			' . do_shortcode($content);

		$art_space_head_code .= '
				</div>
			</div>';

		return $art_space_head_code;
	}


	add_shortcode('sudamala-art-space-body', 'sudamala_art_space_body');

/**

	 * ------------------------------------------------------------------------------------------

	 * Generate Sudamala Art Space Body

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_art_space_body($attr, $content = null)

	{

		$art_space_body = shortcode_atts(

			array(

				'title' => esc_html__('Sudakara Art Space Exhibitions', 'ravis'),
				'image' => '',
				'link' => '',
				'class' => 'fadeInRight'

			), $attr );

		$art_space_body_code = '
			<div class="room-boxes wow fade '. esc_attr( $art_space_body['class'] ) .'">
				<div class="img-container col-xs-6 col-md-7">
					<img src="'. esc_attr( $art_space_body['image'] ) .'" class="room-img">
				</div>
			<div class="room-details col-xs-6 col-md-5">
				<div class="title">'. esc_attr( $art_space_body['title'] ) .'</div>
					<div class="description">'. $content .'
					</div>
					<p><a href="'. esc_attr( $art_space_body['link'] ) .'" class="btn btn-default">Read More</a></p>
				</div>
			</div>';


		return $art_space_body_code;
	}

		/**----------------------------------------------------------------------------------------------------**/
		/**----------------------------------------------------------------------------------------------------**/


	add_shortcode('sudamala-package-head', 'sudamala_package_head');

	/**

	 * ------------------------------------------------------------------------------------------

	 * Generate Sudamala Package Head Shortcode

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_package_head($attr, $content = null)

	{

		$sudamala_package_head = shortcode_atts(

			array(

				'class' => ''

			), $attr );

		$sudamala_package_head_code = '
			<div id="special-packages-type-2" class="container '. esc_attr( $sudamala_package_head['class'] ) .'">
				<div class="package-container">
			' . do_shortcode($content);

		$sudamala_package_head_code .= '
				</div>
			</div>';

		return $sudamala_package_head_code;
	}


	add_shortcode('sudamala-package-body', 'sudamala_package_body');

/**

	 * ------------------------------------------------------------------------------------------

	 * Generate Sudamala Package Body Shortcode

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_package_body($attr, $content = null)

	{

		$sudamala_package_body = shortcode_atts(

			array(

				'title' => esc_html__('Sudakara Art Space Exhibitions', 'ravis'),
				'image' => '',
				'link' => '',
				'class' => 'fadeInRight',
				'button_text' => 'Make a Reservation',
				'price' => '',
				'price-detail' => '',
				'spa-detail' => ''

			), $attr );

		$sudamala_package_body_code = '
			<div class="package-boxes wow '. esc_attr( $sudamala_package_body['class'] ) .'">
			<div class="img-container col-xs-4 col-md-4">
				<img src="'. esc_attr( $sudamala_package_body['image'] ) .'" class="package-img wp-post-image">
			</div>

			<div class="package-details col-xs-8 col-md-8">
				<div class="title">'. esc_attr( $sudamala_package_body['title'] ) .'</div>
				<div class="description">'. $content .'
				</div>

				<div class="button-price clearfix">';

				if($sudamala_package_body['link'] != ''){
					$sudamala_package_body_code .= '
					<a href="'. esc_attr( $sudamala_package_body['link'] ) .'" class="btn btn-default">'
						. esc_attr( $sudamala_package_body['button_text'] ) .'</a>';
				}

		$sudamala_package_body_code .='
			<div class="price"><span>' . esc_attr( $sudamala_package_body['price'] ) .'
				</span>' . esc_attr( $sudamala_package_body['price-detail'] ) .' <br> ' . esc_attr( $sudamala_package_body['spa-detail'] ) .'</div>
			</div>
			</div>
		</div>
		';


		return $sudamala_package_body_code;
	}

	/**----------------------------------------------------------------------------------------------------**/
	/**----------------------------------------------------------------------------------------------------**/

	add_shortcode('sudamala-accordion-head', 'sudamala_accordion_head');

	/**

	 * ------------------------------------------------------------------------------------------

	 * Generate Sudamala Accordion Head Shortcode

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_accordion_head($attr, $content = null)

	{

		$sudamala_accordion_head = shortcode_atts(

			array(

				'class' => ''

			), $attr );

		$sudamala_accordion_head_code = '
			<div class="panel-group '. esc_attr( $sudamala_accordion_head['class'] ) .'" id="accordion">
			' . do_shortcode($content);

		$sudamala_accordion_head_code .= '
				</div><!-- close panel group -->
			';

		return $sudamala_accordion_head_code;

	}

	add_shortcode('sudamala-accordion-body', 'sudamala_accordion_body');

	/**

	 * ------------------------------------------------------------------------------------------

	 * Generate Sudamala Accordion Body Shortcode

	 * ------------------------------------------------------------------------------------------

	 */

	function sudamala_accordion_body($attr, $content = null)

	{

		$sudamala_accordion_body = shortcode_atts(

			array(

				'title' => '',
				'class' => '',
				'id' => '',

			), $attr );

		$sudamala_accordion_body_code = '
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#'. esc_attr( $sudamala_accordion_body['id'] ) .'">
						'. esc_attr( $sudamala_accordion_body['title'] ) .'</a>
					</h3>
				</div>
				<div id="'. esc_attr( $sudamala_accordion_body['id'] ) .'" class="panel-collapse collapse '. esc_attr( $sudamala_accordion_body['class'] ) .'">
					<div class="panel-body">
					' . $content .'
				</div>
			</div>
		</div>';

		return $sudamala_accordion_body_code;

	}

		/**----------------------------------------------------------------------------------------------------**/
		/**----------------------------------------------------------------------------------------------------**/

	add_shortcode('sudamala-welcome-head', 'sudamala_welcome_head');

	/**
	 * ------------------------------------------------------------------------------------------
	 * Generate Sudamala Welcome Head Shortcode
	 * ------------------------------------------------------------------------------------------
	 */

	function sudamala_welcome_head($attr, $content = null)
	{
		$sudamala_welcome_head = shortcode_atts(
			array(
				'class' => '',
				'id' => 'welcome'
			), $attr );
		$sudamala_welcome_head_code = '
			<div id="'. esc_attr( $sudamala_welcome_head['id'] ) .'" class="container '. esc_attr( $sudamala_welcome_head['class'] ) .'">
				' . do_shortcode($content) . '
			</div><!-- close welcome -->';

		return $sudamala_welcome_head_code;
	}

	add_shortcode('sudamala-heading-box', 'sudamala_heading_box');

	/**
	 * ------------------------------------------------------------------------------------------
	 * Generate Sudamala Welcome Heading Box Shortcode
	 * ------------------------------------------------------------------------------------------
	 */

	function sudamala_heading_box($attr)
	{
		$sudamala_heading_box = shortcode_atts(
			array(
				'title' => '',
				'subtitle' => ''
			), $attr );
		$sudamala_heading_box_code = '
			<div class="heading-box">
				<h2>'. esc_attr( $sudamala_heading_box['title'] ) .'</h2>
				<div class="subtitle">'. esc_attr( $sudamala_heading_box['subtitle'] ) .'</div>
			</div><!-- close welcome -->';

		return $sudamala_heading_box_code;
	}

	add_shortcode('sudamala-inner-content', 'sudamala_inner_content');

	/**
	 * ------------------------------------------------------------------------------------------
	 * Generate Sudamala Inner Content Shortcode
	 * ------------------------------------------------------------------------------------------
	 */

	function sudamala_inner_content($attr, $content = null)
	{
		$sudamala_inner_content = shortcode_atts(
			array(
				'class' => ''
			), $attr );
		$sudamala_inner_content_code = '
			<div class="inner-content '. esc_attr( $sudamala_inner_content['class'] ) .'">
				<div class="desc">
					' . do_shortcode($content) . '
				</div>
			</div>';

		return $sudamala_inner_content_code;
	}

	add_shortcode('sudamala-image-frame', 'sudamala_image_frame');

	/**
	 * ------------------------------------------------------------------------------------------
	 * Generate Sudamala Image Frame Shortcode
	 * ------------------------------------------------------------------------------------------
	 */

	function sudamala_image_frame($attr)
	{
		$sudamala_image_frame = shortcode_atts(
			array(
				'class' => '',
				'image' => ''
			), $attr );
		$sudamala_image_frame_code = '
			<div class="img-frame">
				<img src="'. esc_attr( $sudamala_image_frame['image'] ) .'"
					class="alignnone size-full wp-image-1679'. esc_attr( $sudamala_image_frame['class'] ) .'">
			</div>';

		return $sudamala_image_frame_code;
	}

	add_shortcode('sudamala-image-frame-parallax', 'sudamala_image_frame_parallax');

	/**
	 * ------------------------------------------------------------------------------------------
	 * Generate Sudamala Image Frame Parallax Shortcode
	 * ------------------------------------------------------------------------------------------
	 */

	function sudamala_image_frame_parallax($attr)
	{
		$sudamala_image_frame_parallax = shortcode_atts(
			array(
				'class' => '',
				'image' => ''
			), $attr );
		$sudamala_image_frame_parallax_code = '
			<div class="img-frame '. esc_attr( $sudamala_image_frame_parallax['class'] ) .'" data-parallax="scroll"
			data-image-src="'. esc_attr( $sudamala_image_frame_parallax['image'] ) .'">
			</div>';

		return $sudamala_image_frame_parallax_code;
	}



	add_shortcode('sudamala-page-slider', 'sudamala_page_slider');

	/**
	 * ------------------------------------------------------------------------------------------
	 * Generate the Sudamala Page slider
	 * ------------------------------------------------------------------------------------------
	 */

	function sudamala_page_slider($attr)
	{
		$sudamala_page_slider = shortcode_atts(
			array(
				'image' => ''
			), $attr );

		$images = explode(', ', $sudamala_page_slider['image']);

		$pinar_main_slider_code ='';

			$pinar_main_slider_code .='
				<div id="main-slider">';

					foreach ($images as $image) {
						$pinar_main_slider_code .='
							<div class="items">
					            <img src="'.$image.'" alt="3"/>
					        </div>';
					}

			$pinar_main_slider_code .='</div>';

		return balancetags( $pinar_main_slider_code );
	}

        /**
	 * ------------------------------------------------------------------------------------------
	 * Generate Sudamala Other Rooms Navigation Shortcode
	 * ------------------------------------------------------------------------------------------
	 */

        function other_rooms_nav($attr)
	{
		global $pinar_opt;

		/**
		 * Pinar Other Rooms Attribute
		 */
		$other_rooms_attr = shortcode_atts(
			array(
				'title'      => 'Other Rooms',
				'room_count' => 5
			), $attr );

		$args1 = array(
			'post_type'      => 'rooms',
			'post_status'    => 'publish',
			'order'			 => 'ASC',
			'orderby'        => 'ID',
		);

		$currentID = get_the_id();

		/*Get all rooms list*/
		$rooms_list  = new WP_Query( $args1 );

		/*Get all rooms ID and current room ID*/
		$roomID = array();
		$roomID_nav = array();
		$order = array();

		if($rooms_list->have_posts())
		{
			while($rooms_list->have_posts())
			{
				$rooms_list->the_post();
				$roomID[] = get_the_ID();
			}
		}

		wp_reset_postdata();

		if($currentID == $roomID[0])
		{
			$roomID_nav[0] = $roomID[count($roomID) - 1];
			$roomID_nav[1] = $roomID[1];
		}
		elseif($currentID == $roomID[count($roomID) - 1])
		{
			$roomID_nav[0] = $roomID[count($roomID) - 2];
			$roomID_nav[1] = $roomID[0];
		}
		else
		{
			for ($i=0; $i < count($roomID); $i++)
			{
				if($currentID == $roomID[$i])
				{
					$roomID_nav[0] = $roomID[$i - 1];
					$roomID_nav[1] = $roomID[$i + 1];
				}
			}

			//$order = 'DESC';
		}

		/*Get other 2 rooms*/
		$args2 = array(
			'post_type'      => 'rooms',
			'post__in'		 => $roomID_nav,
			//'order' 		 => $order
		);

		$rooms_list_nav = new WP_Query( $args2 );

		$price_unit = !empty($pinar_opt['pinar-booking-currency']) ? ravis_currency_converter($pinar_opt['pinar-booking-currency']) : '&#36;';

		$other_rooms = '
			<div class="room-container container room-grid">';
				if(!empty($other_rooms_attr['title'])){
					$other_rooms .= '
						<div class="heading-box">
							<h2>'.ravis_fn_title_effect(esc_html($other_rooms_attr['title'])).'</h2>
						</div>
					';
				}

				if($rooms_list_nav->have_posts())
				{
					while($rooms_list_nav->have_posts())
					{
						$rooms_list_nav->the_post();
						$post_id          = get_the_ID();
						$thumb_size       = array('580', '380' );
						$rooms_price      = get_post_meta( $post_id, 'rooms_price', true );
						$rooms_short_desc = get_post_meta( $post_id, 'rooms_short_desc', true );
						$room_imgs_id     = explode( ',' , get_post_meta( $post_id, 'rooms_slideshow_images' , true ));
						$room_cover       = trim($room_imgs_id[0]);

						$other_rooms .= '
						<div class="room-box col-xs-6">
							<div class="img-container">';
							if($room_cover != '')
		                    {
		                    	$other_rooms .= wp_get_attachment_image( $room_cover, $thumb_size );
		                    }
		                    else
		                    {
		                    	$other_rooms .= '<img src="'. esc_url ( PINAR_IMG_PATH ).'room-placeholder.jpg" alt="'. esc_attr( esc_html__('No Image','ravis') ).'" />';
		                    }

							$other_rooms .= '<a href="'.esc_url(get_permalink()).'" class="btn btn-default btn-out-border">'.esc_html__('More Details', 'ravis').'</a>
							</div>
							<div class="details">
								<div class="title"><a href="'.esc_url(get_permalink()).'">'.ravis_fn_title_effect(esc_html(get_the_title())).'</a></div>
								<div class="desc">'.esc_html($rooms_short_desc).'</div>
								<div class="price">
									<a href="'.esc_url(get_permalink()).'" style="display:block">'.esc_html('More Details', 'ravis').'</a>
								</div>
							</div>
						</div>';
					}
				}

			$other_rooms .= '</div> ';

		return balancetags( $other_rooms );
	}

add_shortcode('sudamala-other-rooms-nav', 'other_rooms_nav');
