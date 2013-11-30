<?php
/**
 * @author Brooke Bryan @bajbnet
 */

namespace Packaged\Config;

/**
 * Interface IConfigSection
 *
 * Configuration storage for a specific section of your entire config
 *
 * An example of this would be the configuration for a specific service
 *
 * @package Packaged\Config
 */
interface IConfigSection
{
  /**
   * Get the name of the current section e.g. database
   *
   * @return string
   */
  public function getName();

  /**
   * Retrieve an item from the configuration
   *
   * @param string $key     Configuration item key e.g. hostname
   * @param mixed  $default Default value if the config item does not exist
   *
   * @return mixed
   */
  public function getItem($key, $default = null);
}