<?php

namespace FernleafSystems\Wordpress\Services\Utilities\WpOrg\Plugin;

use FernleafSystems\Wordpress\Services\Utilities\HttpUtil;

class Download {

	use Base;

	/**
	 * @param string $version
	 * @return string|null
	 */
	public function getDownloadUrlForVersion( $version ) {
		$all = ( new Versions() )
			->setWorkingSlug( $this->getWorkingSlug() )
			->allVersionsUrls();
		return empty( $all[ $version ] ) ? null : $all[ $version ];
	}

	/**
	 * @return string|null
	 * @throws \Exception
	 */
	public function latest() {
		$url = ( new Versions() )
			->setWorkingSlug( $this->getWorkingSlug() )
			->latest();
		return empty( $url ) ? null : ( new HttpUtil() )->downloadUrl( $url );
	}

	/**
	 * @param string $version
	 * @return string
	 * @throws \Exception
	 */
	public function version( $version ) {
		$url = $this->getDownloadUrlForVersion( $version );
		return empty( $url ) ? null : ( new HttpUtil() )->downloadUrl( $url );
	}
}