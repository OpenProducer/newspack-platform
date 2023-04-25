<?php

namespace Google\Site_Kit_Dependencies;

// Don't redefine the functions if included multiple times.
if (!\function_exists('Google\\Site_Kit_Dependencies\\GuzzleHttp\\uri_template')) {
    require __DIR__ . '/functions.php';
}
