<?php

	declare(strict_types=1);

	namespace Teio\Modules;

	use Teio\Dom\Dom;
	use Teio\Dom\Node;
	use Teio\Module;


	class RenameClassModule implements Module
	{
		/** @var array<string, string|NULL> */
		private $replacements;


		/**
		 * @param array<string, string|NULL> $replacements
		 */
		public function __construct(array $replacements)
		{
			$this->replacements = $replacements;
		}


		public function process(Dom $dom): void
		{
			$dom->walk(function (Node $node) {
				if ($node->isElement()) {
					foreach ($this->replacements as $old => $new) {
						if (!$node->hasClass($old)) {
							continue;
						}

						$node->removeClass($old);

						if ($new !== NULL) {
							$node->addClass($new);
						}
					}
				}
			});
		}
	}
