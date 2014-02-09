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
    $this->assertEquals(
      "localhost",
      $provider->getItem("database", "hostname", "non")
    );

    $provider->addSection(
      new \Packaged\Config\Provider\Test\TestConfigSection("db")
    );
    $this->assertInstanceOf(
      '\Packaged\Config\Provider\Test\TestConfigProvider',
      $provider->addItem('db', 'hostname', 'localhost')
    );
    $this->assertEquals(
      "localhost",
      $provider->getItem("db", "hostname", "non")
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
  public function testGetSections()
  {
    $provider = $this->getConfigProvider();
    $provider->addSection(
      new \Packaged\Config\Provider\Test\TestConfigSection("db")
    );
    $provider->addSection(
      new \Packaged\Config\Provider\Test\TestConfigSection("database")
    );
    $this->assertContainsOnlyInstancesOf(
      '\Packaged\Config\ConfigSectionInterface',
      $provider->getSections()
    );
  }

  /**
   * @depends testValidProvider
   */
  public function testSectionAdd()
  {
    $section  = new \Packaged\Config\Provider\Test\TestConfigSection("db");
    $provider = $this->getConfigProvider();
    $this->assertInstanceOf(
      '\Packaged\Config\ConfigProviderInterface',
      $provider->addSection($section)
    );
    $this->setExpectedException(
      '\Exception',
      "The section db cannot be re-added"
    );
    $provider->addSection($section);
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
  public function testMissingSectionThrows()
  {
    $provider = $this->getConfigProvider();
    $this->setExpectedException(
      "Exception",
      "Configuration section database could not be found"
    );
    $provider->getSection("database");
  }

  /**
   * @depends testValidProvider
   */
  public function testGetMissingItem()
  {
    $provider = $this->getConfigProvider();
    $this->assertEquals(
      "notset",
      $provider->getItem("database", "hostname", "notset")
    );
    $provider->addSection(
      new \Packaged\Config\Provider\Test\TestConfigSection("database")
    );
    $section = $provider->getSection("database");
    $this->assertEquals("notset", $section->getItem("hostname", "notset"));
  }

  /**
   * @depends testValidProvider
   */
  public function testSectionExists()
  {
    $provider = $this->getConfigProvider();
    $this->assertFalse($provider->sectionExists("database"));
    $provider->addItem("database", "hostname", "localhost");
    $this->assertTrue($provider->sectionExists("database"));
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
