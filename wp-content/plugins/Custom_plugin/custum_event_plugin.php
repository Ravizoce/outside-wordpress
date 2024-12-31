<?php
/**
 * Plugin Name: Custom Events Plugin
 * Description: Adds a custom database table for managing events.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit;
}

function create_events_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'events';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        date DATE NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME,
        location VARCHAR(255) NOT NULL,
        organizer VARCHAR(255) NOT NULL,
        contact VARCHAR(255) NOT NULL,
        media_url VARCHAR(255),
        category VARCHAR(100),
        tags JSON,
        is_featured BOOLEAN DEFAULT FALSE,
        status ENUM('upcoming', 'ongoing', 'completed', 'canceled') DEFAULT 'upcoming',
        -- price DECIMAL(10, 2) DEFAULT 0.00,
        -- capacity INT DEFAULT 0,
        views_count INT DEFAULT 0,
        -- likes_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}


register_activation_hook(__FILE__, 'create_events_table');
