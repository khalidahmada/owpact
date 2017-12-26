<?php
    namespace pleaseHandler;


    class RepoHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('repo',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){
                $post_type = false;
                if(isset($this->argv[4])){
                    $post_type = $this->argv[4];
                }
                $this->Execute($this->argv[3],$post_type);
            }else{
                echo "Please enter repo name";
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
    }

