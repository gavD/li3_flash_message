<?php
/**
 * li3_flash_message plugin for Lithium: the most rad php framework.
 *
 * @copyright     Copyright 2010, Michael Hüneburg
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_flash_message\extensions\helper;

use \li3_flash_message\extensions\action\FlashMessage as Main;

/**
 * Helper to output flash messages.
 *
 * @see li3_flash_message\extensions\action\FlashMessage
 */
class FlashMessage extends \lithium\template\Helper {

	/**
	 * Outputs a flash message using a template. The message will be cleared afterwards.
	 * With defaults settings it looks for the template 
	 * `app/views/elements/flash_message.html.php`. If it doesn't exist, the  plugin's view 
	 * at `li3_flash_message/views/elements/flash_message.html.php` will be used. Use this 
	 * file as a starting point for your own flash message element. In order to use a 
	 * different template, adjust `$options['type']` and `$options['template']` to your needs.
	 *
	 * @param string [$key] Optional message key. 
	 * @param array [$options] Optional options.
	 *              - type: Template type that will be rendered.
	 *              - template: Name of the template that will be rendered.
	 *              - data: Additional data for the template.
	 *              - options: Additional options that will be passed to the renderer.
	 * @return string Returns the rendered template.
	 */
	public function output($key = 'default', array $options = array()) {
		$defaults = array(
			'type' => 'element',
			'template' => 'flash_message',
			'data' => array(),
			'options' => array()
		);
		$options += $defaults;
		
		$view = $this->_context->view();
		$output = '';
		$type = array($options['type'] => $options['template']);
		$flash = Main::get($key);
		
		if (!empty($flash)) {
			$data = $options['data'] + array('message' => $flash['message']) + $flash['atts'];
			Main::clear($key);
		
			try {
				$output = $view->render($type, $data, $options['options']);
			} catch (\Exception $e) {
				$output = $view->render($type, $data, array('library' => 'li3_flash_message'));
			}
		}
		return $output;
	}

}

?>