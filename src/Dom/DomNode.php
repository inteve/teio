<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Teio\Helpers;


	class DomNode
	{
		const TYPE_HTML = 0;
		const TYPE_TEXT = 1;
		const TYPE_HTML_STRING = 2;

		/** @var int|NULL */
		private $index;

		/** @var Html|string */
		private $node;

		/** @var DomNode */
		private $parent;

		/** @var int */
		private $type;

		/** @var int|NULL */
		private $position;

		/** @var bool */
		private $isLast;


		/**
		 * @param  int|NULL $index
		 * @param  bool $isLast
		 */
		public function __construct($index, $node, DomNode $parent = NULL, $position, $isLast)
		{
			$this->index = $index;

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

			$this->parent = $parent;
			$this->position = $position;
			$this->isLast = $isLast;
		}


		/**
		 * @return bool
		 */
		public function hasParent()
		{
			return $this->parent !== NULL;
		}


		/**
		 * @return DomNode|NULL
		 */
		public function getParent()
		{
			return $this->parent;
		}


		/**
		 * @return DomNode[]
		 */
		public function getParents()
		{
			$result = [];
			$child = $this;

			while ($parent = $child->getParent()) {
				$result[] = $parent;
				$child = $parent;
			}

			return array_reverse($result);
		}


		public function isHtml()
		{
			return $this->type === self::TYPE_HTML;
		}


		public function isText()
		{
			return $this->type === self::TYPE_TEXT;
		}


		public function isRoot()
		{
			return $this->parent === NULL;
		}


		public function isElement()
		{
			return !$this->isRoot() && $this->isHtml() && $this->hasName();
		}


		/**
		 * @return bool
		 */
		public function hasName()
		{
			return !self::isNameEmpty($this->getElementNode()->getName());
		}


		/**
		 * @return string|NULL
		 */
		public function getName()
		{
			$name = $this->getElementNode()->getName();
			return !self::isNameEmpty($name) ? $name : NULL;
		}


		public function setName($name)
		{
			$this->getElementNode()->setName($name);
			return $this;
		}


		public function hasAttribute($attr)
		{
			return isset($this->getElementNode()->attrs[$attr]);
		}


		public function getAttribute($attr)
		{
			return $this->getElementNode()->getAttribute($attr);
		}


		public function setAttribute($attr, $value)
		{
			$this->getElementNode()->setAttribute($attr, $value);
			return $this;
		}


		public function removeAttribute($attr)
		{
			$this->getElementNode()->removeAttribute($attr);
			return $this;
		}


		public function getAttributes()
		{
			return $this->getElementNode()->attrs;
		}


		public function setAttributes(array $attrs)
		{
			$this->getElementNode()->attrs = $attrs;
			return $this;
		}


		public function hasClass($class)
		{
			if (!$this->hasAttribute('class')) {
				return FALSE;
			}

			$value = self::formatAttributeValue($this->getAttribute('class'));
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


		public function hasPosition()
		{
			return $this->position !== NULL;
		}


		public function isFirst()
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('Node has not position.');
			}

			return $this->position === 0;
		}


		public function isLast()
		{
			if (!$this->hasPosition()) {
				throw new \Teio\InvalidStateException('Node has not position.');
			}

			return $this->isLast;
		}


		public function wrapBy(Html $wrapper)
		{
			if ($this->node instanceof Html) {
				$original = clone $this->node;
				$this->node->setName($wrapper->getName());
				$this->node->attrs = $wrapper->attrs;
				$this->node->setHtml($original);

			} else {
				throw new \Teio\InvalidStateException('Only Html instance can be wrapped.');
			}
			return $this;
		}


		public function replaceByHtml($html)
		{
			$this->node = $html;
			$this->type = ($html instanceof Html) ? self::TYPE_HTML : self::TYPE_HTML_STRING;
			$this->getParent()->replaceChild($this->index, $this->node);
			return $this;
		}


		public function moveUp()
		{
			$parent = $this->getParent();

			if ($parent->isRoot()) {
				return;
			}

			$parentParent = $parent->getParent();
			$parentParentNode = $parentParent->node;
			$parentNode = $parent->node;
			$parentChildren = $parentNode->getChildren();
			$first = array_slice($parentChildren, 0, $this->index);
			$second = array_slice($parentChildren, $this->index + 1);

			$parentIndex = $parent->index;
			$parentNode->removeChildren();
			$secondParentNode = clone $parentNode;
			$replace = TRUE;

			if (!empty($first)) {
				foreach ($first as $child) {
					$parentNode->addHtml($child);
				}

				$parentParentNode->insert($parentIndex, $parentNode, $replace);
				$replace = FALSE;
				$parentIndex++;
			}

			$parentParentNode->insert($parentIndex, $this->node, $replace);
			$parentIndex++;

			if (!empty($second)) {
				foreach ($second as $child) {
					$secondParentNode->addHtml($child);
				}

				$parentParentNode->insert($parentIndex, $secondParentNode, FALSE);
			}

			$this->parent = $parentParent;
		}


		/**
		 * @return self[]
		 */
		public function getChildren()
		{
			$parent = $this->getHtmlNode();
			$children = $parent->getChildren();
			$count = 0;

			foreach ($children as $child) {
				if ($child instanceof Html) {
					$count++;
				}
			}

			$result = [];
			$position = 0;

			foreach ($children as $index => $child) {
				$childPosition = NULL;

				if ($child instanceof Html) {
					$childPosition = $position;
				}

				$result[] = new self($index, $child, $this, $childPosition, ($position + 1) === $count);

				if ($child instanceof Html) {
					$position++;
				}
			}

			return $result;
		}


		private function replaceChild($index, $child)
		{
			$this->getHtmlNode()->insert($index, $child, TRUE);
		}


		/**
		 * @return Html
		 */
		private function getHtmlNode()
		{
			if ($this->node instanceof Html) {
				return $this->node;
			}

			throw new \Teio\InvalidStateException('Node must be instance of Html.' . gettype($this->node));
		}


		/**
		 * @return Html
		 */
		private function getElementNode()
		{
			if ($this->parent === NULL) {
				throw new \Teio\InvalidStateException('This is root, not element.');
			}

			return $this->getHtmlNode();
		}


		public static function root(Html $node)
		{
			if (!self::isNameEmpty($node->getName())) {
				throw new \Teio\InvalidArgumentException('Root node name must be NULL, "' . $node->getName() . '" given.');
			}

			if (!empty($node->attrs)) {
				throw new \Teio\InvalidArgumentException('Root node attributes must be empty.');
			}

			return new self(NULL, $node, NULL, NULL, FALSE);
		}


		/**
		 * @param  string
		 * @return bool
		 */
		private static function isNameEmpty($name)
		{
			return $name === NULL || $name === '';
		}


		/**
		 * @param  mixed
		 * @return string
		 */
		private static function formatAttributeValue($value)
		{
			if (is_array($value)) {
				$tmp = null;
				foreach ($value as $k => $v) {
					if ($v != null) { // intentionally ==, skip nulls & empty string
						// composite 'style' vs. 'others'
						$tmp[] = $v === true ? $k : (is_string($k) ? $k . ':' . $v : $v);
					}
				}

				if ($tmp === null) {
					return '';
				}

				$value = implode(' ', $tmp);
			}

			return (string) $value;
		}
	}
