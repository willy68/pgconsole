<?php

/**
 * Attempts to load Composer's autoload.php as either a dependency or a
 * stand-alone package.
 *
 * @return bool
 */

return function () {
    $files = [
      __DIR__ . '/../vendor/autoload.php', // stand-alone package vendor/bin
      __DIR__ . '/../../../../vendor/autoload.php', // stand-alone package bin dir symlink
    ];
    foreach ($files as $file) {
        if (is_file($file)) {
            require_once $file;

            return true;
        }
    }

    return false;
};
