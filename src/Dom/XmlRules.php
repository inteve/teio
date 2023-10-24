<?php

	namespace Teio\Dom;

	use Teio\IDomRules;


	class XmlRules implements IDomRules
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
