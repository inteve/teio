<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Teio\DomRules;


	class Html5Rules implements DomRules
	{
		const MUST_BE_IN = 0;
		const MUST_BE_UNDER = 1;
		const MUST_NOT_BE_IN = 2;
		const MUST_NOT_BE_UNDER = 3;
		const CLOSES = 4;
		const CHILD_MUST_BE = 5;

		/** @var array<string, bool> */
		private static $inlineElements = [
			'::text' => TRUE,
			'a' => TRUE,
			'abbr' => TRUE,
			'area' => TRUE,
			'audio' => TRUE,
			'b' => TRUE,
			'bdi' => TRUE,
			'bdo' => TRUE,
			'br' => TRUE,
			'button' => TRUE,
			'canvas' => TRUE,
			'cite' => TRUE,
			'code' => TRUE,
			'data' => TRUE,
			'datalist' => TRUE,
			'del' => TRUE,
			'dfn' => TRUE,
			'em' => TRUE,
			'embed' => TRUE,
			'i' => TRUE,
			'iframe' => TRUE,
			'img' => TRUE,
			'input' => TRUE,
			'ins' => TRUE,
			'kbd' => TRUE,
			'label' => TRUE,
			'link' => TRUE,
			'map' => TRUE,
			'mark' => TRUE,
			'math' => TRUE,
			'meta' => TRUE,
			'meter' => TRUE,
			'noscript' => TRUE,
			'object' => TRUE,
			'output' => TRUE,
			'picture' => TRUE,
			'progress' => TRUE,
			'q' => TRUE,
			'ruby' => TRUE,
			's' => TRUE,
			'samp' => TRUE,
			'script' => TRUE,
			'select' => TRUE,
			'slot' => TRUE,
			'small' => TRUE,
			'span' => TRUE,
			'strong' => TRUE,
			'sub' => TRUE,
			'sup' => TRUE,
			'svg' => TRUE,
			'template' => TRUE,
			'textarea' => TRUE,
			'time' => TRUE,
			'u' => TRUE,
			'var' => TRUE,
			'video' => TRUE,
			'wbr' => TRUE,
		];

		/** @var array<string, array<self::*, string|string[]>> */
		private static $rules = [
			// child
			'a' => [
				self::MUST_NOT_BE_UNDER => 'a',
				// 'a' => self::CLOSE_PARENT,
			],
			'dd' => [
				self::MUST_BE_IN => 'dl',
				self::CLOSES => ['dd', 'dt'],
			],
			'dt' => [
				self::MUST_BE_IN => 'dl',
				self::CLOSES => ['dd', 'dt'],
			],
			'li' => [
				self::MUST_BE_IN => ['ol', 'ul'],
				self::CLOSES => 'li',
			],
			'option' => [
				self::MUST_BE_IN => ['datalist', 'select', 'optgroup'],
				self::CLOSES => 'option',
			],
			'optgroup' => [
				self::MUST_BE_IN => 'select',
				self::CLOSES => 'option',
			],
			'p' => [
				self::CLOSES => 'p',
			],
			'table' => [
				self::CHILD_MUST_BE => ['tbody', 'tfoot', 'thead', 'tr'],
			],
			'tbody' => [
				self::MUST_BE_IN => 'table',
				self::MUST_NOT_BE_IN => 'tbody',
				self::CLOSES => ['td', 'tfoot', 'th', 'thead', 'tr'],
			],
			'td' => [
				self::MUST_BE_IN => 'tr',
				self::CLOSES => ['td', 'th'],
			],
			'tfoot' => [
				self::MUST_BE_IN => 'table',
				self::MUST_NOT_BE_IN => 'tfoot',
				self::CLOSES => ['tbody', 'td', 'th', 'thead', 'tr'],
			],
			'th' => [
				self::MUST_BE_IN => 'tr',
				self::CLOSES => ['td', 'th'],
			],
			'thead' => [
				self::MUST_BE_IN => 'table',
				self::MUST_NOT_BE_IN => 'thead',
				self::CLOSES => ['tbody', 'td', 'tfoot', 'th', 'tr'],
			],
			'tr' => [
				self::MUST_BE_IN => ['table', 'tbody', 'thead', 'tfoot'],
				self::CLOSES => ['td', 'th', 'tr'],
			],
		];


		public function canBeEmpty($tagName)
		{
			return isset(Html::$emptyElements[$tagName]);
		}


		public function canBeParent($tagName)
		{
			return !isset(Html::$emptyElements[$tagName]);
		}


		public function isCommentAllowed($comment, array $parents)
		{
			return self::ALLOW;
		}


		public function isTextAllowed($text, array $parents)
		{
			if (trim($text) === '')  {
				return self::ALLOW;
			}

			return $this->isAllowed('::text', $parents);
		}


		public function isElementAllowed($tagName, array $parents)
		{
			return $this->isAllowed($tagName, $parents);
		}


		/**
		 * @param  string[] $parents
		 * @return self::CLOSE_PARENT|self::DISALLOW|self::ALLOW
		 */
		private function isAllowed(string $tagName, array $parents): int
		{
			$reverseParents = array_reverse($parents);
			$childRules = isset(self::$rules[$tagName]) ? self::$rules[$tagName] : NULL;

			// first closes what can be closed
			if (isset($childRules[self::CLOSES])) {
				foreach ($reverseParents as $parentTagName) {
					if (in_array($parentTagName, (array) $childRules[self::CLOSES], TRUE)) {
						return self::CLOSE_PARENT;
					}

					break;
				}
			}

			// check children
			if (!empty($parents)) {
				$directParentTagName = end($parents);

				if (isset(self::$rules[$directParentTagName][self::CHILD_MUST_BE]) && !in_array($tagName, (array) self::$rules[$directParentTagName][self::CHILD_MUST_BE], TRUE)) {
					return self::DISALLOW;
				}
			}

			// check MUST_BE_IN & MUST_NOT_BE_IN
			if (isset($childRules[self::MUST_BE_IN])) {
				if (empty($parents)) {
					return self::DISALLOW;
				}

				$directParentTagName = end($parents);

				if (!in_array($directParentTagName, (array) $childRules[self::MUST_BE_IN], TRUE)) {
					return self::DISALLOW;
				}
			}

			if (isset($childRules[self::MUST_NOT_BE_IN])) {
				if (!empty($parents)) {
					$directParentTagName = end($parents);

					if (in_array($directParentTagName, (array) $childRules[self::MUST_NOT_BE_IN], TRUE)) {
						return self::DISALLOW;
					}
				}
			}

			// check MUST_BE_UNDER & MUST_NOT_BE_UNDER
			if (isset($childRules[self::MUST_BE_UNDER])) {
				if (empty($parents)) {
					return self::DISALLOW;
				}

				$isUnder = FALSE;

				foreach ($parents as $parentTagName) {
					if (in_array($parentTagName, (array) $childRules[self::MUST_BE_UNDER], TRUE)) {
						$isUnder = TRUE;
					}
				}

				if (!$isUnder) {
					return self::DISALLOW;
				}
			}

			if (isset($childRules[self::MUST_NOT_BE_UNDER])) {
				if (!empty($parents)) {
					$isUnder = FALSE;

					foreach ($parents as $parentTagName) {
						if (in_array($parentTagName, (array) $childRules[self::MUST_NOT_BE_UNDER], TRUE)) {
							$isUnder = TRUE;
						}
					}

					if ($isUnder) {
						return self::DISALLOW;
					}
				}
			}

			if (isset($childRules[self::CLOSES])) {
				foreach ($reverseParents as $parentTagName) {
					if (in_array($parentTagName, (array) $childRules[self::CLOSES], TRUE)) {
						return self::CLOSE_PARENT;
					}

					break;
				}
			}

			$isChildInline = isset(self::$inlineElements[$tagName]);

			foreach ($reverseParents as $parentTagName) {
				if ($childRules !== NULL) {
					$isParentAllowed = (isset($childRules[self::MUST_BE_IN]) && in_array($parentTagName, (array) $childRules[self::MUST_BE_IN], TRUE))
						|| (isset($childRules[self::MUST_BE_UNDER]) && in_array($parentTagName, (array) $childRules[self::MUST_BE_UNDER], TRUE));

					if ($isParentAllowed) {
						continue;
					}
				}

				$isParentInline = isset(self::$inlineElements[$parentTagName]);

				if ($isParentInline && !$isChildInline) {
					return self::CLOSE_PARENT;
				}
			}

			return self::ALLOW;
		}
	}
