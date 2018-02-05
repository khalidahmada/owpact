<?php
    /*
    * La home Page ACF Relationnel
    */
    /******************************************** La Home page Dispatch to contenu ************
     *
     */
    function acf_override_ACF_LIST_NAME($field)
    {
        $results  = array();
        // reset choices
        $field['choices']=array(''=>'PLACE_HOLDER');

        // your logic to ovverid the Array of results
        //$results =


        if ($results) {
            foreach ($results as $key=> $result) {
                $field['choices'][$key]=$result;
            }
        }

        return $field;

    }

    add_filter('acf/load_field/name=ACF_LIST_NAME', 'acf_override_ACF_LIST_NAME');