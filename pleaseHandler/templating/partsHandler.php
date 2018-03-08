<?php
    namespace pleaseHandler;

    use Handleable;
    use OWPactConfig;

    class partsHandler extends HandlerPlease{

        use Handleable;
        public function __construct($argv)
        {
            parent::__construct('prt',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){


                $dirPath = OWPactConfig::getTemplatePartsDir();
                $templateName = $this->argv[3];
                $parse =  $this->getFileAndDirNameAndPrepareDirectoryByGivenPath($this->argv[3],$dirPath);

                $dirPath = OWPactConfig::getTemplatePartsDir();
                $this->PrepareDirectoryWithFile($dirPath.'/'.$parse[0].'/'.$parse[1]);

                $this->Execute($templateName,$parse[1],$parse[0]);

            }else{
                $this->error("Please enter the template name to create into your directory template-parts");
                die();
            }
        }

        private function Execute($templateName, $fileName,$dir)
        {

            $replacements = array(
                '__NAME__'=> $templateName
            );

            $page = OWPactConfig::getTemplatePartsDir();
            $page = $page."/$dir/$fileName.php";

            $create = new CreteElement($page,$replacements,'template',__DIR__.'/../../ressources/src/templates/template-parts-item.php',false);
            $create->full_path = $page;
            $create->CreateItem();
            die();

        }


        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'prt',
                'demo' => "php owp prt \"Template parts name\"",
                'doc' => "To create template into directory template-parts of your current theme."
            );
        }
    }