<?php

namespace FernleafSystems\Wordpress\Services\Utilities\WpOrg\Plugin;

use FernleafSystems\Wordpress\Services\Utilities\HttpUtil;
use FernleafSystems\Wordpress\Services\Utilities\WpOrg\Base\PluginThemeVersionsBase;

class Versions extends PluginThemeVersionsBase {

	use Base;

	/**
	 * @return Api
	 */
	protected function getApi() {
		return new Api();
	}

	/**
	 * @param string $version
	 * @param bool   $verifyUrl
	 * @return bool
	 */
	public function exists( $version, $verifyUrl = false ) {
		$bExists = in_array( $version, $this->all() );
		if ( $bExists && $verifyUrl ) {
			try {
				( new HttpUtil() )->checkUrl( Repo::GetUrlForPluginVersion( $this->getWorkingSlug(), $version ) );
			}
			catch ( \Exception $oE ) {
				$bExists = false;
			}
		}
		return $bExists;
	}
}