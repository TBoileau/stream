<?php

namespace App\Command;

use App\Chat\Message;
use App\Chat\StoreMessageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsCommand(
    name: 'stream:chat',
    description: 'Get questions from Twitch chat',
)]
final class StreamChatCommand extends Command
{
    public function __construct(private readonly StoreMessageInterface $storeMessage)
    {
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
            $this->storeMessage->__invoke(
                $message = Message::create(
                    (string) $input->getArgument('username'),
                    (string) $input->getArgument('message')
                )
            );
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
