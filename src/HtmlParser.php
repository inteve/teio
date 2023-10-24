<?php

	declare(strict_types=1);

	namespace Teio;

	use Nette\Utils\Html;
	use Nette\Utils\Strings;


	class HtmlParser
	{
		const TYPE_TAG = 'tag';
		const TYPE_COMMENT = 'comment';


		/** @var DomRules */
		private $domRules;


		public function __construct(DomRules $domRules)
		{
			$this->domRules = $domRules;
		}


		/**
		 * @param  string $s
		 * @return Dom\Dom
		 */
		public function parse($s)
		{
			$s = Strings::fixEncoding($s); // TODO only ?checkEncoding?
			$s = Strings::normalize($s);

			$matches = $this->matchPatterns($s, [
				self::TYPE_TAG => '#<(/?)([a-z][a-z0-9_:-]{0,50})((?:\s++[a-z0-9\_:-]++|=\s*+"[^"]*+"|=\s*+\'[^\']*+\'|=[^\s>]++)*)\s*+(/?)>#isu',
				self::TYPE_COMMENT => '#<!--(.*?)-->#is',
			]);

			$domBuilder = new Dom\DomBuilder($this->domRules);
			$lastOffset = 0;
			// TODO: inline vs block elements

			foreach ($matches as $match) {
				$offset = $match[0];
				$type = $match[1];

				if ($lastOffset < $offset) {
					$domBuilder->addTextNode(substr($s, $lastOffset, $offset - $lastOffset));
					$lastOffset = $offset;
				}

				if ($type === self::TYPE_COMMENT) {
					$comment = $match[2][0];
					$domBuilder->addCommentNode($comment);
					$lastOffset += strlen($comment);

				} elseif ($type === self::TYPE_TAG) {
					$mEnd = $match[2][1];
					$mTag = strtolower($match[2][2]);
					$mAttr = $match[2][3];
					$mEmpty = $match[2][4];

					$isStart = $mEnd !== '/';
					$isEmpty = $mEmpty === '/';

					if (!$isEmpty && substr($mAttr, -1) === '/') { // uvizlo v $mAttr?
						$mAttr = substr($mAttr, 0, -1);
						$isEmpty = TRUE;
					}

					// error - can't close empty element </abc/>
					if ($isEmpty && !$isStart) {
						continue;
					}

					// error - end element with atttrs </abc attr>
					$mAttr = trim(strtr($mAttr, "\n", ' '));

					if ($mAttr && !$isStart) {
						continue;
					}

					if (!$isStart) {
						$domBuilder->endNode($mTag);

					} else {
						if ($isEmpty) {
							$domBuilder->addEmptyNode($mTag, $mAttr);

						} else {
							$domBuilder->startNode($mTag, $mAttr);
						}
					}

					$tag = $match[2][0];
					$lastOffset += strlen($tag);

				} else {
					throw new \Teio\InvalidStateException("Invalid match type '$type'.");
				}
			}

			if ($lastOffset < strlen($s)) {
				$domBuilder->addTextNode(substr($s, $lastOffset));
			}

			return $domBuilder->toDom();
		}


		/**
		 * Inspired by Texy
		 * @param  string $s
		 * @param  array<string, string> $patterns
		 * @return array<array{
		 *   0: int,
		 *   1: string,
		 *   2: array<int, string>,
		 *   3: int,
		 * }>
		 */
		private function matchPatterns($s, array $patterns)
		{
			$matches = [];
			$priority = 0;

			foreach ($patterns as $name => $pattern) {
				/** @var array<int, array<int, array{0: string, 1: int}>>|null $ms */
				$ms = Strings::matchAll($s, $pattern, PREG_OFFSET_CAPTURE);

				foreach ((array) $ms as $m) {
					$offset = $m[0][1];
					$m = array_map(function ($v) {
						return $v[0];
					}, $m);

					$matches[] = [$offset, $name, $m, $priority];
				}

				$priority++;
			}

			unset($name, $pattern, $ms, $m);

			usort($matches, function ($a, $b): int {
				if ($a[0] === $b[0]) {
					return $a[3] < $b[3] ? -1 : 1;
				}

				if ($a[0] < $b[0]) {
					return -1;
				}

				return 1;
			});

			return $matches;
		}
	}
