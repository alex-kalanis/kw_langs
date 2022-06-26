<?php

//// Example bootstrap code for KWCMS

// where is the system?
$paths = new \kalanis\kw_paths\Path();
$paths->setDocumentRoot(realpath($_SERVER['DOCUMENT_ROOT']));
$paths->setPathToSystemRoot('/..');
\kalanis\kw_paths\Stored::init($paths);

// init config
\kalanis\kw_confs\Config::init(new \kalanis\kw_confs\Loaders\PhpLoader($paths));
\kalanis\kw_confs\Config::load('Core'); // autoload core config

// load virtual parts - if exists
$virtualDir = \kalanis\kw_confs\Config::get('Core', 'net.virtual_dir', 'dir_from_config/');
$params = new \kalanis\kw_paths\Params\Request\Server();
$params->set($virtualDir)->process();
$paths->setData($params->getParams());

session_start();

// pass parsed params as external source
$source = new \kalanis\kw_input\Sources\Basic();
$source->setCli($argv ?: [])->setExternal($params->getParams()); // argv is for params from cli
$inputs = new \kalanis\kw_input\Inputs();
$inputs->setSource($source)->loadEntries();
$session = new \kalanis\kw_input\Simplified\SessionAdapter();

// init langs - the similar way like configs, but it's necessary to already have loaded params
\kalanis\kw_langs\Lang::init(
    new \kalanis\kw_langs\Loaders\PhpLoader($paths),
    \kalanis\kw_langs\Support::fillFromPaths(
        $paths,
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
