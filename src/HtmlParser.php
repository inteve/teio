<?php

	namespace Teio;

	use Nette\Utils\Html;
	use Nette\Utils\Strings;


	class HtmlParser
	{
		const TYPE_TAG = 'tag';
		const TYPE_COMMENT = 'comment';


		/**
		 * @param  string
		 * @return Html
		 */
		public function parse($s)
		{
			$s = Strings::fixEncoding($s); // TODO only ?checkEncoding?
			$s = Strings::normalize($s);

			$matches = $this->matchPatterns($s, [
				self::TYPE_TAG => '#<(/?)([a-z][a-z0-9_:-]{0,50})((?:\s++[a-z0-9\_:-]++|=\s*+"[^"]*+"|=\s*+\'[^\']*+\'|=[^\s>]++)*)\s*+(/?)>#isu',
				self::TYPE_COMMENT => '#<!--(.*?)-->#is',
			]);

			$dom = Html::el();
			$currentElement = $dom;
			$queue = [];
			$lastOffset = 0;
			// TODO: inline vs block elements

			foreach ($matches as $match) {
				$offset = $match[0];
				$type = $match[1];

				if ($lastOffset < $offset) {
					$currentElement->addText(html_entity_decode(substr($s, $lastOffset, $offset - $lastOffset), ENT_QUOTES, 'UTF-8'));
					$lastOffset = $offset;
				}

				if ($type === self::TYPE_COMMENT) {
					$comment = $match[2][0];
					$currentElement->addHtml($comment);
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

					if (!$isEmpty && isset(Html::$emptyElements[$mTag])) {
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
						if ($currentElement->getName() === $mTag) {
							array_pop($queue);
							$currentElement = end($queue);

							if (!($currentElement instanceof Html)) {
								$currentElement = $dom;
							}
						}

					} else {
						if ($isEmpty) {
							$currentElement->addHtml(Html::el($mTag));

						} else {
							$el = Html::el($mTag . ' ' . $mAttr);
							$currentElement->addHtml($el);
							$queue[] = $el;
							$currentElement = $el;
						}
					}

					$tag = $match[2][0];
					$lastOffset += strlen($tag);

				} else {
					throw new \Teio\InvalidStateException("Invalid match type '$type'.");
				}
			}

			return $dom;
		}


		/**
		 * Inspired by Texy
		 * @param  string
		 * @param  array
		 * @return array
		 */
		private function matchPatterns($s, array $patterns)
		{
			$matches = [];
			$priority = 0;

			foreach ($patterns as $name => $pattern) {
				/** @var array<int, array<int, array{string, int}>>|null $ms */
				$ms = Strings::matchAll($s, $pattern, PREG_OFFSET_CAPTURE);

				foreach ((array) $ms as $m) {
					$offset = $m[0][1];

					foreach ($m as $k => $v) {
						$m[$k] = $v[0];
					}

					$matches[] = [$offset, $name, $m, $priority];
				}

				$priority++;
			}

			unset($name, $pattern, $ms, $m, $k, $v);

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
