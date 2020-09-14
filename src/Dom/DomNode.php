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

		/** @var Html|string */
		private $node;

		/** @var DomParentNodes|NULL */
		private $parents;

		/** @var int */
		private $type;

		/** @var DomPosition|NULL */
		private $position;

		/** @var bool */
		private $skipChildren = FALSE;


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
		public function detach()
		{
			$this->node = NULL;
			$this->type = self::TYPE_DETACHED;
			$this->parents = NULL;
		}


		public function remove()
		{
			$this->node = NULL;
			$this->type = self::TYPE_REMOVED;
			$this->parents = NULL;
		}


		public function canSkipChildren()
		{
			return $this->skipChildren;
		}


		public function skipChildren($skipChildren = TRUE)
		{
			$this->skipChildren = $skipChildren;
			return $this;
		}


		public function getNode()
		{
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


		public function isHtml()
		{
			return $this->type === self::TYPE_HTML;
		}


		public function isText()
		{
			return $this->type === self::TYPE_TEXT;
		}


		public function isRemoved()
		{
			return $this->type === self::TYPE_REMOVED;
		}


		public function isElement()
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


		/**
		 * @return string|NULL
		 */
		public function getName()
		{
			$name = $this->getHtmlNode()->getName();
			return !Helpers::isNameEmpty($name) ? $name : NULL;
		}


		public function setName($name)
		{
			$this->getHtmlNode()->setName($name);
			return $this;
		}


		public function hasAttribute($attr)
		{
			return isset($this->getHtmlNode()->attrs[$attr]);
		}


		public function getAttribute($attr)
		{
			return $this->getHtmlNode()->getAttribute($attr);
		}


		public function setAttribute($attr, $value)
		{
			$this->getHtmlNode()->setAttribute($attr, $value);
			return $this;
		}


		public function removeAttribute($attr)
		{
			$this->getHtmlNode()->removeAttribute($attr);
			return $this;
		}


		public function getAttributes()
		{
			return $this->getHtmlNode()->attrs;
		}


		public function setAttributes(array $attrs)
		{
			$this->getHtmlNode()->attrs = $attrs;
			return $this;
		}


		public function hasClass($class)
		{
			if (!$this->hasAttribute('class')) {
				return FALSE;
			}

			$value = Helpers::formatAttributeValue($this->getAttribute('class'));
			return strpos(" $value ", " $class ") !== FALSE;
		}


		public function setHtml($html)
		{
			$this->getHtmlNode()->setHtml($html);
			return $this;
		}


		public function setText($text)
		{
			$this->getHtmlNode()->setText($text);
			return $this;
		}


		public function getText()
		{
			if ($this->node instanceof Html) {
				return $this->node->getText();
			}

			return Helpers::htmlToText($this->node);
		}


		public function addHtml($html)
		{
			$this->getHtmlNode()->addHtml($html);
			return $this;
		}


		public function addText($text)
		{
			$this->getHtmlNode()->addText($text);
			return $this;
		}


		public function hasChildren()
		{
			return (bool) $this->getHtmlNode()->count();
		}


		public function setChildren(array $children)
		{
			$node = $this->getHtmlNode();
			$node->removeChildren();

			foreach ($children as $child) {
				$node->addHtml($child);
			}

			return $this;
		}


		public function removeChildren()
		{
			$this->getHtmlNode()->removeChildren();
			return $this;
		}


		public function hasPosition()
		{
			return $this->position !== NULL;
		}


		public function isFirst()
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('Node has not position.');
			}

			return $this->position->isFirst();
		}


		public function isLast()
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('Node has not position.');
			}

			return $this->position->isLast();
		}


		public function wrapBy(Html $wrapper)
		{
			if ($this->node instanceof Html) {
				$original = clone $this->node;
				$this->node->setName($wrapper->getName());
				$this->node->attrs = $wrapper->attrs;
				$this->node->setHtml($original);

			} else {
				throw new \Teio\InvalidArgumentException('Only Html instance can be wrapped.');
			}
			return $this;
		}


		public function replaceByHtml($html)
		{
			$this->node = $html;
			$this->type = ($html instanceof Html) ? self::TYPE_HTML : self::TYPE_HTML_STRING;
			return $this;
		}


		public function moveUp($levels = 1)
		{
			$this->parents->moveUp($levels);
			return $this;
		}


		public function moveToRoot()
		{
			$this->parents->moveToRoot();
		}


		public function canMoveUp()
		{
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
