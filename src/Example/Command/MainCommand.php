<?php

namespace Example\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class MainCommand extends Command
{
    protected function configure()
    {
        $this->setName('example:main')
            ->setDescription('Run example command with optional number of CPUs')
            ->addArgument('CPUs', null, 'number of working CPUs', 2);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channels    = [];
        $maxChannels = $input->getArgument('CPUs');

        $exampleArray = $this->getExampleArray();
        $output->writeln('<fg=green>Start example process</>');
        while (count($exampleArray) > 0 || count($channels) > 0) {
            foreach ($channels as $key => $channel) {
                if ($channel instanceof Process && $channel->isTerminated()) {
                    unset($channels[$key]);
                }
            }
            if (count($channels) >= $maxChannels) {
                continue;
            }

            if (!$item = array_pop($exampleArray)) {
                continue;
            }
            $process = new Process(sprintf('php index.php example:sub-process %s', $item), __DIR__ . '/../../../');
            $process->start();
            if (!$process->isStarted()) {
                throw new \Exception($process->getErrorOutput());
            }
            $channels[] = $process;
        }
        $output->writeln('<bg=green;fg=black>Done.</>');
    }

    /**
     * @return array
     */
    private function getExampleArray()
    {
        $array = [];
        for ($i = 0; $i < 30; $i++) {
            $name = 'No' . $i;
            $x1   = rand(1, 10);
            $y1   = rand(1, 10);
            $x2   = rand(1, 10);
            $y2   = rand(1, 10);

            $array[] = $name . '.' . $x1 . '.' . $y1 . '.' . $x2 . '.' . $y2;
        }

        return $array;
    }
}
