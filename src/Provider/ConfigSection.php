<?php
namespace Packaged\Config\Provider;

use ArrayAccess;
use Exception;
use Packaged\Config\ConfigSectionInterface;

class ConfigSection implements ConfigSectionInterface, ArrayAccess
{
  protected string $_name;
  protected array $_items;

  public function __construct(string $name = '', array $items = [])
  {
    $this->_name = $name;
    $this->_items = $items;
  }

  #[\Override]
  public function setName(string $name): static
  {
    $this->_name = $name;
    return $this;
  }

  #[\Override]
  public function getName(): string
  {
    return $this->_name;
  }

  #[\Override]
  public function has(string $key): bool
  {
    return isset($this->_items[$key]);
  }

  #[\Override]
  public function getItem(string $key, mixed $default = null): mixed
  {
    if(isset($this->_items[$key]))
    {
      return $this->_items[$key];
    }
    if($default instanceof Exception)
    {
      throw $default;
    }
    return $default;
  }

  #[\Override]
  public function getItems(): array
  {
    return $this->_items;
  }

  #[\Override]
  public function addItem(string $key, mixed $value): static
  {
    $this->_items[$key] = $value;
    return $this;
  }

  public function addItems(array $keyValueItems): static
  {
    foreach($keyValueItems as $k => $v)
    {
      $this->_items[$k] = $v;
    }
    return $this;
  }

  #[\Override]
  public function removeItem(string $key): static
  {
    unset($this->_items[$key]);
    return $this;
  }

  #[\Override]
  public function offsetExists(mixed $offset): bool
  {
    return isset($this->_items[$offset]);
  }

  #[\Override]
  public function offsetGet(mixed $offset): mixed
  {
    return $this->getItem($offset);
  }

  #[\Override]
  public function offsetSet(mixed $offset, mixed $value): void
  {
    $this->addItem($offset, $value);
  }

  #[\Override]
  public function offsetUnset(mixed $offset): void
  {
    unset($this->_items[$offset]);
  }
}
