<?php

namespace Packaged\Config\Test;

use Packaged\Config\Provider\Ini\CachedIniConfigProvider;

/**
 * @requires extension apcu
 */
class IniCachedConfigProviderTest extends ConfigProviderBaseTest
{
  public function testMissingLoadFile()
  {
    $provider = $this->getConfigProvider();
    $result = $provider->loadCached($this->_dataDir(), 'missing.file');
    $this->assertSame($provider, $result);
  }

  /**
   * @return CachedIniConfigProvider
   */
  public function getConfigProvider()
  {
    return new CachedIniConfigProvider();
  }

  private function _dataDir()
  {
    return [__DIR__ . '/testData'];
  }

  public function testInvalidLoadFile()
  {
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage("The ini string passed is corrupt or invalid");
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
    $provider = new CachedIniConfigProvider(
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
    $contents = file_get_contents(__DIR__ . '/testData/cached.ini');
    file_put_contents($tempFile, $contents);

    $provider = new CachedIniConfigProvider([$dir], $filename, false, 1000);
    $this->assertEquals("value2", $provider->getItem("main", "item2"));

    // replace the value of item2 in the temp file
    file_put_contents($tempFile, str_replace('value2', 'new_value_2', $contents));

    // 1 second should not have passed yet so the value should be returned from the cache
    $provider = new CachedIniConfigProvider([$dir], $filename, false, 1000);
    $this->assertEquals("value2", $provider->getItem("main", "item2"));

    sleep(2);

    // TTL expired, should load from the file again
    $provider = new CachedIniConfigProvider([$dir], $filename, false, 0);
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
