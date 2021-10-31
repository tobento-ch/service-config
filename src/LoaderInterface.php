<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Config;

/**
 * Loader interface
 */
interface LoaderInterface
{
    /**
     * Get the extension.
     *
     * @return string
     */    
    public function extension(): string;
    
    /**
     * Load the data.
     *
     * @param string $file The file.
     * @return array The data parsed.
     * @throws ConfigLoadException
     */    
    public function load(string $file): array;
}