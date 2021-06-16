<?php

namespace Packaged\Config\Test;

use Packaged\Config\Test\Providers\TestConfigProvider;

class TestConfigProviderTest extends ConfigProviderBaseTest
{
  public function getConfigProvider()
  {
    return new TestConfigProvider();
  }
}
