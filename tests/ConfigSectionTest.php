<?php

namespace Packaged\Config\Test;

use Exception;
use Packaged\Config\ConfigSectionInterface;
use Packaged\Config\Provider\ConfigSection;

class ConfigSectionTest extends ConfigSectionBaseTest
{
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

    $this->expectException(Exception::class);
    $this->expectExceptionMessage("Config Item Not Found");
    $this->expectExceptionCode(999);
    $section->getItem('ghj', new Exception("Config Item Not Found", 999));
  }

  /**
   * @return ConfigSectionInterface
   */
  public function getConfigSection()
  {
    return new ConfigSection();
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
