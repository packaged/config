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
}
