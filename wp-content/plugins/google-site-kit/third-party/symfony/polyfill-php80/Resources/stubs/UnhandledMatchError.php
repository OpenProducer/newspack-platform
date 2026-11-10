<?php

namespace Google\Site_Kit_Dependencies;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
if (\PHP_VERSION_ID < 80000) {
    class UnhandledMatchError extends \Error
    {
    }
    \class_alias('Google\Site_Kit_Dependencies\UnhandledMatchError', 'UnhandledMatchError', \false);
}
