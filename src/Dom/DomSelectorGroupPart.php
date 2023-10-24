<?php

	declare(strict_types=1);

	namespace Teio\Dom;


	class DomSelectorGroupPart
	{
		/** @var DomSelectorGroup */
		private $group;

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


		public function addPart(): DomSelectorGroupPart
		{
			return $this->group->addPart();
		}


		/**
		 * @return $this
		 */
		public function requireTag(string $tag)
		{
			if ($this->tag !== NULL) {
				throw new \Teio\InvalidStateException('Part already requires tag.');
			}

			$this->tag = $tag;
			return $this;
		}


		/**
		 * @return $this
		 */
		public function requireId(string $id)
		{
			$this->ids[] = $id;
			return $this;
		}


		/**
		 * @return $this
		 */
		public function requireClass(string $class)
		{
			$this->classes[] = $class;
			return $this;
		}


		/**
		 * @return $this
		 */
		public function requireFirstPosition()
		{
			$this->firstPosition = TRUE;
			return $this;
		}


		/**
		 * @return $this
		 */
		public function requireLastPosition()
		{
			$this->lastPosition = TRUE;
			return $this;
		}


		/**
		 * @return bool
		 */
		public function matchNode(SelectableNode $node)
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
