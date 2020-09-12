<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Teio\Helpers;


	class DomPosition
	{
		/** @var int */
		private $position;

		/** @var bool */
		private $isLast;


		/**
		 * @param  bool $isLast
		 */
		public function __construct($position, $isLast)
		{
			if ($position < 0) {
				throw new \Teio\InvalidArgumentException('Position must be 0 or higher.');
			}

			$this->position = $position;
			$this->isLast = $isLast;
		}


		public function isFirst()
		{
			return $this->position === 0;
		}


		public function isLast()
		{
			return $this->isLast;
		}
	}
