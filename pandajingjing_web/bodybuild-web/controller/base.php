<?php

/**
 * Controller_Base
 * @author jxu
 * @package bodybuild-web_controller
 */
/**
 * Controller_Base
 *
 * @author jxu
 */
abstract class Controller_Base extends Controller_Sys_Web
{

    function beforeRequest()
    {
        parent::beforeRequest();
        // do something
        $this->setPageData('aTopUrls', array(
            'sHomeURL' => $this->getURL('Home'),
            'sDietURL' => $this->getURL('Diet'),
            'sMotionURL' => $this->getURL('Motion'),
            'sAboutTreadmillURL' => $this->getURL('AboutTreadmill'),
            'sAboutDumbbellURL' => $this->getURL('AboutDumbbell'),
            'sAboutBMIURL' => $this->getURL('AboutBMI')
        ));
    }
}