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

use Tobento\Service\Dir\DirsInterface;

/**
 * PhpLoader
 */
class PhpLoader implements LoaderInterface
{
    /**
     * Create a new PhpLoader.
     *
     * @param DirsInterface $dirs
     */    
    public function __construct(
        protected DirsInterface $dirs,
    ) {}
    
    /**
     * Get the extension.
     *
     * @return string
     */    
    public function extension(): string
    {
        return 'php';
    }

    /**
     * Load the data.
     *
     * @param string $file The file.
     * @return array The data parsed.
     * @throws ConfigLoadException
     */    
    public function load(string $file): array
    {
        foreach($this->dirs->all() as $dir)
        {
            try {
                return $this->loadFile($dir->dir().$file);
            } catch (ConfigLoadException $e) {
                continue;
            }
        }
        
        throw new ConfigLoadException(
            $file,
            'Php config file "'.$file.'" not found!'
        );
    }
    
    /**
     * Load the file.
     *
     * @param string $file The file.
     * @return array The data parsed.
     * @throws ConfigLoadException
     */    
    protected function loadFile(string $file): array
    {        
        if (! file_exists($file))
        {
            throw new ConfigLoadException(
                $file,
                'Php config file "'.$file.'" not found!'
            );
        }
        
        $fileData = require $file;

        // Check for array and empty.
        if (!$fileData || !is_array($fileData))
        {
            throw new ConfigLoadException(
                $file,
                'Php config file "'.$file.'" must return an array!'
            );
        }
        
        return $fileData;
    }
}