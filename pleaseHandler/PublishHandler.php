<?php
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
                echo "Publish is Already Done go to ".OWPactConfig::getOWPDir();
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

            echo "OWP is Done";
        }

        private function GetAppendCoreToFunction()
        {
            $code = "\n".'/* * Call the Owpact core */ '.
                    "\n".'require get_template_directory() ."/'.
                    OWPactConfig::$project_config->dir_owp.'/RegisteryOwpact.php";'."\n".'/* End call Owpact */';

            return $code;
        }
    }
