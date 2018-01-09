<?php
    namespace pleaseHandler;
    use Console;
    use OWPactConfig;
    use pleaseHandler\CreteElement;

    class PublishHandler
    {

        /**
         * PublishHandler constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        public function boot()
        {

        }


        public function Publish()
        {
            if(!$this->checkDir()){
                $this->PublishFolder();
            }else{
                Console::log("Publish is Already Done go to ".OWPactConfig::getOWPDir(),'red');
            }
        }
        public static function  checkDir()
        {
           return  is_dir( OWPactConfig::getOWPDir());
        }

        /*
         *
         */
        private function PublishFolder()
        {
            // Create Inc Folder
            $inc_folder = OWPactConfig::$project_config->dir_inc;
            $inc_folder = substr($inc_folder, 0, -1);

            CreteElement::CreateDirectoryIntoGlobalPath($inc_folder);

            copy_dir(__DIR__."/../ressources/templates" , OWPactConfig::getOWPDir());

            // Append file to function file
            $function_file = OWPactConfig::getFunctionFilePath();

            $function_content = file_get_contents($function_file);

            if($function_content){

                if(!strpos($function_content,'RegisteryOwpact.php')){
                    $function_content.= $this->GetAppendCoreToFunction();
                    file_put_contents($function_file,$function_content);
                }

            }

            Console::log("OWP is Done!",'green');
        }

        private function GetAppendCoreToFunction()
        {
            $code = "\n".'/* * Call the Owpact core */ '.
                    "\n".'require get_stylesheet_directory() ."/'.
                    OWPactConfig::$project_config->dir_owp.'/RegisteryOwpact.php";'."\n".'/* End call Owpact */';

            return $code;
        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'publish',
                'demo' => "php owp publish",
                'doc' => "Some time you want to create your own module on project with separates files and logic",
            );
        }
    }
