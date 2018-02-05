<?php
    namespace pleaseHandler;

    use Handleable;

    class FiltersHandler extends HandlerPlease{

        use Handleable;

        public function __construct($argv)
        {
            parent::__construct('filter',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){

                // Create directory
                $this->createFilterDirectory();
                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[4],'Filters');

                // Register method
                $this->RegisterFilters();

                $this->Execute($this->argv[3],$parse[1],$parse[0]);

            }else{
                $this->error("Please enter two params filter name and the function callback name");
                die();
            }
        }


        private function Execute($hook_name,$fnc_name,$baseDir)
        {

            $replacements = array(
                "_FILTER_NAME" => $hook_name,
                '__CALLBACK_NAME'=>$fnc_name
            );


            $file_dist = $this->getFullName($baseDir,$fnc_name,'Filters');

            $create = new CreteElement($file_dist,$replacements,'Filters',__DIR__.'/../ressources/src/Filter.php','RegisterFilters');
            $create->CreateItem();
            die();
        }

        /*
         * Register Function
         * RegisterFilters
         *
         */
        private function RegisterFilters(){
            CreteElement::CreateMethodIntoRegistry('RegisterFilters');
        }

        /*
         * get The Documentation
         */
        public static function getDoc()
        {
            return array(
                'trigger' => 'filter',
                'demo' => "php owp make filter name filterCallback name ",
                'doc' => "To create your filter into separate file example a filter for custom logic",
            );
        }

        private function createFilterDirectory()
        {
            CreteElement::CreateDirectory('Filters');
        }
    }

