<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

use App\Service\MarkdownHelper;

class QuestionController extends AbstractController
{

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var bool
     */
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug){

        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(Environment $twigEnviroment){

        /**$html = $twigEnviroment->render('question/homepage.html.twig');
        return new Response($html);*/

        return $this->render("question/homepage.html.twig");
    }


    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show($slug, MarkdownHelper $markdownHelper){

        //dump($slug, $this);
        if($this->isDebug){
            $this->logger->info('we are in debug mode!');
        }
        $answers = [
            'Make sure your cat is sitting purrrfectly still?',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe.. try saying the spell backwards?',
        ];

        $questionText = 'I\'ve been turned into a cat, any *thoughts* on how to turn back? While I\'m **adorable**, I don\'t really care for cat food.';
        //$parsedQuestionText = $markdownParser->transformMarkdown($questionText);

        //parseando con nuestro nuevo servicio MarkdownHelper
        $parsedQuestionText = $markdownHelper->parse($questionText);

        //dd($markdownParser);
        //dump($cache);
        //dump($this->getParameter('cache_adapter'));

        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-',' ', $slug)),
            'answers' => $answers,
            'questionText' => $parsedQuestionText,
        ]);
    }
}