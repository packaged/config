<?php
/**
 * @author Brooke Bryan @bajbnet
 */

namespace Packaged\Config;

/**
 * Interface IConfigProvider
 *
 * The Configuration Provider is responsible for
 *
 * @package Packaged\Config
 */
interface IConfigProvider
{
  /**
   * Retrieve all configuration sections
   *
   * @return IConfigSection[]
   */
  public function getSections();

  /**
   * @param string $name Name/Key of the configuration section
   *
   * @return IConfigSection
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