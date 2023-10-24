<?php

	namespace Teio;


	class Helpers
	{
		private function __construct()
		{
		}


		/**
		 * @param  string $html
		 * @return string
		 */
		public static function htmlToText($html)
		{
			return html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
		}


		/**
		 * @param  string $text
		 * @return string
		 */
		public static function textToHtml($text)
		{
			return htmlspecialchars((string) $text, ENT_NOQUOTES, 'UTF-8');
		}


		/**
		 * @param  string $name
		 * @return bool
		 */
		public static function isNameEmpty($name)
		{
			return $name === NULL || $name === '';
		}


		/**
		 * @param  scalar|NULL|array<scalar|NULL> $value
		 * @return string
		 */
		public static function formatAttributeValue($value)
		{
			if (is_array($value)) {
				$tmp = null;
				foreach ($value as $k => $v) {
					if ($v != null) { // intentionally ==, skip nulls & empty string
						// composite 'style' vs. 'others'
						$tmp[] = $v === true ? $k : (is_string($k) ? $k . ':' . $v : $v);
					}
				}

				if ($tmp === null) {
					return '';
				}

				$value = implode(' ', $tmp);
			}

			return (string) $value;
		}
	}
