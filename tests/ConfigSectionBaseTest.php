<?php

namespace Packaged\Config\Test;

use Packaged\Config\ConfigSectionInterface;
use PHPUnit\Framework\TestCase;

abstract class ConfigSectionBaseTest extends TestCase
{
  public function testNameSetAndGet()
  {
    $section = $this->getConfigSection();
    $section->setName("testing");
    $this->assertEquals("testing", $section->getName());
  }

  /**
   * @return ConfigSectionInterface
   */
  abstract public function getConfigSection();

  public function testCanAddItemAndRetrieve()
  {
    $section = $this->getConfigSection();
    $section->addItem("hostname", "localhost");
    $item = $section->getItem("hostname", "notset");
    $this->assertEquals("localhost", $item);
  }

  public function testCanRemoveItem()
  {
    $section = $this->getConfigSection();
    $section->addItem("hostname", "localhost");
    $item = $section->getItem("hostname", "notset");
    $this->assertEquals("localhost", $item);
    $section->removeItem("hostname");
    $item = $section->getItem("hostname", "notset");
    $this->assertEquals("notset", $item);
  }

  public function testMissingItemReturnsDefault()
  {
    $section = $this->getConfigSection();
    $item = $section->getItem("hostname", "notset");
    $this->assertEquals("notset", $item);
  }
}
