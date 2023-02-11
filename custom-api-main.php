<?php
/*
Plugin Name:  Custom Api for WP
Plugin URI:   https://www.upwork.com/freelancers/~01d00918bea0f63345
Description:  Custom API for Wordpress. 
Version:      1.0
Author:       Qasimmizbah 
Author URI:   https://www.upwork.com/freelancers/~01d00918bea0f63345
*/

class My_Rest_Server extends WP_REST_Controller {
    //The namespace and version for the REST SERVER
    var $my_namespace = 'api/v';
    var $my_version = '1';
    
    // all api route setting 
    public function register_routes() {
        $namespace = $this->my_namespace . $this->my_version;
        register_rest_route($namespace, '/login', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'login'),
            ),
        )
        );
        register_rest_route($namespace, '/register', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'register'),
            ),
        )
        );
        register_rest_route($namespace, '/forget-password', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'forgetPassword'),
            ),
        )
        );
         register_rest_route($namespace, '/category-list', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'categoryList'),
            ),
        )
        );

         register_rest_route($namespace, '/change-password', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'changePassword'),
            ),
        )
        );

         register_rest_route($namespace, '/category-product', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'categoryProduct'),
            ),
        )
        );

        //  register_rest_route($namespace, '/product-details', array(
        //     array(
        //         'methods' => WP_REST_SERVER::CREATABLE,
        //         'callback' => array($this, 'productDetails'),
        //     ),
        // )
        // );

          register_rest_route($namespace, '/product-details', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'productDetailsNew'),
            ),
        )
        );

          register_rest_route($namespace, '/edit-profile', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'editProfile'),
            ),
        )
        );

           register_rest_route($namespace, '/view-profile', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'viewProfile'),
            ),
        )
        );

           register_rest_route($namespace, '/order-list', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'orderList'),
            ),
        )
        );

           register_rest_route($namespace, '/order-details', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'orderDetails'),
            ),
        )
        );

            register_rest_route($namespace, '/address-list', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'addressList'),
            ),
        )
        );

         register_rest_route($namespace, '/update-address', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'updateAddress'),
            ),
        )
        );

          register_rest_route($namespace, '/home', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'homePage'),
            ),
        )
        );

           register_rest_route($namespace, '/add-to-cart', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'addToCart'),
            ),
        )
        );

           register_rest_route($namespace, '/cart-list', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'cartList'),
            ),
        )
        );

            register_rest_route($namespace, '/cart-action', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'cartAction'),
            ),
        )
        );

              register_rest_route($namespace, '/place-order', array(
            array(
                'methods' => WP_REST_SERVER::CREATABLE,
                'callback' => array($this, 'placeOrder'),
            ),
        )
        );

        register_rest_route($namespace, '/sidebar-filter', array(
        array(
        'methods' => WP_REST_SERVER::CREATABLE,
        'callback' => array($this, 'sidebarFilter'),
        ),
        )
        );

        register_rest_route($namespace, '/wishlist-list', array(
        array(
        'methods' => WP_REST_SERVER::CREATABLE,
        'callback' => array($this, 'wishlistList'),
        ),
        )
        );

        register_rest_route($namespace, '/wishlist-add', array(
        array(
        'methods' => WP_REST_SERVER::CREATABLE,
        'callback' => array($this, 'wishlistAdd'),
        ),
        )
        );

        register_rest_route($namespace, '/wishlist-remove', array(
        array(
        'methods' => WP_REST_SERVER::CREATABLE,
        'callback' => array($this, 'wishlistRemove'),
        ),
        )
        );


       
    } 
    
    // Register our REST Server
    public function hook_rest_server() {
        global $wpdb;
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    //login api function
    public function login(WP_REST_Request $request = null) {
        $username = $request->get_param('username');
        $password = $request->get_param('password');
        if (isset($username) && !empty($username) && isset($password) && !empty($password)) {
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $user = get_user_by('email', $username);
            } else {
                $user = get_user_by('login', $username);
            }
            if (isset($user) && !empty($user)) {
                if ($user && wp_check_password($password, $user->data->user_pass, $user->ID)) {
                    $userdetails['id'] = $user->ID;
                    $userdetails['user_email'] = $user->user_email;
                    $userdetails['username'] = $user->user_login;
                    $userdetails['display_name'] = $user->display_name; 
                    $userdetails['first_name'] = $user->first_name; 
                    $userdetails['last_name'] = $user->last_name;
                    $message = 'Login sucessfully.';
                    $response['status'] = '1';
                    $response['message'] = $message;
                    $response['userdetails'] = $userdetails;
                    echo json_encode($response);exit;
                } else {
                    $message = 'Username and password could not match.';
                    $response['message'] = $message;
                    $response['status'] = '0';
                    echo json_encode($response);exit;
                }

            } else {
                $message = 'Invalid user.';
                $response['message'] = $message;
                $response['status'] = '0';
                echo json_encode($response);exit;
            }
        } else {
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;
        }
    }

    // user register api function
    public function register(WP_REST_Request $request = null) {
        $name = $request->get_param('name');
        $email = $request->get_param('email');
        $username = $request->get_param('email');
        $password = $request->get_param('password');
        $phone = $request->get_param('phone');
        if (isset($email) && !empty($email)) {
            if (!is_email($email)) {
                $message = 'Email type Invalid.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;
            }
            if (email_exists($email)) {
                $message = 'Email address has been already used.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;
            }
            //$userDetails = explode('@', $email);
            //$username = $userDetails[0];
            //$random_password = wp_generate_password( $length=6, $include_standard_special_chars=false );
            $userdata = array(
                'user_login' => esc_attr($username),
                'user_email' => esc_attr($email),
                'user_pass'  =>  $password,
                'first_name' => esc_attr($name),
                'last_name' => "",
                'user_nicename' => esc_attr($username),
                'show_admin_bar_front' => 'false',
            );
            $user_id = wp_insert_user($userdata);
            wp_update_user(array('ID' => $user_id, 'role' => 'customer'));
            if ( isset( $phone ) ) {
                   update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $phone ) );
            }
            $message = "<h3>Hello " . $username . ",</h3>";
            $message .= "<p>Thank you for registering with us! We built to help you grow your business! We will review your request and background and respond to you. If you have any questions in the meantime.</p>";
            $message .= "<hr/><p><b>Password: </b>".$password."</p>";
            $headers = "From: Umaru Drop Shipping < abc@gmail.com >\n";
            $headers .= "Mime-Version: 1.0 \r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $to = esc_attr($email);
            $subject = "Thank you for registering - " . time();
            wp_mail($to, $subject, $message, $headers, $attachments = array());

            $message = 'Thank you for submitting your registration!.';
            $response['status'] = '1';
            $response['name'] = $name;
            //$response['lname'] = $lname;
            $response['username'] = $username;
            $response['email'] = $email;
            $response['phone'] = $phone;
            $response['password'] = $password;
            $response['message'] = $message;
            echo json_encode($response);exit;
        } else {
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;
        }
    } 
     // forget password api function
    public function forgetPassword(WP_REST_Request $request = null) {
        $email = $request->get_param('email');
        if (isset($email) && !empty($email)) {
            if (!is_email($email)) {
                $message = 'Invalid E-mail address.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;
            }
            if (!email_exists($email)) {
                $message = 'There is no user registered with that email address.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;
            }
            $user = get_user_by('email', $email);
                $random_password = wp_generate_password( $length=6, $include_standard_special_chars=false );
                $update_user = wp_update_user(array(
                    'ID' => $user->ID,
                    'user_pass' => $random_password,
                )
                );
                if (isset($update_user) && !empty($update_user)) {
                    $to = $email;
                    $subject = 'Password Change';
                    $user_name =$user->user_login;
                    $message = 'Hello ' . $user_name . ',<br><br>';
                    $message .= 'This notice confirms that your password has been changed to: '.$random_password.' <br><br>';
                    $message .= 'If you did not request a password change, please contact us umarudropshippingdev at info@umarudropshippingdev.com.<br><br>';
                    $headers = "From: umarudropshippingdev < info@umarudropshippingdev.com >\n";
                    $headers .= "Mime-Version: 1.0 \r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    wp_mail($to, $subject, $message, $headers);
                    $response['status'] = '1';
                    $response['password'] = $random_password;
                    $message = 'Please check email address for you new password.';
                    $response['message'] = $message;
                    echo json_encode($response);exit;
                } else {
                    $message = 'Oops something went wrong updaing your account.';
                    $response['status'] = '0';
                    $response['message'] = $message;
                    echo json_encode($response);exit;
                }
        } else {
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;
        }
    } 
    // all category list api function
    public function categoryList(WP_REST_Request $request = null) { 
        $category_id = $request->get_param('category_id');
        $category_id = isset($category_id) && !empty($category_id) ? $category_id : '0';
        $args = array();
        $args = array('taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => $category_id);
        $terms = get_terms($args);
        $result = array();
        if (count($terms) > 0) {
            $data = array();
            $count = 1;
            foreach ($terms as $term) {
                $icon_name = $term->slug.'.jpg';
                $imageUrl = get_template_directory_uri() . '/images/'. $icon_name;
                $data['id'] = $term->term_id;
                $data['name'] = htmlspecialchars_decode($term->name);
                $data['image'] = $imageUrl;
                $count++;
                $child_result = array();
                $args = array(
                    'taxonomy' => $term->taxonomy,
                    'parent' => $term->term_id,
                    'orderby' => 'slug',
                    'hide_empty' => false,
                );
                $child_terms = get_terms($args);
                if (count($child_terms) > 0) {
                    $child_data = array();
                    foreach ($child_terms as $child_term) {
                        $child_data['id'] = $child_term->term_id;
                        $child_data['name'] = htmlspecialchars_decode($child_term->name);
                        $child_result[] = $child_data;
                    }
                }
                $data['child_category'] = $child_result;
                $result[] = $data;
            }
            $message = 'Data is found.';
            $response['status'] = '1';
            $response['message'] = $message;
            $response['category_list'] = $result;
            echo json_encode($response);exit;
        } else {
            $message = 'Data is not found.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;
        }
    }

    // change passowrd api function 
    public function changePassword(WP_REST_Request $request = null) {
        $user_id = $request->get_param('user_id');
        $old_password = $request->get_param('old_password');
        $new_password = $request->get_param('new_password');
        $confirm_password = $request->get_param('confirm_password');
        $lang = $request->get_param('lang');
        $lang = isset($lang) && !empty($lang) ? $lang : '1';
        if (isset($user_id) && !empty($user_id) && isset($old_password) && !empty($old_password) && isset($new_password) && !empty($new_password) && isset($confirm_password) && !empty($confirm_password)) {
            $user = get_user_by('id', $user_id);
            if (isset($user) && !empty($user)) {
                if (wp_check_password($old_password, $user->data->user_pass, $user->ID)) {
                    if ($new_password === $confirm_password) {
                        wp_set_password($new_password, $user_id);
                        $message = 'Hello ' . $user->user_nicename . ',<br><br>';
                        $message .= 'This notice confirms that your password has been changed to: '.$new_password.' <br><br>';
                        $headers = "From: info@umarudropshippingdev.com < info@umarudropshippingdev.com >\n";
                        $headers .= "Mime-Version: 1.0 \r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        $to = esc_attr($user->user_email);
                        $subject = "Notice of Password Change.";
                        wp_mail($to, $subject, $message, $headers, $attachments = array());

                        $message = 'Password updated successfully.';
                        $response['status'] = '1';
                        $response['message'] = $message;
                        echo json_encode($response);exit;
                    } else {
                        $message = 'Pasword and confirm password does not similar.';
                        $response['status'] = '0';
                        $response['message'] = $message;
                        echo json_encode($response);exit;
                    }
                } else {
                    $message = ' Old password is not valid.';
                    $response['status'] = '0';
                    $response['message'] = $message;
                    echo json_encode($response);exit;
                }
            } else {
                $message = 'Invalid user.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;
            }
        } else {
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;
        }
    }

    //Category Product 
     public function categoryProduct(WP_REST_Request $request = null) {
        global $wpdb;
        $category_id = $request->get_param('category_id');
        $min_price = $request->get_param('min_price');
        $max_price = $request->get_param('max_price');
        $sort_by = $request->get_param('sort_by');
        $sort_by = !empty($sort_by) ? $sort_by :'name';
        $page = $request->get_param('page');
        $page = !empty($page) ? $page: 1;
        $price_from = !empty($price_from) ? $price_from:10000;
        $tax_query = [];
        $product_ids = [];
        if(!empty($category_id)){
            $tax_query = array( 
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $category_id,
                ) 
            );
        }

        if(!empty($min_price)){
            $matched_products_query =  $wpdb->get_results( $wpdb->prepare("SELECT DISTINCT ID, post_type FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON ID = post_id WHERE post_type IN ( 'product', 'product_variation' ) AND post_status = 'publish' AND meta_key = '%s' AND meta_value BETWEEN %d AND %d ", '_price', $min_price, $max_price ), OBJECT );
            if(count($matched_products_query) > 0){
                foreach ($matched_products_query as $key => $value) {
                    $product_ids[] =  $value->ID; 
                }
            }else{
                $product_ids[] = 0;
            }
        }

        $args = array( 
            'post_type' => 'product',  
            'post_status' => 'publish', 
            'posts_per_page' => 12, 
            'fields' => 'ids',
            'paged' => $page,
            'is_paged' => true,
        );
        if(!empty($product_ids)){
            $args['post__in'] = $product_ids;
        }
        if(!empty($tax_query)){
            $args['tax_query'] = $tax_query;
        }
        if($sort_by =='menu_order'){
            $args['order'] = 'ASC';
            $args['orderby'] = 'name';
        }else if($sort_by =='popularity'){
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }else if($sort_by =='date'){
            $args['order'] = 'DESC';
            $args['orderby'] = 'date';
        }else if($sort_by =='price'){
            $args['order'] = 'ASC';
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num date';
        }else if($sort_by =='price-desc'){
            $args['order'] = 'DESC';
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num date';
        }else if($sort_by =='rating'){
            $args['order'] = 'DESC';
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num date';
        }else{
            $args['order'] = 'ASC';
            $args['orderby'] = 'name';
        }

        $myposts = get_posts( $args );
        if(count($myposts) > 0){
            foreach ( $myposts as $post ) {     
                global $product;
                $product = new WC_Product($post);
                $product_thumbnail_id = get_post_thumbnail_id($post);
                $product_thumbnail_url = wp_get_attachment_url( $product_thumbnail_id );
                $data['id'] = $post;
                $data['name'] = $product->post->post_title; 
                $data['image'] = $product_thumbnail_url;
                $data['price'] = $product->price;
                $data['regular_price'] = $product->regular_price;

                if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){ $data['save'] =  round( 100 - ( $data['price'] / $data['regular_price'] * 100 ) ) . '%'; 
                }
                $result[] = $data;
            }
            $response['status'] = '1';
            $response['result'] = $result;
            $response['message'] = 'sucess';
            echo json_encode($response);exit;
        }else{
            $response['status'] = 0;
            $response['message'] = 'no product found';
            echo json_encode($response);exit;   
        }
    }

        // Product Details

    public function productDetails(WP_REST_Request $request = null) {
        global $wpdb;
        $product_id = $request->get_param('product_id');
        if(!empty($product_id)){
            global $product;
            $product = new WC_Product($product_id);
            //print_r($product); die;
            $data['id'] = $product_id;
            $data['name'] = $product->name;
            $data['price'] = $product->price;
            $data['short_description'] = $product->short_description;
            $description = str_replace("\r\n",'', $product->description);
            $data['description'] = $description;
            $data['stock_quantity'] = $product->stock_quantity;
            $data['regular_price'] = $product->regular_price;

            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){ $data['save'] =  round( 100 - ( $data['price'] / $data['regular_price'] * 100 ) ) . '%'; 
            }
            $data['sku'] = $product->sku;
            $data['in_stock'] = $product->is_in_stock();
            $attachment_ids = $product->gallery_image_ids;
            $gallery = [];
            $data['feature_img'] = !empty($product->image_id) ? wp_get_attachment_url($product->image_id):'';
            if(!empty($attachment_ids) && count($attachment_ids) > 0){
                foreach( $attachment_ids as $attachment_id ) {
                    $col['gallery_thumb'] = wp_get_attachment_url($attachment_id);
                    $gallery[] =  $col;
                }
            }
            $categoryDetails = '';
            $data['gallery'] = $gallery;
            if(!empty($product->category_ids) && count($product->category_ids) >0 ){
                foreach ($product->category_ids as $key => $category_id) {
                    $term = get_term_by( 'id', $category_id, 'product_cat' );
                    $categoryDetails .= $term->name;
                }
            }
            $comments =[];
            $reviews = get_comments( array('post_id' => $product_id ) ); 
            if(count($reviews) > 0){
                foreach( $reviews  as $comment) {
                    $reviewsArray['comment_author'] =  $comment->comment_author;
                    $reviewsArray['comment_content'] =  $comment->comment_content;
                    $reviewsArray['date'] =  date('F d,Y', strtotime($comment->comment_date));
                    $reviewsArray['rating'] = get_comment_meta( $comment->comment_ID, 'rating',true);
                    $comments[] =$reviewsArray;
                }  
            }

            $data['reviews'] = $comments;
            $data['category'] = $categoryDetails;
            $response['status'] = '1';
            $response['result'] = $data;
            $response['message'] = 'sucess';
            echo json_encode($response);exit;
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;    
        }
    }

    public function productDetailsNew(WP_REST_Request $request = null) {
        global $wpdb;
        $product_id = $request->get_param('product_id');
        if(!empty($product_id)){
            global $product;
            $product = new WC_Product($product_id);
            //print_r($product); die;

            $variation =[];
            $handle=new WC_Product_Variable($product_id);
            $variations1=$handle->get_children();
            foreach ($variations1 as $value) {
            $single_variation=new WC_Product_Variation($value);

            $variations['attributes'] = $single_variation->get_variation_attributes();
            $variations['attributes_id'] = $single_variation->get_id();
            $variations['attributes_price'] = $single_variation->price;
            $variations['attributes_sku'] = $single_variation->sku;
            $variations['price'] = $single_variation->price;
            $variations['stock_quantity']=$single_variation->stock_quantity;
            $variations['regular_price'] = $single_variation->regular_price;
            $variations['in_stock'] = $single_variation->is_in_stock();
            if( $product->is_on_sale()){ $variations['save'] =  round( 100 - ( $variations['price'] / $variations['regular_price'] * 100 ) ) . '%'; 
            }

            $variation[]=$variations;

            }
            
            $data['id'] = $product_id;
            $data['name'] = $product->name;
            $data['price'] = $product->price;
            $data['short_description'] = $product->short_description;
            $description = str_replace("\r\n",'', $product->description);
            $data['description'] = $description;
            $data['stock_quantity'] = $product->stock_quantity;
            $data['regular_price'] = $product->regular_price;
            if(!empty($variation))
            {
                $data['variation'] = $variation;
            }

            
            
            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){ $data['save'] =  round( 100 - ( $data['price'] / $data['regular_price'] * 100 ) ) . '%'; 
            }
            $data['sku'] = $product->sku;
            $data['in_stock'] = $product->is_in_stock();
            $attachment_ids = $product->gallery_image_ids;
            $gallery = [];
            $data['feature_img'] = !empty($product->image_id) ? wp_get_attachment_url($product->image_id):'';
            if(!empty($attachment_ids) && count($attachment_ids) > 0){
                foreach( $attachment_ids as $attachment_id ) {
                    $col['gallery_thumb'] = wp_get_attachment_url($attachment_id);
                    $gallery[] =  $col;
                }
            }
            $categoryDetails = '';
            $data['gallery'] = $gallery;
            if(!empty($product->category_ids) && count($product->category_ids) >0 ){
                foreach ($product->category_ids as $key => $category_id) {
                    $term = get_term_by( 'id', $category_id, 'product_cat' );
                    $categoryDetails .= $term->name;
                }
            }
            $comments =[];
            $reviews = get_comments( array('post_id' => $product_id ) ); 
            if(count($reviews) > 0){
                foreach( $reviews  as $comment) {
                    $reviewsArray['comment_author'] =  $comment->comment_author;
                    $reviewsArray['comment_content'] =  $comment->comment_content;
                    $reviewsArray['date'] =  date('F d,Y', strtotime($comment->comment_date));
                    $reviewsArray['rating'] = get_comment_meta( $comment->comment_ID, 'rating',true);
                    $comments[] =$reviewsArray;
                }  
            }

           


            $data['reviews'] = $comments;
            $data['category'] = $categoryDetails;
            //$data['variation'] = $variation;
            $response['status'] = '1';
            $response['result'] = $data;
            $response['message'] = 'sucess';
            echo json_encode($response);exit;
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;    
        }
    }

    //edit Profile
    public function editProfile(WP_REST_Request $request = null) {
        $user_id = $request->get_param('user_id');
        $first_name = $request->get_param('first_name');
        $last_name = $request->get_param('last_name');
        $display_name = $request->get_param('display_name');
        $phone = $request->get_param('phone');
        $email = $request->get_param('email');
        if(!empty($user_id) && !empty($first_name) && !empty($last_name) && !empty($display_name) && !empty($email)){
            $userDetails = get_userdata($user_id);
            if(!empty($userDetails)){
                $args = array(
                    'ID'    => $userDetails->id,
                );
                if ($userDetails->user_email != $email) {  
                    if (!is_email($email)) {
                        $message = 'Email type Invalid.';
                        $response['status'] = '0';
                        $response['message'] = $message;
                        echo json_encode($response);exit;
                    }
                    if (email_exists($email)) {
                        $message = 'Email address has been already used.';
                        $response['status'] = '0';
                        $response['message'] = $message;
                        echo json_encode($response);exit;
                    }
                    $args['user_email'] = $email;
                }
                $args['display_name'] = $display_name;
                wp_update_user( $args );
                update_user_meta($userDetails->id, 'first_name', $first_name);
                update_user_meta($userDetails->id, 'last_name', $last_name);
                if ( isset($phone) ) {
                update_user_meta( $userDetails->id, 'billing_phone', sanitize_text_field( $phone ) );
                }
                $message = 'Account details changed successfully.';
                $response['status'] = '1';
                $response['message'] = $message;
                echo json_encode($response);exit; 
            }else{
                $message = 'Invalid user.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;  
            }
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit; 
        }
    }
    
    //view profile

    public function viewProfile(WP_REST_Request $request = null) {
        $user_id = $request->get_param('user_id');
         if(!empty($user_id)){
            $userDetails = get_userdata($user_id);
            $response['status'] = '1';
            $response['firsname'] = $userDetails->user_firstname;
            $response['lastname'] = $userDetails->user_lastname;
            $response['email'] = $userDetails->user_email;
            $response['phone'] = $userDetails->billing_phone;
            echo json_encode($response);exit;  
        }
       else{
                $message = 'Invalid user.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;  
            }
    }

        public function orderList(WP_REST_Request $request = null){
        global $woocommerce;
        $user_id = $request->get_param('user_id');
        if(!empty($user_id)){
            $orders = wc_get_orders(array(
                'customer_id' => $user_id,
                'return' => 'ids',
            ));
            if(count($orders) > 0){
                foreach ($orders as $key => $order_id) {
                    $order = new WC_Order( $order_id );
                    $order_data = array(
                        'id' => $order->get_id(),
                        'order_number' => $order->get_order_number(),
                        'created_at' => $order->get_date_created()->date('Y-m-d H:i:s'),
                        'updated_at' => $order->get_date_modified()->date('Y-m-d H:i:s'),
                        'completed_at' => !empty($order->get_date_completed()) ? $order->get_date_completed()->date('Y-m-d H:i:s') : '',
                        'status' => $order->get_status(),
                        'currency' => $order->get_currency(),
                        'total' => wc_format_decimal($order->get_total(), $dp),
                        'subtotal' => wc_format_decimal($order->get_subtotal(), $dp),
                        'total_line_items_quantity' => $order->get_item_count(),
                        'total_tax' => wc_format_decimal($order->get_total_tax(), $dp),
                        'total_shipping' => wc_format_decimal($order->get_total_shipping(), $dp),
                        'cart_tax' => wc_format_decimal($order->get_cart_tax(), $dp),
                        'shipping_tax' => wc_format_decimal($order->get_shipping_tax(), $dp),
                        'total_discount' => wc_format_decimal($order->get_total_discount(), $dp),
                        'shipping_methods' => $order->get_shipping_method(),
                        'order_key' => $order->get_order_key(),
                        'payment_details' => array(
                            'method_id' => $order->get_payment_method(),
                            'method_title' => $order->get_payment_method_title(),
                            'paid_at' => !empty($order->get_date_paid()) ? $order->get_date_paid()->date('Y-m-d H:i:s') : '',
                        ),
                        'billing_address' => array(
                            'first_name' => $order->get_billing_first_name(),
                            'last_name' => $order->get_billing_last_name(),
                            'company' => $order->get_billing_company(),
                            'address_1' => $order->get_billing_address_1(),
                            'address_2' => $order->get_billing_address_2(),
                            'city' => $order->get_billing_city(),
                            'state' => $order->get_billing_state(),
                            'formated_state' => WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()], //human readable formated state name
                            'postcode' => $order->get_billing_postcode(),
                            'country' => $order->get_billing_country(),
                            'formated_country' => WC()->countries->countries[$order->get_billing_country()], //human readable formated country name
                            'email' => $order->get_billing_email(),
                            'phone' => $order->get_billing_phone()
                        ),
                        'shipping_address' => array(
                            'first_name' => $order->get_shipping_first_name(),
                            'last_name' => $order->get_shipping_last_name(),
                            'company' => $order->get_shipping_company(),
                            'address_1' => $order->get_shipping_address_1(),
                            'address_2' => $order->get_shipping_address_2(),
                            'city' => $order->get_shipping_city(),
                            'state' => $order->get_shipping_state(),
                            'formated_state' => WC()->countries->states[$order->get_shipping_country()][$order->get_shipping_state()], //human readable formated state name
                            'postcode' => $order->get_shipping_postcode(),
                            'country' => $order->get_shipping_country(),
                            'formated_country' => WC()->countries->countries[$order->get_shipping_country()] //human readable formated country name
                        ),
                        'note' => $order->get_customer_note(),
                        'customer_ip' => $order->get_customer_ip_address(),
                        'customer_user_agent' => $order->get_customer_user_agent(),
                        'customer_id' => $order->get_user_id(),
                        'view_order_url' => $order->get_view_order_url(),
                        'line_items' => array(),
                    );

                    //getting all line items
                    foreach ($order->get_items() as $item_id => $item) {
                        $product = $item->get_product();
                        $product_id = null;
                        $product_sku = null;
                        // Check if the product exists.
                        if (is_object($product)) {
                            $product_id = $product->get_id();
                            $product_sku = $product->get_sku();
                        }
                        $order_data['line_items'][] = array(
                            'id' => $item_id,
                            'subtotal' => wc_format_decimal($order->get_line_subtotal($item, false, false), $dp),
                            'subtotal_tax' => wc_format_decimal($item['line_subtotal_tax'], $dp),
                            'total' => wc_format_decimal($order->get_line_total($item, false, false), $dp),
                            'total_tax' => wc_format_decimal($item['line_tax'], $dp),
                            'price' => wc_format_decimal($order->get_item_total($item, false, false), $dp),
                            'quantity' => wc_stock_amount($item['qty']),
                            'tax_class' => (!empty($item['tax_class']) ) ? $item['tax_class'] : null,
                            'name' => $item['name'],
                            'product_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product->get_parent_id() : $product_id,
                            'variation_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product_id : 0,
                            'product_url' => get_permalink($product_id),
                            'product_thumbnail_url' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail', TRUE)[0],
                            'sku' => $product_sku,
                            'meta' => wc_display_item_meta($item, ['echo' => false])
                        );
                    }
                    $result[] =$order_data;
                }
                $message = 'Order list.';
                $response['order_list'] = $result;
                $response['status'] = '1';
                $response['message'] = $message;
                echo json_encode($response);exit;
            }else{
                $message = 'Order list is empty.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;    
            }
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;    
        }
    }  


    //order details
    public function orderDetails(WP_REST_Request $request = null){
        global $woocommerce;
        $user_id = $request->get_param('user_id');
        $order_id = $request->get_param('order_id');
        if(!empty($order_id)){
            $order = new WC_Order( $order_id );
            if(!empty( $order)){
                $order_data = array(
                    'id' => $order->get_id(),
                    'order_number' => $order->get_order_number(),
                    'created_at' => $order->get_date_created()->date('Y-m-d H:i:s'),
                    'updated_at' => $order->get_date_modified()->date('Y-m-d H:i:s'),
                    'completed_at' => !empty($order->get_date_completed()) ? $order->get_date_completed()->date('Y-m-d H:i:s') : '',
                    'status' => $order->get_status(),
                    'currency' => $order->get_currency(),
                    'total' => wc_format_decimal($order->get_total(), $dp),
                    'subtotal' => wc_format_decimal($order->get_subtotal(), $dp),
                    'total_line_items_quantity' => $order->get_item_count(),
                    'total_tax' => wc_format_decimal($order->get_total_tax(), $dp),
                    'total_shipping' => wc_format_decimal($order->get_total_shipping(), $dp),
                    'cart_tax' => wc_format_decimal($order->get_cart_tax(), $dp),
                    'shipping_tax' => wc_format_decimal($order->get_shipping_tax(), $dp),
                    'total_discount' => wc_format_decimal($order->get_total_discount(), $dp),
                    'shipping_methods' => $order->get_shipping_method(),
                    'order_key' => $order->get_order_key(),
                    'payment_details' => array(
                        'method_id' => $order->get_payment_method(),
                        'method_title' => $order->get_payment_method_title(),
                        'paid_at' => !empty($order->get_date_paid()) ? $order->get_date_paid()->date('Y-m-d H:i:s') : '',
                    ),
                    'billing_address' => array(
                        'first_name' => $order->get_billing_first_name(),
                        'last_name' => $order->get_billing_last_name(),
                        'company' => $order->get_billing_company(),
                        'address_1' => $order->get_billing_address_1(),
                        'address_2' => $order->get_billing_address_2(),
                        'city' => $order->get_billing_city(),
                        'state' => $order->get_billing_state(),
                        'formated_state' => WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()], //human readable formated state name
                        'postcode' => $order->get_billing_postcode(),
                        'country' => $order->get_billing_country(),
                        'formated_country' => WC()->countries->countries[$order->get_billing_country()], //human readable formated country name
                        'email' => $order->get_billing_email(),
                        'phone' => $order->get_billing_phone()
                    ),
                    'shipping_address' => array(
                        'first_name' => $order->get_shipping_first_name(),
                        'last_name' => $order->get_shipping_last_name(),
                        'company' => $order->get_shipping_company(),
                        'address_1' => $order->get_shipping_address_1(),
                        'address_2' => $order->get_shipping_address_2(),
                        'city' => $order->get_shipping_city(),
                        'state' => $order->get_shipping_state(),
                        'formated_state' => WC()->countries->states[$order->get_shipping_country()][$order->get_shipping_state()], //human readable formated state name
                        'postcode' => $order->get_shipping_postcode(),
                        'country' => $order->get_shipping_country(),
                        'formated_country' => WC()->countries->countries[$order->get_shipping_country()] //human readable formated country name
                    ),
                    'note' => $order->get_customer_note(),
                    'customer_ip' => $order->get_customer_ip_address(),
                    'customer_user_agent' => $order->get_customer_user_agent(),
                    'customer_id' => $order->get_user_id(),
                    'view_order_url' => $order->get_view_order_url(),
                    'line_items' => array(),
                    'shipping_lines' => array(),
                    'tax_lines' => array(),
                    'fee_lines' => array(),
                    'coupon_lines' => array(),
                );

                //getting all line items
                foreach ($order->get_items() as $item_id => $item) {

                    $product = $item->get_product();

                    $product_id = null;
                    $product_sku = null;
                    // Check if the product exists.
                    if (is_object($product)) {
                        $product_id = $product->get_id();
                        $product_sku = $product->get_sku();
                    }

                    $order_data['line_items'][] = array(
                        'id' => $item_id,
                        'subtotal' => wc_format_decimal($order->get_line_subtotal($item, false, false), $dp),
                        'subtotal_tax' => wc_format_decimal($item['line_subtotal_tax'], $dp),
                        'total' => wc_format_decimal($order->get_line_total($item, false, false), $dp),
                        'total_tax' => wc_format_decimal($item['line_tax'], $dp),
                        'price' => wc_format_decimal($order->get_item_total($item, false, false), $dp),
                        'quantity' => wc_stock_amount($item['qty']),
                        'tax_class' => (!empty($item['tax_class']) ) ? $item['tax_class'] : null,
                        'name' => $item['name'],
                        'product_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product->get_parent_id() : $product_id,
                        'variation_id' => (!empty($item->get_variation_id()) && ('product_variation' === $product->post_type )) ? $product_id : 0,
                        'product_url' => get_permalink($product_id),
                        'product_thumbnail_url' => wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail', TRUE)[0],
                        'sku' => $product_sku,
                        'meta' => wc_display_item_meta($item, ['echo' => false])
                    );
                }

                //getting shipping
                foreach ($order->get_shipping_methods() as $shipping_item_id => $shipping_item) {
                    $order_data['shipping_lines'][] = array(
                        'id' => $shipping_item_id,
                        'method_id' => $shipping_item['method_id'],
                        'method_title' => $shipping_item['name'],
                        'total' => wc_format_decimal($shipping_item['cost'], $dp),
                    );
                }

                //getting taxes
                foreach ($order->get_tax_totals() as $tax_code => $tax) {
                    $order_data['tax_lines'][] = array(
                        'id' => $tax->id,
                        'rate_id' => $tax->rate_id,
                        'code' => $tax_code,
                        'title' => $tax->label,
                        'total' => wc_format_decimal($tax->amount, $dp),
                        'compound' => (bool) $tax->is_compound,
                    );
                }

                //getting fees
                foreach ($order->get_fees() as $fee_item_id => $fee_item) {
                    $order_data['fee_lines'][] = array(
                        'id' => $fee_item_id,
                        'title' => $fee_item['name'],
                        'tax_class' => (!empty($fee_item['tax_class']) ) ? $fee_item['tax_class'] : null,
                        'total' => wc_format_decimal($order->get_line_total($fee_item), $dp),
                        'total_tax' => wc_format_decimal($order->get_line_tax($fee_item), $dp),
                    );
                }

                //getting coupons
                foreach ($order->get_items('coupon') as $coupon_item_id => $coupon_item) {

                    $order_data['coupon_lines'][] = array(
                        'id' => $coupon_item_id,
                        'code' => $coupon_item['name'],
                        'amount' => wc_format_decimal($coupon_item['discount_amount'], $dp),
                    );
                }
                $message = 'Order details.';
                $response['status'] = '1';
                $response['message'] = $message;
                $response['order_data'] = $order_data;
                echo json_encode($response);exit; 
            }else{
                $message = 'Invalid order_id.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;         
            }
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit;        
        }
    }

    //Address List 

    public function addressList(WP_REST_Request $request = null) {
        $user_id = $request->get_param('user_id');
        if(!empty($user_id)){
            $userDetails = get_userdata($user_id);
            if(!empty($userDetails)){
                $data['user_id'] =  $userDetails->ID;
                $data['first_name'] = !empty($userDetails->billing_first_name) ? $userDetails->billing_first_name:'';
                $data['last_name'] = !empty($userDetails->billing_last_name) ? $userDetails->billing_last_name:'';
                $data['company'] = !empty($userDetails->billing_company) ? $userDetails->billing_company:'';
                $data['address_1'] = !empty($userDetails->billing_address_1) ? $userDetails->billing_address_1:'';
                $data['address_2'] = !empty($userDetails->billing_address_2) ? $userDetails->billing_address_2:'';
                $data['city'] = !empty($userDetails->billing_city) ? $userDetails->billing_city:'';
                $data['state'] = !empty($userDetails->billing_state) ? $userDetails->billing_state:'';
                $data['postcode'] = !empty($userDetails->billing_postcode) ? $userDetails->billing_postcode:'';
                $data['country'] = !empty($userDetails->billing_country) ? $userDetails->billing_country:'';
                $data['email'] = !empty($userDetails->billing_email) ? $userDetails->billing_email:$userDetails->user_email;
                $data['phone'] = !empty($userDetails->billing_phone) ? $userDetails->billing_phone:'';
                $message = 'Billing Address.';
                $response['data'] = $data;
                $response['status'] = '1';
                $response['message'] = $message;
                echo json_encode($response);exit; 
            }else{
                $message = 'Invalid user.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;  
            }
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit; 
        }
    }

    //Update Address
        public function updateAddress(WP_REST_Request $request = null) {
        $user_id = $request->get_param('user_id');
        $first_name = $request->get_param('first_name');
        $last_name = $request->get_param('last_name');
        $company = $request->get_param('company');
        $address_1 = $request->get_param('address_1');
        $address_2 = $request->get_param('address_2');
        $city = $request->get_param('city');
        $state = $request->get_param('state');
        $country = $request->get_param('country');
        $postcode = $request->get_param('postcode');
        $email = $request->get_param('email');
        $phone = $request->get_param('phone');
        if(!empty($user_id) && !empty($first_name) && !empty($last_name) && !empty($address_1) && !empty($city) && !empty($state) && !empty($country) && !empty($postcode) && !empty($email) && !empty($phone)){
            $userDetails = get_userdata($user_id);
            if(!empty($userDetails)){
                update_user_meta($userDetails->id, 'billing_first_name', $first_name);
                update_user_meta($userDetails->id, 'billing_last_name', $last_name);
                if(!empty($company)){
                    update_user_meta($userDetails->id, 'billing_company', $company);
                }else{
                    update_user_meta($userDetails->id, 'billing_company', '');
                }
                update_user_meta($userDetails->id, 'billing_address_1', $address_1);
                if(!empty($address_2)){
                    update_user_meta($userDetails->id, 'billing_address_2', $address_2);
                }else{
                    update_user_meta($userDetails->id, 'billing_address_2', '');
                }
                update_user_meta($userDetails->id, 'billing_city', $city);
                update_user_meta($userDetails->id, 'billing_state', $state);
                update_user_meta($userDetails->id, 'billing_country', $country);
                update_user_meta($userDetails->id, 'billing_postcode', $postcode);
                update_user_meta($userDetails->id, 'billing_email', $email);
                update_user_meta($userDetails->id, 'billing_phone', $phone);
                $message = 'Address changed successfully.';
                $response['status'] = '1';
                $response['message'] = $message;
                echo json_encode($response);exit; 
            }else{
                $message = 'Invalid user.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;  
            }
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit; 
        }
    }
        
        // home page api
    public function homePage(WP_REST_Request $request = null) {
        $catResult = [];
        $prod_categories = get_terms('product_cat', array('hide_empty' => 0, 'exclude' => array(305, 231), 'parent' => 0)); 
        foreach ($prod_categories as $key => $categories) {
            $icon_name = $categories->slug.'.jpg';
            $imageUrl = get_template_directory_uri() . '/images/'. $icon_name;
            $data['id'] = $categories->term_id; 
            $data['name'] =  $categories->name;
            $data['image'] =  $imageUrl;
            $catResult[] = $data;
        }   

        $dataSlider[]['image'] = get_stylesheet_directory_uri() . '/images/slider_1.jpg';
        $dataSlider[]['image'] = get_stylesheet_directory_uri() . '/images/slider_1.jpg';
        $dataSlider[]['image'] = get_stylesheet_directory_uri() . '/images/slider_1.jpg';

        $dataBanner[]['image'] = get_stylesheet_directory_uri() . '/images/banner_1.png';

        $result = [];
        $args = array( 
            'post_type' => 'product',  
            'post_status' => 'publish', 
            'posts_per_page' => 8, 
            'fields' => 'ids',
            'meta_key' => 'total_sales',
            'orderby' => 'meta_value_num',  
            'order' => 'DESC'
        );
        $myposts = get_posts( $args );
        foreach ( $myposts as $post ) {     
            global $product;
            $product = new WC_Product($post);
            $product_thumbnail_id = get_post_thumbnail_id($post);
            $product_thumbnail_url = wp_get_attachment_url( $product_thumbnail_id );
            $data['id'] = $post;
            $data['name'] = $product->post->post_title; 
            $data['image'] = $product_thumbnail_url;
            $data['price'] = $product->price;
            $data['regular_price'] = $product->regular_price;
            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){ 
                $data['save'] =  round( 100 - ( $data['price'] / $data['regular_price'] * 100 ) ) . '%'; 
            }
            $result[]=$data;
        }
        $latest = [];
        $args = array( 
            'post_type' => 'product',  
            'post_status' => 'publish', 
            'posts_per_page' => 12, 
            'fields' => 'ids',
            'meta_key' => 'total_sales',
            'orderby' => 'meta_value_num',  
            'order' => 'DESC'
        );
        $myposts = get_posts( $args );
        foreach ( $myposts as $post ) {     
            global $product;
            $product = new WC_Product($post);
            $product_thumbnail_id = get_post_thumbnail_id($post);
            $product_thumbnail_url = wp_get_attachment_url( $product_thumbnail_id );
            $data['id'] = $post;
            $data['name'] = $product->post->post_title; 
            $data['image'] = $product_thumbnail_url;
            $data['price'] = $product->price;
            $data['regular_price'] = $product->regular_price;

            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){ 
                $data['save'] =  round( 100 - ( $data['price'] / $data['regular_price'] * 100 ) ) . '%'; 
            }
            $latest[] = $data;
        }

        $saleProduct = [];
        $args = array( 
            'post_type' => 'product',  
            'post_status' => 'publish', 
            'posts_per_page' => 8, 
            'fields' => 'ids',
            'meta_query'     => array(
                            'relation' => 'OR',
                            array( // Simple products type
                                'key'           => '_sale_price',
                                'value'         => 0,
                                'compare'       => '>',
                                'type'          => 'numeric'
                            ),
                            array( // Variable products type
                                'key'           => '_min_variation_sale_price',
                                'value'         => 0,
                                'compare'       => '>',
                                'type'          => 'numeric'
                            )
                        )

        );
        $myposts = get_posts( $args );
        foreach ( $myposts as $post ) {     
            global $product;
            $product = new WC_Product($post);
            $product_thumbnail_id = get_post_thumbnail_id($post);
            $product_thumbnail_url = wp_get_attachment_url( $product_thumbnail_id );
            $data['id'] = $post;
            $data['name'] = $product->post->post_title; 
            $data['image'] = $product_thumbnail_url;
            $data['price'] = $product->price;
            $data['regular_price'] = $product->regular_price;
            if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){ 
                $data['save'] =  round( 100 - ( $data['price'] / $data['regular_price'] * 100 ) ) . '%'; 
            }
            $saleProduct[]=$data;
        }



        $response['status'] = '1';
        $response['slider'] = $dataSlider;
        $response['best_sellling'] = $result;
        $response['banner'] = $dataBanner;
        $response['new_arrival'] = $latest;
        $response['saleProduct'] = $saleProduct;
        //$response['category'] = $catResult;
       
        $response['message'] = 'sucess';
        echo json_encode($response);exit;
    }

    //Add to cart

    public function addToCart(WP_REST_Request $request = null) {
        global $wpdb;
        $WC_Cart = new WC_Cart();
        $user_id = $request->get_param('user_id');
        $product_id = $request->get_param('product_id');
        $quantity = $request->get_param('quantity');
        $quantity = !empty($quantity) ? $quantity:1;
        if(!empty($user_id) && !empty($product_id) && !empty($quantity)){
            global $woocommerce;
            $cart_data =  get_user_meta($user_id, '_woocommerce_persistent_cart_1', true);
            if ( ! array($cart_data) ) {
                $cart_data = array();
            }
            $item =0;
            if(!empty($cart_data)) {
                $flag = 0;
                foreach($cart_data['cart'] as $key => $val) {
                    if($val['product_id'] == $product_id) {
                        $cart_data['cart'][$key]['quantity'] = $cart_data['cart'][$key]['quantity'] +  $quantity;
                        $flag = $flag+1;
                    }
                    if(!empty($val['quantity']) && $val['quantity'] > 0 ){
                        $item++;
                    }
                }
                if($flag == 0) {
                    

                    $string = $WC_Cart->generate_cart_id( $product_id, 0, array(), $cart_data['cart'] );
                    $cart_data['cart'][$string] = array(
                        'key' => $string,
                        'product_id' => $product_id,
                        'variation_id' => 0,
                        'variation' => array(),
                        'quantity' => $quantity,
                    );   
                }
            }else{
                $item++;
                $cart_data = array('cart' => array());
                $string = $WC_Cart->generate_cart_id( $product_id, 0, array(), $user_id);
                $data = array(
                    'key' => $string,
                    'product_id' => $product_id,
                    'variation_id' => 0,
                    'variation' => array(),
                    'quantity' => $quantity,
                );
                $cart_data['cart'][$string] = $data;
            }
            //print_r($cart_data); die;
            update_user_meta($user_id,'_woocommerce_persistent_cart_1',$cart_data);
            $message = 'Product successfully added in cart.';
            $response['status'] = '1';
            $response['cart_count'] = $item;
            $response['message'] = $message;
            echo json_encode($response);exit; 
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit; 
        }
    }   


    //cart list
    public function cartList(WP_REST_Request $request = null) {
        $user_id = $request->get_param('user_id');
        
        if(!empty($user_id)){
            $cart_data =  get_user_meta($user_id, '_woocommerce_persistent_cart_1', true);

            if(!empty($tag) && $tag != 'cart'){
                update_user_meta($user_id, '_woocommerce_persistent_cart_1', $cart_data);
                $cart_data =  get_user_meta($user_id, '_woocommerce_persistent_cart_1', true);
            }
            //echo "<pre>"; print_r($cart_data); die;
            if(!empty($cart_data['cart']) && $cart_data['cart'] > 0 ){
                $cart_subtotal = 0;
                $item = 0;
                foreach ($cart_data['cart'] as $key => $value) {
                    if(!empty($value['quantity']) && $value['quantity'] > 0 ){
                        global $product;
                        $product = new WC_Product($value['product_id']);
                        $product_thumbnail_id = get_post_thumbnail_id($value['product_id']);
                        $product_thumbnail_url = wp_get_attachment_url( $product_thumbnail_id );
                        $data['cart_id'] = $value['key'];
                        $data['product_id'] = $value['product_id'];
                        $data['quantity'] = $value['quantity'];
                        $data['product_name'] = $product->name;
                        $data['subtotal'] =  $value['quantity'] * $product->price;
                        $data['image'] = $product_thumbnail_url;
                        $data['price'] = $product->price;
                        $data['regular_price'] = $product->regular_price;
                        if( $product->is_on_sale() && ! is_admin() && ! $product->is_type('variable')){ 
                            $data['save'] =  round( 100 - ( $data['price'] / $data['regular_price'] * 100 ) ) . '%'; 
                        }
                        $item++;
                        $cart_subtotal += $data['subtotal'];
                        $result[] = $data;
                    }
                }
                if(count($result) > 0){
                    $message = 'cart list.';
                    $response['status'] = '1';
                    $response['cart_list'] = $result;
                    $response['cart_count'] = $item;
                    $response['cart_subtotal'] = $cart_subtotal;
                    $response['cart_total'] = $cart_subtotal;
                    $response['message'] = $message;
                    echo json_encode($response);exit; 
                }else{
                    $message = 'Cart list is empty.';
                    $response['status'] = '0';
                    $response['message'] = $message;
                    echo json_encode($response);exit;  
                }
            }else{
                $message = 'Cart list is empty.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit;  
            }
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit; 
        }
    }

        //Place Order


     public function placeOrder(WP_REST_Request $request = null){
        global $woocommerce;
        $user_id = $request->get_param('user_id');
        $payment_method = $request->get_param('payment_method');   
        $payment_method_title = $request->get_param('payment_method_title');   
        $status = $request->get_param('status');
        $status = !empty($status) ? $status:'completed';
        if(!empty($user_id) && !empty($payment_method) && !empty($payment_method_title)){
            $userDetails = get_userdata($user_id);
            if(!empty($userDetails)){
                if($payment_method =='cod'){
                    $status = 'processing'; 
                }
                
                if(empty($userDetails->billing_first_name) && empty($userDetails->billing_last_name) && empty($userDetails->billing_address_1) && empty($userDetails->billing_city) && empty($userDetails->billing_state) && empty($userDetails->billing_country) && empty($userDetails->billing_postcode)  && empty($userDetails->billing_phone)){
                    $message = 'billing address details is empty';
                    $response['status'] = '0';
                    $response['message'] = $message;
                    echo json_encode($response);exit; 
                }
                $cart_data =  get_user_meta($user_id, '_woocommerce_persistent_cart_1', true);
                if(empty($cart_data['cart'])){
                    $message = 'your cart is empty';
                    $response['status'] = '0';
                    $response['message'] = $message;
                    echo json_encode($response);exit;             
                }

                $address = array(
                    'first_name' => !empty($userDetails->billing_first_name) ? $userDetails->billing_first_name:'',
                    'last_name'  => !empty($userDetails->billing_last_name) ? $userDetails->billing_last_name:'',
                    'company'    => !empty($userDetails->billing_company) ? $userDetails->billing_company:'',
                    'email'      => !empty($userDetails->billing_email) ? $userDetails->billing_email:$userDetails->user_email,
                    'phone'      => !empty($userDetails->billing_phone) ? $userDetails->billing_phone:'',
                    'address_1'  => !empty($userDetails->billing_address_1) ? $userDetails->billing_address_1:'',
                    'address_2'  => !empty($userDetails->billing_address_2) ? $userDetails->billing_address_2:'',
                    'city'       => !empty($userDetails->billing_city) ? $userDetails->billing_city:'',
                    'state'      => !empty($userDetails->billing_state) ? $userDetails->billing_state:'',
                    'postcode'   => !empty($userDetails->billing_postcode) ? $userDetails->billing_postcode:'',
                    'country'    => !empty($userDetails->billing_country) ? $userDetails->billing_country:''
                );
                $order = wc_create_order(array('customer_id' => $user_id));

                foreach ($cart_data['cart'] as $key => $value) {
                    $order->add_product( get_product($value['product_id']), $value['quantity'] );
                }
                $order->set_address($address, 'billing');
                $order->set_address($address, 'shipping');
                $order->set_payment_method( $payment_method );
                if(!empty($razorpay_payment_id)){
                    $order->set_transaction_id( $razorpay_payment_id );
                }
                $order->calculate_totals();
                $order->update_status( $status);
                $order->reduce_order_stock();
                update_post_meta($order->id, '_payment_method_title', $payment_method_title);
                update_user_meta($user_id, '_woocommerce_persistent_cart_1', '');
                $message = 'Order successfully placed.';
                $response['order_id'] = $order->id;
                $response['status'] = '1';
                $response['message'] = $message;
                echo json_encode($response);exit;

            }else{
                $message = 'Invalid user id.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit; 
            }
        }else{
            $message = 'Invalid parameter.';
            $response['status'] = '0';
            $response['message'] = $message;
            echo json_encode($response);exit; 
        }
    }


    public function sidebarFilter(WP_REST_Request $request = null){
        global $woocommerce;
        global $product;
       
            $data = dynamic_sidebar( 'product-archive' );
            $sidebarFilter[]= $output;
        



        $response['status'] = '1';
        $response['sidebarFilter'] = $sidebarFilter;
        $response['message'] = 'sucess';
        echo json_encode($response);exit;

        


    }
        
        public function wishlistList(WP_REST_Request $request = null) {
            global $wpdb;
        $user_id = $request->get_param('user_id');

            $sql = "SELECT * FROM wpaq_yith_wcwl where user_id=".$user_id;
            $results = $wpdb->get_results($sql);

         //$wishlist =  $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->yith_wcwl where user_id".$user_id ), OBJECT );
            

            if(!empty($results))
            {

        $response['status'] = '1';
        $response['message'] = 'sucess';
        $response['wishlist'] = $results;
        echo json_encode($response);exit;
            }

            else
            {
        $response['status'] = '0';
        $response['message'] = 'Wish List Empty';
        echo json_encode($response);exit;

            }

        }
           
        public function wishlistAdd(WP_REST_Request $request = null) {
        $user_id = $request->get_param('user_id');
        $prod_id = $request->get_param('product_id');
        $quantity = 1;

        if(!empty($user_id) and !empty($prod_id))
        {
        global $wpdb;
                
             $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                function generate_string($input, $strength = 16) {
                $input_length = strlen($input);
                $random_string = '';
                for($i = 0; $i < $strength; $i++) {
                $random_character = $input[mt_rand(0, $input_length - 1)];
                $random_string .= $random_character;
                }
                return $random_string;
                }           
            

            $sql = "SELECT * FROM wpaq_yith_wcwl_lists where user_id=".$user_id;
            $results = $wpdb->get_results($sql);

            foreach ($results as $value) {
               $wishlistid = $value->ID;
            }
            if(!empty($wishlistid))
            {
                $wishlistid;
                
            }
            else
            {
                global $wpdb;

                $tablename = $wpdb->prefix.'yith_wcwl_lists';
                $token = generate_string($permitted_chars, 12);
                $data=array(
                'user_id' => $user_id, 
                'session_id' => NULL,
                'wishlist_slug' => "", 
                'wishlist_name' => "",
                'wishlist_token' => $token, 
                'wishlist_privacy' =>0, 
                'is_default' => 1,
                'dateadded' => date( 'Y-m-d H:i:s' ), 
                'expiration' => NULL
                 );

                $wpdb->insert( $tablename, $data);

                $wishlistid = $wpdb->insert_id;

            }

            if($wishlistid!="" and $user_id!="")
            {   



                $tablename = $wpdb->prefix.'yith_wcwl';

                    $sql = "SELECT * FROM $tablename where user_id=".$user_id." and prod_id=".$prod_id." and wishlist_id=".$wishlistid;
                    $results = $wpdb->get_results($sql);

                    foreach ($results as $value) {
                        $wish_list_id = $value->ID;
                    }

                    $price = get_post_meta( $prod_id, '_regular_price', true);
                    $sale = get_post_meta( $prod_id, '_sale_price', true);

                if($sale=="")
                {
                    $price = get_post_meta( $prod_id, '_regular_price', true);
                }
                else
                {
                    $price = get_post_meta( $prod_id, '_sale_price', true);
                }

                if($wish_list_id=="") {
                    $tablename = $wpdb->prefix.'yith_wcwl';
                    $data=array(
                    'prod_id' => $prod_id, 
                    'quantity' => 1,
                    'user_id' => $user_id, 
                    'wishlist_id' => $wishlistid,
                    'position' => 0, 
                    'original_price' =>$price, 
                    'original_currency' => "USD",
                    'dateadded' => date( 'Y-m-d H:i:s' ), 
                    'on_sale' => 0
                    );

                    $wpdb->insert( $tablename, $data);

                    $wish_list = $wpdb->insert_id;
                }
                else
                {

                    $response['status'] = '1';
                    $response['message'] = 'Product Already added in wishlist';
                    echo json_encode($response);exit;

                }

                }

                if($wish_list!="")
                {
                    $response['status'] = '1';
                    $response['message'] = 'Product added to wishlist';
                    echo json_encode($response);exit;
                }
                else
                {
                    $response['status'] = '0';
                    $response['message'] = 'Wish list Empty';
                    echo json_encode($response);exit;
                }

                }

                {
                    $message = 'Invalid parameter.';
                    $response['status'] = '0';
                    $response['message'] = $message;
                    echo json_encode($response);exit; 
                }
            }

            
            public function wishlistRemove(WP_REST_Request $request = null) {
        $user_id = $request->get_param('user_id');
        $prod_id = $request->get_param('product_id');
        
                if(!empty($user_id) and !empty($prod_id))
                {
                global $wpdb;

                $tablename = $wpdb->prefix.'yith_wcwl';

                $sql = "SELECT * FROM $tablename where user_id=".$user_id." and prod_id=".$prod_id;
                $results = $wpdb->get_results($sql);

                if(!empty($results)) {
                $sql = "delete FROM $tablename where user_id=".$user_id." and prod_id=".$prod_id;
                $results = $wpdb->get_results($sql);

                        $response['status'] = '1';
                        $response['message'] = 'Product Remvoed';
                        echo json_encode($response);exit;
                    
                }
                else
                {
                        $response['status'] = '0';
                        $response['message'] = 'Product Not Found';
                        echo json_encode($response);exit;
                    
                }
            }
                {
                $message = 'Invalid parameter.';
                $response['status'] = '0';
                $response['message'] = $message;
                echo json_encode($response);exit; 
                }
    }




}
$my_rest_server = new My_Rest_Server();
$my_rest_server->hook_rest_server();






?>
