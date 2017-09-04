<?php
namespace Packaged\Config\Provider\Ini;

class CachedIniConfigProvider extends AbstractIniConfigProvider
{
  public function __construct(array $directories = [], $filename = null, $parseEnv = false, $cacheTTL = 5)
  {
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

    $lastCheck = (int)apcu_fetch($lastCheckKey);
    $data = null;
    $wasCached = true;
    if((time() - $lastCheck) < $cacheTTL)
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

      if(!$data)
      {
        // Failed to load from file. Try the cached version
        $data = apcu_fetch($dataKey);
      }
    }

    $this->_loadString($data, $parseEnv);

    if(!$wasCached)
    {
      apcu_store($dataKey, $data);
      apcu_store($lastCheckKey, time());
    }
    return $this;
  }
}
