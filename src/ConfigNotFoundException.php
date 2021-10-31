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

use Exception;
use Throwable;

/**
 * ConfigNotFoundException
 */
class ConfigNotFoundException extends Exception
{
    /**
     * Create a new ConfigNotFoundException
     *
     * @param string $key, The key.
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected string $key,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Get the key.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }    
}