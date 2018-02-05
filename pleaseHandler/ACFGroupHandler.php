<?php
    namespace pleaseHandler;


    use Console;
    use pleaseHandler\AdminChildHandlers\overrideListHandler;
    use pleaseHandler\AdminChildHandlers\testHandler;

    class ACFGroupHandler extends HandlerGroup{

        /**
         * AdminGroupHandler constructor.
         */
        protected $handlers = array();
        protected $docs = array();


        public function __construct($argv)
        {

            parent::__construct('acf',$argv,'',array(
                'override-list'
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
            $this->RegisterHandler(__DIR__.'/ACFGroupHandlers/overrideListHandler');
        }

        /*
         * Call Your handlers
         * here
         */
        protected function LoadHandlers()
        {
            $this->handlers = array(
                overrideListHandler::class,
            );
        }


        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'acf',
                'demo' => "php acf help",
                'doc' => "Group of commands for package admin",
            );
        }







    }