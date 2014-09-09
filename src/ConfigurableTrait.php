<?php
namespace Packaged\Config;

use Packaged\Config\Provider\ConfigSection;

trait ConfigurableTrait
{
  /**
   * @var ConfigSectionInterface
   */
  protected $_configuration;

  /**
   * Configure the data connection
   *
   * @param ConfigSectionInterface $configuration
   *
   * @return static
   */
  public function configure(ConfigSectionInterface $configuration)
  {
    $this->_configuration = $configuration;
  }

  /**
   * Retrieve the configuration
   *
   * @return ConfigSectionInterface
   */
  protected function _config()
  {
    if($this->_configuration === null)
    {
      $this->_configuration = new ConfigSection();
    }
    return $this->_configuration;
  }
}
