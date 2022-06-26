# kw_langs

[![Build Status](https://travis-ci.org/alex-kalanis/kw_langs.svg?branch=master)](https://travis-ci.org/alex-kalanis/kw_langs)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/kw_langs/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/kw_langs/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/kw_langs/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_langs)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/kw_langs.svg?v1)](https://packagist.org/packages/alex-kalanis/kw_langs)
[![License](https://poser.pugx.org/alex-kalanis/kw_langs/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_langs)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/kw_langs/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/kw_langs/?branch=master)

Define used translations inside the KWCMS tree. Parse them and return them.

## PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_langs": "2.0"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)

## Package info

This package contains example file from KWCMS bootstrap. Use it as reference.

This lang bootstrap is connected with KWCMS modules. Using it outside KWCMS means
you need to know the tree structure of module system and positioning langs there.

The idea is about translations which are separated just by their key (in single namespace).

The basic language file itself is simple php file with defined array variable "$lang"
in which are stored key-value pairs like in normal php array. You do not need to specify
module - it will be automatically set into content array when language loads.

It's also possible to use your own loader which will read your translation files by your
own rules. So you can connect reading translations from get_text module files and that
all will still behave the same way. Just it's need to respect that loader's input is
module and lang name and output is array of key-value pairs which will be set into
translation array. Beware! Previously set translations can be overwritten!

For your own modules and for staying things separated my advice is to have lang key in
form of _"{module_name}.{key_itself}"_ - with dot as separator and module name as prefix.

#### Example:

For ['your internal system key' => 'output is %s, on %d']

```php
print \kalanis\kw_langs\Lang::get('your internal system key', 'first param', 2);
```

And it returns 'output is first param, on 2'

The best usage is inside the translations classes across the other modules - you just
fill ```Lang::get()``` with your keys to your translations. It is possible to make
a whole class which returns the wanted translations which will be instance of ```ILang```
and then pass it into lang loader.
