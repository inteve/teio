<?php

	namespace Teio;

	use Nette\Utils\Html;


	class HtmlDom
	{
		/** @var Html */
		private $dom;


		public function __construct(Html $dom)
		{
			$this->dom = $dom;
		}


		/**
		 * @return string
		 */
		public function toHtml()
		{
			return (string) $dom;
		}
	}
