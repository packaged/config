<?php
namespace Packaged\Config;

use Packaged\Config\Provider\ConfigSection;

trait ConfigurableTrait
{
  protected ?ConfigSectionInterface $_configuration = null;

  public function configure(ConfigSectionInterface $configuration): static
  {
    $this->_configuration = $configuration;
    return $this;
  }

  protected function _config(): ConfigSectionInterface
  {
    if($this->_configuration === null)
    {
      $this->_configuration = new ConfigSection();
    }
    return $this->_configuration;
  }
}
