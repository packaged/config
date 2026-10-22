<?php

namespace Packaged\Config\Test;

use Packaged\Config\Provider\Test\TestConfigProvider;

class TestConfigProviderTest extends ConfigProviderBaseTest
{
  public function getConfigProvider()
  {
    return new TestConfigProvider();
  }
}
