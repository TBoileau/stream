<?php

namespace App\Command;

use App\Entity\Message;
use App\UseCase\Chat\QuestionProcessorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'stream:chat',
    description: 'Get questions from Twitch chat',
)]
final class StreamChatCommand extends Command
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly QuestionProcessorInterface $questionProcessor
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('message', InputArgument::REQUIRED, 'Message')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $message = Message::create(
                (string) $input->getArgument('username'),
                (string) $input->getArgument('message')
            );

            if (($violationsList = $this->validator->validate($message))->count() > 0) {
                throw new ValidationFailedException($message, $violationsList);
            }

            $this->questionProcessor->save($message->toQuestion());

            $io->success(
                sprintf(
                    'Merci @%s pour ta question, j\'y répondrais lors de la FAQ, un peu de patience.',
                    $message->username
                )
            );
            return Command::SUCCESS;
        } catch (ValidationFailedException $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }
    }
}
