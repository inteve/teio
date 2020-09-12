<?php

	namespace Teio\Dom;

	use Teio\Helpers;


	class DomParentNodes
	{
		/** @var DomParentNode[] */
		private $nodes;

		/** @var DomParentNode[] */
		private $endedNodes = [];


		public function __construct(DomParentNode $root)
		{
			$this->nodes = [$root];
		}


		/**
		 * @return DomParentNode
		 */
		public function getLastNode()
		{
			return end($this->nodes);
		}


		public function getNodes()
		{
			return $this->nodes;
		}


		public function addNode(DomParentNode $node)
		{
			$this->nodes[] = $node;
			$this->endedNodes = [];
		}


		public function gotoLevel($level)
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


		public function canMoveUp()
		{
			return count($this->nodes) > 1;
		}


		public function moveUp($levels = 1)
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
			$this->nodes = array_slice($this->nodes, 0, count($this->nodes) - count($endedNodes));
			array_unshift($this->endedNodes, ...$endedNodes);
		}


		public function moveToRoot()
		{
			if (!$this->canMoveUp()) {
				throw new \Teio\InvalidStateException('Cannot be moved up.');
			}

			$endedNodes = array_slice($this->nodes, 1);
			$this->nodes = array_slice($this->nodes, 0, 1);
			array_unshift($this->endedNodes, ...$endedNodes);
		}


		public function recreateEndedNodes()
		{
			foreach ($this->endedNodes as $endedNode) {
				$element = $endedNode->cloneTag();
				$this->getLastNode()->appendChild($element);
				$this->addNode(new DomParentNode($element, 0, FALSE)); // TODO
			}

			$this->endedNodes = [];
		}
	}
