<?php
/**
 * Created by PhpStorm.
 * Kerisy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      (c) 2015 putao.com, Inc.
 * @package         kerisy/framework
 * @version         3.0.0
 */

namespace Kerisy\Foundation\Command\Job;

use Kerisy\Console\Input\InputInterface;
use Kerisy\Console\Input\InputOption;
use Kerisy\Console\Output\OutputInterface;
use Kerisy\Foundation\Command\Base;

class Start extends Base
{
    protected function configure()
    {
        $this->setName('job:start')
            ->setDescription('start the job server ');
        $this->addOption('--daemonize', '-d', InputOption::VALUE_NONE, 'Is daemonize ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        JobBase::operate("start", $output, $input);
    }
}
