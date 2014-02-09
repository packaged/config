<?php

class IniConfigProviderTest extends ConfigProviderBaseTest
{
  /**
   * @return \Packaged\Config\Provider\Ini\IniConfigProvider
   */
  public function getConfigProvider()
  {
    return new \Packaged\Config\Provider\Ini\IniConfigProvider();
  }

  public function testMissingLoadFile()
  {
    $file = dirname(__DIR__) . '/testData/missing.file';
    $this->setExpectedException(
      "RuntimeException",
      "Config file '$file' could not be found"
    );
    $provider = $this->getConfigProvider();
    $provider->loadFile($file);
  }

  public function testInvalidLoadFile()
  {
    $file = dirname(__DIR__) . '/testData/useless.file';
    $this->setExpectedException(
      "RuntimeException",
      "The ini file '$file' is corrupt or invalid"
    );
    $provider = $this->getConfigProvider();
    $provider->loadFile($file);
  }

  public function testLoadFile()
  {
    $file     = dirname(__DIR__) . '/testData/test.ini';
    $provider = $this->getConfigProvider();
    $provider->loadFile($file);
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }

  public function testLoadFileConstruct()
  {
    $file     = dirname(__DIR__) . '/testData/test.ini';
    $provider = new \Packaged\Config\Provider\Ini\IniConfigProvider($file);
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }

  public function testLoadCorruptString()
  {
    $file = dirname(__DIR__) . '/testData/useless.file';
    $this->setExpectedException(
      "RuntimeException",
      "The ini string passed is corrupt or invalid"
    );
    $provider = $this->getConfigProvider();
    $provider->loadString(file_get_contents($file));
  }

  public function testLoadString()
  {
    $file     = dirname(__DIR__) . '/testData/test.ini';
    $provider = $this->getConfigProvider();
    $provider->loadString(file_get_contents($file));
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }
}
