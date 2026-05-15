<?php
namespace Packaged\Config\Provider\Ini;

use RuntimeException;

class IniConfigProvider extends AbstractIniConfigProvider
{
  public function __construct(?string $file = null, bool $parseEnv = false)
  {
    if($file !== null)
    {
      $this->loadFile($file, $parseEnv);
    }
  }

  /**
   * @throws RuntimeException
   */
  public function loadFile(string $fullPath, bool $parseEnv = false): static
  {
    if(!file_exists($fullPath))
    {
      throw new RuntimeException("Config file '$fullPath' could not be found");
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
        throw new RuntimeException("The ini file '$fullPath' is corrupt or invalid");
      }

      $this->_buildFromData($data);
    }

    return $this;
  }

  public function loadFiles(array $paths, bool $parseEnv = false, bool $throw = false): static
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
   * @throws RuntimeException
   */
  public function loadString(string $iniString, bool $parseEnv = false): static
  {
    $this->_loadString($iniString, $parseEnv);
    return $this;
  }
}
