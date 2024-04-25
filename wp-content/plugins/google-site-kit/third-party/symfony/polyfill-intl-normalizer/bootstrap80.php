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
use Google\Site_Kit_Dependencies\Symfony\Polyfill\Intl\Normalizer as p;
if (!\function_exists('normalizer_is_normalized')) {
    function normalizer_is_normalized(?string $string, ?int $form = \Google\Site_Kit_Dependencies\Symfony\Polyfill\Intl\Normalizer\Normalizer::FORM_C) : bool
    {
        return \Google\Site_Kit_Dependencies\Symfony\Polyfill\Intl\Normalizer\Normalizer::isNormalized((string) $string, (int) $form);
    }
}
if (!\function_exists('normalizer_normalize')) {
    function normalizer_normalize(?string $string, ?int $form = \Google\Site_Kit_Dependencies\Symfony\Polyfill\Intl\Normalizer\Normalizer::FORM_C) : string|false
    {
        return \Google\Site_Kit_Dependencies\Symfony\Polyfill\Intl\Normalizer\Normalizer::normalize((string) $string, (int) $form);
    }
}
