<?php

	declare(strict_types=1);

	namespace Teio\Dom;


	class DomPosition
	{
		/** @var int */
		private $position;

		/** @var bool */
		private $isLast;


		/**
		 * @param  bool $isLast
		 */
		public function __construct(int $position, $isLast)
		{
			if ($position < 0) {
				throw new \Teio\InvalidArgumentException('Position must be 0 or higher.');
			}

			$this->position = $position;
			$this->isLast = $isLast;
		}


		public function isFirst(): bool
		{
			return $this->position === 0;
		}


		public function isLast(): bool
		{
			return $this->isLast;
		}
	}
