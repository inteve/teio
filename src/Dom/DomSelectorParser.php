<?php

	declare(strict_types=1);

	namespace Teio\Dom;

	use Nette;
	use Nette\Tokenizer\Stream;


	class DomSelectorParser
	{
		const IDENT = 0;
		const CLASSNAME = 1;
		const ID = 2;
		const PSEUDOCLASS = 3;
		const WHITESPACE = 4;
		const COMMA = 5;
		const ATTRIBUTE = 6;

		const IDENT_RE = '[a-z][a-z0-9-_]*';

		/** @var Nette\Tokenizer\Tokenizer */
		private $tokenizer;


		public function __construct()
		{
			$this->tokenizer = new Nette\Tokenizer\Tokenizer([
				self::IDENT => self::IDENT_RE,
				self::CLASSNAME => '\.' . self::IDENT_RE,
				self::ID => '\#' . self::IDENT_RE,
				self::PSEUDOCLASS => ':' . self::IDENT_RE,
				self::ATTRIBUTE => '\[' . self::IDENT_RE . '\]',
				self::WHITESPACE => '\s+',
				self::COMMA => ',',
			]);
		}



		/**
		 * @param  string $selector
		 * @return DomSelector
		 */
		public function parse($selector)
		{
			$stream = $this->tokenizer->tokenize($selector);
			$selector = new DomSelector;
			while ($this->parseGroup($selector->addGroup(), $stream)) {}
			return $selector;
		}


		private function parseGroup(DomSelectorGroup $group, Stream $stream): bool
		{
			$part = $group->addPart();
			$stream->nextAll(self::WHITESPACE);

			while ($stream->nextToken()) {
				if ($stream->isCurrent(self::IDENT)) {
					$part->requireTag((string) $stream->currentValue());

				} elseif ($stream->isCurrent(self::CLASSNAME)) {
					$part->requireClass(substr((string) $stream->currentValue(), 1));

				} elseif ($stream->isCurrent(self::ID)) {
					$part->requireId(substr((string) $stream->currentValue(), 1));

				} elseif ($stream->isCurrent(self::ATTRIBUTE)) {
					$part->requireAttribute(substr((string) $stream->currentValue(), 1, -1));

				} elseif ($stream->isCurrent(self::PSEUDOCLASS)) {
					$value = $stream->currentValue();

					if ($value === ':first-child') {
						$part->requireFirstPosition();

					} elseif ($value === ':last-child') {
						$part->requireLastPosition();

					} else {
						throw new \Teio\InvalidStateException("Unknow pseudoclass $value.");
					}

				} elseif ($stream->isCurrent(self::WHITESPACE)) {
					$part = $group->addPart();
					$stream->nextAll(self::WHITESPACE);

				} elseif ($stream->isCurrent(self::COMMA)) {
					return TRUE; // next group

				} else {
					throw new \Teio\InvalidStateException('Unknow token.');
				}
			}

			return FALSE; // end
		}
	}
