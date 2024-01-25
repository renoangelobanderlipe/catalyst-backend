<?php


if (!function_exists('formatFullName')) {
  /**
   * Format a full name with options.
   * 
   * USAGE
   * 
   * $firstName = "Reno Angelo"
   * $lastName = "Banderlipe"
   * 
   * Basic Usage
   * formatFullName($firstName, $lastName) // Reno Angelo Banderlipe
   * 
   * With Options
   * $options = [
   *  "prefix" => "Mr. ",
   *  "suffix" => " Jr.",
   *  "delimeter" => "-",
   *  "format" => {prefix}{lastName}, {firstName}{suffix}",
   * ];
   * 
   * formatFullName($firstName, $lastName, $options) // My. Banderlipe-Reno Angelo Jr.
   * 
   * @param string $firstName
   * @param string $lastName
   * @param string|null $middleName
   * @param array $options
   *   - 'prefix': Prefix to be added before the full name.
   *   - 'suffix': Suffix to be added after the full name.
   *   - 'delimiter': Delimiter between name parts.
   *   - 'format': Custom format using placeholders {prefix}, {firstName}, {middleName}, {lastName}, {suffix}.
   * @return string
   */
  function formatFullName($firstName, $lastName, $middleName = null, $options = [])
  {
    $defaultOptions = [
      'prefix' => '',
      'suffix' => '',
      'delimiter' => ' ',
      'format' => '{prefix}{firstName}{delimiter}{middleName}{delimiter}{lastName}{suffix}',
    ];

    $options = array_merge($defaultOptions, $options);

    $formattedName = strtr($options['format'], [
      '{prefix}' => $options['prefix'],
      '{firstName}' => $firstName,
      '{middleName}' => $middleName ?: '',
      '{lastName}' => $lastName,
      '{suffix}' => $options['suffix'],
      '{delimiter}' => $options['delimiter'],
    ]);

    $name = trim($formattedName);
    $cleanedName = preg_replace('/\s+/', ' ', $name);

    return $cleanedName;
  }
}
