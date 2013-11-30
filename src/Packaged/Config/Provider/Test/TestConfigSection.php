<?php
/**
 * @author Brooke Bryan @bajbnet
 */

namespace Packaged\Config\Provider\Test;

use Packaged\Config\IConfigSection;

/**
 * Class TestConfigSection
 *
 * Configuration section
 *
 * @package Packaged\Config\Provider\Test
 */
class TestConfigSection implements IConfigSection
{
  protected $_name;
  protected $_items;

  /**
   * @param string $name  Name of this configuration section
   * @param array  $items all configuration items e.g. [host => localhost]
   */
  public function __construct($name = '', array $items = [])
  {
    $this->_name  = $name;
    $this->_items = $items;
  }

  /**
   * Name the current section
   *
   * @param string $name Name of this section
   *
   * @return $this
   */
  public function setName($name)
  {
    $this->_name = $name;
    return $this;
  }

  /**
   * Get the name of the current section e.g. database
   *
   * @return string
   */
  public function getName()
  {
    return $this->_name;
  }

  /**
   * Retrieve an item from the configuration
   *
   * @param string $key     Configuration item key e.g. hostname
   * @param mixed  $default Default value if the config item does not exist
   *
   * @return mixed
   */
  public function getItem($key, $default = null)
  {
    if(isset($this->_items[$key]))
    {
      return $this->_items[$key];
    }
    else
    {
      return $default;
    }
  }

  /**
   * @param string $key   Configuration item key e.g. hostname
   * @param mixed  $value Configuration item value e.g. localhost
   *
   * @return $this
   */
  public function addItem($key, $value)
  {
    $this->_items[$key] = $value;
    return $this;
  }
}