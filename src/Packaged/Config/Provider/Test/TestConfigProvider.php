<?php
namespace Packaged\Config\Provider\Test;

use Packaged\Config\ConfigProviderInterface;
use Packaged\Config\ConfigSectionInterface;

/**
 * Class TestConfigProvider
 *
 * Config provider for testing basics
 *
 * @package Packaged\Config\Provider\Test
 */
class TestConfigProvider implements ConfigProviderInterface
{
  protected $_sections;

  /**
   * Add an item to the configuration
   *
   * @param string $section Section Name
   * @param string $item    Config Item Key
   * @param mixed  $value   Config Item Value
   *
   * @return $this
   */
  public function addItem($section, $item, $value)
  {
    if(!($this->_sections[$section] instanceof ConfigSectionInterface))
    {
      $this->_sections[$section] = new TestConfigSection($section);
    }
    $this->_sections[$section]->addItem($item, $value);

    return $this;
  }

  /**
   * Retrieve all configuration sections
   *
   * @return ConfigSectionInterface[]
   */
  public function getSections()
  {
    return $this->_sections;
  }

  /**
   * @param string $name Name/Key of the configuration section
   *
   * @return ConfigSectionInterface
   * @throws \Exception
   */
  public function getSection($name)
  {
    if(isset($this->_sections[$name]))
    {
      return $this->_sections[$name];
    }
    throw new \Exception("Configuration section $name could not be found");
  }

  /**
   * Check to see if a section exists within the configuration
   *
   * @param string $name Section name
   *
   * @return bool
   */
  public function sectionExists($name)
  {
    return isset($this->_sections[$name]);
  }

  /**
   * @param string $section Section Name
   * @param string $key     Config Item Key
   * @param mixed  $default Default value for missing item
   *
   * @return mixed Configuration Value
   */
  public function getItem($section, $key, $default = null)
  {
    if(!$this->sectionExists($section))
    {
      return $default;
    }
    return $this->getSection($section)->getItem($key, $default);
  }
}
