<?php

declare(strict_types=1);

namespace App\Chat;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class StoreMessage implements StoreMessageInterface
{
    public function __construct(
        private readonly string $uploads,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function __invoke(Message $message): void
    {
        if (($violationsList = $this->validator->validate($message))->count() > 0) {
            throw new ValidationFailedException($message, $violationsList);
        }

        $filename = sprintf('%s/questions_%s.yaml', $this->uploads, date('Y-m-d'));

        $filesystem = new Filesystem();

        /** @var array<array-key, Question> $questions */
        $questions = $filesystem->exists($filename)
            ? $this->serializer->deserialize(
                file_get_contents($filename),
                sprintf('%s[]', Question::class),
                'yaml'
            )
            : [];

        $questions[] = $message->toQuestion();

        $filesystem->dumpFile($filename, $this->serializer->serialize($questions, 'yaml'));
    }
}
