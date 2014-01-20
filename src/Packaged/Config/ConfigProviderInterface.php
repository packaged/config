<?php
namespace Packaged\Config;

/**
 * Interface ConfigProviderInterface
 *
 * The Configuration Provider is responsible for
 *
 * @package Packaged\Config
 */
interface ConfigProviderInterface
{
  /**
   * Retrieve all configuration sections
   *
   * @return ConfigSectionInterface[]
   */
  public function getSections();

  /**
   * @param string $name Name/Key of the configuration section
   *
   * @return ConfigSectionInterface
   * @throws \Exception
   */
  public function getSection($name);

  /**
   * Check to see if a section exists within the configuration
   *
   * @param string $name Section name
   *
   * @return bool
   */
  public function sectionExists($name);

  /**
   * @param string $section Section Name
   * @param string $key     Config Item Key
   * @param mixed  $default Default value for missing item
   *
   * @return mixed Configuration Value
   */
  public function getItem($section, $key, $default = null);
}
