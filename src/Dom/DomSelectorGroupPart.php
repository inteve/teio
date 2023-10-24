<?php

	namespace Teio\Dom;


	class DomSelectorGroupPart
	{
		/** @var DomSelectorGroup */
		private $group;

		/** @var DomSelectorGroupPart[] */
		private $parts = [];

		/** @var string|NULL */
		private $tag;

		/** @var string[] */
		private $ids = [];

		/** @var string[] */
		private $classes = [];

		/** @var bool */
		private $firstPosition = FALSE;

		/** @var bool */
		private $lastPosition = FALSE;


		public function __construct(DomSelectorGroup $group)
		{
			$this->group = $group;
		}


		public function addPart()
		{
			return $this->group->addPart();
		}


		public function requireTag($tag)
		{
			if ($this->tag !== NULL) {
				throw new \Teio\InvalidStateException('Part already requires tag.');
			}

			$this->tag = $tag;
			return $this;
		}


		public function requireId($id)
		{
			$this->ids[] = $id;
			return $this;
		}


		public function requireClass($class)
		{
			$this->classes[] = $class;
			return $this;
		}


		public function requireFirstPosition()
		{
			$this->firstPosition = TRUE;
			return $this;
		}


		public function requireLastPosition()
		{
			$this->lastPosition = TRUE;
			return $this;
		}


		/**
		 * @return bool
		 */
		public function matchNode(ISelectableNode $node)
		{
			if (!$node->isElement()) {
				return FALSE;
			}

			if ($this->tag !== NULL && $node->getName() !== $this->tag) {
				return FALSE;
			}

			foreach ($this->ids as $id) {
				if (!$node->hasAttribute('id') || $node->getAttribute('id') !== $id) {
					return FALSE;
				}
			}

			foreach ($this->classes as $class) {
				if (!$node->hasClass($class)) {
					return FALSE;
				}
			}

			if ($this->firstPosition && !$node->isFirst()) {
				return FALSE;
			}

			if ($this->lastPosition && !$node->isLast()) {
				return FALSE;
			}

			return TRUE;
		}
	}
