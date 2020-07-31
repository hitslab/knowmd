<?php

namespace App\Service;

class AssetsService
{
    private $filesService;
    private $assetsPath;
    private $buildPath;

    public function __construct(FilesService $filesService, $assetsPath, $buildPath)
    {
        $this->filesService = $filesService;
        $this->assetsPath = $assetsPath;
        $this->buildPath = $buildPath;
    }

    public function buildAssets()
    {
        $this->createDirs();

        $assets = $this->filesService->getFiles($this->assetsPath);
        foreach ($assets as $asset) {
            $targetAsset = str_replace($this->assetsPath, $this->buildPath, $asset);
            copy($asset, $targetAsset);
        }
    }

    private function createDirs()
    {
        $dirs = $this->filesService->getDirs($this->assetsPath);

        foreach ($dirs as $dir) {
            $targetDir = str_replace($this->assetsPath, $this->buildPath, $dir);
            $this->filesService->createDir($targetDir);
        }
    }
}
