<?php

function enqueue_custom_styles()
{
    wp_enqueue_style('theme-style', get_stylesheet_uri()); // Default style.css
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/assets/css/event.css', [], '1.0');
    wp_enqueue_script('custom-script',get_template_directory_uri() . '/assets/js/event_card.js',[],'1.0',true
    );
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
        'supports'           => ['thumbnail'],
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'events'],
    ];

    register_post_type('Event', $args);
}
add_action('init', 'register_event_post_type');

function add_event_meta_boxes()
{
    add_meta_box(
        'event_details',
        'Event Details',
        'render_event_meta_box',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_event_meta_boxes');


function render_event_meta_box($post)
{
    $meta = get_post_meta($post->ID);
?>
    <div>
        <label for="Event Name">Event Name</label>
        <input type="text" name="event_name" value="<?php echo esc_attr($meta['event_name'][0] ?? ''); ?>" />
        <br><br>
        <label for="Event Name">Event Name</label>
        <textarea></textarea>
        <br><br>
        <div>
            <label for="event_date">Event Date:</label>
            <input type="date" name="event_date" value="<?php echo esc_attr($meta['event_date'][0] ?? ''); ?>" />
            <br><br>

            <label for="event_location">Event Location:</label>
            <input type="text" name="event_location" value="<?php echo esc_attr($meta['event_location'][0] ?? ''); ?>" />
            <br><br>
        </div>
    </div>
<?php
}

function save_event_meta_boxes($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['event_date'])) {
        update_post_meta($post_id, 'event_date', sanitize_text_field($_POST['event_date']));
    }
    if (isset($_POST['event_location'])) {
        update_post_meta($post_id, 'event_location', sanitize_text_field($_POST['event_location']));
    }
}
add_action('save_post', 'save_event_meta_boxes');
