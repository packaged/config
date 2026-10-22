<?php
namespace Packaged\Config;

/**
 * Interface ConfigSectionInterface
 *
 * Configuration storage for a specific section of your entire config
 *
 * An example of this would be the configuration for a specific service
 *
 * @package Packaged\Config
 */
interface ConfigSectionInterface
{
  /**
   * Get the name of the current section e.g. database
   */
  public function getName(): string;

  /**
   * Name the current section
   */
  public function setName(string $name): static;

  /**
   * Retrieve an item from the configuration
   *
   * @throws \Exception when $default is passed as an Exception
   */
  public function getItem(string $key, mixed $default = null): mixed;

  /**
   * Check to see if a config item exists within the configuration
   */
  public function has(string $key): bool;

  /**
   * Retrieve all the items in the configuration section
   */
  public function getItems(): array;

  /**
   * Add an item to the configuration section
   */
  public function addItem(string $item, mixed $value): static;

  /**
   * Remove a configuration item
   */
  public function removeItem(string $key): static;
}
