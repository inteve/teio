<?php

	declare(strict_types=1);

	namespace Teio\Dom;


	class DomParentNodes
	{
		/** @var non-empty-array<DomParentNode> */
		private $nodes;

		/** @var DomParentNode[] */
		private $endedNodes = [];


		public function __construct(DomParentNode $root)
		{
			$this->nodes = [$root];
		}


		public function getLastNode(): DomParentNode
		{
			return end($this->nodes);
		}


		/**
		 * @return non-empty-array<DomParentNode>
		 */
		public function getNodes(): array
		{
			return $this->nodes;
		}


		public function addNode(DomParentNode $node): void
		{
			$this->nodes[] = $node;
			$this->endedNodes = [];
		}


		public function gotoLevel(int $level): void
		{
			if ($level < 1) {
				throw new \Teio\InvalidArgumentException('Level must be 1 or higher.');
			}

			$nodes = array_slice($this->nodes, 0, $level);

			if (count($nodes) !== $level || empty($nodes)) {
				throw new \Teio\InvalidStateException('Invalid leveling.');
			}

			$this->nodes = $nodes;
			$this->endedNodes = [];
		}


		public function canMoveUp(): bool
		{
			return count($this->nodes) > 1;
		}


		public function moveUp(int $levels = 1): void
		{
			if (!$this->canMoveUp()) {
				throw new \Teio\InvalidStateException('Cannot be moved up.');
			}

			if ($levels < 1) {
				throw new \Teio\InvalidArgumentException('Invalid level.');
			}

			if ($levels > (count($this->nodes) - 1)) {
				throw new \Teio\InvalidArgumentException('Level is out of range.');
			}

			$endedNodes = array_slice($this->nodes, $levels * -1);
			$newNodes = array_slice($this->nodes, 0, count($this->nodes) - count($endedNodes));

			if (count($newNodes) === 0) {
				throw new \Teio\InvalidStateException('No new nodes.');
			}

			$this->nodes = $newNodes;
			array_unshift($this->endedNodes, ...$endedNodes);
		}


		public function moveToRoot(): void
		{
			if (!$this->canMoveUp()) {
				throw new \Teio\InvalidStateException('Cannot be moved up.');
			}

			$endedNodes = array_slice($this->nodes, 1);
			$newNodes = array_slice($this->nodes, 0, 1);

			if (count($newNodes) === 0) {
				throw new \Teio\InvalidStateException('No new nodes.');
			}

			$this->nodes = $newNodes;
			array_unshift($this->endedNodes, ...$endedNodes);
		}


		public function recreateEndedNodes(): void
		{
			foreach ($this->endedNodes as $endedNode) {
				$element = $endedNode->cloneTag();
				$this->getLastNode()->appendChild($element);
				$this->addNode(new DomParentNode($element, NULL));
			}

			$this->endedNodes = [];
		}
	}
