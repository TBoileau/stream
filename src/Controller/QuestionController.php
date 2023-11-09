<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\Chat\QuestionProcessorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions', name: 'question_')]
final class QuestionController extends AbstractController
{
    #[Route('/live', name: 'live', methods: Request::METHOD_GET)]
    public function live(): Response
    {
        return $this->render('live.html.twig');
    }

    #[Route('/next', name: 'next', methods: Request::METHOD_GET)]
    public function next(QuestionProcessorInterface $questionProcessor): JsonResponse
    {
        $questionProcessor->next();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/previous', name: 'previous', methods: Request::METHOD_GET)]
    public function previous(QuestionProcessorInterface $questionProcessor): JsonResponse
    {
        $questionProcessor->previous();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
