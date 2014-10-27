<?php
namespace JHodges\LoudSurfBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AlgoCommand extends ContainerAwareCommand{
    
    protected function configure(){
        $this
            ->setName('loudsurf:algo:calc')
            ->setDescription('Run algo calculations')
            ->addArgument('username', InputArgument::OPTIONAL, 'User name?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $en=$this->getContainer()->get('jhodges.echonest');
        $userManager = $this->getContainer()->get('fos_user.user_manager');
        $algo=$this->getContainer()->get('jhodges.loudsurf.algorithm');

        if( $username = $input->getArgument('username') ){
            $user=$userManager->findUserByUsername($username);
        }else{
            die("TODO\n");
        }

        $algo->calculateGenreRankings($user);
    }

}