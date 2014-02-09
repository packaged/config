<?php
namespace Packaged\Config\Provider\Ini;

use Packaged\Config\Provider\AbstractConfigProvider;

class IniConfigProvider extends AbstractConfigProvider
{
  /**
   * @param null|string $file Full path of ini file to create configuration from
   */
  public function __construct($file = null)
  {
    if($file !== null)
    {
      $this->loadFile($file);
    }
  }

  /**
   * Add items from an ini file to the configuration
   *
   * @param string $fullPath full path to the ini file to load
   *
   * @return $this
   * @throws \RuntimeException
   */
  public function loadFile($fullPath)
  {
    if(!file_exists($fullPath))
    {
      throw new \RuntimeException("Config file '$fullPath' could not be found");
    }

    $data = parse_ini_file($fullPath, true);

    if(!$data)
    {
      throw new \RuntimeException(
        "The ini file '$fullPath' is corrupt or invalid"
      );
    }

    $this->_buildFromData($data);
    return $this;
  }

  /**
   * Add items from an ini string to the configuration
   *
   * @param string $iniString valid ini string
   *
   * @return $this
   * @throws \RuntimeException
   */
  public function loadString($iniString)
  {
    $data = parse_ini_string($iniString, true);

    if(!$data)
    {
      throw new \RuntimeException(
        "The ini string passed is corrupt or invalid"
      );
    }

    $this->_buildFromData($data);
    return $this;
  }

  /**
   * Build up the configuration from the sectioned array
   *
   * @param array $iniData
   */
  protected function _buildFromData(array $iniData)
  {
    foreach($iniData as $section => $sectionData)
    {
      if(!is_array($sectionData))
      {
        continue;
      }

      foreach($sectionData as $item => $value)
      {
        $this->addItem($section, $item, $value);
      }
    }
  }
}
