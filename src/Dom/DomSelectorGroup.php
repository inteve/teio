<?php

	namespace Teio\Dom;


	class DomSelectorGroup
	{
		/** @var DomSelector */
		private $selector;

		/** @var DomSelectorGroupPart */
		private $parts = [];


		public function __construct(DomSelector $selector)
		{
			$this->selector = $selector;
		}


		public function addPart()
		{
			return $this->parts[] = new DomSelectorGroupPart($this);
		}


		/**
		 * @return bool
		 */
		public function matchPath(DomPath $path)
		{
			if ($path->isRoot() || empty($this->parts)) {
				return FALSE;
			}

			$selectorParts = $this->parts;
			$pathParts = $path->getParts();

			$selectorPart = self::fetch($selectorParts);
			$pathPart = self::fetch($pathParts);

			if (!self::matchPart($selectorPart, $pathPart)) {
				return FALSE;
			}

			while (($selectorPart = self::fetch($selectorParts)) !== NULL) {
				$matchThis = FALSE;

				while (($pathPart = self::fetch($pathParts)) !== NULL) {
					if (self::matchPart($selectorPart, $pathPart)) {
						$matchThis = TRUE;
						break;
					}
				}

				if (!$matchThis) {
					return FALSE;
				}
			}

			return TRUE;
		}


		/**
		 * Fetchs element from end of array or NULL if array is empty.
		 * @return mixed|NULL
		 */
		private static function fetch(array &$arr)
		{
			if (empty($arr)) {
				return NULL;
			}
			return array_pop($arr);
		}


		private static function matchPart(DomSelectorGroupPart $selectorPart = NULL, DomPathPart $pathPart = NULL)
		{
			if ($selectorPart === NULL || $pathPart === NULL) {
				return FALSE;
			}

			return $selectorPart->matchPathPart($pathPart);
		}
	}
