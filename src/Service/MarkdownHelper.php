<?php
namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{

    private $markdownParser;
    private $cache;
    private $isDebug;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MarkdownParserInterface $markdownParser, CacheInterface $cache, bool $isDebug, LoggerInterface $mdLogger)
    {
        $this->markdownParser = $markdownParser;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
        $this->logger = $mdLogger;
        //dump($isDebug);

    }

    public function parse(string $source):string
    {
        //logger service, dependency injection
        if(stripos($source, 'cat') !== false){
            $this->logger->info('Meow!');
        }

        //inyeccion de dependencia con valores escalares
        if ($this->isDebug) {
            return $this->markdownParser->transformMarkdown($source);
        }

        //using cache service
        return $this->cache->get('markdown_'.md5($source), function() use ($source){
            return $this->markdownParser->transformMarkdown($source);
        });
    }

}