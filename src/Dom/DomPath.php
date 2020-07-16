<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;


	class DomPath
	{
		/** @var DomPathPart[] */
		private $parts;


		public function __construct(array $parts)
		{
			$this->parts = $parts;
		}


		/**
		 * @return DomPathPart[]
		 */
		public function getParts()
		{
			return $this->parts;
		}


		/**
		 * @return bool
		 */
		public function isRoot()
		{
			return empty($this->parts);
		}


		public static function root()
		{
			return new self([]);
		}


		public static function append(DomPath $path, Html $element, $index, $isLast)
		{
			$parts = $path->getParts();
			$parts[] = new DomPathPart($element, $index, $isLast);
			return new self($parts);
		}
	}
