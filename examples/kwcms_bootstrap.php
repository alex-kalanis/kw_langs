<?php

//// Example bootstrap code for KWCMS

// where is the system?
$systemPaths = new \kalanis\kw_paths\Path();
$systemPaths->setDocumentRoot(realpath($_SERVER['DOCUMENT_ROOT']));
$systemPaths->setPathToSystemRoot('/..');
\kalanis\kw_paths\Stored::init($systemPaths);

// load virtual parts - if exists
$routedPaths = new \kalanis\kw_routed_paths\RoutedPath(new \kalanis\kw_routed_paths\Sources\Server(
    strval(getenv('VIRTUAL_DIRECTORY') ?: 'dir_from_config/')
));
\kalanis\kw_routed_paths\StoreRouted::init($routedPaths);

// init config
\kalanis\kw_confs\Config::init(new \kalanis\kw_confs\Loaders\PhpLoader($systemPaths, $routedPaths));
\kalanis\kw_confs\Config::load('Core'); // autoload core config

session_start();

// pass parsed params as external source
$source = new \kalanis\kw_input\Sources\Basic();
$source->setCli($argv)->setExternal($routedPaths->getArray()); // argv is for params from cli
$inputs = new \kalanis\kw_input\Inputs();
$inputs->setSource($source)->loadEntries();
$session = new \kalanis\kw_input\Simplified\SessionAdapter();

// init langs - the similar way like configs, but it's necessary to already have loaded params
\kalanis\kw_langs\Lang::init(
    new \kalanis\kw_langs\Loaders\PhpLoader($systemPaths, $routedPaths),
    \kalanis\kw_langs\Support::fillFromPaths(
        $routedPaths,
        \kalanis\kw_langs\Support::fillFromArray(
            $session,
            \kalanis\kw_confs\Config::get('Core', 'page.default_lang', 'hrk')
        ),
        false
    )
);
\kalanis\kw_langs\Lang::load('Core'); // autoload core lang

// And now we have all necessary variables to build the context

// Then in page/framework/whatever

/// for ['your internal key' => 'output is %s, on %d']
print \kalanis\kw_langs\Lang::get('your internal key', 'first param', 2);
/// and it returns 'output is first param, on 2'

// the best usage is inside the translations classes across the other modules - you just fill Lang::get() with your key
