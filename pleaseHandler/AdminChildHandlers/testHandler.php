<?php
namespace pleaseHandler\AdminChildHandlers;


use Console;
use pleaseHandler\ChildHandler;
use pleaseHandler\CreteElement;
use pleaseHandler\HandlerPlease;

class testHandler extends ChildHandler{


    public function __construct($argv)
    {

        parent::__construct('test',$argv,'');

        $this->isTrigger();

        $this->Handler();
    }

    /*
     * is Trigger
     */
    protected function isTrigger()
    {
        return $this->argv[2] == $this->trigger;
    }

    /*
     * Handler
     */
    protected function Handler()
    {
        if(!$this->isTrigger()) return;

        if(isset($this->argv[2])){
           $this->Execute();
            die();
        }else{
            $this->error("Please Enter your test name");
        }
    }

    /*
     * The logic to Execute this
     */
    protected function Execute()
    {
        $this->success('hiiiiiiiiiii');
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