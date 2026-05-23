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
if (!\function_exists('normalizer_is_normalized') && !\function_exists('Google\Site_Kit_Dependencies\normalizer_is_normalized')) {
    function normalizer_is_normalized(?string $string, ?int $form = p\Normalizer::FORM_C): bool
    {
        return p\Normalizer::isNormalized((string) $string, (int) $form);
    }
}
if (!\function_exists('normalizer_normalize') && !\function_exists('Google\Site_Kit_Dependencies\normalizer_normalize')) {
    function normalizer_normalize(?string $string, ?int $form = p\Normalizer::FORM_C): string|false
    {
        return p\Normalizer::normalize((string) $string, (int) $form);
    }
}
