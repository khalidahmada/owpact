<?php
namespace pleaseHandler\AdminChildHandlers;


use Console;
use pleaseHandler\CreteElement;
use pleaseHandler\HandlerPlease;

class testHandler extends HandlerPlease{


    public function __construct($argv)
    {

        parent::__construct('test',$argv,'');

        $this->isMatch();

        $this->Handler();
    }

    protected function isMatch()
    {
        return $this->argv[2] == $this->trigger;
    }

    protected function Handler()
    {
        if(!$this->isMatch()) return;

        if(isset($this->argv[2])){
           $this->Execute();
            die();
        }else{
            $this->error("Please Enter your test name");
        }
    }

    /*
     * The logic of Execute on this
     */
    private function Execute()
    {
        Console::log('hiiiiiiiiiii','green');
        die();

    }

    /*
     * get The Documentation
     */
    public static function  getDoc()
    {
        return array(
            'trigger' => 'test',
            'demo' => "php owp admin test",
            'doc' => "For test",
        );
    }






}