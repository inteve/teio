<?php

	namespace Teio\Modules;

	use Nette\Utils\Html;
	use Nette\Utils\Strings;
	use Teio\IModule;
	use Teio\HtmlDom;


	class ScriptModule implements IModule
	{
		/** @var array<string, callable> */
		private $handlers;


		public function __construct(array $handlers)
		{
			$this->handlers = $handlers;
		}


		public function process(HtmlDom $dom)
		{
			$nodes = $dom->findTextNodes();

			foreach ($nodes as &$node) {
				$matches = Strings::matchAll($node, '#\{\{((?:[^}]++|[}])+)\}\}()#U');

				foreach ($matches as $match) {
					$content = $match[1];
					$cmd = trim($content);

					if ($cmd === '') {
						continue;
					}

					
				}
				// Strings::match()
				// a pripadne pokud je handler tak replace
			}
		}
	}
