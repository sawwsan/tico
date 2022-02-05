<?php

namespace FernleafSystems\Wordpress\Services\Utilities\WpOrg\Theme;

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
		$exists = in_array( $version, $this->all() );
		if ( $exists && $verifyUrl ) {
			try {
				( new HttpUtil() )->checkUrl( Repo::GetUrlForThemeVersion( $this->getWorkingSlug(), $version ) );
			}
			catch ( \Exception $e ) {
				$exists = false;
			}
		}
		return $exists;
	}
}