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
use Tobento\Service\Config\DataInterface;
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
        $dirs = (new Dirs())->dir(__DIR__.'/config', 'config');
        
        $loader = new PhpLoader($dirs);
        
        $data = $loader->load('app.php');
        
        $this->assertInstanceOf(DataInterface::class, $data);
        
        $this->assertSame($dirs->get('config').'app.php', $data->file());
        
        $this->assertSame(
            [
                'name' => 'Tobento',
                'author' => 'Tobias',
            ],
            $data->data()
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
            $loader->load('app.php')->data()
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
    
    public function testLoadMethodThrowsConfigLoadExceptionIfFileIsNotWithinDir()
    {
        $this->expectException(ConfigLoadException::class);
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config/de')
        );
        
        $loader->load('../app.php');
    }
}