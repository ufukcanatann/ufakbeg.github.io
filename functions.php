<?php
/**
 * Theme constants definition and functions.
 *
 * @since   1.0.0
 * @package Claue
 */

// Constants definition
define( 'JAS_CLAUE_PATH', get_template_directory()     );
define( 'JAS_CLAUE_URL',  get_template_directory_uri() );
define( 'JAS_CLAUE_VERSION', '2.1.8' );

// Initialize core file
require JAS_CLAUE_PATH . '/core/init.php';