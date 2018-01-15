<?php
    namespace pleaseHandler;

    use Handleable;

    class QueueHandler extends HandlerPlease{

        use Handleable;

        public function __construct($argv)
        {
            parent::__construct('queue',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(
                    isset($this->argv[3]) &&
                    isset($this->argv[4]) &&
                    isset($this->argv[5])
            ){

                if(in_array($this->argv[5],array('async','background','-a','-b'))){
                    // Call Library Core
                    $this->RegisterLibrary();

                    $action_name = $this->argv[4];

                    if($action_name == '@') $action_name = $this->argv[3];

                    $this->Execute($this->argv[3],$action_name,$this->argv[5]);
                }else{
                    $this->error("Queue Type should be async(-a) or background(-b)");
                    die();
                }


            }else{
                $this->error("Please Enter tree params QueueFileName and, action name (should be unique) and type (async or background");
                die();
            }
        }

        private function Execute($QueueName,$action_name,$type)
        {
            $file_name = "";

            if(in_array($type,array('async','-a'))){
                $file_name = 'QueueAsyncRequest';
            }

            if(in_array($type,array('background','-b'))){
                $file_name = 'QueueBackgroundProcess';
            }


            $replacements = array(
                                    "_QUEUE_NAME"=>$QueueName,
                                    '_ACTION_NAME'=>$action_name,
            );

           $create = new CreteElement(
                                        "Queues/$QueueName.php",
                                        $replacements,'queue',
                                        __DIR__.'/../ressources/src/'.$file_name.'.php',
                                        'RegisterQueues'
           );

           $create->CreateItem();

           die();

        }

        private function RegisterLibrary()
        {
            CreteElement::CreateDirectory('Queues');
            $this->AddLibrary('wp-background-processing');
        }

        /*
         * get The Documentation
         */
        public static function getDoc()
        {
            return array(
                'trigger' => 'queue',
                'demo' => "php owp queue YourQueName QueyeAction type(-a or -b)",
                'doc' => "Queue can be used to fire off non-blocking ".
                         "asynchronous requests or background use ".
                         "(use @ if you want use name of queue as name of action ) type(-a for async or -b for background)"
            );
        }
    }