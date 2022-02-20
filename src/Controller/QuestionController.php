<?php

namespace App\Controller;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

class QuestionController extends AbstractController
{
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
    public function show($slug, MarkdownParserInterface $markdownParser, CacheInterface $cache){

        //dump($slug, $this);

        $answers = [
            'Make sure your cat is sitting purrrfectly still?',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe.. try saying the spell backwards?',
        ];

        $questionText = 'I\'ve been turned into a cat, any *thoughts* on how to turn back? While I\'m **adorable**, I don\'t really care for cat food.';
        //$parsedQuestionText = $markdownParser->transformMarkdown($questionText);

        //using cache service
        $parsedQuestionText = $cache->get('markdown_'.md5($questionText), function() use ($questionText, $markdownParser){
            return $markdownParser->transformMarkdown($questionText);
        });

        //dd($markdownParser);
        dump($cache);

        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-',' ', $slug)),
            'answers' => $answers,
            'questionText' => $parsedQuestionText,
        ]);
    }
}