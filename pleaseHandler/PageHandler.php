<?php
    namespace pleaseHandler;

    use Handleable;
    use OWPactConfig;

    class PageHandler extends HandlerPlease{

        use Handleable;
        public function __construct($argv)
        {
            parent::__construct('page',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){

                // Prepare path page
                $this->Prepare();

                $templateName = $this->argv[3];

                $pageName = _slugify($templateName);

                if(isset($this->argv[4])){
                    $pageName = $this->argv[4];
                }

                /*
                 * Prepare Directory
                 * Create sub dir if is sended
                 */
                $dirPath = OWPactConfig::getPagesDir();
                $this->PrepareDirectoryWithFile($dirPath.'/'.$pageName);


                $this->Execute($templateName,$pageName);


            }else{
                $this->error("Please enter the \"template name\" and the file name (file name is option )");
                die();
            }
        }

        private function Execute($templateName, $fileName)
        {
            $page = OWPactConfig::getPagesDir();
            $page = $page."/$fileName.php";

            $replacements = array(
                                    "_TEMPLATE_NAME" => $templateName,
            );

            $create = new CreteElement($page,$replacements,'page',__DIR__.'/../ressources/src/Page.php',false);
            // change the path
            // the path is out owp
            $create->full_path = $page;
            $create->CreateItem();
            die();

        }

        private function Prepare()
        {
            $page_dist = OWPactConfig::getPagesDir();
           $this->createDir($page_dist);

        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'page',
                'demo' => "php owp page \"Template Name\" pagefilename (option ) if not template name will used as page",
                'doc' => "To create Wordpress page template."
            );
        }
    }