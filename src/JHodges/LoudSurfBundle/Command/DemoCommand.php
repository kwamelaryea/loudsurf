<?php
namespace JHodges\LoudSurfBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DemoCommand extends ContainerAwareCommand{
    
    protected function configure(){
        $this
            ->setName('loudsurf:demo:user')
            ->setDescription('Create a demo user')
            ->addArgument('username', InputArgument::REQUIRED, 'User name')
            ->addArgument('count', InputArgument::OPTIONAL, 'count')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $username = $input->getArgument('username');
        if(!$count = $input->getArgument('count')){
            $count=rand(1,80);
        }

        $en=$this->getContainer()->get('jhodges.echonest');
        $userManager = $this->getContainer()->get('fos_user.user_manager');
        $algo=$this->getContainer()->get('jhodges.loudsurf.algorithm');

        $user=$userManager->findUserByUsername($username);
        if(!$user){
            $user = $userManager->createUser();
            $user->setUsername($username);
            $user->setEmail($username.'@dev.loudsurf.com');
            $user->setPassword('qwertyuiop');
            $userManager->updateUser($user);
        }

        $results = $en->query('song', 'search', array(
            'combined' => $username,
            'results' => $count,
            'sort' => 'song_hotttnesss-desc',            
            'bucket'=>array('id:7digital-US','audio_summary','tracks')
        ));

        foreach($results->response->songs as $song){
            sleep(8);
            $algo->addFav($user,$song->id,$song->artist_name.' - '.$song->title);
            echo "Added {$song->artist_name} - {$song->title}\n";
        }

    }

}
