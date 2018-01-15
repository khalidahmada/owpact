<?php
    namespace pleaseHandler;


    class RepoHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('repo',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){
                $post_type = false;
                if(isset($this->argv[4])){
                    $post_type = $this->argv[4];
                }
                $this->Execute($this->argv[3],$post_type);
            }else{
                $this->error("Please enter repo name");
            }
        }

        private function Execute($RepoName,$post_type)
        {
            $replaces = array("__NAME__" => $RepoName);

            if($post_type){
                $replaces['_POST_TYPE'] = $post_type;
            }


            $create = new CreteElement("Repo/$RepoName.php",$replaces,'Repo',__DIR__.'/../ressources/src/Repo.php','RegisterRepo');
            $create->CreateItem();
            die();
        }


        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'repo',
                'demo' => "php owp repo YourRepoName and post_type name (the post_type is option)",
                'doc' => "Repository pattern is very import into wordpress to separate data with templates . Once your create a repo to specific post type you can get only data of this post type"
            );
        }
    }

