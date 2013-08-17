<?php
class Yaml {
	public static function parse($yaml = false) {
		// First split by new line
		$yaml_lines_array = explode("\n", $yaml);

		// Then split each element by ':'
		foreach ($yaml_lines_array as $line) {
			$exploded_yaml = explode(':', $line);
			$yaml_values[$exploded_yaml[0]] = trim($exploded_yaml[1]);
		}
		return $yaml_values;
	}

	public static function get($content) {
		// Look for YAML enclosed in ---
		preg_match('/-{3}(.*)-{3}/s', $content, $yaml);
		return $yaml[0];
	}
}