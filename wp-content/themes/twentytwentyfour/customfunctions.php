<?php
function enqueue_custom_styles()
{
    wp_enqueue_style('theme-style', get_stylesheet_uri());
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/css/event.css', [], '1.0');
    wp_enqueue_script('jquery');
    wp_enqueue_script(
        'custom-script',
        get_template_directory_uri() . '/assets/js/event_card.js',
        ['jquery'],
        '1.0',
        true
    );
    wp_localize_script('custom-script', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');
function register_event_post_type()
{
    $labels = [
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'menu_name'          => 'Events',
        'name_admin_bar'     => 'Event',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'new_item'           => 'New Event',
        'edit_item'          => 'Edit Event',
        'view_item'          => 'View Event',
        'all_items'          => 'All Events',
        'search_items'       => 'Search Events',
        'not_found'          => 'No events found.',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-calendar',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'events'],
        'publicly_queryable' => false,
        'rewrite'            => false,
    ];

    register_post_type('event', $args);
}
add_action('init', 'register_event_post_type');

function add_event_meta_boxes()
{
    add_meta_box(
        'event_media',
        'Event Media',
        'render_event_media_meta_box',
        'event',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_event_meta_boxes');


// Add custom columns to the event list
function add_event_columns($columns)
{
    $columns['status'] = 'Status';
    $columns['boost_status'] = 'Boost Status';
    $columns['boost_priority'] = 'Boost Priority';
    return $columns;
}
add_filter('manage_event_posts_columns', 'add_event_columns');

// Render custom column content
function render_event_columns($column, $post_id)
{
    if ($column === 'status') {
        $status = get_post_status($post_id);
        echo esc_html(ucfirst($status));
    }
    if ($column === 'boost_status') {
        echo esc_html(get_post_meta($post_id, 'boost_status', true) ?: 'No');
    }
    if ($column === 'boost_priority') {
        echo esc_html(get_post_meta($post_id, 'boost_priority', true) ?: 'N/A');
    }
}
add_action('manage_event_posts_custom_column', 'render_event_columns', 10, 2);

// adding fields to quick event
function add_event_quick_edit($column_name, $post_type)
{
    if ($post_type === 'event' && in_array($column_name, ['status', 'boost_status', 'boost_priority', "available_from", "available_to"])) {
?>
        <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <?php if ($column_name === 'boost_status') { ?>
                    <label>
                        <span class="title">Boost Status</span>
                        <select name="boost_status">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </label>
                <?php } elseif ($column_name === 'boost_priority') { ?>
                    <label class="is-flex-container>
                        <span class=" title">Boost Priority</span>
                        <input type="number" name="boost_priority" min="1" max="5" value="1">
                    </label>
                <?php } ?>
            </div>
        </fieldset>
    <?php
    }
}
add_action('quick_edit_custom_box', 'add_event_quick_edit', 10, 2);

// quick edit data saver
function save_event_quick_edit($post_id)
{
    if (isset($_POST['boost_status'])) {
        update_post_meta($post_id, 'boost_status', sanitize_text_field($_POST['boost_status']));
    }
    if (isset($_POST['boost_priority'])) {
        update_post_meta($post_id, 'boost_priority', (int)$_POST['boost_priority']);
    }
}
add_action('save_post', 'save_event_quick_edit');


function save_availability_meta_box($post_id)
{
    if (isset($_POST['available_from'])) {
        update_post_meta($post_id, 'available_from', sanitize_text_field($_POST['available_from']));
    }
    if (isset($_POST['available_to'])) {
        update_post_meta($post_id, 'available_to', sanitize_text_field($_POST['available_to']));
    }
}
add_action('save_post', 'save_availability_meta_box');



//code for video addition

function render_event_media_meta_box($post)
{
    wp_nonce_field('save_event_media', 'event_media_nonce');

    $event_video = get_post_meta($post->ID, '_event_video', true);

    echo '<p><label for="event_video">Video URL</label></p>';
    echo '<input type="text" id="event_video" name="event_video" value="' . esc_attr($event_video) . '" style="width: 100%;">';
    echo '<p class="description">Add a YouTube or Vimeo video URL. If empty, the featured image will be used.</p>';
}

// Save Meta Box Data
function save_event_meta_box($post_id)
{
    if (!isset($_POST['event_media_nonce']) || !wp_verify_nonce($_POST['event_media_nonce'], 'save_event_media')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['event_video'])) {
        update_post_meta($post_id, '_event_video', sanitize_text_field($_POST['event_video']));
    }
}
add_action('save_post', 'save_event_meta_box');

//short code for featured card
// Register the shortcode
function display_event_data_shortcode($atts)
{
    $args = [
        'post_type'      => 'event',
        'posts_per_page' => 8,
        'meta_query'     => [
            [
                'key'     => 'boost_status',
                'value'   => 'Yes',
                'compare' => '='
            ]
        ],
        'orderby'        => 'meta_value_num',
        'meta_key'       => 'boost_priority',
        'order'          => 'ASC',
    ];

    $events = get_posts($args);

    ob_start();  // Start output buffering
    ?>
    <div class="featured_events">
        <?php
        if (!$events) {
        ?>
            <div class="Text_container" style="margin:10px 0px 100px 0px">
                <div class="Tet_wrapper">
                    <strong>
                        Sorry, No Featured Events at the moment
                    </strong>
                </div>
                <hr>
            </div>

        <?php
            return ob_get_clean();
        }
        foreach ($events as $event) {
            $image = get_post_meta($event->ID, '_thumbnail_id');

            if ($image) {
                // Get the URL of the image using the attachment ID
                $image_url = wp_get_attachment_url($image[0]);
            }
        ?>
            <div class="card_wrapper" id="featured_events<?php echo $event->ID ?>">
                <div class="card">
                    <div class="card_head">
                        <div class="card_content">
                            <div class="content_front">
                                <div class="media">
                                    <img src="<?php echo $image_url ?>" alt="<?php echo basename($image_url) ?>" />
                                </div>
                                <div class="card_description title">
                                    <p>
                                        <?php echo $event->post_title ?>
                                    </p>
                                </div>
                            </div>
                            <div class="content_back display_none">
                                <div class="card_description">
                                    <p>
                                        <?php echo $event->post_content ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="button" id="card_button_wrapper" onclick="animator('featured_events<?php echo $event->ID ?>')">
                            <span class="span card_button">+</span>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('event_data', 'display_event_data_shortcode');
function display_search_box_html()
{
    ob_start();
?>
    <form method="get" class="search_form">
        <div class="search_wrapper">
            <input class="search_input" id="search_box" oninput="searchAjax()" type="text" for="search" name="search" placeholder="Search event">
            <span class="dashicons dashicons-search search_button"></span>
        </div>
    </form>
<?php
    return ob_get_clean();
}

add_shortcode('search_box', 'display_search_box_html');

function display_searched_event()
{
    $search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    $args = [
        'post_type'      => 'event',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if (!empty($search_query)) {
        $args['s'] = $search_query;
    }

    $events = get_posts($args);

    $results = "";

    if (!$events) {
        $results .= '
            <div class="Text_container" style="margin:10px 0px 100px 0px">
                <div class="Tet_wrapper">
                    <strong>
                        Sorry, No related Events found
                    </strong>
                </div>
                <hr>
            </div>';
    } else {
        foreach ($events as $event) {
            $image = get_post_meta($event->ID, "_thumbnail_id");

            if ($image) {

                $image_url = wp_get_attachment_url($image[0]);
            } else {
                $image_url = '';
            }
            $preID = "searched_events" . $event->ID;
            $results .= '
                <div class="card_wrapper" id="searched_events' . $event->ID . '">
                    <div class="card">
                        <div class="card_head">
                            <div class="card_content">
                                <div class="content_front">
                                    <div class="media">
                                        <img src="' . esc_url($image_url) . '" alt="' . basename($image_url) . '" />
                                    </div>
                                    <div class="card_description title">
                                        <p>' . esc_html($event->post_title) . '</p>
                                    </div>
                                </div>
                                <div class="content_back display_none">
                                    <div class="card_description">
                                        <p>' . esc_html($event->post_content) . '</p>
                                    </div>
                                </div>
                            </div>
                            <div class="button" id="card_button_wrapper" onclick="animator(\'' . $preID . '\')">
                                <span class="span card_button" >+</span>
                            </div>
                        </div>
                    </div>
                </div>';
        }
    }


    echo json_encode($results);
    wp_die();
}
add_action('wp_ajax_filter_events', 'display_searched_event');
add_action('wp_ajax_nopriv_filter_events', 'display_searched_event');
