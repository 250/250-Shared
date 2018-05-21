<?php
declare(strict_types=1);

namespace ScriptFUSION\Steam250\Shared\Command;

use ScriptFUSION\Steam250\Shared\Storage\ReadWriteStorageFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeleteCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('delete')
            ->setDescription('Deletes an uploaded file.')
            ->addArgument('file', InputArgument::REQUIRED, 'File path.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        return (int)!(new ReadWriteStorageFactory)->create()->delete($input->getArgument('file'));
    }
}
