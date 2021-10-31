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

namespace Tobento\Service\Config\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Config\LoaderInterface;
use Tobento\Service\Config\PhpLoader;
use Tobento\Service\Config\ConfigLoadException;
use Tobento\Service\Dir\Dirs;

/**
 * PhpLoaderTest tests
 */
class PhpLoaderTest extends TestCase
{    
    public function testThatImplementsLoaderInterface()
    {
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $this->assertInstanceOf(
            LoaderInterface::class,
            $loader
        );     
    }
    
    public function testExtensionMethod()
    {
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $this->assertSame(
            'php',
            $loader->extension()
        );        
    }
    
    public function testLoadMethod()
    {
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $this->assertSame(
            [
                'name' => 'Tobento',
                'author' => 'Tobias',
            ],
            $loader->load('app.php')
        );        
    }
    
    public function testLoadMethodWithDirsPriority()
    {
        $dirs = new Dirs();
        $dirs->dir(dir: __DIR__.'/config', priority: 5)
             ->dir(dir: __DIR__.'/config-dev', priority: 10);
        
        $loader = new PhpLoader($dirs->sort());
        
        $this->assertSame(
            [
                'name' => 'Tobento Dev',
                'author' => 'Tobias',
            ],
            $loader->load('app.php')
        );        
    }
    
    public function testLoadMethodThrowsConfigLoadExceptionIfFileNotFound()
    {
        $this->expectException(ConfigLoadException::class);
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $loader->load('site.php');      
    }
    
    public function testLoadMethodThrowsConfigLoadExceptionIfFileDoesNotReturnArray()
    {
        $this->expectException(ConfigLoadException::class);
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $loader->load('invalid.php');      
    }    
}