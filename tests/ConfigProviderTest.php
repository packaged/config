<?php

namespace Packaged\Config\Test;

use Packaged\Config\Provider\ConfigProvider;

class ConfigProviderTest extends ConfigProviderBaseTest
{
  public function getConfigProvider()
  {
    return new ConfigProvider([]);
  }

  public function testConstruct()
  {
    $config = [
      'section1' => ['item1' => 'value1', 'item2' => 'value2'],
      'section2' => ['test1'],
      'sectioned',
    ];
    $provider = new ConfigProvider($config);
    $this->assertEquals('value2', $provider->getItem('section1', 'item2'));
    $this->assertEquals('value1', $provider->getItem('section1', 'item1'));
    $this->assertEquals(
      'default',
      $provider->getItem('section2', 'test1', 'default')
    );
    $this->assertTrue($provider->sectionExists('sectioned'));
    $this->assertFalse($provider->sectionExists('nosection'));
    $this->assertTrue($provider->getSection('section1')->has('item2'));
  }
}
