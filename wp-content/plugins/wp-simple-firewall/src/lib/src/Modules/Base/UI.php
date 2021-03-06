<?php

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\Base;

use FernleafSystems\Wordpress\Plugin\Shield;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\ModConsumer;
use FernleafSystems\Wordpress\Services\Services;

class UI {

	use ModConsumer;

	public function buildOptionsForStandardUI() :array {
		return ( new Options\BuildForDisplay() )
			->setMod( $this->getMod() )
			->setIsWhitelabelled( $this->getCon()->getModule_SecAdmin()->getWhiteLabelController()->isEnabled() )
			->standard();
	}

	public function buildSelectData_ModuleSettings() :array {
		return $this->getMod()->getModulesSummaryData();
	}

	public function buildSelectData_OptionsSearch() :array {
		$modsToSearch = array_filter(
			$this->getMod()->getModulesSummaryData(),
			function ( $modSummary ) {
				return !empty( $modSummary[ 'show_mod_opts' ] );
			}
		);
		$searchSelect = [];
		foreach ( $modsToSearch as $slug => $summary ) {
			$searchSelect[ $summary[ 'name' ] ] = $summary[ 'options' ];
		}
		return $searchSelect;
	}

	public function getBaseDisplayData() :array {
		$mod = $this->getMod();
		$con = $this->getCon();
		$urlBuilder = $con->urls;

		/** @var Shield\Modules\Plugin\Options $pluginOptions */
		$pluginOptions = $con->getModule_Plugin()->getOptions();

		return [
			'sPluginName'   => $con->getHumanName(),
			'sTagline'      => $this->getOptions()->getFeatureTagline(),
			'nonce_field'   => wp_nonce_field( $con->getPluginPrefix(), '_wpnonce', true, false ), //don't echo!
			'form_action'   => 'admin.php?page='.$mod->getModSlug(),
			'aPluginLabels' => $con->getLabels(),
			'help_video'    => [
				'auto_show'   => $this->getIfAutoShowHelpVideo(),
				'display_id'  => 'ShieldHelpVideo'.$mod->getSlug(),
				'options'     => $this->getHelpVideoOptions(),
				'displayable' => $this->isHelpVideoDisplayable(),
				'show'        => $this->isHelpVideoDisplayable() && !$this->getHelpVideoHasBeenClosed(),
				'width'       => 772,
				'height'      => 454,
			],

			'aSummaryData' => array_filter(
				$mod->getModulesSummaryData(),
				function ( $summary ) {
					return $summary[ 'show_mod_opts' ];
				}
			),

			'sPageTitle' => $mod->getMainFeatureName(),
			'data'       => [
				'mod_slug'       => $mod->getModSlug( true ),
				'mod_slug_short' => $mod->getModSlug( false ),
				'all_options'    => $this->buildOptionsForStandardUI(),
				'xferable_opts'  => ( new Shield\Modules\Plugin\Lib\ImportExport\Options\BuildTransferableOptions() )
					->setMod( $mod )
					->build(),
				'hidden_options' => $this->getOptions()->getHiddenOptions()
			],
			'vars'       => [
				'mod_slug' => $mod->getModSlug( true ),
			],
			'ajax'       => [
				'mod_options'          => $mod->getAjaxActionData( 'mod_options', true ),
				'mod_opts_form_render' => $mod->getAjaxActionData( 'mod_opts_form_render', true ),
			],
			'vendors'    => [
				'widget_freshdesk' => '3000000081' /* TODO: plugin spec config */
			],
			'strings'    => $mod->getStrings()->getDisplayStrings(),
			'flags'      => [
				'access_restricted'     => !$mod->canDisplayOptionsForm(),
				'show_ads'              => $mod->getIsShowMarketing(),
				'wrap_page_content'     => true,
				'show_standard_options' => true,
				'show_content_help'     => true,
				'show_alt_content'      => false,
				'has_wizard'            => $mod->hasWizard(),
				'is_premium'            => $con->isPremiumActive(),
				'show_transfer_switch'  => $con->isPremiumActive(),
				'is_wpcli'              => $pluginOptions->isEnabledWpcli(),
			],
			'hrefs'      => [
				'go_pro'         => 'https://shsec.io/shieldgoprofeature',
				'goprofooter'    => 'https://shsec.io/goprofooter',
				'wizard_link'    => $mod->getUrl_WizardLanding(),
				'wizard_landing' => $mod->getUrl_WizardLanding(),

				'form_action'      => Services::Request()->getUri(),
				'css_bootstrap'    => $urlBuilder->forCss( 'bootstrap' ),
				'css_pages'        => $urlBuilder->forCss( 'pages' ),
				'css_steps'        => $urlBuilder->forCss( 'jquery.steps' ),
				'css_fancybox'     => $urlBuilder->forCss( 'jquery.fancybox.min' ),
				'css_globalplugin' => $urlBuilder->forCss( 'global-plugin' ),
				'css_wizard'       => $urlBuilder->forCss( 'wizard' ),
				'js_jquery'        => Services::Includes()->getUrl_Jquery(),
				'js_bootstrap'     => $urlBuilder->forJs( 'bootstrap' ),
				'js_fancybox'      => $urlBuilder->forJs( 'jquery.fancybox.min' ),
				'js_globalplugin'  => $urlBuilder->forJs( 'global-plugin' ),
				'js_steps'         => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js',
			],
			'imgs'       => [
				'svgs'           => [
					'ignore'   => $con->svgs->raw( 'bootstrap/eye-slash-fill.svg' ),
					'triangle' => $con->svgs->raw( 'bootstrap/triangle-fill.svg' ),
				],
				'favicon'        => $urlBuilder->forImage( 'pluginlogo_24x24.png' ),
				'plugin_banner'  => $urlBuilder->forImage( 'banner-1500x500-transparent.png' ),
				'background_svg' => $urlBuilder->forImage( 'shield/background-blob.svg' )
			],
			'content'    => [
				'options_form'   => '',
				'alt'            => '',
				'actions'        => '',
				'help'           => '',
				'wizard_landing' => ''
			]
		];
	}

	public function getInsightsOverviewCards() :array {
		/** @var Insights\OverviewCards $oc */
		$oc = $this->loadInsightsHelperClass( 'OverviewCards' );
		return $oc->build();
	}

	protected function getModDisabledCard() :array {
		$mod = $this->getMod();
		return [
			'name'    => __( 'Module Disabled', 'wp-simple-firewall' ),
			'summary' => __( 'All features of this module are completely disabled', 'wp-simple-firewall' ),
			'state'   => -1,
			'href'    => $mod->getUrl_DirectLinkToOption( $mod->getEnableModOptKey() ),
		];
	}

	protected function getModDisabledInsight() :array {
		$mod = $this->getMod();
		return [
			'name'    => __( 'Module Disabled', 'wp-simple-firewall' ),
			'enabled' => false,
			'summary' => __( 'All features of this module are completely disabled', 'wp-simple-firewall' ),
			'weight'  => 2,
			'href'    => $mod->getUrl_DirectLinkToOption( $mod->getEnableModOptKey() ),
		];
	}

	protected function getHelpVideoOptions() :array {
		$aOptions = $this->getOptions()->getOpt( 'help_video_options', [] );
		if ( is_null( $aOptions ) || !is_array( $aOptions ) ) {
			$aOptions = [
				'closed'    => false,
				'displayed' => false,
				'played'    => false,
			];
			$this->getOptions()->setOpt( 'help_video_options', $aOptions );
		}
		return $aOptions;
	}

	protected function getHelpVideoUrl( string $id ) :string {
		return sprintf( 'https://player.vimeo.com/video/%s', $id );
	}

	protected function getIfAutoShowHelpVideo() :bool {
		return !$this->getHelpVideoHasBeenClosed();
	}

	protected function getHelpVideoHasBeenDisplayed() :bool {
		return (bool)$this->getHelpVideoOption( 'displayed' );
	}

	protected function getVideoHasBeenPlayed() :bool {
		return (bool)$this->getHelpVideoOption( 'played' );
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	protected function getHelpVideoOption( $key ) {
		$opts = $this->getHelpVideoOptions();
		return $opts[ $key ] ?? null;
	}

	protected function getHelpVideoHasBeenClosed() :bool {
		return (bool)$this->getHelpVideoOption( 'closed' );
	}

	/**
	 * @return bool
	 */
	protected function isHelpVideoDisplayable() {
		return false;
	}

	public function getSectionNotices( string $section ) :array {
		return [];
	}

	public function getSectionWarnings( string $section ) :array {
		return [];
	}

	/**
	 * @return bool
	 * @deprecated 10.0
	 */
	public function isEnabledForUiSummary() :bool {
		return $this->getMod()->isModuleEnabled();
	}

	protected function loadInsightsHelperClass( string $classToLoad ) {
		try {
			$NS = ( new \ReflectionClass( $this ) )->getNamespaceName();
		}
		catch ( \Exception $e ) {
			$NS = __NAMESPACE__;
		}

		$fullClass = rtrim( $NS, '\\' ).'\\Insights\\'.$classToLoad;
		if ( !@class_exists( $fullClass ) ) {
			$fullClass = __NAMESPACE__.'\\Insights\\'.$classToLoad;
		}

		/** @var ModConsumer $class */
		$class = new $fullClass();
		$class->setMod( $this->getMod() );
		return $class;
	}
}