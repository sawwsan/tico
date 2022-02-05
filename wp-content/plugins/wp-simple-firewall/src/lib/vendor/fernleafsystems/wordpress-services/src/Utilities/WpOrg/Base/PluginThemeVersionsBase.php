<?php

namespace FernleafSystems\Wordpress\Services\Utilities\WpOrg\Base;

use FernleafSystems\Wordpress\Services\Utilities\WpOrg\Plugin;
use FernleafSystems\Wordpress\Services\Utilities\WpOrg\Theme;

abstract class PluginThemeVersionsBase {

	/**
	 * @return string[]
	 */
	public function all() {
		$aVersions = array_filter( array_keys( $this->allVersionsUrls() ) );
		usort( $aVersions, 'version_compare' );
		return $aVersions;
	}

	/**
	 * @return string[]
	 */
	public function allVersionsUrls() {
		$versions = [];
		$slug = $this->getWorkingSlug();
		if ( !empty( $slug ) ) {
			try {
				$info = $this->getApi()
							  ->setWorkingSlug( $slug )
							  ->getInfo();
				$versions = $info->versions ?? [];
			}
			catch ( \Exception $e ) {
			}
		}
		return is_array( $versions ) ? $versions : [];
	}

	/**
	 * @return Plugin\Api|Theme\Api
	 */
	abstract protected function getApi();

	/**
	 * @return string
	 */
	abstract protected function getWorkingSlug();

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function latest() {
		return $this->getApi()
					->setWorkingSlug( $this->getWorkingSlug() )
					->getInfo()->version;
	}

	/**
	 * @param string $version
	 * @param bool   $verifyUrl
	 * @return bool
	 */
	abstract public function exists( $version, $verifyUrl = false );
}