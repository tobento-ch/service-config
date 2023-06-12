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
 * ConfigInterface
 */
interface ConfigInterface
{
    /**
     * Add a loader.
     *
     * @param LoaderInterface $loader
     * @return static $this
     */
    public function addLoader(LoaderInterface $loader): static;

    /**
     * Get a loader.
     *
     * @param string $extension The extension such as 'php'
     * @return null|LoaderInterface
     */
    public function loader(string $extension): null|LoaderInterface;  
    
    /**
     * Loads a file and stores config is set.
     *
     * @param string $file The file to load.
     * @param null|string $key If a key is set, it stores as such.
     * @param null|int|string $locale
     * @return array The loaded config data.
     * @throws ConfigLoadException
     */        
    public function load(string $file, null|string $key = null, null|int|string $locale = null): array;

    /**
     * Returns the data for the specified file.
     *
     * @param string $file
     * @return DataInterface
     * @throws ConfigLoadException
     */
    public function data(string $file): DataInterface;
    
    /**
     * Set a value by key.
     * 
     * @param string $key The key.
     * @param mixed $value The value.
     * @param null|string|int|array $locale The locale
     * @return ConfigInterface
     */         
    public function set(string $key, mixed $value, null|int|string|array $locale = null): ConfigInterface;
    
    /**
     * Get a value by key.
     *
     * @param string $key The key.
     * @param mixed $default A default value.
     * @param null|int|string|array $locale 
     *        string: locale,
     *        array: [] if empty gets all languages,
     *        otherwise the keys set ['de', 'en']
     * @return mixed The value or the default value if not exist.
     * throws ConfigNotFoundException
     */
    public function get(string $key, mixed $default = null, null|int|string|array $locale = null): mixed;
    
    /**
     * Returns true if config exists, otherwise false.
     * 
     * @param string $key The key.
     * @param null|string|int|array $locale The locale
     * @return bool
     */
    public function has(string $key, null|int|string|array $locale = null): bool;
}