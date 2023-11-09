<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Live;
use App\UseCase\Thumbnail\ThumbnailGeneratorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'stream:thumbnail',
    description: 'Generate Thumbnail',
)]
final class StreamThumbnailCommand extends Command
{
    public function __construct(
        private readonly string $logoDir,
        private readonly ThumbnailGeneratorInterface $thumbnailGenerator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $live = new Live();

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $seasonQuestion = new Question('Veuillez insérer le numéro de la saison : ');
        $live->season = (int) $helper->ask($input, $output, $seasonQuestion);

        $episodeQuestion = new Question('Veuillez insérer le numéro de l\'épisode : ');
        $live->episode = (int) $helper->ask($input, $output, $episodeQuestion);

        $titleQuestion = new Question('Veuillez insérer le titre de l\'épisode : ');
        $live->title = (string) $helper->ask($input, $output, $titleQuestion);

        $finder = new Finder();
        $finder->files()->in($this->logoDir);

        /** @var array<string, string> $logos */
        $logos = [];

        foreach ($finder as $file) {
            $logos[$file->getFilenameWithoutExtension()] = $file->getPathname();
        }

        $logoQuestion = new ChoiceQuestion('Veuillez choisir un logo : ', array_keys($logos));
        $live->logo = $logos[(string) $helper->ask($input, $output, $logoQuestion)];

        $filename = $this->thumbnailGenerator->generate($live);

        $io->success(sprintf('Votre thumbnail a été créé : %s', $filename));

        return Command::SUCCESS;
    }
}
