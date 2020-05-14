<?php

declare(strict_types=1);

namespace Foo;

use Composer\Script\Event;
use Symfony\Component\Finder\Finder;

class Foo
{
    public static function run(Event $event): void
    {
        $finder = new Finder();
        $finder
            ->ignoreDotFiles(false)
            ->ignoreVCSIgnored(true)
            ->in(__DIR__);

        foreach ($finder as $file) {
            $event->getIO()->write($file->getFilename(), true);
        }
    }
}
