<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Alert Twig Extensions
 *
 * @package   Kohana/Alert
 * @category  Twig/Extensions
 * @author    Marcel Beck <marcel.beck@mbeck.org>
 * @copyright (c) 2012 Marcel Beck
 */
class Kohana_Twig_Extensions_Alert extends Twig_Extension
{
    /**
     * Returns the added functions
     *
     * @author Marcel Beck <marcel.beck@mbeck.org>
     * @uses   Twig_Function_Function
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'alerts' => new Twig_Function_Function('Alert::get_once'),
        );
    }

    /**
     * @author Marcel Beck <marcel.beck@mbeck.org>
     * @return string
     */
    public function getName()
    {
        return 'kohana_twig_alert';
    }
}

