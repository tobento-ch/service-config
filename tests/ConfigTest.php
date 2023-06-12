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
use Tobento\Service\Config\Config;
use Tobento\Service\Config\ConfigInterface;
use Tobento\Service\Config\DataInterface;
use Tobento\Service\Config\PhpLoader;
use Tobento\Service\Config\ConfigLoadException;
use Tobento\Service\Config\ConfigNotFoundException;
use Tobento\Service\Collection\Translations;
use Tobento\Service\Dir\Dirs;

/**
 * ConfigTest tests
 */
class ConfigTest extends TestCase
{    
    public function testThatImplementsConfigInterface()
    {
        $this->assertInstanceOf(
            ConfigInterface::class,
            new Config(new Translations())
        );     
    }
    
    public function testAddLoaderAndLoaderMethod()
    {
        $config = new Config(new Translations());
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $config->addLoader($loader);
        
        $this->assertSame(
            $loader,
            $config->loader('php')
        );        
    }
    
    public function testLoadMethodReturnsLoadedData()
    {
        $config = new Config(new Translations());
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $config->addLoader($loader);
        
        $this->assertSame(
            [
                'name' => 'Tobento',
                'author' => 'Tobias',
            ],
            $config->load('app.php')
        );        
    }
    
    public function testLoadMethodShouldNotStoreDataIfNoKeyIsSet()
    {
        $config = new Config(new Translations());
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $config->addLoader($loader);
        
        $config->load('app.php');
            
        $this->assertSame(
            'default',
            $config->get('name', 'default')
        );        
    }
    
    public function testLoadMethodStoresDataByKeySet()
    {
        $config = new Config(new Translations());
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $config->addLoader($loader);
        
        $config->load('app.php', 'app');
            
        $this->assertSame(
            'Tobento',
            $config->get('app.name')
        );        
    }
    
    public function testLoadMethodWithLocale()
    {
        $config = new Config(new Translations());
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $config->addLoader($loader);
        
        $config->load('app.php', 'app');
        $config->load('de/app.php', 'app', 'de');
            
        $this->assertSame(
            'Tobento',
            $config->get('app.name')
        );
        
        $this->assertSame(
            'Tobento De',
            $config->get(key: 'app.name', locale: 'de')
        );
        
        $this->assertSame(
            'Tobento',
            $config->get(key: 'app.name', locale: 'it')
        );
        
        $this->assertSame(
            'Tobento It',
            $config->get(key: 'app.name', default: 'Tobento It', locale: 'it')
        );
    }
    
    public function testLoadMethodThrowsConfigLoadExceptionIfNoLoaderFoundForTheExtension()
    {
        $this->expectException(ConfigLoadException::class);
        
        $config = new Config(new Translations());
        
        $config->load('app.php', 'app');
    }
    
    public function testDataMethod()
    {
        $config = new Config(new Translations());
        
        $loader = new PhpLoader(
            (new Dirs())->dir(__DIR__.'/config')
        );
        
        $config->addLoader($loader);

        $this->assertInstanceof(
            DataInterface::class,
            $config->data('app.php')
        );
        
        $this->assertSame(
            [
                'name' => 'Tobento',
                'author' => 'Tobias',
            ],
            $config->data('app.php')->data()
        );
    }
    
    public function testSetMethod()
    {
        $config = new Config(new Translations());

        $config->set('database', [
            'host' => 'localhost',
            'name' => 'db_name',
        ]);
        
        $config->set('database.driver', 'mysql');
            
        $this->assertSame(
            [
                'host' => 'localhost',
                'name' => 'db_name',
                'driver' => 'mysql',
            ],
            $config->get('database')
        );
        
        $this->assertSame(
            'db_name',
            $config->get('database.name')
        );
        
        $this->assertSame(
            'mysql',
            $config->get('database.driver')
        );
    }
    
    public function testSetMethodWithLocale()
    {
        $config = new Config(new Translations());

        $config->set('site', [
            'name' => 'Tobento',
            'author' => 'Tobias',
        ]);
        
        $config->set('site', [
            'name' => 'Tobento De',
        ], 'de');
            
        $this->assertSame(
            [
                'name' => 'Tobento',
                'author' => 'Tobias',
            ],
            $config->get('site')
        );
        
        $this->assertSame(
            [
                'name' => 'Tobento De',
            ],
            $config->get('site', null, 'de')
        );        
    }    
    
    public function testGetMethodThrowsConfigNotFoundException()
    {
        $this->expectException(ConfigNotFoundException::class);
        
        $config = new Config(new Translations());

        $config->get('sitename');      
    }
    
    public function testGetMethodWithLocaleThrowsConfigNotFoundException()
    {
        $this->expectException(ConfigNotFoundException::class);
        
        $config = new Config(new Translations());
        $config->set('sitename', null, 'de');
        $config->get('sitename', null, 'fr');
    }
    
    public function testGetMethodDoesNotThrowsConfigNotFoundExceptionWithDefaultValue()
    {    
        $config = new Config(new Translations());

        $config->get('sitename', 'default');
        
        $this->assertTrue(true);
    }
    
    public function testGetMethodReturnsValue()
    {        
        $config = new Config(new Translations());
        
        $config->set('name', 'Foo');
        $config->set('site.name', 'Tobento');

        $this->assertSame(
            'Foo',
            $config->get('name')
        );
        
        $this->assertSame(
            'Tobento',
            $config->get('site.name')
        );         
    }
    
    public function testGetMethodReturnsDefaultValueIfNotFound()
    {        
        $config = new Config(new Translations());
        
        $this->assertSame(
            'Default',
            $config->get('site.name', 'Default')
        );         
    }
    
    public function testGetMethodReturnsDefaultValueIfTypeDoesNotMatch()
    {        
        $config = new Config(new Translations());
        
        $config->set('site.name', 'Tobento');
        
        $this->assertSame(
            [],
            $config->get('site.name', [])
        ); 
        
        $this->assertSame(
            'Tobento',
            $config->get('site.name', '')
        ); 
    }
    
    public function testGetMethodWithLocale()
    {        
        $config = new Config(new Translations());
        $config->set('sitename', 'Sitename');
        $config->set('sitename', 'Seitenname', 'de');
        
        $this->assertSame(
            'Sitename',
            $config->get('sitename')
        );
        
        $this->assertSame(
            'Seitenname',
            $config->get(key: 'sitename', locale: 'de')
        );
        
        // returns default as locale does not exist
        // and no other fallback is set:
        $this->assertSame(
            'Sitename',
            $config->get(key: 'sitename', locale: 'it')
        );       
        
        $this->assertSame(
            'Site It',
            $config->get(key: 'sitename', default: 'Site It', locale: 'it')
        );        
    }
    
    public function testGetMethodWithNullValueSet()
    {        
        $config = new Config(new Translations());
        $config->set('sitename', null);
        
        $this->assertSame(
            null,
            $config->get('sitename')
        );         
    }
    
    public function testGetMethodWithLocaleNullValueSet()
    {        
        $config = new Config(new Translations());
        $config->set('sitename', null, 'de');
        
        $this->assertSame(null, $config->get('sitename', null, 'de'));
    }
    
    public function testHasMethod()
    {        
        $config = new Config(new Translations());
        
        $config->set('name', 'Foo');
        $config->set('site.name', 'Tobento');

        $this->assertTrue($config->has('name'));
        $this->assertTrue($config->has('site'));
        $this->assertTrue($config->has('site.name'));
        $this->assertFalse($config->has('site.foo'));
        $this->assertFalse($config->has('foo'));
    }
    
    public function testHasMethodWithLocale()
    {        
        $config = new Config(new Translations());
        
        $config->set('sitename', 'Sitename');
        $config->set('sitename', 'Seitenname', 'de');

        $this->assertTrue($config->has('sitename'));
        $this->assertTrue($config->has('sitename', 'de'));
        $this->assertFalse($config->has('sitename', 'fr'));
    }
}