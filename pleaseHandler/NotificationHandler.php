<?php
    namespace pleaseHandler;

    use Handleable;

    class NotificationHandler extends HandlerPlease{

        use Handleable;

        public function __construct($argv)
        {
            parent::__construct('notification',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){

                // Create directory
                $this->createFilterDirectory();
                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[3],'Notifications');

                // Register method
                $this->RegisterFilters();

                $this->Execute($parse[1],$parse[0]);

            }else{
                $this->error("Please notification name");
                die();
            }
        }


        private function Execute($fnc_name,$baseDir)
        {

            $replacements = array(
                '__NAME__'=>$fnc_name
            );


            $file_dist = $this->getFullName($baseDir,$fnc_name,'Notifications');

            $create = new CreteElement($file_dist,$replacements,'Notification',__DIR__.'/../ressources/src/Notification.php','RegisterNotifications');
            $create->CreateItem();
            die();
        }

        /*
         * Register Function
         * RegisterFilters
         *
         */
        private function RegisterFilters(){
            CreteElement::CreateMethodIntoRegistry('RegisterNotifications');
        }

        /*
         * get The Documentation
         */
        public static function getDoc()
        {
            return array(
                'trigger' => 'notification',
                'demo' => "php owp make notification Your notification name",
                'doc' => "Custom notification",
            );
        }

        private function createFilterDirectory()
        {
            CreteElement::CreateDirectory('Notifications');
        }
    }

