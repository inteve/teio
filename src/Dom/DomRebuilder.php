<?php

	declare(strict_types=1);

	namespace Teio\Dom;

	use Nette\Utils\Html;


	class DomRebuilder
	{
		/** @var Html */
		private $dom;

		/** @var callable */
		private $cb;

		/**
		 * @var array<array{
		 *   element: Html|string,
		 *   index: int,
		 *   level: int,
		 *   position: int|NULL,
		 *   isLast: bool
		 * }>
		 */
		private $stack = [];


		public function __construct(Html $dom, callable $cb)
		{
			$this->dom = $dom;
			$this->cb = $cb;
		}


		public function rebuild(): void
		{
			$this->stack = [];
			$cb = $this->cb;

			array_unshift($this->stack, ...$this->addChildrenFrom($this->dom, 1));
			$this->dom->removeChildren();
			$parents = new DomParentNodes(new DomParentNode($this->dom, NULL));

			while (count($this->stack) > 0) {
				$item = array_shift($this->stack);
				$element = $item['element'];
				$level = $item['level'];

				$parents->gotoLevel($level);
				$position = $item['position'] !== NULL ? new DomPosition($item['position'], $item['isLast']) : NULL;
				$node = new DomNode($element, $parents, $position);
				$cb($node);

				if ($node->isRemoved()) {
					continue;
				}

				$newElement = $node->getNode();
				$newContent = $newElement;

				foreach ($node->getWrappers() as $wrapper) {
					$newContent = Html::el($wrapper->getName(), $wrapper->attrs)->setHtml($newContent);
				}

				$parents->getLastNode()->appendChild($newContent);
				$parents->recreateEndedNodes();
				$node->detach();

				if (!$node->canSkipChildren() && ($newElement instanceof Html) && count($newElement) > 0) {
					array_unshift($this->stack, ...$this->addChildrenFrom($newElement, $level + 1));
					$newElement->removeChildren();
					$parents->addNode(new DomParentNode($newElement, $position));
				}
			}
		}


		/**
		 * @param  int $childLevel
		 * @return array<array{
		 *   element: Html|string,
		 *   index: int,
		 *   level: int,
		 *   position: int|NULL,
		 *   isLast: bool
		 * }>
		 */
		private function addChildrenFrom(Html $parent, $childLevel)
		{
			$toStack = [];
			$position = 0;
			$item = NULL;

			foreach ($parent->getChildren() as $index => $child) {
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

			return $toStack;
		}
	}
