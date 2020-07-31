<?php

namespace App\Service;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FilesService
{
    public function getDirs($dir, $returnSelf = false)
    {
        $dirs = [];

        if ($returnSelf) {
            $dirs[] = $dir;
        }

        foreach ($this->getFilesIterator($dir) as $file) {
            if ($file->isDir()) {
                $dirs[] = $file->getPathname();
            }
        }

        return $dirs;
    }

    public function getFiles($dir, $recursive = true)
    {
        $files = [];

        if ($recursive) {
            foreach ($this->getFilesIterator($dir) as $file) {
                if (!$file->isDir()) {
                    $files[] = $file->getPathname();
                }
            }
        } else {
            $scannedDir = array_diff(scandir($dir), ['..', '.']);
            foreach ($scannedDir as $key => $file) {
                $file = $dir . DIRECTORY_SEPARATOR . $file;
                if (!is_dir($file)) {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    public function createDir($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public function clearDir($dir)
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = $this->getFilesIterator($dir);

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
    }

    private function getFilesIterator($dir)
    {
        $dirIterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        return new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::CHILD_FIRST);
    }
}
