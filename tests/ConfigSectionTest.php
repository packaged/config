<?php

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
  }
}
