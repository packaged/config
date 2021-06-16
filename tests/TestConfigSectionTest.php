<?php

namespace Packaged\Config\Test;

use Packaged\Config\ConfigSectionInterface;
use Packaged\Config\Test\Providers\TestConfigSection;

class TestConfigSectionTest extends ConfigSectionBaseTest
{
  /**
   * @return ConfigSectionInterface
   */
  public function getConfigSection()
  {
    return new TestConfigSection();
  }
}
