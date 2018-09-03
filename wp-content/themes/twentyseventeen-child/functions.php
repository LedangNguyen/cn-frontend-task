<?php
/**
 * Init styles and scripts
 */
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles_and_scripts');

function my_theme_enqueue_styles_and_scripts() {
  $parent_style = 'parent-style';

  wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
  wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', [$parent_style]);
  wp_enqueue_script('popup', get_stylesheet_directory_uri() . '/assets/js/popup.js');
}

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
  $config = [
    'before_widget' => '<aside class="widget %1$s %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'
  ];

  register_sidebar([
    'name' => __('Footer Bottom'),
    'id' => 'footer-bottom'
  ] + $config);
});

/**
 * Cleanup and reorder the summary of product single page
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 30 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 10 );

/**
 * Registers the `book` post type.
 */
function book_init() {
  register_post_type( 'book', array(
      'labels'                => array(
          'name'                  => __( 'Books', 'twentyseventeen-child' ),
          'singular_name'         => __( 'Book', 'twentyseventeen-child' ),
          'all_items'             => __( 'All Books', 'twentyseventeen-child' ),
          'archives'              => __( 'Book Archives', 'twentyseventeen-child' ),
          'attributes'            => __( 'Book Attributes', 'twentyseventeen-child' ),
          'insert_into_item'      => __( 'Insert into Book', 'twentyseventeen-child' ),
          'uploaded_to_this_item' => __( 'Uploaded to this Book', 'twentyseventeen-child' ),
          'featured_image'        => _x( 'Featured Image', 'book', 'twentyseventeen-child' ),
          'set_featured_image'    => _x( 'Set featured image', 'book', 'twentyseventeen-child' ),
          'remove_featured_image' => _x( 'Remove featured image', 'book', 'twentyseventeen-child' ),
          'use_featured_image'    => _x( 'Use as featured image', 'book', 'twentyseventeen-child' ),
          'filter_items_list'     => __( 'Filter Books list', 'twentyseventeen-child' ),
          'items_list_navigation' => __( 'Books list navigation', 'twentyseventeen-child' ),
          'items_list'            => __( 'Books list', 'twentyseventeen-child' ),
          'new_item'              => __( 'New Book', 'twentyseventeen-child' ),
          'add_new'               => __( 'Add New', 'twentyseventeen-child' ),
          'add_new_item'          => __( 'Add New Book', 'twentyseventeen-child' ),
          'edit_item'             => __( 'Edit Book', 'twentyseventeen-child' ),
          'view_item'             => __( 'View Book', 'twentyseventeen-child' ),
          'view_items'            => __( 'View Books', 'twentyseventeen-child' ),
          'search_items'          => __( 'Search Books', 'twentyseventeen-child' ),
          'not_found'             => __( 'No Books found', 'twentyseventeen-child' ),
          'not_found_in_trash'    => __( 'No Books found in trash', 'twentyseventeen-child' ),
          'parent_item_colon'     => __( 'Parent Book:', 'twentyseventeen-child' ),
          'menu_name'             => __( 'Books', 'twentyseventeen-child' ),
      ),
      'public'                => true,
      'hierarchical'          => false,
      'show_ui'               => true,
      'show_in_nav_menus'     => true,
      'supports'              => array( 'title', 'editor' ),
      'has_archive'           => true,
      'rewrite'               => true,
      'query_var'             => true,
      'menu_icon'             => 'dashicons-admin-post',
      'show_in_rest'          => true,
      'rest_base'             => 'book',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
  ) );

}
add_action( 'init', 'book_init' );

/**
 * Sets the post updated messages for the `book` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `book` post type.
 */
function book_updated_messages( $messages ) {
  global $post;

  $permalink = get_permalink( $post );

  $messages['book'] = array(
      0  => '', // Unused. Messages start at index 1.
    /* translators: %s: post permalink */
      1  => sprintf( __( 'Book updated. <a target="_blank" href="%s">View Book</a>', 'twentyseventeen-child' ), esc_url( $permalink ) ),
      2  => __( 'Custom field updated.', 'twentyseventeen-child' ),
      3  => __( 'Custom field deleted.', 'twentyseventeen-child' ),
      4  => __( 'Book updated.', 'twentyseventeen-child' ),
    /* translators: %s: date and time of the revision */
      5  => isset( $_GET['revision'] ) ? sprintf( __( 'Book restored to revision from %s', 'twentyseventeen-child' ), wp_post_revision_title( (int) $_GET['revision'], false ) )
          : false,
    /* translators: %s: post permalink */
      6  => sprintf( __( 'Book published. <a href="%s">View Book</a>', 'twentyseventeen-child' ), esc_url( $permalink ) ),
      7  => __( 'Book saved.', 'twentyseventeen-child' ),
    /* translators: %s: post permalink */
      8  => sprintf( __( 'Book submitted. <a target="_blank" href="%s">Preview Book</a>', 'twentyseventeen-child' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) )
  ,
    /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
      9  => sprintf( __( 'Book scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Book</a>', 'twentyseventeen-child' ),
          date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
    /* translators: %s: post permalink */
      10 => sprintf( __( 'Book draft updated. <a target="_blank" href="%s">Preview Book</a>', 'twentyseventeen-child' ), esc_url( add_query_arg( 'preview', 'true', $permalink )
      ) ),
  );

  return $messages;
}
add_filter( 'post_updated_messages', 'book_updated_messages' );

/**
 * Registers the `genre` taxonomy,
 * for use with 'book'.
 */
function genre_init() {
  register_taxonomy( 'genre', array( 'book' ), array(
      'hierarchical'      => true,
      'public'            => true,
      'show_in_nav_menus' => true,
      'show_ui'           => true,
      'show_admin_column' => false,
      'query_var'         => true,
      'rewrite'           => true,
      'capabilities'      => array(
          'manage_terms'  => 'edit_posts',
          'edit_terms'    => 'edit_posts',
          'delete_terms'  => 'edit_posts',
          'assign_terms'  => 'edit_posts',
      ),
      'labels'            => array(
          'name'                       => __( 'Genres', 'YOUR-TEXTDOMAIN' ),
          'singular_name'              => _x( 'Genre', 'taxonomy general name', 'YOUR-TEXTDOMAIN' ),
          'search_items'               => __( 'Search Genres', 'YOUR-TEXTDOMAIN' ),
          'popular_items'              => __( 'Popular Genres', 'YOUR-TEXTDOMAIN' ),
          'all_items'                  => __( 'All Genres', 'YOUR-TEXTDOMAIN' ),
          'parent_item'                => __( 'Parent Genre', 'YOUR-TEXTDOMAIN' ),
          'parent_item_colon'          => __( 'Parent Genre:', 'YOUR-TEXTDOMAIN' ),
          'edit_item'                  => __( 'Edit Genre', 'YOUR-TEXTDOMAIN' ),
          'update_item'                => __( 'Update Genre', 'YOUR-TEXTDOMAIN' ),
          'view_item'                  => __( 'View Genre', 'YOUR-TEXTDOMAIN' ),
          'add_new_item'               => __( 'New Genre', 'YOUR-TEXTDOMAIN' ),
          'new_item_name'              => __( 'New Genre', 'YOUR-TEXTDOMAIN' ),
          'separate_items_with_commas' => __( 'Separate Genres with commas', 'YOUR-TEXTDOMAIN' ),
          'add_or_remove_items'        => __( 'Add or remove Genres', 'YOUR-TEXTDOMAIN' ),
          'choose_from_most_used'      => __( 'Choose from the most used Genres', 'YOUR-TEXTDOMAIN' ),
          'not_found'                  => __( 'No Genres found.', 'YOUR-TEXTDOMAIN' ),
          'no_terms'                   => __( 'No Genres', 'YOUR-TEXTDOMAIN' ),
          'menu_name'                  => __( 'Genres', 'YOUR-TEXTDOMAIN' ),
          'items_list_navigation'      => __( 'Genres list navigation', 'YOUR-TEXTDOMAIN' ),
          'items_list'                 => __( 'Genres list', 'YOUR-TEXTDOMAIN' ),
          'most_used'                  => _x( 'Most Used', 'genre', 'YOUR-TEXTDOMAIN' ),
          'back_to_items'              => __( '&larr; Back to Genres', 'YOUR-TEXTDOMAIN' ),
      ),
      'show_in_rest'      => true,
      'rest_base'         => 'genre',
      'rest_controller_class' => 'WP_REST_Terms_Controller',
  ) );

}
add_action( 'init', 'genre_init' );

/**
 * Sets the post updated messages for the `genre` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `genre` taxonomy.
 */
function genre_updated_messages( $messages ) {

  $messages['genre'] = array(
      0 => '', // Unused. Messages start at index 1.
      1 => __( 'Genre added.', 'YOUR-TEXTDOMAIN' ),
      2 => __( 'Genre deleted.', 'YOUR-TEXTDOMAIN' ),
      3 => __( 'Genre updated.', 'YOUR-TEXTDOMAIN' ),
      4 => __( 'Genre not added.', 'YOUR-TEXTDOMAIN' ),
      5 => __( 'Genre not updated.', 'YOUR-TEXTDOMAIN' ),
      6 => __( 'Genres deleted.', 'YOUR-TEXTDOMAIN' ),
  );

  return $messages;
}
add_filter( 'term_updated_messages', 'genre_updated_messages' );

/**
 * Add the custom columns to the book post type:
 */
add_filter( 'manage_book_posts_columns', 'set_custom_edit_book_columns' );
function set_custom_edit_book_columns() {
  return [
      'cb' => '<input type="checkbox" />',
      'title' => __('Title'),
      'book_genre' =>__( 'Genre'),
      'date' => __('Date')
  ];
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_book_posts_custom_column' , 'custom_book_column', 10, 2 );
function custom_book_column( $column, $post_id ) {
  if ($column === 'book_genre') {
    $terms = get_the_term_list( $post_id , 'genre' , '' , ',' , '' );

    if ( is_string( $terms ) )
      echo $terms;
    else
      _e( 'Unable to get genre(s)', 'twentyseventeen-child' );
  }
}
