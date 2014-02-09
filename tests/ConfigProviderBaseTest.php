<?php

abstract class ConfigProviderBaseTest extends PHPUnit_Framework_TestCase
{
  /**
   * @return \Packaged\Config\ConfigProviderInterface
   */
  abstract public function getConfigProvider();

  public function testCanBeLoaded()
  {
    $provider = $this->getConfigProvider();
    $this->assertNotNull($provider);
  }

  public function testValidProvider()
  {
    $provider = $this->getConfigProvider();
    $this->assertInstanceOf(
      '\Packaged\Config\ConfigProviderInterface',
      $provider
    );
  }

  /**
   * @depends testValidProvider
   */
  public function testCanAddItem()
  {
    $provider = $this->getConfigProvider();
    $return   = $provider->addItem("database", "hostname", "localhost");
    $this->assertInstanceOf(
      '\Packaged\Config\Provider\Test\TestConfigProvider',
      $return
    );
  }

  /**
   * @depends testValidProvider
   */
  public function testCanAddItemAndRetrieve()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("database", "hostname", "localhost");
    $item = $provider->getItem("database", "hostname", "notset");
    $this->assertEquals("localhost", $item);
  }

  /**
   * @depends testValidProvider
   */
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

  /**
   * @depends testValidProvider
   */
  public function testSectionGetItem()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("database", "hostname", "localhost");
    $section = $provider->getSection("database");
    $this->assertEquals("localhost", $section->getItem("hostname", "notset"));
  }

  /**
   * @depends testValidProvider
   */
  public function testSectionExists()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("database", "hostname", "localhost");
    $exists = $provider->sectionExists("database");
    $this->assertTrue($exists);
  }

  /**
   * @depends testValidProvider
   */
  public function testSectionNameGet()
  {
    $provider = $this->getConfigProvider();
    $provider->addItem("database", "hostname", "localhost");
    $section = $provider->getSection("database");
    $this->assertEquals("database", $section->getName());
  }
}
