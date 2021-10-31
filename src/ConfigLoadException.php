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
 * ConfigLoadException
 */
class ConfigLoadException extends Exception
{
    /**
     * Create a new ConfigLoadException
     *
     * @param string $configFile The config file.
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected string $configFile,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Get the config file.
     *
     * @return string
     */
    public function configFile(): string
    {
        return $this->configFile;
    }    
}