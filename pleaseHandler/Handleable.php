<?php
    use pleaseHandler\CreteElement;

    trait Handleable{

        /*
         * Copy to owp dir from resource path
         */
        protected function CopyDirFromTemplate($path_to_directory,$dist){

            $dir = __DIR__ . "/../ressources/$path_to_directory";

            if(!is_dir($dir)) {
                return copy_dir($dir, OWPactConfig::getOWPDir() . '/' . $dist);
            }

            return false;

        }


        /*
         * Add to Library /owp/libs
         */
        protected function AddLibrary($libraryName,$entry='plug.php'){
            $this->CopyDirFromTemplate("extra/$libraryName","libs/$libraryName");
            $this->RegisterLib($libraryName."/$entry");
        }

        /*
         * Register Library into libs file
         */
        protected function RegisterLib($library_plug_path){

            CreteElement::AddCallToFunctionIntoFile(
                            '/libs/BaseLibs.php',
                            '$this->RegisterModule(\''.$library_plug_path.'\');',
                            'RegisterModules'
            );

        }

        protected function createDir($path){
            if(!is_dir($path)) {
                mkdir($path,0777,true);
            }
        }


        protected function PrepareDirectoryWithFile($file){

            $baseDir = dirname($file);

            if($baseDir && $baseDir!= '.' && $baseDir!= '/'){
                $this->createDir($baseDir);
            }

            $nameFile = basename($file);

            return $nameFile;
        }


        /*
         * Copy file from to
         * OWP current Project
         */
        protected function copyFileToDir($file,$dist){

           $source = __DIR__ . "/../ressources/$file";

           $dist =  OWPactConfig::getOWPDir() . '/'.$dist ;

            if(!file_exists ( $dist )){
                return copy($source,$dist);
            }

            return false;


        }

        /*
         * Page Create Folder
         * Into Dir
         */
        protected  function CreateFolderIntoDir($dir_name)
        {
            $dir =  OWPactConfig::getDirectory().$dir_name;
            $this->createDir($dir);
        }

    }