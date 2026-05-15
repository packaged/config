<?php
namespace Packaged\Config;

/**
 * Interface ConfigProviderInterface
 *
 * The Configuration Provider is responsible for
 *
 * @package Packaged\Config
 */
interface ConfigProviderInterface
{
  /**
   * Retrieve all configuration sections
   *
   * @return ConfigSectionInterface[]
   */
  public function getSections(): array;

  /**
   * @throws \Exception when the section does not exist (and $throw is true)
   */
  public function getSection(string $name): ConfigSectionInterface;

  /**
   * @throws \Exception when the section already exists
   */
  public function addSection(ConfigSectionInterface $section): static;

  /**
   * Same as addSection, however, will replace an existing section if one exists
   */
  public function setSection(ConfigSectionInterface $section): static;

  public function sectionExists(string $name): bool;

  public function has(string $name): bool;

  /**
   * @throws \Exception when $default is passed as an Exception
   */
  public function getItem(string $section, string $key, mixed $default = null): mixed;

  public function hasItem(string $section, string $key): bool;

  public function addItem(string $section, string $item, mixed $value): static;

  public function removeItem(string $section, string $item): static;

  public function removeSection(ConfigSectionInterface $section): static;

  public function removeSectionByName(string $sectionName): static;
}
