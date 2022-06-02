# kw_langs

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
