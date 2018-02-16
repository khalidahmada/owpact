<?php
    namespace pleaseHandler;

    use Handleable;
    use OWPactConfig;

    class TplHandler extends HandlerPlease
    {

        use Handleable;

        protected $FnName='InitializeLayoutStrates';
        protected $PathDist='Layouts/Strates';
        protected $baseLayout='BaseStrateLayout.php';

        protected $tpl_source=false;

        public function __construct($argv)
        {

            parent::__construct('tpl', $argv, 'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if (!$this->match) return;

            $this->tpl_source=OWPactConfig::getTplConfig();

            if (!$this->tpl_source) {
                $this->error("There is no file please check your config/config.json and param tpl_config");
                die();
            }

            // if is Set
            if (isset($this->argv[3]) && isset($this->argv[4])) {

                $cmd=$this->argv[3];

                if ($tpl_object=$this->CmdExist($cmd)) {
                    if ($this->ObjectTplIstValid($tpl_object)) {

                        $file_src=OWPactConfig::getPathFromOwp($tpl_object->src);

                        if (is_file($file_src)) {
                            $this->HandleTplWithObject($tpl_object, $file_src);
                        } else {
                            $this->error("Can't find file $file_src");
                            die();
                        }

                    } else {
                        $this->error("Please your entry $cmd need parms  src,dist,fn");
                        die();
                    }
                } else {
                    $keys=$this->getKeysOfConfig();
                    $this->error("Please check your templates key or add new your available is $keys");
                    die();
                }
            } else {
                $this->error("Please enter template name and params");
                die();
            }


            die();

        }


        /*
         * Cmd Exist
         */
        protected function CmdExist($cmd)
        {

            $tpl=$this->tpl_source;
            if ($tpl) {
                if (isset($tpl->alias) && $tpl->alias) {
                    foreach ($tpl->alias as $tpl_item) {
                        if (isset($tpl_item->key) && $tpl_item->key == $cmd) {
                            return $tpl_item;
                        }
                    }
                }
            }

            return false;
        }

        protected function getKeysOfConfig()
        {

            $tpl=$this->tpl_source;
            $keys=false;

            if ($tpl) {
                if (isset($tpl->alias) && $tpl->alias) {
                    foreach ($tpl->alias as $tpl_item) {
                        $keys[]=$tpl_item->key;
                    }
                }
            }

            return join(',', $keys);

        }


        /**
         * Object Tpl valid if object is has
         * props
         * @param $tpl_object
         * @return bool
         */
        private function ObjectTplIstValid($tpl_object)
        {
            if (isset($tpl_object->src) && isset($tpl_object->dist)) {
                return true;
            } else {
                return false;
            }
        }


        private function HandleTplWithObject($tpl_object, $file_src)
        {
            CreteElement::CreateDirectory($tpl_object->dist);
            $parse=$this->getFileAndDirNameAndPrepareDirectory($this->argv[4], $tpl_object->dist);

            // if fn is created
            if(isset($tpl_object->fn) && !empty($tpl_object->fn) && $tpl_object->fn){
                CreteElement::CreateMethodIntoRegistry($tpl_object->fn);
            }


            $this->Execute($this->argv, $parse[1], $parse[0], $tpl_object, $file_src);
        }


        private function Execute($argvs, $fnc_name, $baseDir, $obj, $file_src)
        {

            $replacements=array(
                '__NAME__'=>$fnc_name
            );

            if ($this->argv) {

                $new_array=array_slice($this->argv, 5);
                $curs=1;

                if ($new_array) {
                    foreach ($new_array as $arg) {
                        $replacements["__arg_$curs"]=$arg;
                        $curs++;
                    }
                }

            }


            $file_dist=$this->getFullName($baseDir, $fnc_name, $obj->dist);

            $create=new CreteElement($file_dist, $replacements, $obj->key, $file_src, false);
            $create->CreateItem();
            die();
        }

        /*
         * get The Documentation
         */
        public static function getDoc()
        {
            return array(
                'trigger'=>'tpl',
                'demo'   =>"php owp tpl ",
                'doc'    =>"",
            );
        }


    }

