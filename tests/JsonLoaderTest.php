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
use Tobento\Service\Config\JsonLoader;
use Tobento\Service\Config\ConfigLoadException;
use Tobento\Service\Dir\Dirs;

/**
 * JsonLoaderTest tests
 */
class JsonLoaderTest extends TestCase
{    
    public function testThatImplementsLoaderInterface()
    {
        $loader = new JsonLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $this->assertInstanceOf(
            LoaderInterface::class,
            $loader
        );     
    }
    
    public function testExtensionMethod()
    {
        $loader = new JsonLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $this->assertSame(
            'json',
            $loader->extension()
        );        
    }
    
    public function testLoadMethod()
    {
        $dirs = (new Dirs())->dir(__DIR__.'/config', 'config');
        
        $loader = new JsonLoader($dirs);
        
        $data = $loader->load('app.json');
        
        $this->assertInstanceOf(DataInterface::class, $data);
        
        $this->assertSame($dirs->get('config').'app.json', $data->file());
        
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
        
        $loader = new JsonLoader($dirs->sort());
        
        $this->assertSame(
            [
                'name' => 'Tobento Dev',
                'author' => 'Tobias',
            ],
            $loader->load('app.json')->data()
        );        
    }
    
    public function testLoadMethodThrowsConfigLoadExceptionIfFileNotFound()
    {
        $this->expectException(ConfigLoadException::class);
        
        $loader = new JsonLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $loader->load('site.json');      
    }
    
    public function testLoadMethodReturnsEmptyDataIfInvalidJson()
    {        
        $loader = new JsonLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );

        $this->assertSame(
            [],
            $loader->load('invalid.json')->data()
        ); 
    }
    
    public function testLoadMethodThrowsConfigLoadExceptionIfFileIsNotWithinDir()
    {
        $this->expectException(ConfigLoadException::class);
        
        $loader = new JsonLoader(
            (new Dirs())->dir(__DIR__.'/config/de')
        );

        $loader->load('../app.json');
    }    
}