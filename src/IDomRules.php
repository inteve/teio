<?php

	namespace Teio;


	interface IDomRules
	{
		const DISALLOW = 0;
		const ALLOW = 1;
		const CLOSE_PARENT = 2;


		/**
		 * @param  string
		 * @return bool
		 */
		function canBeEmpty($tagName);


		/**
		 * @param  string
		 * @return bool
		 */
		function canBeParent($tagName);


		/**
		 * @param  string[]
		 * @return int  see self::DISALLOW, self::ALLOW
		 */
		function isCommentAllowed($comment, array $parents);


		/**
		 * @param  string[]
		 * @return int  see self::DISALLOW, self::ALLOW
		 */
		function isTextAllowed($text, array $parents);


		/**
		 * @param  string
		 * @param  string[]
		 * @return int  see self::DISALLOW, self::ALLOW, self::CLOSE_PARENT
		 */
		function isElementAllowed($tagName, array $parents);
	}
