<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.secretmodal
 *
 * @copyright   (C) 2022 Brian Teeman. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;

class plgSystemSecretModal extends CMSPlugin {

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
		$modules = ModuleHelper::getModules('secretmodal');
	
		if ($this->app->isClient('site') && $modules)
		{
			$selector	=	$this->params->get('selector', 'a[href*="login"], a[href*="logout"]');
	
	$script = <<<SCRIPT
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('secretModal');

    if (!modalElement) {
        return;
    }

    const modal = new bootstrap.Modal(modalElement);

    document.querySelectorAll('$selector').forEach((logo) => {
        logo.addEventListener('contextmenu', function (event) {
            event.preventDefault();
            modal.show();
        });
    });
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
		$modules = ModuleHelper::getModules('secretmodal');
		
		if ($modules) { ?>
			<div
				class="modal fade"
				id="logo"
				tabindex="-1"
				aria-labelledby="secretModalLabel"
				aria-hidden="true"
			>
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="secretModalLabel">
								<?php echo Text::_('SECRETMODAL'); ?>
							</h5>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="<?php echo Text::_('JCLOSE'); ?>"
							></button>
						</div>
						<div class="modal-body">
							<?php foreach ($modules as $module) : ?>
							<?php echo ModuleHelper::renderModule($module); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
