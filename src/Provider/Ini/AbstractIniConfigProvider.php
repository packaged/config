<?php
namespace Packaged\Config\Provider\Ini;

use Packaged\Config\Provider\AbstractConfigProvider;
use RuntimeException;

abstract class AbstractIniConfigProvider extends AbstractConfigProvider
{
  /**
   * Load configuration from a string containing INI data and optionally parse
   * environment variable placeholders
   */
  protected function _loadString(string $iniString, bool $parseEnv): void
  {
    if($parseEnv)
    {
      $iniString = $this->_parseEnvVars($iniString);
    }
    $data = parse_ini_string($iniString, true);

    if(!$data)
    {
      throw new RuntimeException(
        "The ini string passed is corrupt or invalid"
      );
    }

    $this->_buildFromData($data);
  }

  protected function _buildFromData(array $iniData): void
  {
    foreach($iniData as $section => $sectionData)
    {
      if(!is_array($sectionData))
      {
        continue;
      }

      foreach($sectionData as $item => $value)
      {
        $this->addItem($section, $item, $value);
      }
    }
  }

  /**
   * Parse environment variable markers in a string and replace them with the
   * variable's value.
   * Variables are expected to be in this format: {{ENV:VARNAME:defaultValue}}
   * The ":defaultValue" part is optional and defaults to an empty string
   */
  protected function _parseEnvVars(string $iniString): string
  {
    return preg_replace_callback(
      '/{{ENV:([0-9A-Za-z_]*)(:([^{}]*))?}}/',
      function(array $matches): string {
        $varName = $matches[1];
        $default = $matches[3] ?? '';
        $value = getenv($varName);
        return $value === false ? $default : $value;
      },
      $iniString
    );
  }
}
