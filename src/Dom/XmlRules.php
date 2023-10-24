<?php

	declare(strict_types=1);

	namespace Teio\Dom;

	use Teio\DomRules;


	class XmlRules implements DomRules
	{
		public function canBeEmpty($tagName)
		{
			return TRUE;
		}


		public function canBeParent($tagName)
		{
			return TRUE;
		}


		public function isCommentAllowed($comment, array $parents)
		{
			return self::ALLOW;
		}


		public function isTextAllowed($text, array $parents)
		{
			return self::ALLOW;
		}


		public function isElementAllowed($tagName, array $parents)
		{
			return self::ALLOW;
		}
	}
