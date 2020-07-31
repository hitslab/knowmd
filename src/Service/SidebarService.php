<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Document;

class SidebarService
{
    private $docsPath;
    private $buildPath;

    public function __construct($docsPath, $buildPath)
    {
        $this->docsPath = $docsPath;
        $this->buildPath = $buildPath;
    }

    /**
     * @param Document[] $documents
     * @return string
     */
    public function generateSidebar(array $documents)
    {
        $html = "<ul>";

        foreach ($documents as $document) {
            $html .= "<li>";
            $link = $this->getArticleLink($document);
            $html .= "<a href=\"{$link}\">{$document->name}</a>";
            if (count($document->articles)) {
                $html .= "<ul>";
                foreach ($document->articles as $article) {
                    $html .= "<li>";
                    $link = $this->getArticleLink($article);
                    $html .= "<a href=\"{$link}\">{$article->name}</a>";
                    $html .= "</li>";
                }
                $html .= "</ul>";
            }
            $html .= "</li>";
        }

        $html .= "</ul>";

        return $html;
    }

    public function getArticleLink(Article $article)
    {
        $path = str_replace($this->docsPath, '', $article->path);
        $path = trim($path, DIRECTORY_SEPARATOR);
        $path = "/" . str_replace(DIRECTORY_SEPARATOR, "/", $path);
        $path = str_replace("README", "index", $path);
        $path = str_replace(".md", ".html", $path);
        return $path;
    }
}
