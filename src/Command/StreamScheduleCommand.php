<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Live;
use App\Entity\Schedule;
use App\UseCase\Schedule\ScheduleGeneratorInterface;
use DateInterval;
use DateTimeImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'stream:schedule',
    description: 'Generate Schedule',
)]
final class StreamScheduleCommand extends Command
{
    public function __construct(private readonly ScheduleGeneratorInterface $scheduleGenerator)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $schedule = new Schedule();

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $yearQuestion = new Question('Veuillez saisir l\'année : ', (int) date('Y'));
        $year = (int) $helper->ask($input, $output, $yearQuestion);

        $weekQuestion = new Question('Veuillez saisir le numéro de semaine : ');
        $week = (int) $helper->ask($input, $output, $weekQuestion);

        $schedule->startedAt = (new DateTimeImmutable())->setISODate($year, $week);
        $schedule->endedAt = $schedule->startedAt->add(new DateInterval('P4D'));

        $day = 1;

        do {
            $live = new Live();

            $dayQuestion = new Question('Veuillez saisir le jour du prochain live : ', 0);
            $day = (int) $helper->ask($input, $output, $dayQuestion);

            if ($day < 1 || $day > 7) {
                continue;
            }

            $timeQuestion = new Question('Veuillez saisir l\'heure du live : ', '18:00');
            [$hour, $minute] = explode(':', (string) $helper->ask($input, $output, $timeQuestion));

            $live->startTime= $schedule->startedAt->add(new DateInterval(sprintf('P%dD', ($day - 1))))->setTime((int) $hour, (int) $minute);

            $titleQuestion = new Question('Veuillez insérer le titre de l\'épisode : ');
            $live->title = (string) $helper->ask($input, $output, $titleQuestion);

            $schedule->lives[] = $live;
        } while ($day >= 1 && $day <= 7);


        $filename = $this->scheduleGenerator->generate($schedule);

        $io->success(sprintf('Votre planning a été créé : %s', $filename));

        return Command::SUCCESS;
    }
}
