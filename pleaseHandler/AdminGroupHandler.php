<?php
    namespace pleaseHandler;


    class AdminGroupHandler extends HandlerPlease{

        /**
         * AdminGroupHandler constructor.
         */

        public function __construct($argv)
        {

            parent::__construct('admin',$argv,'',array(
                'help'
            ));

            $this->Handler();
        }


        private function Handler()
        {
            if(!$this->match) return;


            if(isset($this->argv[2])){

                $child_trigger  = $this->argv[2];

                if( $this->isChildTrigger($child_trigger) ){

                    $this->ProcessChildHandler($child_trigger);
                    die();

                }else{

                    $this->error("Please enter command child >>>");
                    die();

                }

            }else{
                $this->error("Please enter command is required ");
                die();
            }

        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'admin',
                'demo' => "php owp make ajax YourAjaxControllerName ajax_action_name ",
                'doc' => "Create Ajax file to handler ajax request the action name will be the action value on Wordpress ajax requests",
            );
        }

        /*
         * Process Handler
         */
        private function ProcessChildHandler($child_trigger)
        {

        }

    }