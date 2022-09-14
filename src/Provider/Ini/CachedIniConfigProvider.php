<?php
namespace Packaged\Config\Provider\Ini;

class CachedIniConfigProvider extends AbstractIniConfigProvider
{
  protected static bool $_hasApcu;

  public function __construct(array $directories = [], $filename = null, $parseEnv = false, $cacheTTL = 5)
  {
    if(self::$_hasApcu === null)
    {
      self::$_hasApcu = function_exists('apcu_fetch');
    }
    if($directories && $filename)
    {
      $this->loadCached($directories, $filename, $parseEnv, $cacheTTL);
    }
  }

  public function loadCached($directories, $filename, $parseEnv = false, $cacheTTL = 5)
  {
    $dirsHash = md5(implode('|', $directories));
    $lastCheckKey = 'CachedIniCP:' . $dirsHash . ':lastCheck:' . $filename;
    $dataKey = 'CachedIniCP:' . $dirsHash . ':data:' . $filename;

    $lastCheck = self::$_hasApcu ? apcu_fetch($lastCheckKey) : false;
    $data = null;
    $wasCached = true;
    if(self::$_hasApcu && (time() - $lastCheck) < $cacheTTL)
    {
      // TTL not yet expired, return cached version if available
      $data = apcu_fetch($dataKey);
    }

    if(!$data)
    {
      foreach($directories as $dir)
      {
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        $file = $dir . DIRECTORY_SEPARATOR . $filename;
        if(file_exists($file))
        {
          $data = @file_get_contents($file);
          if($data)
          {
            $wasCached = false;
            break;
          }
        }
      }

      if(!$data && self::$_hasApcu)
      {
        // Failed to load from file. Try the cached version
        $data = apcu_fetch($dataKey);
      }
    }

    if($data)
    {
      $this->_loadString($data, $parseEnv);

      if(self::$_hasApcu && !$wasCached)
      {
        apcu_store($dataKey, $data);
        apcu_store($lastCheckKey, time());
      }
    }
    return $this;
  }
}
