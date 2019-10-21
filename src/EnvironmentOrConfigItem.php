<?php
namespace Packaged\Config;

use Packaged\Config\Provider\ConfigProvider;

class EnvironmentOrConfigItem
{
  protected $_config;

  public function __construct(ConfigProvider $config)
  {
    $this->_config = $config;
  }

  public function get($envKey, $section, $item, $default = null)
  {
    $envValue = getenv($envKey);
    if($envValue === false)
    {
      return $this->_config->getItem($section, $item, $default);
    }
    return $envValue;
  }
}
