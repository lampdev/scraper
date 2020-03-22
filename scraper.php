<?php
/**
 * Scraper entrypoint and main script
 */

require_once 'vendor/autoload.php';

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

define('BASE_URL','https://www.walmart.com');
define('ALL_DEPARTMENTS');