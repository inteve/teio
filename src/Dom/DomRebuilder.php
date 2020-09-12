<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Nette\Utils\Strings;


	class DomRebuilder
	{
		/** @var Html */
		private $dom;

		/** @var callable */
		private $cb;

		/** @var array */
		private $stack = [];


		public function __construct(Html $dom, callable $cb)
		{
			$this->dom = $dom;
			$this->cb = $cb;
		}


		public function rebuild()
		{
			$this->stack = [];
			$parents = [];
			$lastLevel = 0;
			$cb = $this->cb;

			$this->addChildrenFrom($this->dom, $lastLevel + 1);
			$this->dom->removeChildren();
			$parents[] = [
				'node' => new DomParentNode($this->dom, 0, TRUE),
				'element' => $this->dom,
			];

			while (!empty($this->stack)) {
				$item = array_shift($this->stack);
				$element = $item['element'];
				$level = $item['level'];

				if ($level < 1) {
					throw new \Teio\InvalidStateException('Level must be 1 or higher.');
				}

				$parents = array_slice($parents, 0, $level);

				if (count($parents) !== $level || empty($parents)) {
					throw new \Teio\InvalidStateException('Invalid leveling.');
				}

				$parent = end($parents);
				$parentElement = $parent['element'];
				$node = new DomNode($element, new DomParentNodes(array_slice($parents, 1)), $item['position'], $item['isLast']);
				$cb($node);

				if ($node->isRemoved()) {
					continue;
				}

				$newElement = $node->getNode();
				$node->detach();
				$parentElement->addHtml($newElement);

				if (($newElement instanceof Html) && count($newElement) > 0) {
					$this->addChildrenFrom($newElement, $level + 1);
					$parents[] = [
						'node' => new DomParentNode($newElement, $item['position'], $item['isLast']),
						'element' => $newElement,
					];
					$newElement->removeChildren();
				}
			}
		}


		private function addChildrenFrom(Html $parent, $childLevel)
		{
			$toStack = [];
			$position = 0;

			foreach ($parent as $index => $child) {
				$childPosition = NULL;

				if ($child instanceof Html) {
					$childPosition = $position;
					$position++;
				}

				$toStack[] = $item = [
					'element' => $child,
					'index' => $index,
					'level' => $childLevel,
					'position' => $childPosition,
					'isLast' => FALSE,
				];
			}

			if (is_array($item)) {
				$item['isLast'] = TRUE;
			}

			array_unshift($this->stack, ...$toStack);
		}
	}
