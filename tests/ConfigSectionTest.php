<?php

use Packaged\Config\Provider\ConfigSection;

class ConfigSectionTest extends ConfigSectionBaseTest
{
  /**
   * @return \Packaged\Config\ConfigSectionInterface
   */
  public function getConfigSection()
  {
    return new \Packaged\Config\Provider\ConfigSection();
  }

  public function testArrayAccess()
  {
    $section = $this->getConfigSection();
    $this->assertFalse(isset($section['random']));
    $this->assertNull($section['random']);
    $section['random'] = 'testing';
    $this->assertTrue(isset($section['random']));
    $this->assertEquals('testing', $section['random']);
    $this->assertEquals('testing', $section->getItem('random'));
    unset($section['random']);
    $this->assertFalse(isset($section['random']));

    $section['a'] = 'B';
    $section['c'] = 'D';
    $this->assertEquals(['a' => 'B', 'c' => 'D'], $section->getItems());

    $this->setExpectedException("Exception", "Config Item Not Found", 999);
    $section->getItem('ghj', new Exception("Config Item Not Found", 999));
  }

  public function testAddItems()
  {
    $section = new ConfigSection();
    $section->addItems(['a' => 1, 'b' => 2, 'c' => ['a', 'b', 'c']]);
    $this->assertEquals(1, $section->getItem('a'));
    $this->assertEquals(2, $section->getItem('b'));
    $this->assertEquals(['a', 'b', 'c'], $section->getItem('c'));
  }
}
