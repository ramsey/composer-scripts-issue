# composer-scripts-issue

I've discovered, if developing any Composer scripts, when running
`composer run-script [script-name]`, those scripts will use libraries bundled
in `composer.phar` instead of the vendor directory. This means you might get
out-of-date libraries and unexpected errors.

I have put together this simple example to illustrate this issue.

Here is how to reproduce:

```
git clone https://github.com/ramsey/composer-scripts-issue.git
cd composer-scripts-issue
```

Now, follow the instructions here to download `composer.phar`:
https://getcomposer.org/download/

```
./composer.phar install
./composer.phar run-script foo
```

With Composer version 1.10.6 (the latest version), you should see the following
error output:

```
> Foo\Foo::run
PHP Fatal error:  Uncaught Error: Call to undefined method Symfony\Component\Finder\Finder::ignoreVCSIgnored() in /.../composer-scripts-issue/Foo.php:17
Stack trace:
#0 phar:///.../composer-scripts-issue/composer.phar/src/Composer/EventDispatcher/EventDispatcher.php(305): Foo\Foo::run(Object(Composer\Script\Event))
#1 phar:///.../composer-scripts-issue/composer.phar/src/Composer/EventDispatcher/EventDispatcher.php(209): Composer\EventDispatcher\EventDispatcher->executeEventPhpScript('Foo\\Foo', 'run', Object(Composer\Script\Event))
#2 phar:///.../composer-scripts-issue/composer.phar/src/Composer/EventDispatcher/EventDispatcher.php(96): Composer\EventDispatcher\EventDispatcher->doDispatch(Object(Composer\Script\Event))
#3 phar:///.../composer-scripts-issue/composer.phar/src/Composer/Command/RunScriptCommand.php(106): Composer\EventDispatcher\EventDispatcher->dispatchScript('foo', true, Array)
#4 phar:///.../composer-scripts-issue/composer.phar/vendor/symfony/cons in /.../composer-scripts-issue/Foo.php on line 17

Fatal error: Uncaught Error: Call to undefined method Symfony\Component\Finder\Finder::ignoreVCSIgnored() in /.../composer-scripts-issue/Foo.php:17
Stack trace:
#0 phar:///.../composer-scripts-issue/composer.phar/src/Composer/EventDispatcher/EventDispatcher.php(305): Foo\Foo::run(Object(Composer\Script\Event))
#1 phar:///.../composer-scripts-issue/composer.phar/src/Composer/EventDispatcher/EventDispatcher.php(209): Composer\EventDispatcher\EventDispatcher->executeEventPhpScript('Foo\\Foo', 'run', Object(Composer\Script\Event))
#2 phar:///.../composer-scripts-issue/composer.phar/src/Composer/EventDispatcher/EventDispatcher.php(96): Composer\EventDispatcher\EventDispatcher->doDispatch(Object(Composer\Script\Event))
#3 phar:///.../composer-scripts-issue/composer.phar/src/Composer/Command/RunScriptCommand.php(106): Composer\EventDispatcher\EventDispatcher->dispatchScript('foo', true, Array)
#4 phar:///.../composer-scripts-issue/composer.phar/vendor/symfony/cons in /.../composer-scripts-issue/Foo.php on line 17
```

If running `composer` from `./vendor/bin/composer`, the script executes as
expected:

```
$ ./vendor/bin/composer run-script foo
> Foo\Foo::run
README.md
.gitignore
Foo.php
composer.json
```
