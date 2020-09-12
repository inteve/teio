<?php

	namespace Teio\Dom;

	use Nette\Utils\Html;
	use Teio\Helpers;


	class DomParentNode
	{
		/** @var Html */
		private $node;

		/** @var int */
		private $position;

		/** @var bool */
		private $isLast;


		/**
		 * @param  bool $isLast
		 */
		public function __construct(Html $node, $position, $isLast)
		{
			$this->node = $node;
			$this->position = $position;
			$this->isLast = $isLast;
		}


		/**
		 * @return string|NULL
		 */
		public function getName()
		{
			$name = $this->node->getName();
			return !self::isNameEmpty($name) ? $name : NULL;
		}


		public function hasAttribute($attr)
		{
			return isset($this->node->attrs[$attr]);
		}


		public function getAttribute($attr)
		{
			return $this->node->getAttribute($attr);
		}


		public function hasClass($class)
		{
			if (!$this->hasAttribute('class')) {
				return FALSE;
			}

			$value = self::formatAttributeValue($this->getAttribute('class'));
			return strpos(" $value ", " $class ") !== FALSE;
		}


		public function isFirst()
		{
			return $this->position === 0;
		}


		public function isLast()
		{
			return $this->isLast;
		}


		/**
		 * @param  Html|string
		 * @return void
		 */
		public function appendChild($html)
		{
			$this->node->addHtml($html);
		}


		/**
		 * @return Html
		 */
		public function cloneTag()
		{
			return Html::el($this->node->getName(), $this->node->attrs);
		}
	}
