<?php
    namespace pleaseHandler;


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
                echo "$this->type $this->fileName is already exist!";
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

                    $updated = $this->UpdateRegistery();

                    // Create Extra Files
                    $this->CreateExtraFiles();
                    if($updated && $saved){
                        echo "$this->type $this->fileName has been created !";
                        die();
                    }
                }
            }
        }

        // Update the Registary
        private function UpdateRegistery()
        {
            $Registray_file = \OWPactConfig::getOWPDir().'/RegisteryOwpact.php';

            $content = file_get_contents($Registray_file );

            if($content){
                // Replate With Call Module
                $str_entry = 'function '.$this->RegistrayEntry.'(){';
                $newSaved = $str_entry."\n \t \t \t".'$this->RegisterModule("'.$this->fileName.'");';
                $content = str_replace($str_entry ,$newSaved,$content );

                return file_put_contents($Registray_file,$content);
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

        public static function CreateDirectory($dir_name)
        {
            $dir_name = \OWPactConfig::getOWPDir().'/'.$dir_name;
            if(!is_dir($dir_name)){
                mkdir($dir_name,0777,true);
            }
        }


    }