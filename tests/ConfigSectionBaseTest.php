<?php

abstract class ConfigSectionBaseTest extends PHPUnit_Framework_TestCase
{
  /**
   * @return \Packaged\Config\ConfigSectionInterface
   */
  abstract public function getConfigSection();

  public function testNameSetAndGet()
  {
    $section = $this->getConfigSection();
    $section->setName("testing");
    $this->assertEquals("testing", $section->getName());
  }
}
