<?php
namespace pleaseHandler;


class UpdateTraitRepoHandler extends HandlerPlease{


    public function __construct($argv)
    {

        parent::__construct('base-trait-repo',$argv,'update');

        $this->Handler();
    }

    protected function Handler()
    {
        if(!$this->match) return;

        $this->Execute();

        die();
    }

    private function Execute()
    {
        $helperBaseTemplate = __DIR__.'/../../ressources/templates/Repo/TraitRepo.php';
        $helperPath = \OWPactConfig::getOWPDir().'/Repo/TraitRepo.php';

        if (!copy($helperBaseTemplate, $helperPath)) {
            $this->error("Cant Update Repo/TraitRepo.php please check you config");
        }else{
            $this->success("Repo/TraitRepo.php Updated successfully!");
        }


    }

    /*
     * get The Documentation
     */
    public static function  getDoc()
    {
        return array(
            'trigger' => 'base-trait-repo',
            'demo' => "php owp update base-trait-repo",
            'doc' => "Update current repo trait core",
        );
    }






}