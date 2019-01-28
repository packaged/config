<?php
namespace Packaged\Config\Provider\Ini;

use RuntimeException;

class IniConfigProvider extends AbstractIniConfigProvider
{
  /**
   * @param null|string $file     Full path of ini file to create configuration from
   * @param bool        $parseEnv If true then parse environment variables in the INI file
   */
  public function __construct($file = null, $parseEnv = false)
  {
    if($file !== null)
    {
      $this->loadFile($file, $parseEnv);
    }
  }

  /**
   * Add items from an ini file to the configuration
   *
   * @param string $fullPath full path to the ini file to load
   * @param bool   $parseEnv If true then parse environment variables in the INI file
   *
   * @return $this
   * @throws \RuntimeException
   */
  public function loadFile($fullPath, $parseEnv = false)
  {
    if(!file_exists($fullPath))
    {
      throw new \RuntimeException("Config file '$fullPath' could not be found");
    }

    if($parseEnv)
    {
      $this->loadString(file_get_contents($fullPath), true);
    }
    else
    {
      $data = parse_ini_file($fullPath, true);

      if(!$data)
      {
        throw new \RuntimeException(
          "The ini file '$fullPath' is corrupt or invalid"
        );
      }

      $this->_buildFromData($data);
    }

    return $this;
  }

  /**
   * Add items from multiple ini files
   *
   * @param array $paths
   * @param bool  $parseEnv
   * @param bool  $throw
   *
   * @return $this
   */
  public function loadFiles(array $paths, $parseEnv = false, $throw = false)
  {
    foreach($paths as $path)
    {
      try
      {
        $this->loadFile($path, $parseEnv);
      }
      catch(RuntimeException $e)
      {
        if($throw)
        {
          throw $e;
        }
      }
    }
    return $this;
  }

  /**
   * Add items from an ini string to the configuration
   *
   * @param string $iniString valid ini string
   * @param bool   $parseEnv  If true then parse environment variables in the INI file
   *
   * @return $this
   * @throws \RuntimeException
   */
  public function loadString($iniString, $parseEnv = false)
  {
    $this->_loadString($iniString, $parseEnv);
    return $this;
  }
}
