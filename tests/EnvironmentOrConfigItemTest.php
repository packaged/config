<?php

use Packaged\Config\EnvironmentOrConfigItem;
use Packaged\Config\Provider\ConfigProvider;

class EnvironmentOrConfigItemTest extends PHPUnit_Framework_TestCase
{
  public function testGetEnv()
  {
    $config = new ConfigProvider();
    $eConfig = new EnvironmentOrConfigItem($config);
    $this->assertEquals('default', $eConfig->get("TEST_AUTHED", 'testing', 'authed', 'default'));

    $config->addItem('testing', 'authed', 'value');
    $this->assertEquals('value', $eConfig->get("TEST_AUTHED", 'testing', 'authed', 'value'));

    putenv("TEST_AUTHED=envvalue");
    $this->assertEquals('envvalue', $eConfig->get("TEST_AUTHED", 'testing', 'authed', 'default'));
  }
}
