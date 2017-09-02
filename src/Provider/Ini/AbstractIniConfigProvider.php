<?php
namespace Packaged\Config\Provider\Ini;

use Packaged\Config\Provider\AbstractConfigProvider;

abstract class AbstractIniConfigProvider extends AbstractConfigProvider
{
  /**
   * Load configuration from a string containing INI data and optionally parse
   * environment variable placeholders
   *
   * @param string $iniString
   * @param bool   $parseEnv
   */
  protected function _loadString($iniString, $parseEnv)
  {
    if($parseEnv)
    {
      $iniString = $this->_parseEnvVars($iniString);
    }
    $data = parse_ini_string($iniString, true);

    if(!$data)
    {
      throw new \RuntimeException(
        "The ini string passed is corrupt or invalid"
      );
    }

    $this->_buildFromData($data);
  }

  /**
   * Build up the configuration from the sectioned array
   *
   * @param array $iniData
   */
  protected function _buildFromData(array $iniData)
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
   *
   * @param string $iniString
   *
   * @return string
   */
  protected function _parseEnvVars($iniString)
  {
    return preg_replace_callback(
      '/{{ENV:([0-9A-Za-z_]*)(:([^{}]*))?}}/',
      function ($matches)
      {
        $varName = $matches[1];
        $default = isset($matches[3]) ? $matches[3] : '';
        $value = getenv($varName);
        return $value === false ? $default : $value;
      },
      $iniString
    );
  }
}
