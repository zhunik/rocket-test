<?php
$root_dir = dirname(__DIR__);

$dotenv = new Dotenv\Dotenv($root_dir);

if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    $dotenv->required(['ENV', 'API_URL', 'API_USER', 'API_PASSWORD']);
}


define('ENV', getenv('ENV'));
define('API_URL', getenv('API_URL'));
define('API_USER', getenv('API_USER'));
define('API_PASSWORD', getenv('API_PASSWORD'));

define('NOTAM_SCHEMA_FILE', $root_dir.'/resources/REQNOTAM.xsd');

date_default_timezone_set('Europe/Kiev');