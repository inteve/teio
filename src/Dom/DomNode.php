<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Teio\Helpers;


	class DomNode implements ISelectableNode
	{
		const TYPE_HTML = 0;
		const TYPE_TEXT = 1;
		const TYPE_HTML_STRING = 2;
		const TYPE_DETACHED = 3;
		const TYPE_REMOVED = 4;

		/** @var Html|string|NULL */
		private $node;

		/** @var DomParentNodes|NULL */
		private $parents;

		/** @var int */
		private $type;

		/** @var DomPosition|NULL */
		private $position;

		/** @var bool */
		private $skipChildren = FALSE;

		/** @var Html[] */
		private $wrappers = [];


		/**
		 * @param Html|string $node
		 */
		public function __construct($node, DomParentNodes $parents, DomPosition $position = NULL)
		{
			if ($node instanceof Html) {
				$this->type = self::TYPE_HTML;
				$this->node = $node;

			} elseif (is_string($node)) {
				if (strpos($node, '<') === FALSE) {
					$this->type = self::TYPE_TEXT;
					$this->node = $node;

				} else {
					$this->type = self::TYPE_HTML_STRING;
					$this->node = $node;
				}

			} else {
				throw new \Teio\InvalidArgumentException("Node must be Html instance or HTML string.");
			}

			$this->parents = $parents;
			$this->position = $position;
		}


		/**
		 * @internal
		 */
		public function detach(): void
		{
			$this->node = NULL;
			$this->type = self::TYPE_DETACHED;
			$this->parents = NULL;
		}


		public function remove(): void
		{
			$this->node = NULL;
			$this->type = self::TYPE_REMOVED;
			$this->parents = NULL;
		}


		public function canSkipChildren(): bool
		{
			return $this->skipChildren;
		}


		/**
		 * @return $this
		 */
		public function skipChildren(bool $skipChildren = TRUE)
		{
			$this->skipChildren = $skipChildren;
			return $this;
		}


		/**
		 * @return Html|string
		 */
		public function getNode()
		{
			if ($this->node === NULL) {
				throw new \Teio\InvalidStateException('Node is detached.');
			}

			return $this->node;
		}


		/**
		 * @return bool
		 */
		public function hasParent()
		{
			return $this->parents !== NULL;
		}


		/**
		 * @return DomParentNode[]
		 */
		public function getParents()
		{
			if ($this->parents === NULL) {
				throw new \Teio\InvalidStateException('Node has not parents.');
			}

			return $this->parents->getNodes();
		}


		public function isHtml(): bool
		{
			return $this->type === self::TYPE_HTML;
		}


		public function isText(): bool
		{
			return $this->type === self::TYPE_TEXT;
		}


		public function isRemoved(): bool
		{
			return $this->type === self::TYPE_REMOVED;
		}


		public function isElement(): bool
		{
			return $this->isHtml() && $this->hasName();
		}


		/**
		 * @return bool
		 */
		public function hasName()
		{
			return !Helpers::isNameEmpty($this->getHtmlNode()->getName());
		}


		public function getName(): string
		{
			$name = $this->getHtmlNode()->getName();
			return !Helpers::isNameEmpty($name) ? $name : '';
		}


		/**
		 * @return $this
		 */
		public function setName(string $name)
		{
			$this->getHtmlNode()->setName($name);
			return $this;
		}


		public function hasAttribute(string $attr): bool
		{
			return isset($this->getHtmlNode()->attrs[$attr]);
		}


		/**
		 * @return scalar|scalar[]
		 */
		public function getAttribute(string $attr)
		{
			$value = $this->getHtmlNode()->getAttribute($attr);
			assert(is_array($value) || is_scalar($value));
			return $value;
		}


		/**
		 * @param  scalar|scalar[] $value
		 * @return $this
		 */
		public function setAttribute(string $attr, $value)
		{
			$this->getHtmlNode()->setAttribute($attr, $value);
			return $this;
		}


		/**
		 * @return $this
		 */
		public function removeAttribute(string $attr)
		{
			$this->getHtmlNode()->removeAttribute($attr);
			return $this;
		}


		/**
		 * @return array<string, scalar|scalar[]>
		 */
		public function getAttributes()
		{
			$attrs = [];

			foreach ($this->getHtmlNode()->attrs as $name => $value) {
				assert(is_array($value) || is_scalar($value));
				$attrs[$name] = $value;
			}

			return $attrs;
		}


		/**
		 * @param  array<string, scalar|scalar[]> $attrs
		 * @return $this
		 */
		public function setAttributes(array $attrs)
		{
			$this->getHtmlNode()->attrs = $attrs;
			return $this;
		}


		public function hasClass(string $class): bool
		{
			if (!$this->hasAttribute('class')) {
				return FALSE;
			}

			$value = Helpers::formatAttributeValue($this->getAttribute('class'));
			return strpos(" $value ", " $class ") !== FALSE;
		}


		/**
		 * @return $this
		 */
		public function setHtml(string $html)
		{
			$this->getHtmlNode()->setHtml($html);
			return $this;
		}


		/**
		 * @return $this
		 */
		public function setText(string $text)
		{
			$this->getHtmlNode()->setText($text);
			return $this;
		}


		public function getText(): string
		{
			if ($this->node instanceof Html) {
				return $this->node->getText();
			}

			return $this->node !== NULL ? Helpers::htmlToText($this->node) : '';
		}


		/**
		 * @return $this
		 */
		public function addHtml(string $html)
		{
			$this->getHtmlNode()->addHtml($html);
			return $this;
		}


		/**
		 * @return $this
		 */
		public function addText(string $text)
		{
			$this->getHtmlNode()->addText($text);
			return $this;
		}


		public function hasChildren(): bool
		{
			return (bool) $this->getHtmlNode()->count();
		}


		/**
		 * @param  array<Html|string> $children
		 * @return $this
		 */
		public function setChildren(array $children)
		{
			$node = $this->getHtmlNode();
			$node->removeChildren();

			foreach ($children as $child) {
				$node->addHtml($child);
			}

			return $this;
		}


		/**
		 * @return $this
		 */
		public function removeChildren()
		{
			$this->getHtmlNode()->removeChildren();
			return $this;
		}


		/**
		 * @phpstan-assert-if-true !NULL $this->position
		 */
		public function hasPosition(): bool
		{
			return $this->position !== NULL;
		}


		public function isFirst(): bool
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('Node has not position.');
			}

			return $this->position->isFirst();
		}


		public function isLast(): bool
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('Node has not position.');
			}

			return $this->position->isLast();
		}


		/**
		 * @return $this
		 */
		public function wrapBy(Html $wrapper)
		{
			$this->wrappers[] = $wrapper;
			return $this;
		}


		/**
		 * @return Html[]
		 */
		public function getWrappers()
		{
			return $this->wrappers;
		}


		/**
		 * @param  Html|string $html
		 * @return $this
		 */
		public function replaceByHtml($html)
		{
			$this->node = $html;
			$this->type = ($html instanceof Html) ? self::TYPE_HTML : self::TYPE_HTML_STRING;
			return $this;
		}


		/**
		 * @return $this
		 */
		public function moveUp(int $levels = 1)
		{
			if ($this->parents === NULL) {
				throw new \Teio\InvalidStateException('Node is detached.');
			}

			$this->parents->moveUp($levels);
			return $this;
		}


		public function moveToRoot(): void
		{
			if ($this->parents === NULL) {
				throw new \Teio\InvalidStateException('Node is detached.');
			}

			$this->parents->moveToRoot();
		}


		public function canMoveUp(): bool
		{
			if ($this->parents === NULL) {
				throw new \Teio\InvalidStateException('Node is detached.');
			}

			return $this->parents->canMoveUp();
		}


		/**
		 * @return Html[]
		 */
		public function getChildren()
		{
			return $this->getHtmlNode()->getChildren();
		}


		/**
		 * @return Html
		 */
		private function getHtmlNode()
		{
			if ($this->node instanceof Html) {
				return $this->node;
			}

			throw new \Teio\InvalidStateException('Node must be instance of Html, ' . gettype($this->node) . ' given.');
		}
	}
