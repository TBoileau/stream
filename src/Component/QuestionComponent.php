<?php

declare(strict_types=1);

namespace App\Component;

use App\Entity\Question;
use App\UseCase\Chat\QuestionProviderInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(name: 'question', template: 'question/component.html.twig')]
final class QuestionComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?Question $question = null;

    public function __construct(private readonly QuestionProviderInterface $questionProvider)
    {
        $this->load();
    }

    #[LiveAction]
    public function load(): void
    {
        $this->question = $this->questionProvider->current();
    }
}
