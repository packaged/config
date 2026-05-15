<?php
namespace Packaged\Config\Provider;

use Exception;
use Packaged\Config\ConfigProviderInterface;
use Packaged\Config\ConfigSectionInterface;

abstract class AbstractConfigProvider implements ConfigProviderInterface
{
  /** @var ConfigSection[] */
  protected array $_sections = [];

  #[\Override]
  public function addItem(string $section, string $item, mixed $value): static
  {
    if(isset($this->_sections[$section]))
    {
      $this->_sections[$section]->addItem($item, $value);
    }
    else
    {
      $this->addSection(new ConfigSection($section, [$item => $value]));
    }

    return $this;
  }

  #[\Override]
  public function removeItem(string $section, string $item): static
  {
    if(isset($this->_sections[$section]))
    {
      $this->_sections[$section]->removeItem($item);
    }
    return $this;
  }

  #[\Override]
  public function getItem(string $section, string $key, mixed $default = null): mixed
  {
    if(!$this->sectionExists($section))
    {
      if($default instanceof Exception)
      {
        throw $default;
      }
      return $default;
    }
    return $this->getSection($section)->getItem($key, $default);
  }

  #[\Override]
  public function hasItem(string $section, string $key): bool
  {
    try
    {
      return $this->getSection($section)->has($key);
    }
    catch(Exception $e)
    {
      return false;
    }
  }

  /**
   * @return ConfigSectionInterface[]
   */
  #[\Override]
  public function getSections(): array
  {
    return $this->_sections;
  }

  /**
   * @throws Exception when the section does not exist and $throw is true
   */
  public function getSection(string $name, bool $throw = true): ConfigSectionInterface
  {
    if(isset($this->_sections[$name]))
    {
      return $this->_sections[$name];
    }
    if(!$throw)
    {
      return new ConfigSection($name);
    }
    throw new Exception("Configuration section $name could not be found");
  }

  #[\Override]
  public function sectionExists(string $name): bool
  {
    return isset($this->_sections[$name]);
  }

  #[\Override]
  public function has(string $name): bool
  {
    return isset($this->_sections[$name]);
  }

  #[\Override]
  public function addSection(ConfigSectionInterface $section): static
  {
    if($this->sectionExists($section->getName()))
    {
      throw new Exception(
        "The section " . $section->getName() . " cannot be re-added"
      );
    }

    $this->_sections[$section->getName()] = $section;
    return $this;
  }

  #[\Override]
  public function setSection(ConfigSectionInterface $section): static
  {
    $this->_sections[$section->getName()] = $section;
    return $this;
  }

  #[\Override]
  public function removeSection(ConfigSectionInterface $section): static
  {
    $this->removeSectionByName($section->getName());
    return $this;
  }

  #[\Override]
  public function removeSectionByName(string $sectionName): static
  {
    if(isset($this->_sections[$sectionName]))
    {
      unset($this->_sections[$sectionName]);
    }

    return $this;
  }
}
