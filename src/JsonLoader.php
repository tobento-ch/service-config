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
use Tobento\Service\Filesystem\JsonFile;

/**
 * JsonLoader
 */
class JsonLoader implements LoaderInterface
{
    /**
     * Create a new JsonLoader.
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
        return 'json';
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
            'Json config file "'.$file.'" not found!'
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
        $file = new JsonFile($dir->dir().$file);
        
        if (!$file->isWithinDir($dir->dir()))
        {
            throw new ConfigLoadException(
                $file->getFile(),
                'Json config file "'.$file->getFile().'" is not within dir "'.$dir->dir().'"!'
            );
        }
        
        if (!$file->isJson())
        {
            throw new ConfigLoadException(
                $file->getFile(),
                'Json config file "'.$file->getFile().'" not found or invalid!'
            );
        }
        
        return $file->toArray();
    }
}