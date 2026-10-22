<?php

namespace Packaged\Config\Test;

use Packaged\Config\Provider\ConfigProvider;
use Packaged\Config\Provider\ConfigSection;
use Packaged\Config\Provider\Ini\IniConfigProvider;
use PHPUnit\Framework\TestCase;

/**
 * A null key or section name is stored under the empty string key, as PHP
 * did implicitly before 8.5 deprecated null array offsets
 */
class NullKeyTest extends TestCase
{
  public function testSectionNullKey()
  {
    $section = new ConfigSection('db');
    $section->addItem(null, 'value');

    $this->assertSame(['' => 'value'], $section->getItems());
    $this->assertSame('value', $section->getItem(null));
    $this->assertTrue($section->has(null));

    $section->removeItem(null);
    $this->assertFalse($section->has(''));
    $this->assertSame('default', $section->getItem(null, 'default'));
  }

  public function testSectionArrayAccessNullOffset()
  {
    $section = new ConfigSection('db');
    $section[] = 'appended';

    $this->assertSame('appended', $section->getItem(''));
    $this->assertTrue(isset($section[null]));
    $this->assertSame('appended', $section[null]);

    unset($section[null]);
    $this->assertFalse(isset($section['']));
  }

  public function testProviderNullSectionAndKey()
  {
    $provider = new ConfigProvider();
    $provider->addItem(null, 'key', 'value');
    $provider->addItem('db', null, 'empty');

    $this->assertTrue($provider->sectionExists(null));
    $this->assertTrue($provider->has(null));
    $this->assertSame('value', $provider->getItem(null, 'key'));
    $this->assertSame('empty', $provider->getItem('db', null));
    $this->assertTrue($provider->hasItem('db', null));
    $this->assertSame('value', $provider->getSection(null)->getItem('key'));

    $provider->removeItem(null, 'key');
    $this->assertFalse($provider->hasItem('', 'key'));

    $provider->removeSectionByName(null);
    $this->assertFalse($provider->sectionExists(''));
  }

  public function testProviderNullSectionName()
  {
    $provider = new ConfigProvider();
    $provider->addSection(new ConfigSection(null, ['a' => 'b']));
    $this->assertSame('b', $provider->getItem('', 'a'));

    $provider->setSection(new ConfigSection(null, ['a' => 'c']));
    $this->assertSame('c', $provider->getItem('', 'a'));

    $provider->removeSection(new ConfigSection(null));
    $this->assertFalse($provider->sectionExists(''));
    $this->assertSame('missing', $provider->getSection(null, false)->getItem('x', 'missing'));
  }

  public function testIniNullFile()
  {
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage("Config file '' could not be found");
    (new IniConfigProvider())->loadFile(null);
  }

  public function testIniNullString()
  {
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage("The ini string passed is corrupt or invalid");
    (new IniConfigProvider())->loadString(null);
  }

  public function testIniNullStringWithEnv()
  {
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage("The ini string passed is corrupt or invalid");
    (new IniConfigProvider())->loadString(null, true);
  }
}
