<?php
/**
 * Crossfire CMS
 *
 * @package   Crossfire CMS
 * @author    Martin Langenberg
 * @copyright Copyright (c) 2011 - 2015, Martin Langenberg
 * @since     Version 2.0
 * @filesource
 */

/**
 *
 * This class replaces the CodeIgniter form_helper method form_open().
 * It adds the use of a invisible honey pot field if required by the config.
 * The hidden and invisible fields are wrapped in a invisible container.
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Form Helpers
 *
 * @package CodeIgniter
 * @subpackage Helpers
 * @category Helpers
 * @author EllisLab Dev Team
 * @link http://codeigniter.com/user_guide/helpers/form_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('form_open'))
{
    /**
     * Form Declaration
     *
     * Creates the opening portion of the form.
     *
     * @param string the URI segments of the form destination
     * @param array a key/value pair of attributes
     * @param array a key/value pair hidden data
     * @return string
     */
    function form_open($action = '', $attributes = array(), $hidden = array())
    {
        $CI =& get_instance();

        // If no action is provided then set to the current url
        if ( ! $action)
        {
            $action = $CI->config->site_url($CI->uri->uri_string());
        }
        // If an action is not a full URL then turn it into one
        elseif (strpos($action, '://') === FALSE)
        {
            $action = $CI->config->site_url($action);
        }

        $attributes = _attributes_to_string($attributes);

        if (stripos($attributes, 'method=') === FALSE)
        {
            $attributes .= ' method="post"';
        }

        if (stripos($attributes, 'accept-charset=') === FALSE)
        {
            $attributes .= ' accept-charset="'.strtolower(config_item('charset')).'"';
        }

        $form = '<form action="'.$action.'"'.$attributes.">\n";

        // Add CSRF field if enabled, but leave it out for GET requests and requests to external websites
        if ($CI->config->item('csrf_protection') === TRUE && strpos($action, $CI->config->base_url()) !== FALSE && ! stripos($form, 'method="get"'))
        {
            $hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
        }
 
        // Add honey pot field if enabled, but leave it out for GET requests and requests to external websites
        if ($CI->config->item('spam_protection') === TRUE && strpos($action, $CI->config->base_url()) !== FALSE && ! stripos($form, 'method="get"'))
        {
            $hidden[$CI->security->get_honey_pot_token_name()] = NULL;
        }

        if (is_array($hidden))
        {
            foreach ($hidden as $name => $value)
            {
                $form .= '<input type="hidden" name="'.$name.'" value="'.html_escape($value).'" style="display:none;" />'."\n";
            }
        }

        return $form;
    }
} 

// ------------------------------------------------------------------------

if ( ! function_exists('form_close'))
{
	/**
	 * Form Close Tag
	 *
	 * @param	string
	 * @return	string
	 */
	function form_close($extra = '')
	{
		return '</form>'.$extra;
	}
}