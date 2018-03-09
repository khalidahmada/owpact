<?php
    namespace pleaseHandler;


    use Console;

    class AdminGroupHandler extends HandlerGroup{

        /**
         * AdminGroupHandler constructor.
         */
        protected $handlers = array();
        protected $docs = array();


        public function __construct($argv)
        {

            parent::__construct('admin',$argv,'',array(
                'help'
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

        }

        /*
         * Call Your handlers
         * here
         */
        protected function LoadHandlers()
        {
            $this->handlers = array(
                testHandler::class,
            );
        }


        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'admin',
                'demo' => "php admin help",
                'doc' => "Group of commands for package admin",
            );
        }







    }