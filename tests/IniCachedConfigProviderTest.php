<?php

/**
 * @requires extension apcu
 */
class IniCachedConfigProviderTest extends ConfigProviderBaseTest
{
  /**
   * @return \Packaged\Config\Provider\Ini\CachedIniConfigProvider
   */
  public function getConfigProvider()
  {
    return new \Packaged\Config\Provider\Ini\CachedIniConfigProvider();
  }

  private function _dataDir()
  {
    return [dirname(__DIR__) . '/testData'];
  }

  public function testMissingLoadFile()
  {
    $this->setExpectedException(
      "RuntimeException",
      "The ini string passed is corrupt or invalid"
    );
    $provider = $this->getConfigProvider();
    $provider->loadCached($this->_dataDir(), 'missing.file');
  }

  public function testInvalidLoadFile()
  {
    $this->setExpectedException(
      "RuntimeException",
      "The ini string passed is corrupt or invalid"
    );
    $provider = $this->getConfigProvider();
    $provider->loadCached($this->_dataDir(), 'useless.file');
  }

  public function testLoadFile()
  {
    $provider = $this->getConfigProvider();
    $provider->loadCached($this->_dataDir(), 'test.ini');
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }

  public function testLoadConstruct()
  {
    $provider = new \Packaged\Config\Provider\Ini\CachedIniConfigProvider(
      $this->_dataDir(), 'test.ini'
    );
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }

  public function testLoadFileMissingFirstDir()
  {
    $dirs = $this->_dataDir();
    array_unshift($dirs, '/nonexistent/dir');
    $provider = $this->getConfigProvider();
    $provider->loadCached($dirs, 'test.ini');
    $this->assertEquals("packaged", $provider->getItem("database", "database"));
  }

  public function testCachedLoad()
  {
    $dir = sys_get_temp_dir();
    $tempFile = tempnam($dir, 'cached-ini');
    $filename = basename($tempFile);
    // Copy cached.ini to the temp file
    $contents = file_get_contents(dirname(__DIR__) . '/testData/cached.ini');
    file_put_contents($tempFile, $contents);

    $provider = new \Packaged\Config\Provider\Ini\CachedIniConfigProvider(
      [$dir], $filename, false, 3
    );
    $this->assertEquals("value2", $provider->getItem("main", "item2"));

    // replace the value of item2 in the temp file
    file_put_contents(
      $tempFile,
      str_replace('value2', 'new_value_2', $contents)
    );

    // 3 seconds should not have passed yet so the value should be returned from the cache
    $provider = new \Packaged\Config\Provider\Ini\CachedIniConfigProvider(
      [$dir], $filename, false, 3
    );
    $this->assertEquals("value2", $provider->getItem("main", "item2"));

    sleep(3);

    // TTL expired, should load from the file again
    $provider = new \Packaged\Config\Provider\Ini\CachedIniConfigProvider(
      [$dir], $filename, false, 3
    );
    $this->assertEquals("new_value_2", $provider->getItem("main", "item2"));

    unlink($tempFile);
  }

  public function testLoadFileEnv()
  {
    putenv('TESTVAR1=fileTestValue1');
    putenv('TESTVAR2=fileTestValue2');

    $file = 'envtest.ini';
    $provider = $this->getConfigProvider();
    $provider->loadCached($this->_dataDir(), $file, true);

    $this->assertEquals(
      'fileTestValue1',
      $provider->getItem('default', 'var1')
    );
    $this->assertEquals(
      'fileTestValue2',
      $provider->getItem('default', 'var2')
    );
    $this->assertEquals(
      'fileTestValue1-fileTestValue2',
      $provider->getItem('default', 'var1and2')
    );
    $this->assertEquals(
      'defaultValue',
      $provider->getItem('default', 'nonexistentDefault')
    );
    $this->assertEquals(
      '',
      $provider->getItem('default', 'nonexistentNoDefault')
    );
  }
}
