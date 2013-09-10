<?php
/**
 * Simple Yaml parsing class
 * @author Garrett Grimm
 * @date September, 2013
 */
class Yaml {
	/**
	 * Parses Yaml into an array of key => value
	 * @param string $yaml
	 * @return array
	 */
	public static function parse($yaml = null) {
		// First split by new line
		$yaml_lines_array = explode("\n", $yaml);

		// Then split each element by ':'
		foreach ($yaml_lines_array as $line) {
			if (strstr($line, ':')) {
				$exploded_yaml = explode(':', $line);
				$yaml_values[$exploded_yaml[0]] = trim($exploded_yaml[1]);
			}
		}
		return $yaml_values;
	}

	/**
	 * Pull a Yaml block enclosed by --- out of content
	 * @param string $content
	 * @return string
	 */
	public static function get($content) {
		// Look for YAML enclosed in ---
		preg_match('/-{3}(.*)-{3}/s', $content, $yaml);
		return $yaml[0];
	}
}