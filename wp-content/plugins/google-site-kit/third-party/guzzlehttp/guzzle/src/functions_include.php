<?php

namespace Google\Site_Kit_Dependencies;

// Don't redefine the functions if included multiple times.
if (!\function_exists('Google\\Site_Kit_Dependencies\\GuzzleHttp\\describe_type')) {
    require __DIR__ . '/functions.php';
}
