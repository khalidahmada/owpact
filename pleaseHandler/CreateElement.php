<?php
    namespace pleaseHandler;


    use Console;

    class CreteElement {

       public $replaces = array();
       public $fileName = false;
       public $full_path;
       public $type;
       public $template;
       public $RegistrayEntry;

       public $extra_files = array();

        public function __construct($fileName, array $replaces,$type,$template,$RegistrayEntry,$extra_files = array())
        {
            $this->replaces=$replaces;
            $this->fileName=$fileName;
            $this->full_path = \OWPactConfig::getOWPDir().'/'.$this->fileName;
            $this->type = $type;
            $this->template=$template;
            $this->RegistrayEntry=$RegistrayEntry;
            $this->extra_files = $extra_files;

        }

        public function CreateItem()
        {
            if($this->isExist()){
                Console::log("$this->type $this->fileName is already exist!",'red');
            }else{
                $this->handlerElement();
            }
        }

        public function isExist()
        {
            $full_file = $this->full_path;
            return file_exists($full_file);
        }

        private function handlerElement()
        {
            $content = file_get_contents($this->template);
            if($content){
                if($this->replaces){
                    foreach($this->replaces as $find => $replace){
                        $content = str_replace($find , $replace,$content);
                    }

                    // crete File to Path
                    $saved = file_put_contents($this->full_path,$content);

                    $updated = false;

                    if($this->RegistrayEntry){
                        $updated = $this->UpdateRegistry();
                    }else{
                        $updated = true;
                    }

                    // Create Extra Files
                    $this->CreateExtraFiles();
                    if($updated && $saved){
                        Console::log("$this->type ". basename($this->fileName)." has been created !",'green');
                        die();
                    }
                }
            }
        }

        // Update the Registary
        private function UpdateRegistry()
        {
            $Registry_file = \OWPactConfig::getRegistryPath();

            $content = file_get_contents($Registry_file );

            if($content){

                // Replate With Call Module
                $str_entry = 'function '.$this->RegistrayEntry.'(){';

                $replacementHandler='$this->RegisterModule("'.$this->fileName.'");';

                if(!strpos($content,$replacementHandler)){
                    $newSaved = $str_entry."\n \t \t \t".$replacementHandler;
                    $content = str_replace($str_entry ,$newSaved,$content );
                    return file_put_contents($Registry_file,$content);
                }else{
                    // already exist
                    // but escape to recall fn
                    return true;
                }

            }

            return false;
        }

        private function CreateExtraFiles()
        {
            if($this->extra_files){
                foreach($this->extra_files as $dir_ => $template_name){
                    $file = \OWPactConfig::getOWPDir().$dir_.$template_name.'.php';
                    file_put_contents($file,'');
                }
            }
        }


        public static function AddCallToFunctionIntoFile($filename , $AppendString , $functionName ){

            $file_target = \OWPactConfig::getOWPDir().$filename;

            $content = file_get_contents($file_target );

            if($content){
                if(!strpos($content , $AppendString)){
                    // Replate With Call Module
                    $str_entry = 'function '.$functionName.'(){';
                    $newSaved = $str_entry."\n \t \t \t".$AppendString;
                    $content = str_replace($str_entry ,$newSaved,$content );
                    return file_put_contents($file_target,$content);
                }
            }

        }

        public static function RegisterModuleIntoRegisterBase($file_name){

            $Registry_file = \OWPactConfig::getRegistryPath();

            $content = file_get_contents($Registry_file );
            $AppendString = '$this->RegisterModule(\''.$file_name.'\');';

            if($content){
                if(!strpos($content , $AppendString)){
                    // Replace With Call Module
                    $str_entry = 'function RegisterBases(){';
                    $newSaved = $str_entry."\n \t \t \t".$AppendString;
                    $content = str_replace($str_entry ,$newSaved,$content );
                    return file_put_contents($Registry_file,$content);
                }
            }
        }

        public static function CreateDirectory($dir_name)
        {
            $dir_name = \OWPactConfig::getOWPDir().'/'.$dir_name;
            if(!is_dir($dir_name)){
                mkdir($dir_name,0777,true);
            }
        }

        public static function CreateDirectoryIntoGlobalPath($dir_name)
        {
            $dir_name = __DIR__.'/'.\OWPactConfig::getGlobalPath().$dir_name;

            if(!is_dir($dir_name)){
                mkdir($dir_name,0777,true);
            }
        }


        /*
         * Create and call function into the RegisterExtraHandlers method
         * Registery
         * @param $methodName file method
         */
        public static function CreateMethodIntoRegistry($methodName,$methodNameIntoRegistryFile='RegisterExtraHandlers')
        {
            $Registry_file = \OWPactConfig::getRegistryPath();
            $content = file_get_contents($Registry_file );

            $formMethod = "private function $methodName(){";
            $str_entry = "private function $methodNameIntoRegistryFile(){";



            if($content){
                if(!strpos($content,$formMethod)){

                    if(strpos($content,$str_entry)){
                        $newReplacement = $formMethod."\n\n\t \t}\n\n\t \t".$str_entry."\n\t \t \t".'$this->'.$methodName.'();';
                        $content = str_replace($str_entry,$newReplacement,$content);
                        file_put_contents($Registry_file,$content);
                    }
                }


            }
        }


    }