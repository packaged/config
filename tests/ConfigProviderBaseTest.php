<?php

namespace Packaged\Config\Test;

use Exception;
use Packaged\Config\ConfigProviderInterface;
use Packaged\Config\Provider\ConfigSection;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

abstract class ConfigProviderBaseTest extends TestCase
{
  public function testCanBeLoaded()
  {
    $provider = $this->getConfigProvider();
    $this->assertNotNull($provider);
  }

  abstract public function getConfigProvider(): ConfigProviderInterface;

  public function testValidProvider()
  {
    $provider = $this->getConfigProvider();
    $this->assertInstanceOf(
      '\Packaged\Config\ConfigProviderInterface',
      $provider
    );
  }

  #[Depends('testValidProvider')]
  public function testCanAddItem()
  {
    $provider = $this->getConfigProvider();
    $return = $provider->addItem("database", "hostname", "localhost");
    $this->assertInstanceOf(
      '\Packaged\Config\ConfigProviderInterface',
      $return
    );
    $this->assertEquals(
      "localhost",
      $provider->getItem("database", "hostname", "non")
    );

    $provider->addSection(
      new ConfigSection("db")
    );
    $this->assertInstanceOf(
      '\Packaged\Config\ConfigProviderInterface',
      $provider->addItem('db', 'hostname', 'localhost')
    );
    $this->assertEquals(
      "localhost",
      $provider->getItem("db", "hostname", "non")
    );
  }

  #[Depends('testValidProvider')]
  public function testCanAddItemAndRetrieve()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("database", "hostname", "localhost");
    $item = $provider->getItem("database", "hostname", "notset");
    $this->assertEquals("localhost", $item);
  }

  #[Depends('testValidProvider')]
  public function testCanAddItemAndRetrieveSection()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("database", "hostname", "localhost");
    $section = $provider->getSection("database");
    $this->assertInstanceOf(
      '\Packaged\Config\ConfigSectionInterface',
      $section
    );
  }

  #[Depends('testValidProvider')]
  public function testCanAddRemoveItem()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("db", "hostname", "localhost");
    $this->assertEquals("localhost", $provider->getItem("db", "hostname"));
    $provider->removeItem("db", "hostname");
    $this->assertEquals("rm", $provider->getItem("db", "hostname", 'rm'));
  }

  #[Depends('testValidProvider')]
  public function testGetSections()
  {
    $provider = $this->getConfigProvider();
    $provider->addSection(
      new ConfigSection("db")
    );
    $provider->addSection(
      new ConfigSection("database")
    );
    $this->assertContainsOnlyInstancesOf(
      '\Packaged\Config\ConfigSectionInterface',
      $provider->getSections()
    );
  }

  #[Depends('testValidProvider')]
  public function testSectionAdd()
  {
    $section = new ConfigSection("db");
    $provider = $this->getConfigProvider();
    $this->assertInstanceOf(
      '\Packaged\Config\ConfigProviderInterface',
      $provider->addSection($section)
    );
    $this->expectException(Exception::class);
    $this->expectExceptionMessage("The section db cannot be re-added");
    $provider->addSection($section);
  }

  #[Depends('testValidProvider')]
  public function testSectionSet()
  {
    $section = new ConfigSection("db");
    $provider = $this->getConfigProvider();
    $this->assertInstanceOf(
      '\Packaged\Config\ConfigProviderInterface',
      $provider->setSection($section)
    );
    $provider->setSection($section);
  }

  #[Depends('testValidProvider')]
  public function testSectionGetItem()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("database", "hostname", "localhost");
    $section = $provider->getSection("database");
    $this->assertEquals("localhost", $section->getItem("hostname", "notset"));
  }

  #[Depends('testValidProvider')]
  public function testMissingSectionThrows()
  {
    $provider = $this->getConfigProvider();
    $this->expectException(Exception::class);
    $this->expectExceptionMessage("Configuration section database could not be found");
    $provider->getSection("database");
  }

  #[Depends('testValidProvider')]
  public function testMissingSectionNoThrow()
  {
    $provider = $this->getConfigProvider();
    $section = $provider->getSection("missing", false);
    $this->assertInstanceOf(ConfigSection::class, $section);
    $this->assertEquals("missing", $section->getName());
  }

  #[Depends('testValidProvider')]
  public function testGetMissingItem()
  {
    $provider = $this->getConfigProvider();
    $this->assertEquals(
      "notset",
      $provider->getItem("database", "hostname", "notset")
    );
    $provider->addSection(
      new ConfigSection("database")
    );
    $section = $provider->getSection("database");
    $this->assertEquals("notset", $section->getItem("hostname", "notset"));
  }

  #[Depends('testValidProvider')]
  public function testDefaultExceptionThrown()
  {
    $provider = $this->getConfigProvider();
    $this->expectException(Exception::class);
    $this->expectExceptionMessage("Config Item Not Found");
    $this->expectExceptionCode(999);
    $provider->getItem('', 'gone', new Exception("Config Item Not Found", 999));
  }

  #[Depends('testValidProvider')]
  public function testSectionExists()
  {
    $provider = $this->getConfigProvider();
    $this->assertFalse($provider->sectionExists("database"));
    $provider->addItem("database", "hostname", "localhost");
    $this->assertTrue($provider->sectionExists("database"));
    $this->assertTrue($provider->has("database"));
    $this->assertEquals($provider->has("xx"), $provider->sectionExists("xx"));

    $provider->removeSectionByName("database");
    $this->assertFalse($provider->sectionExists("database"));

    $provider->addItem("database", "hostname", "localhost");
    $this->assertTrue($provider->has("database"));
    $provider->removeSection(new ConfigSection("database"));
    $this->assertFalse($provider->sectionExists("database"));
  }

  #[Depends('testValidProvider')]
  public function testHasItem()
  {
    $provider = $this->getConfigProvider();
    $this->assertFalse($provider->hasItem('invalidsection', 'invaliditem'));
    $provider->addItem("newsection", "newitem", "newvalue");
    $this->assertFalse($provider->hasItem('newsection', 'invaliditem'));
    $this->assertFalse($provider->hasItem('invalidsection', 'newitem'));
    $this->assertTrue($provider->hasItem('newsection', 'newitem'));
  }

  #[Depends('testValidProvider')]
  public function testSectionNameGet()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("database", "hostname", "localhost");
    $section = $provider->getSection("database");
    $this->assertEquals("database", $section->getName());
  }
}
