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
use Tobento\Service\Dir\DirInterface;
use Tobento\Service\Filesystem\File;

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
                return $this->loadFile($dir, $file);
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
     * @param DirInterface $dir
     * @param string $file The file.
     * @return array The data parsed.
     * @throws ConfigLoadException
     */    
    protected function loadFile(DirInterface $dir, string $file): array
    {
        $file = new File($dir->dir().$file);
        
        if (!$file->isWithinDir($dir->dir()))
        {
            throw new ConfigLoadException(
                $file->getFile(),
                'Php config file "'.$file->getFile().'" is not within dir "'.$dir->dir().'"!'
            );
        }
        
        if (!$file->isFile())
        {
            throw new ConfigLoadException(
                $file->getFile(),
                'Php config file "'.$file->getFile().'" not found!'
            );
        }
        
        $fileData = require $file->getFile();

        // Check for array and empty.
        if (!$fileData || !is_array($fileData))
        {
            throw new ConfigLoadException(
                $file->getFile(),
                'Php config file "'.$file->getFile().'" must return an array!'
            );
        }
        
        return $fileData;
    }
}