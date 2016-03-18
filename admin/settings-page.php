<?php

/*
 *  Creates the main settings page
 *
 */

 /**
 * Adds a new top-level menu to the bottom of the WordPress administration menu.
 */
function eddlaa_create_submenu_page() {
  global $eddlaa_adminpage;

  $eddlaa_adminpage = add_submenu_page(
    'users.php',                  // Register this submenu with the menu defined above
    'EDD License Activity',          // The text to the display in the browser when this menu item is active
    'EDD License Activity',                  // The text for this menu item
    'administrator',            // Which type of users can see this menu
    'edd-laa',          // The unique ID - the slug - for this menu item
    'eddlaa_submenu_page_display'   // The function used to render the menu for this page to the screen
  );

} // end sandbox_create_menu_page
add_action('admin_menu', 'eddlaa_create_submenu_page');

/**
 * Renders the basic display of the menu page for the theme.
 */
function eddlaa_submenu_page_display() {

    // Create a header in the default WordPress 'wrap' container
    $header = '<div class="wrap">';
        $header .= '<h2>EDD License Activation Activity</h2><hr />';
    $header .= '</div>';

    // Send the markup to the browser
    echo $header; ?>
    <script>
    jQuery(document).ready(function($) {
      $('#example').DataTable();
    } );
    </script>
    <table id="example" class="dataTable" cellspacing="0" width="100%">
      <thead>
          <tr>
              <th>Download Name</th>
              <th>Activated Domain</th>
              <th>Activation Date</th>
              <th>WP Version</th>
          </tr>
      </thead>
      <tfoot>
          <tr>
              <th>Download Name</th>
              <th>Activated Domain</th>
              <th>Activation Date</th>
              <th>WP Version</th>
          </tr>
      </tfoot>
      <tbody>
        <?php eddlaa_display_users(); ?>
      </tbody>
    </table>

    <?php
} // end sandbox_menu_page_display

function eddlaa_display_users() {
    $logs = edd_software_licensing()->get_license_logs('');

    //var_dump($logs);

    if( $logs ) {
        $i=0;
        foreach ( $logs as $log ) {
            if($i==10) break;
            preg_match('/log-license-(.+?)-[0-9]+/ism', $log->post_name, $matches);
            $action = $matches[1];

            $data = json_decode(get_post_field('post_content', $log->ID));
            $lic_title = $log->post_title;
            //$pattern = '/^LOG - License Activated: /';
            preg_match('/(?P<name>\w+): (?P<digit>\d+)/', $lic_title, $licmatches);

            $downloadid = edd_software_licensing()->get_download_id($licmatches[2]);
            $downtitle = get_the_title($downloadid);
            //var_dump($downtitle);

            list($wp_info, $site_url) = explode(';', $data->HTTP_USER_AGENT);
            list($wordpress, $version) = explode('/', $wp_info);

                $results .= '
                <tr>
                    <td>' . $downtitle . '</td>
                    <td>
                        <a href="' . esc_attr($site_url) . '" target="_blank">' . esc_html($site_url) . '</a>
                    </td>
                    <td>' . esc_html(ucfirst($action)) . ' on ' . esc_html(date_i18n(get_option('date_format'), $data->REQUEST_TIME) . ' ' . date_i18n(get_option('time_format'), $data->REQUEST_TIME)) . '
                    </td>
                    <td>' . esc_html($version) . '</td>
                </tr>';

            echo $results;
            $i++;
        }
} else {
	// no users found
  $results = 'Sorry! No Users Found';
}

return $results;

}
