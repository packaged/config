<?php
namespace Packaged\Config;

interface ConfigurableInterface
{
  public function configure(ConfigSectionInterface $configuration): static;
}
