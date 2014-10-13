<?php
namespace Packaged\Config\Provider;

class ConfigProvider extends AbstractConfigProvider
{
  /**
   * @param array $sections [section => [item => value]]
   */
  public function __construct($sections)
  {
    foreach($sections as $section => $items)
    {
      foreach($items as $item => $value)
      {
        $this->addItem($section, $item, $value);
      }
    }
  }
}
