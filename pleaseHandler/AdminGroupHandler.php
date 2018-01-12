<?php
    namespace pleaseHandler;


    use Console;
    use pleaseHandler\AdminChildHandlers\testHandler;

    class AdminGroupHandler extends HandlerPlease{

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
        private function Handler()
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
        private function RequireLibs()
        {
            $this->RegisterHandler('AdminChildHandlers/testHandler');
        }

        /*
         * Init Handlers child
         */
        private function initHandlers()
        {
            $this->RequireLibs();


            $this->handlers = array(
                testHandler::class,
            );

            $this->createDocs();

            // Apply handlers
            $this->ApplyHandlers();
        }

        /*
         * Create Doc
         */
        private function  createDocs(){
            if($this->handlers){
                foreach($this->handlers as $handler){
                    $this->docs[]=($handler)::getDoc();
                }
            }
        }

        /*
         * Print Document
         */
        private function PrintDocs()
        {
            echo "\n";
            foreach ($this->docs as $doc) {
                echo "\n";
                Console::log($doc['trigger'], 'cyan', false);
                Console::log(" ||  ", 'bold', false);
                Console::log($doc['demo'] . ' : ', 'green', false);
                Console::log($doc['doc'], 'white', false, 'brown');
                echo "\n";
            }
            echo "\n";
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


        /*
         * Apply Handlers
         */
        private function ApplyHandlers()
        {
            if($this->handlers){
                foreach($this->handlers as $handler){
                    new $handler($this->argv);
                }
            }
        }




        private function RegisterHandler($handler)
        {
            require_once $handler.'.php';
        }




    }