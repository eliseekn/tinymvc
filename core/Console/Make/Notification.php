<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Core\Enums\NotificationType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new mail template.
 */
class Notification extends Command
{
    protected function configure(): void
    {
        $this->setName('make:notification');
        $this->setDescription('Create new mail');
        $this->addArgument('notification', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of notification (separated by space if many)');
        $this->addOption('type', null, InputOption::VALUE_REQUIRED, 'The type of notification (sms or mail)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $notifications = $input->getArgument('notification');
        $type = $input->getOption('type');

        foreach ($notifications as $notification) {
            [, $class] = Maker::generateClass($notification, $type, force_singular: true);

            if (! $this->createNotification($notification, $type)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create notification <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Notification <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createNotification(string $notification, string $type): bool
    {
        [, $class] = Maker::generateClass($notification, $type, force_singular: true);

        $data = Maker::stubs()->addPath('notifications')->readFile(ucfirst($type).'.stub');
        $data = str_replace('CLASS_NAME', $class, $data);
        $data = str_replace('RESOURCE_NAME', $notification, $data);

        if ($type === NotificationType::SMS) {
            return storage(config('storage.sms'))->writeFile($class.'.php', $data);
        }

        if (! storage(config('storage.mails'))->writeFile($class.'.php', $data)) {
            return false;
        }

        $data = Maker::stubs()->addPath('views')->readFile('email.stub');

        return storage(config('storage.views'))
            ->addPath('emails')
            ->writeFile($notification.'.html.twig', $data);
    }
}
