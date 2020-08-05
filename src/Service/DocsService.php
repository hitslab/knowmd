<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Directory;
use App\Entity\Document;
use Parsedown;

class DocsService
{
    private $filesService;
    private $docsPath;
    private $buildPath;
    private $parser;
    private $templateService;
    private $sidebarService;

    public function __construct(
        FilesService $filesService,
        TemplateService $templateService,
        SidebarService $sidebarService,
        Parsedown $parser,
        $docsPath,
        $buildPath
    ) {
        $this->filesService = $filesService;
        $this->templateService = $templateService;
        $this->docsPath = $docsPath;
        $this->buildPath = $buildPath;
        $this->parser = $parser;
        $this->sidebarService = $sidebarService;
    }

    public function buildDocs()
    {
        $documents = $this->getDocuments();

        $sidebarHtml = $this->sidebarService->generateSidebar($documents);

        foreach ($documents as $document) {
            $this->buildArticle($document, $sidebarHtml);
            foreach ($document->articles as $article) {
                $this->buildArticle($article, $sidebarHtml);
            }
        }
    }

    /**
     * @return Document[]
     */
    public function getDocuments()
    {
        $documents = [];

        $documentsFiles = $this->filesService->getFiles($this->docsPath, true);

        foreach ($documentsFiles as $documentFile) {
            if (mb_strtoupper(basename($documentFile)) !== "README.MD") {
                continue;
            }

            $document = new Document($documentFile);
            $this->fillArticle($document);
            $document->articles = $this->getDocumentArticles($document);

            $documents[] = $document;
        }

        usort($documents, function ($d1, $d2) {
            return strcasecmp(dirname($d1->path), dirname($d2->path));
        });

        return $documents;
    }

    /**
     * @param Document $document
     * @return Article[]
     */
    public function getDocumentArticles(Document $document)
    {
        $articles = [];

        $files = $this->filesService->getFiles(dirname($document->path), false);

        foreach ($files as $file) {
            if ($file === $document->path) {
                continue;
            }

            $article = new Article($file);
            $this->fillArticle($article);

            $articles[] = $article;
        }

        return $articles;
    }

    private function buildArticle(Article $article, $sidebarHtml = '')
    {
        $targetDir = str_replace($this->docsPath, $this->buildPath, dirname($article->path));
        $this->filesService->createDir($targetDir);

        $htmlPath = str_replace($this->docsPath, $this->buildPath, $article->path);
        $htmlPath = str_replace('README.md', 'index.md', $htmlPath);
        $htmlPath = str_replace('.md', '.html', $htmlPath);

        $documentContent = file_get_contents($article->path);

        $htmlDocumentContent = $this->parser->text($documentContent);

        $htmlPageContent = $this->templateService->renderTemplate([
            'document' => $this->parser->text($htmlDocumentContent),
            'sidebar' => $sidebarHtml
        ]);

        file_put_contents($htmlPath, $htmlPageContent);
    }

    /**
     * @param Article $article
     */
    private function fillArticle(Article $article)
    {
        $regexp = '/^#{1,2}[^#].*/m';

        $name = null;
        $headers = [];

        $handle = fopen($article->path, "r");

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                preg_match($regexp, $line, $matches);
                if (count($matches)) {
                    $headerValue = str_replace('#', '', $matches[0]);
                    $headerValue = trim($headerValue);

                    if ($name === null) {
                        $name = $headerValue;
                    } else {
                        $headers[] = $headerValue;
                    }
                }
            }
            fclose($handle);
        } else {
            throw new \RuntimeException("Can't open file: {$article->path}");
        }

        if ($name === null) {
            $name = basename($article->path);
            $name = str_replace('.md', '', $name);
        }

        $article->name = $name;
        $article->headers = $headers;
    }
}
