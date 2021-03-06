<?php declare( strict_types=1 );

namespace FernleafSystems\Wordpress\Plugin\Shield\Scans\Mal;

use FernleafSystems\Wordpress\Plugin\Shield\Scans\Base\BaseScanActionVO;

/**
 * Class ScanActionVO
 * @package FernleafSystems\Wordpress\Plugin\Shield\Scans\Mal
 * @property string[] $file_exts
 * @property string[] $scan_root_dirs
 * @property string[] $paths_whitelisted
 * @property string[] $patterns_fullregex
 * @property string[] $patterns_regex
 * @property string[] $patterns_simple
 * @property int      $confidence_threshold
 */
class ScanActionVO extends BaseScanActionVO {

	const QUEUE_GROUP_SIZE_LIMIT = 50;
	const DEFAULT_SLEEP_SECONDS = 0.1;
}