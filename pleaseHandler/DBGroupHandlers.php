<?php
namespace pleaseHandler;


use Console;
use pleaseHandler\DBGroupHandler\exportDbHandler;

class DBGroupHandlers extends HandlerGroup{

    /**
     * AdminGroupHandler constructor.
     */
    protected $handlers = array();
    protected $docs = array();


    public function __construct($argv)
    {

        parent::__construct('db',$argv,'',array(
            'export'
        ));

        $this->Handler();
    }


    /*
     * Handler Event
     */
    protected function Handler()
    {

        if(!$this->match) return;


        if(isset($this->argv[2])){

            $this->initHandlers();
            $this->PrintDocs();
            die();
        }else{
            $this->error("Please enter command child");
            $this->PrintDocs();
            die();
        }

    }

    /*
    * Required Handler Childs  file
    */
    protected function RequireLibs()
    {
        $this->RegisterHandler(__DIR__.'/DBGroupHandlers/exportDbHandler');
    }

    /*
     * Call Your handlers
     * here
     */
    protected function LoadHandlers()
    {
        $this->handlers = array(
            exportDbHandler::class,
        );
    }


    /*
     * get The Documentation
     */
    public static function  getDoc()
    {
        return array(
            'trigger' => 'db',
            'demo' => "owp db help",
            'doc' => "Group of db package",
        );
    }







}