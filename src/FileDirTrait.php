<?php

namespace Application\Console;

trait FileDirTrait
{
    /**
     * Create dir
     *
     * @param string $dir
     * @return int
     */
    protected function createDir(string $dir): int
    {
        if (!is_dir($dir)) {
            $oldumask = umask(0);
            umask($oldumask);
            if (!mkdir($dir, 0777, true)) {
                return -1;
            }
        }
        return 0;
    }

    /**
     * Save file
     *
     * @param string $model
     * @param string $filename
     * @return int
     */
    protected function saveFile(string $model, string $filename): int
    {
        if (!file_exists($filename)) {
            if (($handle = fopen($filename, 'x'))) {
                fwrite($handle, $model);
                fclose($handle);
                chmod($filename, 0666);
                return 0;
            }
        }
        return -1;
    }
}
