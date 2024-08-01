<?php
// Load WordPress
require_once('wp-load.php');

// Set a secret key for security
$secret_key = 'Secret_Key_Here'; // Change this to a long, random string - make sure you surround it with the existing brackets! 

// Check if the provided key matches our secret key
if (empty($_GET['key']) || $_GET['key'] !== $secret_key) {
    die('Unauthorized access');
}

// Get all calendars
$calendars = wpsbc_get_calendars();

foreach ($calendars as $calendar) {
    $calendar_id = $calendar->get('id');
    
    $ical_feeds = wpsbc_get_calendar_meta_ical_feeds($calendar_id);

    foreach ($ical_feeds as $ical_feed) {
        if (empty($ical_feed['id']) || empty($ical_feed['url'])) {
            continue;
        }

        $ical_contents = wp_remote_get($ical_feed['url'], array('timeout' => 30, 'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:20.0) Gecko/20100101 Firefox/20.0'));

        if (wp_remote_retrieve_response_code($ical_contents) != 200) {
            continue;
        }

        $ical_contents = wp_remote_retrieve_body($ical_contents);

        $ical_contents = apply_filters('wpsbc_ical_import_url_file_contents', $ical_contents);

        if (0 !== strpos($ical_contents, 'BEGIN:VCALENDAR') || false === strpos($ical_contents, 'END:VCALENDAR')) {
            continue;
        }

        $ical_feed['file_contents'] = $ical_contents;
        $ical_feed['last_updated'] = current_time('Y-m-d H:i:s');

        wpsbc_update_calendar_meta($calendar_id, 'ical_feed_' . $ical_feed['id'], $ical_feed);
    }
}

echo "iCal refresh completed at " . date('Y-m-d H:i:s');
