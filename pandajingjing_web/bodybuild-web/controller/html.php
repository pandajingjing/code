<?php

/**
 * Controller_Html
 * @author jxu
 * @package bodybuild-web_controller
 */
/**
 * Controller_Html
 *
 * @author jxu
 */
class Controller_Html extends Controller_Base
{

    function doRequest()
    {
        switch ($this->getParam('sKey', 'router')) {
            case 'diet':
                return 'Diet';
                break;
            case 'motion':
                return 'Motion';
                break;
            case 'about/treadmill':
                return 'About_Treadmill';
                break;
            case 'about/dumbbell':
                return 'About_Dumbbell';
                break;
            case 'about/bmi':
                return 'About_Bmi';
                break;
        }
    }
}