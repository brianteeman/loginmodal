<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.loginmodal
 *
 * @copyright   (C) 2022 Brian Teeman. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\CMSPlugin;

class plgSystemLoginModal extends CMSPlugin {

	/**
	 * Application object.
	 *
	 * @var    CMSApplicationInterface
	 * @since  1.0.0
	 */
	protected $app;

	/**
	 * setup the scripts.
	 */
	function onAfterDispatch() {
		if ($this->app->isClient('site'))
		{
			$selector	=	$this->params->get('selector', 'a[href*="login"], a[href*="logout"]');
			$script	= <<<SCRIPT
			document.addEventListener("DOMContentLoaded", function() {
			var login = document.querySelectorAll('$selector').forEach((login, index) => {login.setAttribute('data-bs-toggle', 'modal');login.setAttribute('data-bs-target', '#loginModal');});
			});
			SCRIPT;
			/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
			$wa = Factory::getDocument()->getWebAssetManager();

			$wa->useScript('bootstrap.modal')
				->addInlineScript($script);
		}
	}
	/**
	 * setup the module/modal
	 */
	function onAfterDisplay() {
		$modules = ModuleHelper::getModules('modal');
		foreach ($modules as $module) {
			?>
			<div
				class="modal fade"
				id="loginModal"
				tabindex="-1"
				aria-labelledby="loginModalLabel"
				aria-hidden="true"
			>
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="loginModalLabel">
								<?php echo ($this->app->getIdentity()->get('guest')) ? Text::_('JLOGIN') : Text::_('JLOGOUT'); ?>
							</h5>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="<?php echo Text::_('JCLOSE'); ?>"
							></button>
						</div>
						<div class="modal-body">
							<?php echo ModuleHelper::renderModule($module); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}



