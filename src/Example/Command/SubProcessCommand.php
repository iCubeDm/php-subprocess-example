<?php

namespace Example\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubProcessCommand extends Command
{
    protected function configure()
    {
        $this->setName('example:sub-process')
            ->setDescription('Run example sub-process command')
            ->addArgument('item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = explode('.', $input->getArgument('item'));
        $pointName = $items[0];
        $x1        = $items[1];
        $y1        = $items[2];
        $x2        = $items[3];
        $y2        = $items[4];

        // Used for mocking heavy execution.
        $sum = 0;
        for ($i = 1; $i <= 30000000; $i++){
            $sum += $i;
        }

        $distance = bcsqrt(pow(($x2 - $x1),2) + pow(($y2 - $y1),2));
        $data = sprintf('Point %s: %s', $pointName, (string)$distance);

        file_put_contents(__DIR__.'/../../../output/Point'.$pointName , print_r($data, 1), FILE_APPEND);
    }
}
