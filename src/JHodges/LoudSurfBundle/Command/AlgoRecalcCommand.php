<?php
namespace JHodges\LoudSurfBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AlgoRecalcCommand extends ContainerAwareCommand{
    
    protected function configure(){
        $this
            ->setName('loudsurf:algo:recalc')
            ->setDescription('Run algo genre ranking calculations')
            ->addArgument('username', InputArgument::OPTIONAL, 'Username')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $userManager = $this->getContainer()->get('fos_user.user_manager');
        $algo=$this->getContainer()->get('jhodges.loudsurf.algorithm');
        $em = $this->getContainer()->get('doctrine')->getManager();

        if( $username = $input->getArgument('username') ){
            $user=$userManager->findUserByUsername($username);
            $algo->calculateGenreRankings($user);
        }else{
            $users=$em->getRepository('JHodgesLoudSurfBundle:User')->findAll();
            foreach($users as $user){
                $algo->calculateGenreRankings($user);
            }
        }

    }

}