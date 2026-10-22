<?php

namespace Packaged\Config\Test;

use Closure;
use Packaged\Config\Provider\ConfigProvider;
use Packaged\Config\Provider\ConfigSection;
use Packaged\Config\Provider\Ini\CachedIniConfigProvider;
use Packaged\Config\Provider\Ini\IniConfigProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * Keys, section names, INI paths and INI strings must be strings; null throws
 */
class NullKeyTest extends TestCase
{
  public static function nullCalls(): array
  {
    $section = static fn() => new ConfigSection('db', ['a' => 1]);
    $provider = static fn() => (new ConfigProvider())->addItem('db', 'a', 1);

    return [
      'section getItem'          => [fn() => $section()->getItem(null)],
      'section has'              => [fn() => $section()->has(null)],
      'section addItem'          => [fn() => $section()->addItem(null, 1)],
      'section removeItem'       => [fn() => $section()->removeItem(null)],
      'section isset offset'     => [fn() => isset($section()[null])],
      'section get offset'       => [fn() => $section()[null]],
      'section append'           => [function () use ($section) { $s = $section(); $s[] = 1; }],
      'section unset offset'     => [function () use ($section) { $s = $section(); unset($s[null]); }],
      'section name'             => [fn() => new ConfigSection(null)],
      'section setName'          => [fn() => $section()->setName(null)],
      'provider getItem section' => [fn() => $provider()->getItem(null, 'a')],
      'provider getItem key'     => [fn() => $provider()->getItem('db', null)],
      'provider addItem section' => [fn() => $provider()->addItem(null, 'a', 1)],
      'provider addItem key'     => [fn() => $provider()->addItem('db', null, 1)],
      'provider getSection'      => [fn() => $provider()->getSection(null)],
      'provider sectionExists'   => [fn() => $provider()->sectionExists(null)],
      'provider has'             => [fn() => $provider()->has(null)],
      'provider hasItem'         => [fn() => $provider()->hasItem(null, 'a')],
      'provider removeItem'      => [fn() => $provider()->removeItem(null, 'a')],
      'provider removeSection'   => [fn() => $provider()->removeSectionByName(null)],
      'ini loadFile'             => [fn() => (new IniConfigProvider())->loadFile(null)],
      'ini loadString'           => [fn() => (new IniConfigProvider())->loadString(null)],
      'cached ini loadCached'    => [fn() => (new CachedIniConfigProvider())->loadCached([sys_get_temp_dir()], null)],
    ];
  }

  #[DataProvider('nullCalls')]
  public function testNullThrows(Closure $call): void
  {
    $this->expectException(TypeError::class);
    $call();
  }

  public function testIntegerOffsetsStillWork(): void
  {
    $section = new ConfigSection('db');
    $section[0] = 'zero';
    $this->assertTrue(isset($section[0]));
    $this->assertSame('zero', $section[0]);
    unset($section[0]);
    $this->assertFalse(isset($section[0]));
  }
}
