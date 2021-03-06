<?php
/*function my_custom_woocommerce_theme_support() {
    add_theme_support( 'woocommerce', array(
        // . . .
        // thumbnail_image_width, single_image_width, etc.
 
        // Product grid theme settings
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
             
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ) );
}
 
add_action( 'after_setup_theme', 'my_custom_woocommerce_theme_support' );*/

function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'html5', array( 'search-form' ) );
	
	//Enabling the gallery in themes
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	//Disabling gallery
	//remove_theme_support( 'wc-product-gallery-zoom' );
	//remove_theme_support( 'wc-product-gallery-lightbox' );
	//remove_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );



function listify_child_styles() {
    // enqueue style
	    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
	
	wp_enqueue_script( 'loadmore_js', get_stylesheet_directory_uri() . '/assets/js/loadmore.js', array('jquery'),'1.0', false );
}
add_action( 'wp_enqueue_scripts', 'listify_child_styles', 999 );

function shr_add_admin_scripts(){
 
    wp_enqueue_media();
    wp_enqueue_script('shr-uploader', get_stylesheet_directory_uri().'/assets/js/uploader.js', array('jquery'), '1.0', true );
}
add_action('admin_enqueue_scripts', 'shr_add_admin_scripts');


function theme_js() {
	//wp_enqueue_style('parent-theme-style'); // parent theme code
	//wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	//wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/style.css', false, '1.0', 'all' );
	// enqueue style
	//wp_enqueue_style('twentysixteen-style', get_stylesheet_uri());
	// enqueue parent styles
	//wp_enqueue_style('parent-theme', get_template_directory_uri() .'/style.css');
	// enqueue parent styles
	wp_enqueue_style('parent-style', get_template_directory_uri() .'/style.css');
	
	// enqueue child styles
	wp_enqueue_style('child-theme', get_stylesheet_directory_uri() .'/style.css', array('parent-theme'));
    wp_enqueue_script( 'theme_js', get_stylesheet_directory_uri() . '/assets/js/global.js', array( 'jquery' ), '1.0', false );
	wp_enqueue_script( 'loadmore_js', get_stylesheet_directory_uri() . '/assets/js/loadmore.js', array('jquery'),'1.0', false );
    wp_localize_script( 'theme_js', 'global', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
	
    wp_localize_script( 'loadmore_js', 'loadmore', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
}

add_action('wp_enqueue_scripts', 'theme_js');
//add_action( 'admin_enqueue_scripts', 'theme_js' );	// Add admin area script


function wpb_custom_new_menu() {
  register_nav_menu('primary-menu',__( 'Primary Menu' ));
  register_nav_menu('logout-menu',__( 'Logout Menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );



// Remove coupon section from checkout page
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 ); 

add_action( 'wp_ajax_nopriv_post_coupon_code_to_order', 'post_coupon_code_to_order' );
add_action( 'wp_ajax_post_coupon_code_to_order', 'post_coupon_code_to_order' );


function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'My Custom Search',
		'id'            => 'search_page_1',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );

function post_coupon_code_to_order() {
    //print_r($_POST);

    global $woocommerce;
 
    $coupon_code = $_POST['coupon_code'];

    if ($woocommerce->cart->has_discount( $coupon_code ) ) return;
    $woocommerce->cart->add_discount( $coupon_code );
    echo $response_array['status'] = 'success';

/*if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
        $response_array['status'] = 'Already Coupon Applied';
    }*/

    

    //header('Content-type: application/json');
    //return json_encode($response_array);

    /*if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
        //return false;
        $response_array['status'] = 'Already Coupon Applied';
    }
    else {
        $woocommerce->cart->add_discount( $coupon_code );
        $response_array['status'] = 'success';     
    }
    header('Content-type: application/json');
    return json_encode($response_array);*/
    
    
 
    /*if ( $woocommerce->cart->has_discount( $coupon_code ) ) return;
 
    foreach ($woocommerce->cart->cart_contents as $key => $values ) {
 
    // this is your product ID
    $autocoupon = array(79);
 
    if(in_array($values['product_id'],$autocoupon)){    
     
        $woocommerce->cart->add_discount( $coupon_code );
        $woocommerce->show_messages();
    }
    }*/
    //wp_die();

}

// Check already applied coupons
/*add_filter( 'woocommerce_coupons_enabled', 'woocommerce_coupons_enabled_checkout' );
function woocommerce_coupons_enabled_checkout( $coupons_enabled ) {
    global $woocommerce;
    if ( ! empty( $woocommerce->cart->applied_coupons ) ) {
        return false;
    }
    return $coupons_enabled;
}*/



// Add a message at the top of page
add_action( 'woocommerce_before_checkout_form', 'wnd_checkout_message_top', 10 );

function wnd_checkout_message_top( ) {
 echo '<div class="wnd-checkout-message"><h3>For shipments outside the United States, call <a href=tel:"333-444-5555">333-444-5555</a>!</h3>
</div>';
//echo '<div class="wnd-checkout-details"><button type="button" class="btn btn-primary btn_more_details">More Details</button></div>';
}


// Add a text message in bottom of page

add_action( 'woocommerce_after_checkout_form', 'wnd_checkout_message_bottom', 10 );

function wnd_checkout_message_bottom( ) {
 echo '<div class="wnd-checkout-message"><h3>For shipments outside the United States, call <a href=tel:"333-444-5555">333-444-5555</a>!</h3>
</div>';
}

// Remove billing checkout fields
/*add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
 
function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_email']);
    unset($fields['billing']['billing_city']);
    return $fields;
}*/


// Remove fields from WooCommerce checkout page

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    unset($fields['order']['order_comments']);
    unset($fields['shipping']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_email']);
	
    // Setting placeholder texts for Email, Name and Last Name
    /*$fields['billing']['billing_email']['placeholder'] = "E-mail address";
    $fields['billing']['billing_first_name']['required'] = false;
    $fields['billing']['billing_first_name']['placeholder'] = "First name";
   
    $fields['billing']['billing_last_name']['required'] = false;
    $fields['billing']['billing_last_name']['placeholder'] = "Last name";
     
    // Setting form field classes
    $fields['billing']['billing_email']['class'] = array('form-row-wide');
    $fields['billing']['billing_first_name']['class'] = array('form-row-wide');
    $fields['billing']['billing_last_name']['class'] = array('form-row-wide');*/
	 
    return $fields;
}


/**
 * Add the field to the checkout page
 */
add_action('woocommerce_after_order_notes', 'customise_checkout_field_card');
 
function customise_checkout_field_card($checkout)
{
	echo '<div id="customise_checkout_field"><h4>' . __('Card Number') . '</h4>';
	woocommerce_form_field('card_number', array(
		'type' => 'text',
		'class' => array(
			'my-field-class form-row-wide'
		) ,
		//'label' => __('Card Number') ,
		'placeholder' => __('Enter Card Number') ,
		'required' => true,
	) , $checkout->get_value('card_number'));
	echo '</div>';
}


add_action('woocommerce_after_order_notes', 'customise_checkout_field_date');
 
function customise_checkout_field_date($checkout)
{
	echo '<div id="customise_checkout_field"><h4>' . __('Date') . '</h4>';
	woocommerce_form_field('card_date', array(
		'type' => 'text',
		'class' => array(
			'my-field-class form-row-wide'
		) ,
		//'label' => __('Card Date') ,
		'placeholder' => __('Enter Card Expiry Date') ,
		'required' => true,
	) , $checkout->get_value('card_date'));
	echo '</div>';
}


add_action('woocommerce_after_order_notes', 'customise_checkout_field_cardholder');
 
function customise_checkout_field_cardholder($checkout)
{
	echo '<div id="customise_checkout_field"><h4>' . __('Card Holder') . '</h4>';
	woocommerce_form_field('cardholder_name', array(
		'type' => 'text',
		'class' => array(
			'my-field-class form-row-wide'
		) ,
		//'label' => __('Card Holder') ,
		'placeholder' => __('Enter Card Holder Name') ,
		'required' => true,
	) , $checkout->get_value('cardholder_name'));
	echo '</div>';
}


 /*add_filter('woocommerce_after_checkout_validation', 'additional_validation');
 function additional_validation($fields)
 {
     // bypass all error returned
     wc_clear_notices();
 }*/



/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');

function my_custom_checkout_field_process() {
    // Check if set, if its not set add an error.
     if ( ! $_POST['card_number'] ) {
		  wc_add_notice( __( 'Your card number is required.' ), 'error' );
		}else if ( ! $_POST['card_date'] )
		{
        wc_add_notice( __( 'cardholder date is compulsory. Please enter a value' ), 'error' );
	    } else if ( ! $_POST['cardholder_name'] )
			{
	        wc_add_notice( __( 'cardholder name is compulsory. Please enter a value' ), 'error' );
	    }
}


/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta2' );

function my_custom_checkout_field_update_order_meta2( $order_id ) {
	$user_id = get_current_user_id();
    if ( ! empty( $_POST['card_number'] ) && ! empty( $_POST['card_date'] ) && ! empty( $_POST['cardholder_name'] ) ) {
        update_post_meta( $order_id, 'card_number', sanitize_text_field( $_POST['card_number'] ) );
        update_post_meta( $order_id, 'card_date', sanitize_text_field( $_POST['card_date'] ) );
        update_post_meta( $order_id, 'cardholder_name', sanitize_text_field( $_POST['cardholder_name'] ) );

	    update_user_meta( $user_id, 'cardnumber', $_POST['card_number'] );
	    update_user_meta( $user_id, 'carddate', $_POST['card_date'] );
	    update_user_meta( $user_id, 'cardholdername', $_POST['cardholder_name'] );
    }
}


/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta4', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta4($order){
    echo '<p><strong>'.__('cardholder name').':</strong> <br/>' . get_post_meta( $order->id, 'cardholder_name', true ) . '</p>';
    echo '<p><strong>'.__('cardholder Date').':</strong> <br/>' . get_post_meta( $order->id, 'card_date', true ) . '</p>';
    echo '<p><strong>'.__('cardholder number').':</strong> <br/>' . get_post_meta( $order->id, 'card_number', true ) . '</p>';
}


// Cart Page Settings

/* Add message before or after the cart
------------------------------------------------------------ */
// add_action( 'woocommerce_after_cart', 'wnd_before_cart' );
add_action( 'woocommerce_before_cart', 'wnd_before_cart' );

function wnd_before_cart()	{
echo '<strong>Your message goes here</strong>';
}


add_action( 'woocommerce_after_cart', 'wnd_after_cart' );
function wnd_after_cart()	{
echo '<strong>Your message goes here</strong>';
}


// Redirect custom checkout
add_filter('woocommerce_get_checkout_url', 'dj_redirect_checkout');

    function dj_redirect_checkout($url) {
         global $woocommerce;
         if(is_cart()){
              $checkout_url = 'http://localhost/wordpress/mycheckout/';
         }
         else { 
         //other url or leave it blank.
         }
         return  $checkout_url; 
    }


// Add custom field in general tab in woocommerce setting page
add_filter( 'woocommerce_general_settings', 'add_order_number_start_setting' );

function add_order_number_start_setting( $settings ) {



  $updated_settings = array();



  foreach ( $settings as $section ) {



    // at the bottom of the General Options section

    if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&

       isset( $section['type'] ) && 'sectionend' == $section['type'] ) {



      $updated_settings[] = array(

        'name'     => __( 'Order Number Start', 'wc_seq_order_numbers' ),

        'desc_tip' => __( 'The starting number for the incrementing portion of the order numbers, unless there is an existing order with a higher number.', 'wc_seq_order_numbers' ),

        'id'       => 'woocommerce_order_number_start',

        'type'     => 'text',

        'css'      => 'min-width:300px;',

        'std'      => '1',  // WC < 2.0

        'default'  => '1',  // WC >= 2.0

        'desc'     => __( 'Sample order number: AA-20130219-000-ZZ', 'wc_seq_order_numbers' ),

      );

    }



    $updated_settings[] = $section;

  }

  return $updated_settings;

}



// Add product custom product tab
add_filter( 'woocommerce_product_tabs', 'custom_product_tab' );
function custom_product_tab( $tabs ) {

    $tabs['custom_tab'] = array(
        'title'     => __( 'Custom Data', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'custom_data_product_tab_content'
    );

    return $tabs;
}

// The custom product tab content
function custom_data_product_tab_content(){
    global $post;

    // 1. Get the data
    $select = get_post_meta( $post->ID, '_select', true );
    $checkbox = get_post_meta( $post->ID, '_checkbox', true );
    $cf_type = get_post_meta( $post->ID, '_custom_field_type', true );

    // 2. Display the data
    $output = '<div class="custom-data">';

    if( ! empty( $select ) )
        $output .= '<p>'. __('Select field: ').'</span style="color:#96588a;">'.$select.'</span></p>';
    if( ! empty( $checkbox ) )
        $output .= '<p>'. __('Checkbox field: ').'</span style="color:#96588a;">'.$checkbox.'</span></p>';
    if( ! empty( $cf_type[0] ) )
        $output .= '<p>'. __('Number field 1: ').'</span style="color:#96588a;">'.$cf_type[0].'</span></p>';
    if( ! empty( $cf_type[1] ) )
        $output .= '<p>'. __('Number field 2: ').'</span style="color:#96588a;">'.$cf_type[1].'</span></p>';

    echo $output.'</div>';
}


// add user profile fields custom

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("Card Detail", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="cardnumber"><?php _e("Card Number"); ?></label></th>
        <td>
            <input type="text" name="cardnumber" id="cardnumber" value="<?php echo esc_attr( get_the_author_meta( 'cardnumber', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your card number."); ?></span>
        </td>
    </tr>
    <tr>
        <th><label for="date"><?php _e("Date"); ?></label></th>
        <td>
            <input type="text" name="carddate" id="carddate" value="<?php echo esc_attr( get_the_author_meta( 'carddate', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your card date."); ?></span>
        </td>
    </tr>
    <tr>
    <th><label for="cardholdername"><?php _e("Card Holder"); ?></label></th>
        <td>
            <input type="text" name="cardholdername" id="cardholdername" value="<?php echo esc_attr( get_the_author_meta( 'cardholdername', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Enter card holder name."); ?></span>
        </td>
    </tr>
    </table>
<?php }


add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta( $user_id, 'cardnumber', $_POST['cardnumber'] );
    update_user_meta( $user_id, 'carddate', $_POST['carddate'] );
    update_user_meta( $user_id, 'cardholdername', $_POST['cardholdername'] );
}



// Redirect custom thank you
 
/*add_action( 'woocommerce_thankyou', 'bbloomer_redirectcustom');
 
function bbloomer_redirectcustom( $order_id ){
    $order = new WC_Order( $order_id );
 
    $url = 'http://localhost/wordpress/mythankyou/';    //http://yoursite.com/custom-url
 
    if ( $order->status != 'failed' ) {
        wp_redirect($url);
        exit;
    }
}*/





// run the action 
do_action( 'woocommerce_before_checkout_billing_form', $wccs_custom_checkout_field_pro );



// define the woocommerce_before_checkout_billing_form callback 
function action_woocommerce_before_checkout_billing_form( $wccs_custom_checkout_field_pro ) { 
    // make action magic happen here... 
}; 
         
// add the action 
add_action( 'woocommerce_before_checkout_billing_form', 'action_woocommerce_before_checkout_billing_form', 10, 1 );



//show attributes after summary in product single view
add_action('woocommerce_single_product_summary', function() {
    //template for this is in storefront-child/woocommerce/single-product/product-attributes.php
    global $product;
    //echo $product->list_attributes();
    $attributes = $product->get_attributes();
    //print_r($attributes);
    foreach ( $attributes as $attribute ) :

        $values = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'names' ) );
        print_r($values);

        //echo wc_attribute_label( $attribute['name'] );

    endforeach;




}, 25);


function custom_meta_box_markup()
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <label for="meta-box-text">Text</label>
            <input name="meta-box-text" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-text", true); ?>">

    <?php  
}

function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Custom Meta Box", "custom_meta_box_markup", "post", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");



function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    

    if(isset($_POST["meta-box-text"]))
    {
        $meta_box_text_value = $_POST["meta-box-text"];
    }   
    update_post_meta($post_id, "meta-box-text", $meta_box_text_value);

    
}

add_action("save_post", "save_custom_meta_box", 10, 3);



// programmatically create some basic pages, and then set Home and Blog
// setup a function to check if these pages exist
function the_slug_exists($post_name) {
    global $wpdb;
    if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
        return true;
    } else {
        return false;
    }
}
// create the blog page
if (isset($_GET['activated']) && is_admin()){
    $blog_page_title = 'Blog';
    $blog_page_content = 'This is blog page placeholder. Anything you enter here will not appear in the front end, except for search results pages.';
    $blog_page_check = get_page_by_title($blog_page_title);
    $blog_page = array(
        'post_type' => 'page',
        'post_title' => $blog_page_title,
        'post_content' => $blog_page_content,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_slug' => 'blog'
    );
    if(!isset($blog_page_check->ID) && !the_slug_exists('blog')){
        $blog_page_id = wp_insert_post($blog_page);
    }
}
// create the site map page
if (isset($_GET['activated']) && is_admin()){
    $sitemap_page_title = 'Site Map';
    $sitemap_page_check = get_page_by_title($sitemap_page_title);
    $sitemap_page = array(
        'post_type' => 'page',
        'post_title' => $sitemap_page_title,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_slug' => 'site-map'
    );
    if(!isset($sitemap_page_check->ID) && !the_slug_exists('site-map')){
        $sitemap_page_id = wp_insert_post($sitemap_page);
    }
}
// change the Sample page to the home page
if (isset($_GET['activated']) && is_admin()){
    $home_page_title = 'Home';
    $home_page_content = '';
    $home_page_check = get_page_by_title($home_page_title);
    $home_page = array(
        'post_type' => 'page',
        'post_title' => $home_page_title,
        'post_content' => $home_page_content,
        'post_status' => 'publish',
        'post_author' => 1,
        'ID' => 2,
        'post_slug' => 'home'
    );
    if(!isset($home_page_check->ID) && !the_slug_exists('home')){
        $home_page_id = wp_insert_post($home_page);
    }
}
if (isset($_GET['activated']) && is_admin()){
    // Set the blog page
    $blog = get_page_by_title( 'Blog' );
    update_option( 'page_for_posts', $blog->ID );

    // Use a static front page
    $front_page = 2; // this is the default page created by WordPress
    update_option( 'page_on_front', $front_page );
    update_option( 'show_on_front', 'page' );
}




//define your page content in below array
$new_page = array(
    'slug' => 'this-is-my-new-page',
    'title' => 'Write a Headline that Captivates',
    'content' => "Enter the body Content for your Page here"
);
 
$new_page_id = wp_insert_post( array(
    'post_title' => $new_page['title'],
    'post_type'     => 'page',
    'post_name'  => $new_page['slug'],
    'comment_status' => 'closed',
    'ping_status' => 'closed',
    'post_content' => $new_page['content'],
    'post_status' => 'publish',
    'post_author' => 1,
    'menu_order' => 0
));



// Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'mv_add_meta_boxes' );
if ( ! function_exists( 'mv_add_meta_boxes' ) )
{
    function mv_add_meta_boxes()
    {
        add_meta_box( 'mv_other_fields', __('My Field','woocommerce'), 'mv_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
    }
}

// Adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'mv_add_other_fields_for_packaging' ) )
{
    function mv_add_other_fields_for_packaging()
    {
        global $post;

        $meta_field_data = get_post_meta( $post->ID, '_my_field_slug', true ) ? get_post_meta( $post->ID, '_my_field_slug', true ) : '';

        echo '<input type="hidden" name="mv_other_meta_field_nonce" value="' . wp_create_nonce() . '">
        <p style="border-bottom:solid 1px #eee;padding-bottom:13px;">
            <input type="text" style="width:250px;";" name="my_field_name" placeholder="' . $meta_field_data . '" value="' . $meta_field_data . '"></p>';

    }
}

// Save the data of the Meta field
add_action( 'save_post', 'mv_save_wc_order_other_fields', 10, 1 );
if ( ! function_exists( 'mv_save_wc_order_other_fields' ) )
{

    function mv_save_wc_order_other_fields( $post_id ) {

        // We need to verify this with the proper authorization (security stuff).

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'mv_other_meta_field_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'mv_other_meta_field_nonce' ];

        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST[ 'post_type' ] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        // --- Its safe for us to save the data ! --- //

        // Sanitize user input  and update the meta field in the database.
        update_post_meta( $post_id, '_my_field_slug', $_POST[ 'my_field_name' ] );
    }
}



// Add the field to the product
add_action('woocommerce_before_add_to_cart_button', 'my_custom_product_field');
function my_custom_product_field() {
    echo '<div id="my_custom_field">
        <label>' . __( 'My Field') . ' </label>
        <input type="text" name="my_field_name" value="">
    </div><br>';
}

// Store custom field
add_action( 'woocommerce_add_cart_item_data', 'save_my_custom_product_field', 10, 2 );
function save_my_custom_product_field( $cart_item_data, $product_id ) {
    if( isset( $_REQUEST['my_field_name'] ) ) {
        $cart_item_data[ 'my_field_name' ] = $_REQUEST['my_field_name'];
        // below statement make sure every add to cart action as unique line item
        $cart_item_data['unique_key'] = md5( microtime().rand() );
        WC()->session->set( 'my_order_data', $_REQUEST['my_field_name'] );
    }
    return $cart_item_data;
}

// Add a hidden field with the correct value to the checkout
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );
function my_custom_checkout_field( $checkout ) {
    $value = WC()->session->get( 'my_order_data' );
    echo '<div id="my_custom_checkout_field">
            <input type="hidden" class="input-hidden" name="my_field_name" id="my_field_name" value="' . $value . '">
    </div>';
}

// Save the order meta with hidden field value
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['my_field_name'] ) ) {
        update_post_meta( $order_id, '_my_field_slug', $_POST['my_field_name'] );
    }
}

// Display field value on the order edit page (not in custom fields metabox)
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta($order){
    $my_custom_field = get_post_meta( $order->id, '_my_field_slug', true );
    if ( ! empty( $my_custom_field ) ) {
        echo '<p><strong>'. __("My Field", "woocommerce").':</strong> ' . get_post_meta( $order->id, '_my_field_slug', true ) . '</p>';
    }
}

// Render meta on cart and checkout
add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout', 10, 2 );
function render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {
    $custom_items = array();
    if( !empty( $cart_data ) ) $custom_items = $cart_data;

    if( isset( $cart_item['my_field_name'] ) )
        $custom_items[] = array( "name" => 'My Field', "value" => $cart_item['my_field_name'] );

    return $custom_items;
}

// Add the information as meta data so that it can be seen as part of the order
add_action('woocommerce_add_order_item_meta','add_values_to_order_item_meta', 10, 3 );
function add_values_to_order_item_meta( $item_id, $cart_item, $cart_item_key ) {
    // lets add the meta data to the order (with a label as key slug)
    if( ! empty( $cart_item['my_field_name'] ) )
        wc_add_order_item_meta($item_id, __('My field label name'), $cart_item['my_field_name'], true);
}


    function hide_update_msg_non_admins(){
     if (!current_user_can( 'manage_options' )) { // non-admin users
            echo '<style>#setting-error-tgmpa>.updated settings-error notice is-dismissible, .update-nag, .updated { display: none; }</style>';
        }
    }
    add_action( 'admin_head', 'hide_update_msg_non_admins');

// Add a admin menu page

add_action( 'admin_menu', 'my_plugin_menu11' );

/** Step 1. */
function my_plugin_menu11() {
    add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}

/** Step 3. */
function my_plugin_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo '<p>Here is where the form would go if I actually had options.</p>';
    echo '</div>';
}


/*function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
        __( 'Custom Menu Title', 'textdomain' ),
        'custom menu',
        'manage_options',
        'myplugin/myplugin-admin.php',
        '',
        plugins_url( 'myplugin/images/icon.png' ),
        6
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );*/


add_action('admin_menu', 'my_menu_pages');
function my_menu_pages(){
    add_menu_page('My menu page Title', 'My Control', 'manage_options', 'my-menu', 'my_menu_output' );
    add_submenu_page('my-menu', 'Submenu Page Title 1', 'Page 1', 'manage_options', 'my-menu1', 'my_menu1' );
    add_submenu_page('my-menu', 'Submenu Page Title2', 'Page 2', 'manage_options', 'my-menu2', 'my_menu2');
}

function my_menu_output()
{
    include ('test-page.php');
}

function my_menu1()
{
    include ('test-page1.php');
}

function my_menu2()
{
    include ('test-page2.php');
}


//require get_template_directory() . '/inc/customizer1.php';


/*add_filter('get_search_form', 'my_search_form');
function my_search_form($html) {
 //$html = 'Hello search page'; // here you can either customize the WordPress builtin search form or totally overwrite it
 //return $html;
 //print_r($_GET);
}*/


function wporg_simple_role()
{
    add_role(
        'simple_role',
        'Simple Role',
        [
            'read'         => true,
            'edit_posts'   => true,
            'upload_files' => true,
        ]
    );
}
 
// add the simple_role
add_action('init', 'wporg_simple_role');



add_filter( 'plugin_action_links', 'ttt_wpmdr_add_action_plugin', 10, 5 );
function ttt_wpmdr_add_action_plugin( $actions, $plugin_file ) 
{
	static $plugin;

	if (!isset($plugin))
		$plugin = 'test-plugin/test-plugin.php';	//plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {

			$settings = array('settings' => '<a href="options-general.php#redirecthere">' . __('Settings', 'General') . '</a>');
			$site_link = array('support' => '<a href="http://domain.com" target="_blank">Support</a>');
		
    			$actions = array_merge($settings, $actions);
				$actions = array_merge($site_link, $actions);
			
		}
		
		return $actions;
}



add_filter( 'plugin_action_links_test-cron/test-cron.php', 'my_plugin_action_links' );

function my_plugin_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=gpaisr') ) .'">Settings</a>';
   $links[] = '<a href="http://wp-buddy.com" target="_blank">Supports</a>';
   return $links;
}


add_action( 'add_meta_boxes', 'add_events_metaboxes' );

function add_events_metaboxes() {
	add_meta_box(
		'wpt_events_location',
		'Event Location',
		'wpt_events_location',
		'page',
		'side',
		'default'
	);
}

function wpt_events_location()
{
	echo '<label>' . __( 'My Field') . ' </label>'; ?>
	<p>
		<input type="text" name="location" value="<?php echo ''; ?>" /> 
	</p>
<?php
}





function testing (){
	global $wpdb;
	$random_number = wp_rand( 1, 100 );
	$user_name = 'user' . $random_number;
	$user_email = 'email@server' . $random_number . '.com';
	$user_id = username_exists( $user_name );
	
if ( !$user_id and email_exists($user_email) == false ) {
	$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
	$user_id = wp_create_user( $user_name, $random_password, $user_email );
} else {
	$random_password = __('User already exists.  Password inherited.');
}
$lastid = $wpdb->insert_id;
$user_id = get_current_user_id();
$cars = array
  (
  array("Field_1",22,18),
  array("Field_2",15,13),
  array("Field_3",5,2),
  array("Field_4",17,15)
  );
$website = 'test' . $random_number . '.com';
$user_data = wp_update_user( array( 'ID' => $lastid , 'user_url' => $website ) );
//update_user_meta($lastid , 'user_data_test_' . $user_id, $cars);
add_user_meta( $lastid, 'user_data_test_', $cars, true);
}
add_action('after_setup_theme', 'testing');

function your_function( $user_login, $user ) {
	$user_id = get_current_user_id();
    update_user_meta($user_id , 'user_email', 'laxman091@gmail.com');
}
add_action('wp_login', 'your_function', 10, 2);


//remove_action('load-update-core.php','wp_update_plugins');
//add_filter('pre_site_transient_update_plugins','__return_null');

/*function cvf_remove_wp_core_updates(){
    global $wp_version;
    return(object) array('last_checked' => time(),'version_checked' => $wp_version);
}

// Core Notifications
add_filter('pre_site_transient_update_core','cvf_remove_wp_core_updates');
// Plugin Notifications
add_filter('pre_site_transient_update_plugins','cvf_remove_wp_core_updates');
// Theme Notifications
add_filter('pre_site_transient_update_themes','cvf_remove_wp_core_updates');*/

function hide_update_notice_to_all_but_admin_users() 
{
    if (!current_user_can('update_core')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
}
add_action( 'admin_head', 'hide_update_notice_to_all_but_admin_users', 1 );

function remove_core_updates1(){
    global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates1');
add_filter('pre_site_transient_update_plugins','remove_core_updates1');
add_filter('pre_site_transient_update_themes','remove_core_updates1');


add_action('after_setup_theme','remove_core_updates22');
function remove_core_updates22()
{
if(! current_user_can('update_core')){return;}
add_action('init', create_function('$a',"remove_action( 'init', 'wp_version_check' );"),2);
add_filter('pre_option_update_core','__return_null');
add_filter('pre_site_transient_update_core','__return_null');
}

remove_action('load-update-core.php','wp_update_plugins');
add_filter('pre_site_transient_update_plugins','__return_null');


function hide_update_notice1() {
get_currentuserinfo();
if (!current_user_can('manage_options')) {
remove_action( 'admin_notices', 'update_nag', 3 );
}
}

add_action( 'admin_notices', 'hide_update_notice1', 1 );


// Hide the "Please update now" notification
function hide_update_notice2() {
   remove_action( 'admin_notices', 'update_nag', 3 );
}
add_action( 'admin_notices', 'hide_update_notice2', 1 );


// Hide the "Please update now" notification
function hide_update_notice3() {
    get_currentuserinfo();
    if (!current_user_can('manage_options')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
}
add_action( 'admin_notices', 'hide_update_notice3', 1 );


if ($user_login != "username") {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }



add_action( 'wp_before_admin_bar_render', 'wpse161696_toolbar_menu' );
add_action( 'admin_menu', 'wpse161696_updates' );

function wpse161696_toolbar_menu() { // Remove update menu item from the toolbar
    global $wp_admin_bar;
    $wp_admin_bar -> remove_menu( 'updates' );
}

function wpse161696_updates() { // Remove all updating related functions
    remove_submenu_page( 'index.php', 'update-core.php' ); // Remove Update submenu
    // Redirect to Dashboard if update page is accessed
    global $pagenow;
    $page = array(
        'update-core.php',
        'update.php',
        'update.php?action=upgrade-plugin'
        );
    if ( in_array( $pagenow, $page, true ) ) {
        wp_redirect( admin_url( 'index.php' ), 301 );
        // wp_die( 'Updates are disabled.' ); // An error message can be displayed instead
        exit;
    }
}


// Remove update notifications
/*function remove_update_notifications( $value ) {

    if ( isset( $value ) && is_object( $value ) ) {
        unset( $value->response[ 'lifterlms/lifterlms.php' ] );
    }

    return $value;
}
add_filter( 'site_transient_update_plugins', 'remove_update_notifications' );*/


//Remove a top level admin menu.
function remove_menus(){  

  /*remove_menu_page( 'index.php' );                  //Dashboard  
  remove_menu_page( 'edit.php' );                   //Posts  
  remove_menu_page( 'upload.php' );                 //Media  
  remove_menu_page( 'edit.php?post_type=page' );    //Pages  
  remove_menu_page( 'edit-comments.php' );          //Comments  
  remove_menu_page( 'themes.php' );                 //Appearance  
  remove_menu_page( 'plugins.php' );                //Plugins  
  remove_menu_page( 'users.php' );                  //Users  
  remove_menu_page( 'tools.php' );                  //Tools  
  remove_menu_page( 'options-general.php' );        //Settings  
  //Remove third party plugins admin menu items
  remove_menu_page( 'edit.php?post_type=shop_order' );//for custom posts
  remove_menu_page( 'my-menu' );//for custom plugin's page*/  

}  
add_action( 'admin_menu', 'remove_menus' ); 

//How to hide sidebar widgets in all pages except Hompage
add_action('wp_head', function(){       
    if ( is_home() ) return; // on home it's okay

    //unregister_sidebar('sidebar-1');
});


function misha_loadmore_ajax_handler(){
 
	// prepare our arguments for the query
	$args = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
	$args['post_status'] = 'publish';
 
	// it is always better to use WP_Query but not here
	query_posts( $args );
 
	if( have_posts() ) :
 
		// run the loop
		while( have_posts() ): the_post();
 
			// look into your theme code how the posts are inserted, but you can use your own HTML of course
			// do you remember? - my example is adapted for Twenty Seventeen theme
			get_template_part( 'template-parts/post/content', get_post_format() );
			// for the test purposes comment the line above and uncomment the below one
			// the_title();
 
 
		endwhile;
 
	endif;
	die; // here we exit the script and even no wp_reset_query() required!
}
 
 
 
add_action('wp_ajax_loadmore', 'misha_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'misha_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}


if ( ! wp_next_scheduled( 'wpdocs_task_hook' ) ) {
    wp_schedule_event( time(), 'hourly', 'wpdocs_task_hook' );
}
add_action( 'wpdocs_task_hook', 'wpdocs_task_function' ); // 'wpdocs_task_hook` is registered when the event is scheduled
 
/**
 * Send an alert by email.
 */
function wpdocs_task_function() {
wp_mail( 'laxman@netscriptindia.com', 'Automatic email', 'Automatic scheduled email from WordPress.');	// working

    // the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("laxman@netscriptindia.com","Automatic email",$msg);	// working
}


function change_my_logo(){
wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/css/style-login.css' );
//wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/style-login.js' );
//http://localhost/wordpress/wp-content/themes/twentyseventeen-child/assets/images/espresso.jpeg
}
add_action('login_enqueue_scripts', 'change_my_logo');

function load_custom_wp_admin_style() {
        wp_register_style( 'custom_wp_admin_css', get_stylesheet_directory_uri() . '/assets/css/style-admin.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );


if (wp_mkdir_p('http://localhost/wordpress/wp-content/themes/test123/test')) {
  echo 'It worked! Now look for a directory named';
}

function my_login_logo_url() {
	//return 'www.google.com';
	return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Your Site Name and Info';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a {
        background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/mypic.jpg);
		/*height:65px;
		width:320px;
		background-size: 320px 65px;
		background-repeat: no-repeat;
        padding-bottom: 30px;*/
		border-radius: 50%;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

    add_action( 'admin_menu', 'linked_url' );
    function linked_url() {
    add_menu_page( 'linked_url', 'External link', 'read', 'my_slug', '', 'dashicons-text', 1 );
    }

    add_action( 'admin_menu' , 'linkedurl_function' );
    function linkedurl_function() {
    global $menu;
    $menu[1][2] = "http://www.example.com";
    }

add_action( 'admin_menu', 'register_custom_menu_link' );
/**
 * @author    Brad Dalton
 * @example   http://wpsites.net/wordpress-admin/add-top-level-custom-admin-menu-link-in-dashboard-to-any-url/
 * @copyright 2014 WP Sites
 */
function register_custom_menu_link(){
    add_menu_page( 'custom menu link', 'Your Menu Link', 'manage_options', 'any-url', 'wpsites_custom_menu_link', 'dashicons-external', 0 ); 
}

function wpsites_custom_menu_link(){
    wp_redirect( 'http://www.example.com', 301 ); 
	exit;
}

add_action('admin_menu', 'example_admin_menu');
 
/**
* add external link to Tools area
*/
function example_admin_menu() {
	/*index.php => Dashboard
	edit.php => Posts
	upload.php => Media
	link-manager.php => Links
	edit.php?post_type=page => Pages
	edit-comments.php => Comments
	themes.php => Appearance
	plugins.php => Plugins
	users.php => Users
	tools.php => Tools
	options-general.php => Settings*/
    global $submenu;
    $url = 'http://www.example.com/';
    $submenu['tools.php'][] = array('Example', 'manage_options', $url);
}

add_action('admin_bar_menu', 'add_toolbar_items', 100);
function add_toolbar_items($admin_bar){
    $admin_bar->add_menu( array(
        'id'    => 'my-item',
        'title' => 'My Item',
        'href'  => '#',
        'meta'  => array(
            'title' => __('My Item'),            
        ),
    ));
    $admin_bar->add_menu( array(
        'id'    => 'my-sub-item',
        'parent' => 'my-item',
        'title' => 'My Sub Menu Item',
        'href'  => '#',
        'meta'  => array(
            'title' => __('My Sub Menu Item'),
            'target' => '_blank',
            'class' => 'my_menu_item_class'
        ),
    ));
    $admin_bar->add_menu( array(
        'id'    => 'my-second-sub-item',
        'parent' => 'my-item',
        'title' => 'My Second Sub Menu Item',
        'href'  => '#',
        'meta'  => array(
            'title' => __('My Second Sub Menu Item'),
            'target' => '_blank',
            'class' => 'my_menu_item_class'
        ),
    ));
}


// add a link to the WP Toolbar
function custom_toolbar_link($wp_admin_bar) {
    $args = array(
        'id' => 'wplink',
        'title' => 'Search Google', 
        'href' => 'https://www.google.com', 
        'meta' => array(
            'class' => 'wplink', 
            'title' => 'Search Google Tutorials'
            )
    );
    $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'custom_toolbar_link', 999);


/*
* add a group of links under a parent link
*/
 
// Add a parent shortcut link
 
function custom_toolbar_link2($wp_admin_bar) {
    $args = array(
        'id' => 'wpbeginner',
        'title' => 'My Link', 
        'href' => 'https://www.wpbeginner.com', 
        'meta' => array(
            'class' => 'wpbeginner', 
            'title' => 'Visit WPBeginner'
            )
    );
    $wp_admin_bar->add_node($args);
 
// Add the first child link 
     
    $args = array(
        'id' => 'wpbeginner-guides',
        'title' => 'WPBeginner Guides', 
        'href' => 'http://www.wpbeginner.com/category/beginners-guide/',
        'parent' => 'wpbeginner', 
        'meta' => array(
            'class' => 'wpbeginner-guides', 
            'title' => 'Visit WordPress Beginner Guides'
            )
    );
    $wp_admin_bar->add_node($args);
 
// Add another child link
$args = array(
        'id' => 'wpbeginner-tutorials',
        'title' => 'WPBeginner Tutorials', 
        'href' => 'http://www.wpbeginner.com/category/wp-tutorials/',
        'parent' => 'wpbeginner', 
        'meta' => array(
            'class' => 'wpbeginner-tutorials', 
            'title' => 'Visit WPBeginner Tutorials'
            )
    );
    $wp_admin_bar->add_node($args);
 
// Add a child link to the child link
 
$args = array(
        'id' => 'wpbeginner-themes',
        'title' => 'WPBeginner Themes', 
        'href' => 'http://www.wpbeginner.com/category/wp-themes/',
        'parent' => 'wpbeginner-tutorials', 
        'meta' => array(
            'class' => 'wpbeginner-themes', 
            'title' => 'Visit WordPress Themes Tutorials on WPBeginner'
            )
    );
    $wp_admin_bar->add_node($args);
 
}
 
add_action('admin_bar_menu', 'custom_toolbar_link2', 999);

function elh_insert_into_db() {
 
    global $wpdb;
    // creates my_table in database if not exists
    $table = $wpdb->prefix . "my_table"; 
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `name` text NOT NULL,
    UNIQUE (`id`)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    // starts output buffering
    ob_start();
    ?>
    <form action="#v_form" method="post" id="v_form">
        <label for="visitor_name"><h3>Hello there! What is your name?</h3></label>
        <input type="text" name="visitor_name" id="visitor_name" />
        <input type="submit" name="submit_form" value="submit" />
    </form>
    <?php
    $html = ob_get_clean();
    // does the inserting, in case the form is filled and submitted
    if ( isset( $_POST["submit_form"] ) && $_POST["visitor_name"] != "" ) {
        $table = $wpdb->prefix."my_table";
        $name = strip_tags($_POST["visitor_name"], "");
        $wpdb->insert( 
            $table, 
            array( 
                'name' => $name
            )
        );
        $html = "<p>Your name <strong>$name</strong> was successfully recorded. Thanks!!</p>";
    }
    // if the form is submitted but the name is empty
    if ( isset( $_POST["submit_form"] ) && $_POST["visitor_name"] == "" )
        $html .= "<p>You need to fill the required fields.</p>";
    // outputs everything
    return $html;
     
}
// adds a shortcode you can use: [insert-into-db]
add_shortcode('elh-db-insert', 'elh_insert_into_db');

function shr_extra_profile_fields( $user ) {
//print_r($_POST);
//die(); 
    $profile_pic = ($user!=='add-new-user') ? get_user_meta($user->ID, 'shr_pic', true): false;
 
    if( !empty($profile_pic) ){
        $image = wp_get_attachment_image_src( $profile_pic, 'thumbnail' );
		


 
    }
	
	$months 	= array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
    $default	= array( 'day' => 1, 'month' => 'Jnuary', 'year' => 1950, );
    $birth_date = wp_parse_args( get_the_author_meta( 'birth_date', $user->ID ), $default );
	
	?>
 
    <table class="form-table fh-profile-upload-options">
        <tr>
            <th>
                <label for="image"><?php _e('Main Profile Image', 'shr') ?></label>
            </th>
 
            <td>
                
                <input type="hidden" class="button" name="shr_image_id" id="shr_image_id" value="<?php echo !empty($profile_pic) ? $profile_pic : ''; ?>" />
                <img id="shr-img" src="<?php echo !empty($profile_pic) ? $image[0] : ''; ?>" style="<?php echo  empty($profile_pic) ? 'display:none;' :'' ?> max-width: 100px; max-height: 100px;" />
				<input type="button" data-id="shr_image_id" data-src="shr-img" class="button shr-image" name="shr_image" id="shr-image" value="Upload" style="    margin-top: 30px;"/>
            </td>
        </tr>
		   	 <tr>
   		 <th><label for="birth-date-day">Birth date</label></th>
   		 <td>
   			 <select id="birth-date-day" name="birth_date[day]"><?php
   				 for ( $i = 1; $i <= 31; $i++ ) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $i, selected( $birth_date['day'], $i, false ) );
   				 }
   			 ?></select>
   			 <select id="birth-date-month" name="birth_date[month]"><?php
   				 foreach ( $months as $month ) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $month, selected( $birth_date['month'], $month, false ) );
   				 }
   			 ?></select>
   			 <select id="birth-date-year" name="birth_date[year]"><?php
   				 for ( $i = 1950; $i <= 2015; $i++ ) {
   					 printf( '<option value="%1$s" %2$s>%1$s</option>', $i, selected( $birth_date['year'], $i, false ) );
   				 }
   			 ?></select>
   		 </td>
   	 </tr>
		<tr>
			<th><label for="twitter">Twitter</label></th>

			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Twitter username.</span>
			</td>
		</tr>
				<tr>
			<th><label for="instagram">Instagram</label></th>

			<td>
				<input type="text" name="instagram" id="instagram" value="<?php echo esc_attr( get_the_author_meta( 'instagram', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Instagram username.</span>
			</td>
		</tr>
				<tr>
			<th><label for="facebook">Facebook</label></th>

			<td>
				<input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Facebook username.</span>
			</td>
		</tr>
    </table><?php
 
}
add_action( 'show_user_profile', 'shr_extra_profile_fields' );
add_action( 'edit_user_profile', 'shr_extra_profile_fields' );
add_action( 'user_new_form', 'shr_extra_profile_fields' );

function save_extra_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    /* Edit the following lines according to your set fields */
    update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
    update_usermeta( $user_id, 'instagram', $_POST['instagram'] );
    update_usermeta( $user_id, 'facebook', $_POST['facebook'] );
}

add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );


function shr_profile_update($user_id){
 
    if( current_user_can('edit_users') ){
        $profile_pic = empty($_POST['shr_image_id']) ? '' : $_POST['shr_image_id'];
        update_user_meta($user_id, 'shr_pic', $profile_pic);
    }
 
}
add_action('profile_update', 'shr_profile_update');
add_action('user_register', 'shr_profile_update');


add_filter( 'wp_login_errors', 'my_logout_message' );

function my_logout_message( $errors ){

    if ( isset( $errors->errors['loggedout'] ) ){
        $errors->errors['loggedout'][0] = 'This is the <strong style="color:red;">logged out</strong> message.';
    }

    return $errors;
}

add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

function wti_loginout_menu_link( $items, $args ) {
 if ($args->theme_location == 'primary-menu') {
    if (is_user_logged_in()) {
       $items .= '<li class="right"><a href="'. wp_logout_url(home_url()) .'">'. __("Logout") .'</a></li>';
    } else {
       $items .= '<li class="right"><a href="'. wp_login_url(get_permalink()) .'">'. __("Login") .'</a></li>';
    }
 }
 return $items;
}


/**
 * Add custom addon field
 */
function echo5_add_checkbox_image_field($post, $product_addons, $loop, $option) {
    wp_enqueue_media();
    ob_start();
    ?>
    <td class="checkbox_column">
        <input type="hidden" name="product_addon_option_image[<?php echo $loop; ?>][]" value="<?php echo esc_attr( $option['image']  ); ?>" class="image_attachment_id" />
        <?php if (is_numeric($option['image'])) { 
            $image_src = wp_get_attachment_image_src($option['image']);
            ?>
            <img class="image-preview" src="<?php echo $image_src[0]; ?>" width="60" height="60" style="max-height: 60px; width: 60px;">
        <?php } ?>
        <input type="button" class="button upload_image_button" value="<?php _e( 'Upload image' ); ?>" />
    </td>
    <?php
    $output = ob_get_clean();
    echo $output;

}
add_action('woocommerce_product_addons_panel_option_row', 'echo5_add_checkbox_image_field', 10, 4);

function custom_product_tabs( $tabs) {
	$tabs['giftcard'] = array(
		'label'		=> __( 'Gift Card', 'woocommerce' ),
		'target'	=> 'giftcard_options',
		'class'		=> array( 'show_if_simple', 'show_if_variable'  ),
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'custom_product_tabs' );


add_action("add_meta_boxes", "add_custom_meta_box2");
function add_custom_meta_box2()
{	
	$types = array( 'product' );	//post, page, product

	foreach( $types as $type ) {
		add_meta_box("demo-meta-box", "More Images", "custom_meta_box_markup2", $type, "side", "low", null);
	}
}



function custom_meta_box_markup2($post_id)
{
	//delete_post_meta( get_the_ID(), 'images' );
	?>
	<div class="my_images_container">
	<ul class="my_product_images">
	<?php
	$images = get_post_meta( get_the_ID(), 'images' );
	foreach($images as $key=>$imgs)
	{
		foreach($imgs as $image_id)
		{
			$img_url = wp_get_attachment_url( $image_id );
			echo '<li class="my_image_id" name="image_id" data-attachment_id="'.$image_id.'">
				<img width="150" height="150" src="'.$img_url.'" class="attachment-thumbnail size-thumbnail" alt="" srcset="'.$img_url.'" />
				</li>';
		}
	}
?>
</ul>
	</div>
    <input type="button" name="product_image_meta_button" id="product_image_meta_button" value="Add Images" />
    <?php   
}

function product_images_save_meta( $post_id ) {
$image_ids = array();
$images_array = $_POST['image_id'];

  if( !isset( $_POST['image_id'] ))
  {
	return;  
  }
  else{
	foreach($images_array as $img){
		array_push($image_ids,$img);
		add_post_meta( $post_id, 'images', $image_ids );	
	}
	
  }
  
}
add_action('save_post', 'product_images_save_meta');




/*WP Editor work TinyMCe*/
/*$content = 'Hello';
$editor_id = 'mycustomeditor';

$settings = array( 
	'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close' ), // note that spaces in this list seem to cause an issue
);

wp_editor( $content, $editor_id, $settings );*/

	
/*function myprefix_mce_buttons_1( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}

add_filter( 'mce_buttons_1', 'myprefix_mce_buttons_1' );
	
function add_style_select_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'add_style_select_buttons' );*/	



/*add_filter( 'default_content', 'my_editor_content' );

function my_editor_content( $content ) {

	$content = "This is some custom content I'm adding to the post editor because I hate re-typing it.";

	return $content;
}*/


/*function wpdocs_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );*/

/*function myprefix_add_format_styles( $init_array ) {
	$style_formats = array(
		// Each array child is a format with it's own settings - add as many as you want
		array(
			'title'    => __( 'Theme Button', 'text-domain' ), // Title for dropdown
			'selector' => 'a', // Element to target in editor
			'classes'  => 'theme-button' // Class name used for CSS
		),
		array(
			'title'    => __( 'Highlight', 'text-domain' ), // Title for dropdown
			'inline'   => 'span', // Wrap a span around the selected content
			'classes'  => 'text-highlight' // Class name used for CSS
		),
	);
	$init_array['style_formats'] = json_encode( $style_formats );
	return $init_array;
} 
add_filter( 'tiny_mce_before_init', 'myprefix_add_format_styles' );*/

//add custom styles to the WordPress editor
/*function my_custom_styles( $init_array ) {  
 
    $style_formats = array(  
        // These are the custom styles
        array(  
            'title' => 'Red Button',  
            'block' => 'span',  
            'classes' => 'red-button',
            'wrapper' => true,
        ),  
        array(  
            'title' => 'Content Block',  
            'block' => 'span',  
            'classes' => 'content-block',
            'wrapper' => true,
        ),
        array(  
            'title' => 'Highlighter',  
            'block' => 'span',  
            'classes' => 'highlighter',
            'wrapper' => true,
        ),
    );  
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );  
    
    return $init_array;  
  
}
add_filter( 'tiny_mce_before_init', 'my_custom_styles' );*/

/*add_filter( 'default_content', 't5_preset_editor_content', 10, 2 );


function t5_preset_editor_content( $content, $post )
{
    if ( '' !== $content or 'post' !== $post->post_type )
    {
        return $content ='asdf';
    }

    return 'This is the <em>default</em> content. You may customize it.';
}

function wpdocs_theme_add_editor_styles2() {
    $font_url = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Lato:300,400,700' );
    add_editor_style( $font_url );
}
add_action( 'after_setup_theme', 'wpdocs_theme_add_editor_styles2' );*/

# This sets the Visual Editor as default #
//add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );

# This sets the HTML Editor as default #
//add_filter( 'wp_default_editor', create_function('', 'return "html";') );

/*function enable_more_buttons($buttons) {

$buttons[] = 'fontselect';
$buttons[] = 'fontsizeselect';
$buttons[] = 'styleselect';
$buttons[] = 'backcolor';
$buttons[] = 'newdocument';
$buttons[] = 'cut';
$buttons[] = 'copy';
$buttons[] = 'charmap';
$buttons[] = 'hr';
$buttons[] = 'visualaid';

return $buttons;
}
add_filter('mce_buttons_3', 'enable_more_buttons');

add_filter( 'tiny_mce_before_init', 'myformatTinyMCE' );
function myformatTinyMCE( $in ) {

$in['wordpress_adv_hidden'] = FALSE;

return $in;
}*/


add_filter( 'default_content', 'my_editor_content' );
function my_editor_content( $content ) {
    $content = "If you like this post, then please consider retweeting it or sharing it on Facebook.";
    return $content;
}

/*function to add async to all scripts*/
function js_async_attr($tag){

# Add async to all remaining scripts
return str_replace( ' src', ' async="async" src', $tag );
}
add_filter( 'script_loader_tag', 'js_async_attr', 10 );