<?php

	namespace Teio;


	class Helpers
	{
		public function __construct()
		{
			throw new \Teio\StaticClassException('This is static class.');
		}


		/**
		 * @param  string
		 * @return string
		 */
		public static function htmlToText($html)
		{
			return html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
		}
	}
