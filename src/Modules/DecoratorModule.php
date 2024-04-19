<?php

	declare(strict_types=1);

	namespace Teio\Modules;

	use Teio\Dom\Dom;
	use Teio\Module;


	class DecoratorModule implements Module
	{
		/** @var array<string, callable> */
		private $decorators;


		/**
		 * @param array<string, callable> $decorators
		 */
		public function __construct(array $decorators)
		{
			$this->decorators = $decorators;
		}


		public function process(Dom $dom): void
		{
			foreach ($this->decorators as $selector => $decorator) {
				$dom->find($selector, $decorator);
			}
		}
	}
