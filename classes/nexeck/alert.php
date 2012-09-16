<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Alert module for Kohana
 *
 * @package   Nexeck/Alert
 * @author    Marcel Beck <marcel.beck@outlook.com>
 * @copyright (c) 2012 Marcel Beck
 */
abstract class Nexeck_Alert
{
    /**
     * Alert type alert
     */
    const ALERT = 'alert';

    /**
     * Alert type error
     */
    const ERROR = 'error';

    /**
     * Alert type success
     */
    const SUCCESS = 'success';

    /**
     * Alert type info
     */
    const INFO = 'info';

    /**
     * @var string Session Key
     */
    protected static $_session_key = 'alert';

    /**
     * Don't allow creating new instances
     */
    final private function __construct()
    {
    }

    /**
     * Don't allow cloning
     */
    final private function __clone()
    {
    }

    /**
     * Set a new alert.
     *
     * <code>
     * // Embed values with sprintf
     * Alert::set(Alert::INFO, 'Text: %s', array('Embed values with sprintf in text'), 'Subejct: %s');
     *
     * // Embed values with strtr
     * Alert::set(Alert::INFO, 'Text: :string', array(':string' => 'Embed values with strtr'), 'Subject: :string');
     *
     * // Render normal
     * Alert::set(Alert::INFO, 'INFO Text');
     *
     * // Render normal with subject
     * Alert::set(Alert::INFO, 'INFO Text', array(), 'INFO Subject');
     *
     * // Render as block
     * Alert::set(Alert::INFO, 'INFO Text', array(), null, true);
     *
     * // Render as block with subject
     * Alert::set(Alert::INFO, 'INFO Text Block', array(), 'INFO Subject Block', true);
     * </code>
     *
     * @param string $type    alert type (e.g. Alert::SUCCESS)
     * @param mixed  $text    alert text
     * @param array  $values  values to replace with sprintf or strtr
     * @param null   $subject subject or heading
     * @param bool   $block   render as block alert
     *
     * @uses Arr::is_assoc
     * @uses Session::instance
     * @return void
     */
    public static function set($type, $text, array $values = array(), $subject = null, $block = false)
    {
        if (empty($values) === false) {
            if (Arr::is_assoc($values)) {
                // Insert the values into the alert
                $text    = strtr($text, $values);
                $subject = strtr($subject, $values);
            } else {
                $tmp_values = $values;

                // The target string goes first
                array_unshift($values, $text);
                // Insert the values into the alert
                $text = call_user_func_array('sprintf', $values);

                $values = $tmp_values;
                // The target string goes first
                array_unshift($values, $subject);
                // Insert the values into the alert
                $subject = call_user_func_array('sprintf', $values);
            }
        }

        // Load existing alerts
        $alerts = Alert::get();

        // Append a new alert
        $alerts[] = array(
            'type'      => $type,
            'text'      => $text,
            'subject'   => $subject,
            'block'     => $block,
        );

        // Store the updated alerts
        Session::instance()->set(Alert::$_session_key, $alerts);
    }

    /**
     * Get alerts.
     *
     * <code>
     * // Get all alerts
     * $messages = Alert::get();
     *
     * // Get error alerts
     * $error_messages = Alert::get(Alert::ERROR);
     *
     * // Get error and alert alerts
     * $messages = Alert::get(array(Alert::ERROR, Alert::ALERT));
     * </code>
     *
     * @param   mixed $type   alert type (e.g. Alert::SUCCESS, array(Alert::ERROR, Alert::ALERT))
     * @param   bool  $delete delete the alerts?
     *
     * @return array
     *
     * @uses Session::instance
     */
    public static function get($type = null, $delete = false)
    {
        $alerts = Session::instance()->get(Alert::$_session_key);

        if ($alerts === null) {
            return array();
        }

        if ($type !== null) {
            // Will hold the filtered set of alerts to return
            $return = array();

            // Store the remainder in case `delete` or `get_once` is called
            $remainder = array();

            foreach ($alerts as $alert) {
                if (($alert['type'] === $type) or (is_array($type) and in_array($alert['type'], $type))) {
                    $return[] = $alert;
                } else {
                    $remainder[] = $alert;
                }
            }

            if (empty($return)) {
                // No alerts of '$type' to return
                return array();
            }

            $alerts = $return;
        }

        if ($delete === true) {
            if (empty($remainder) or ($type === null)) {
                // Nothing to save, delete the key from memory
                Alert::delete();
            } else {
                // Override alerts with the remainder to simulate a deletion
                Session::instance()->set(Alert::$_session_key, $remainder);
            }
        }

        return $alerts;
    }

    /**
     * Get alerts once.
     *
     * <code>
     * Get all alerts and delete them
     * $messages = Alert::get_once();
     *
     * // Get error alerts and delete them
     * $error_messages = Alert::get_once(Alert::ERROR);
     *
     * // Get error and alert alerts and delete them
     * $error_messages = Alert::get_once(array(Alert::ERROR, Alert::ALERT));
     * </code>
     *
     * @param   mixed $type alert type (e.g. Alert::SUCCESS, array(Alert::ERROR, Alert::ALERT))
     *
     * @return  array
     */
    public static function get_once($type = null)
    {
        return Alert::get($type, true);
    }

    /**
     * Delete alerts.
     *
     * <code>
     * // Delete all alerts
     * Alert::delete();
     *
     * // Delete error alerts
     * Alert::delete(Alert::ERROR);
     *
     * // Delete error and alert alerts
     * Alert::delete(array(Alert::ERROR, Alert::ALERT));
     * </code>
     *
     * @param  mixed $type alert type (e.g. Alert::SUCCESS, array(Alert::ERROR, Alert::ALERT))
     */
    public static function delete($type = null)
    {
        if ($type === null) {
            Session::instance()->delete(Alert::$_session_key);
        } else {
            // Deletion by type happens in get()
            Alert::get($type, null, true);
        }
    }
}

