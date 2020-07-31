<?php

namespace App\Service;

class BuildService
{
    private $assetsService;
    private $docsService;
    private $filesService;
    private $buildPath;

    public function __construct(
        AssetsService $assetsService,
        DocsService $docsService,
        FilesService $filesService,
        $buildPath
    ) {
        $this->assetsService = $assetsService;
        $this->docsService = $docsService;
        $this->filesService = $filesService;
        $this->buildPath = $buildPath;
    }

    public function build()
    {
        $this->filesService->clearDir($this->buildPath);
        $this->filesService->createDir($this->buildPath);
        $this->assetsService->buildAssets();
        $this->docsService->buildDocs();
    }
}
