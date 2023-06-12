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
 * DataInterface
 */
interface DataInterface
{
    /**
     * Returns the data.
     *
     * @return array
     */
    public function data(): array;
    
    /**
     * Returns a new instance with the specified data.
     *
     * @param array $data
     * @return static
     */
    public function withData(array $data): static;
    
    /**
     * Returns the file.
     *
     * @return string
     */
    public function file(): string;
}