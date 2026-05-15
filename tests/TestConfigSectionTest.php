<?php

namespace Packaged\Config\Test;

use Packaged\Config\ConfigSectionInterface;
use Packaged\Config\Provider\Test\TestConfigSection;

class TestConfigSectionTest extends ConfigSectionBaseTest
{
  public function getConfigSection(): TestConfigSection
  {
    return new TestConfigSection();
  }
}
