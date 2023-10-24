<?php

	declare(strict_types=1);

	declare(strict_types=1);

	namespace Teio;


	interface DomRules
	{
		const DISALLOW = 0;
		const ALLOW = 1;
		const CLOSE_PARENT = 2;


		/**
		 * @param  string $tagName
		 * @return bool
		 */
		function canBeEmpty($tagName);


		/**
		 * @param  string $tagName
		 * @return bool
		 */
		function canBeParent($tagName);


		/**
		 * @param  string $comment
		 * @param  string[] $parents
		 * @return int  see self::DISALLOW, self::ALLOW
		 */
		function isCommentAllowed($comment, array $parents);


		/**
		 * @param  string $text
		 * @param  string[] $parents
		 * @return int  see self::DISALLOW, self::ALLOW
		 */
		function isTextAllowed($text, array $parents);


		/**
		 * @param  string $tagName
		 * @param  string[] $parents
		 * @return int  see self::DISALLOW, self::ALLOW, self::CLOSE_PARENT
		 */
		function isElementAllowed($tagName, array $parents);
	}
