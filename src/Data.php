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
 * Data
 */
class Data implements DataInterface
{
    /**
     * Create a new Data.
     *
     * @param array $data
     * @param string $file
     */
    public function __construct(
        protected array $data,
        protected string $file,
    ) {}
    
    /**
     * Returns the data.
     *
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }
    
    /**
     * Returns a new instance with the specified data.
     *
     * @param array $data
     * @return static
     */
    public function withData(array $data): static
    {
        $new = clone $this;
        $new->data = $data;
        return $new;
    }
    
    /**
     * Returns the file.
     *
     * @return string
     */
    public function file(): string
    {
        return $this->file;
    }
}