<?php

namespace Packaged\Config\Test;

use Packaged\Config\ConfigSectionInterface;
use Packaged\Config\ConfigurableTrait;
use Packaged\Config\Provider\ConfigSection;
use PHPUnit\Framework\TestCase;

class ConfigurableTraitTest extends TestCase
{
  public function testTrait()
  {
    $config = new ConfigSection('abstract', ['name' => 'test']);
    $mock = new MockConfigurableTrait();
    $mock->configure($config);
    $this->assertSame($config, $mock->config());
  }

  public function testDefaultConfig()
  {
    $mock = new MockConfigurableTrait();
    $this->assertEmpty($mock->config()->getItems());
  }
}

class MockConfigurableTrait
{
  use ConfigurableTrait;

  /**
   * @return ConfigSectionInterface
   */
  public function config()
  {
    return $this->_config();
  }
}
