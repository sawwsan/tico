<?php

namespace FernleafSystems\Wordpress\Services\Utilities;

use FernleafSystems\Wordpress\Services\Services;
use FernleafSystems\Wordpress\Services\Utilities;
use FernleafSystems\Wordpress\Services\Utilities\Integrations\Ipify;
use FernleafSystems\Wordpress\Services\Utilities\Net\FindSourceFromIp;

/**
 * Class IpUtils
 * @package FernleafSystems\Wordpress\Services\Utilities
 */
class IpUtils {

	/**
	 * @var Utilities\Net\VisitorIpDetection
	 */
	private $oIpDetector;

	/**
	 * @var string - used to override IP Detector
	 */
	private $sIp;

	/**
	 * @var string[]
	 */
	private $aMyIps;

	/**
	 * @var IpUtils
	 */
	protected static $oInstance = null;

	/**
	 * @return IpUtils
	 */
	public static function GetInstance() {
		if ( is_null( self::$oInstance ) ) {
			self::$oInstance = new self();
		}
		return self::$oInstance;
	}

	/**
	 * Checks if an IPv4 or IPv6 address is contained in the list of given IPs or subnets.
	 * @param string       $requestIp      IP to check
	 * @param string|array $ips            List of IPs or subnets (can be a string if only a single one)
	 * @param bool         $throwException Whether to throw the exception on IPv6 support lacking
	 * @return bool Whether the IP is valid
	 */
	public static function checkIp( $requestIp, $ips, $throwException = false ) {
		$isIP = false;

		if ( !is_array( $ips ) ) {
			$ips = [ $ips ];
		}

		$method = substr_count( $requestIp, ':' ) > 1 ? 'checkIp6' : 'checkIp4';
		foreach ( $ips as $ip ) {
			try {
				if ( self::$method( $requestIp, $ip ) ) {
					$isIP = true;
					break;
				}
			}
			catch ( \Exception $e ) {
				if ( $throwException ) {
					throw $e;
				}
				$isIP = false;
			}
		}

		return $isIP;
	}

	/**
	 * Compares two IPv4 addresses.
	 * In case a subnet is given, it checks if it contains the request IP.
	 * @param string $requestIp IPv4 address to check
	 * @param string $ip        IPv4 address or subnet in CIDR notation
	 * @return bool Whether the IP is valid
	 */
	public static function checkIp4( $requestIp, $ip ) {
		$isIP = false;

		if ( filter_var( $requestIp, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4 ) ) {

			if ( false !== strpos( $ip, '/' ) ) {
				list( $address, $netmask ) = explode( '/', $ip, 2 );
			}
			else {
				$address = $ip;
				$netmask = 32;
			}

			$isIP = $netmask >= 0 && $netmask <= 32
					&& ( false !== ip2long( $address ) )
					&& filter_var( $address, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4 )
					&&
					0 === substr_compare(
						sprintf( '%032b', ip2long( $requestIp ) ),
						sprintf( '%032b', ip2long( $address ) ),
						0, $netmask
					);
		}
		return $isIP;
	}

	/**
	 * Compares two IPv6 addresses.
	 * In case a subnet is given, it checks if it contains the request IP.
	 * @param string $requestIp IPv6 address to check
	 * @param string $ip        IPv6 address or subnet in CIDR notation
	 * @return bool Whether the IP is valid
	 * @throws \Exception When IPV6 support is not enabled
	 * @author David Soria Parra <dsp at php dot net>
	 * @see    https://github.com/dsp/v6tools
	 */
	public static function checkIp6( $requestIp, $ip ) {
		if ( !( ( extension_loaded( 'sockets' ) && defined( 'AF_INET6' ) ) || @inet_pton( '::1' ) ) ) {
			throw new \Exception( 'Unable to check Ipv6. Check that PHP was not compiled with option "disable-ipv6".' );
		}

		if ( false !== strpos( $ip, '/' ) ) {
			list( $address, $netmask ) = explode( '/', $ip, 2 );

			if ( '0' === $netmask ) {
				return (bool)unpack( 'n*', @inet_pton( $address ) );
			}

			if ( $netmask < 1 || $netmask > 128 ) {
				return false;
			}
		}
		else {
			$address = $ip;
			$netmask = 128;
		}

		$bytesAddr = unpack( 'n*', inet_pton( $address ) );
		$bytesTest = unpack( 'n*', inet_pton( $requestIp ) );

		$is = false;
		if ( !empty( $bytesAddr ) && !empty( $bytesTest ) ) {
			$is = true;
			for ( $i = 1, $ceil = ceil( $netmask/16 ) ; $i <= $ceil ; ++$i ) {
				$left = $netmask - 16*( $i - 1 );
				$left = ( $left <= 16 ) ? $left : 16;
				$mask = ~( 0xffff >> $left ) & 0xffff;
				if ( ( $bytesAddr[ $i ] & $mask ) != ( $bytesTest[ $i ] & $mask ) ) {
					$is = false;
					break;
				}
			}
		}
		return $is;
	}

	/**
	 * @param string $ip
	 * @return bool|int
	 */
	public function getIpVersion( $ip ) {
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			return 4;
		}
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
			return 6;
		}
		return false;
	}

	/**
	 * @param string $ip
	 * @return string
	 */
	public function getIpWhoisLookup( $ip ) {
		return sprintf( 'https://apps.db.ripe.net/db-web-ui/#/query?bflag&searchtext=%s#resultsSection', $ip );
	}

	/**
	 * @param string $ip
	 * @return string
	 */
	public function getIpInfo( $ip ) {
		return sprintf( 'https://redirect.li/map/?ip=%s', $ip );
	}

	/**
	 * @param string $ip
	 * @return string
	 */
	public function getIpGeoInfo( $ip = null ) {
		return Services::HttpRequest()->getContent(
			sprintf( 'http://ip6.me/api/%s', empty( $ip ) ? '' : '/'.$ip )
		);
	}

	/**
	 * @return Utilities\Net\VisitorIpDetection
	 */
	public function getIpDetector() {
		if ( !$this->oIpDetector instanceof Utilities\Net\VisitorIpDetection ) {
			$this->oIpDetector = new Utilities\Net\VisitorIpDetection();
		}
		return $this->oIpDetector;
	}

	/**
	 * @param bool $asHuman
	 * @return int|string|bool - visitor IP Address as IP2Long
	 */
	public function getRequestIp( $asHuman = true ) {
		$sIP = empty( $this->sIp ) ? $this->getIpDetector()->getIP() : $this->sIp;

		// If it's IPv6 we never return as long (we can't!)
		if ( !empty( $sIP ) || $asHuman || $this->getIpVersion( $sIP ) == 6 ) {
			return $sIP;
		}

		return ip2long( $sIP );
	}

	/**
	 * @param $sIP
	 * @return bool
	 */
	public function isPrivateIP( $sIP ) {
		return $this->isValidIp( $sIP )
			   && !$this->isValidIp_PublicRemote( $sIP );
	}

	/**
	 * @param string $sIP
	 * @return bool
	 */
	public function isTrueLoopback( $sIP ) {
		try {
			$bLB = ( $this->getIpVersion( $sIP ) == 4 && $this->checkIp4( $sIP, '127.0.0.0/8' ) )
				   || ( $this->getIpVersion( $sIP == 6 ) && $this->checkIp6( $sIP, '::1/128' ) );
		}
		catch ( \Exception $e ) {
			$bLB = false;
		}
		return $bLB;
	}

	public function isLoopback() :bool {
		return in_array( $this->getRequestIp(), $this->getServerPublicIPs() );
	}

	public function isSupportedIpv6() :bool {
		return ( extension_loaded( 'sockets' ) && defined( 'AF_INET6' ) ) || @inet_pton( '::1' );
	}

	/**
	 * @param string $ip
	 * @param bool   $flags
	 * @return bool
	 */
	public function isValidIp( $ip, $flags = null ) {
		return filter_var( trim( $ip ), FILTER_VALIDATE_IP, $flags );
	}

	/**
	 * @param string $ip
	 * @return bool
	 */
	public function isValidIp4Range( $ip ) {
		$range = false;
		if ( strpos( $ip, '/' ) ) {
			list( $ip, $CIDR ) = explode( '/', $ip );
			$range = $this->isValidIp( $ip ) && ( (int)$CIDR >= 0 && (int)$CIDR <= 32 );
		}
		return $range;
	}

	/**
	 * @param string $ip
	 * @return bool
	 */
	public function isValidIp6Range( $ip ) {
		$bIsRange = false;
		if ( strpos( $ip, '/' ) ) {
			list( $ip, $CIDR ) = explode( '/', $ip );
			$bIsRange = $this->isValidIp( $ip ) && ( (int)$CIDR >= 0 && (int)$CIDR <= 128 );
		}
		return $bIsRange;
	}

	/**
	 * @param string $ip
	 * @return bool
	 */
	public function isValidIpOrRange( $ip ) {
		return $this->isValidIp_PublicRemote( $ip ) || $this->isValidIpRange( $ip );
	}

	/**
	 * Assumes a valid IPv4 address is provided as we're only testing for a whether the IP is public or not.
	 * @param string $ip
	 * @return bool
	 */
	public function isValidIp_PublicRange( $ip ) {
		return $this->isValidIp( $ip, FILTER_FLAG_NO_PRIV_RANGE );
	}

	/**
	 * @param string $ip
	 * @return bool
	 */
	public function isValidIp_PublicRemote( $ip ) {
		return $this->isValidIp( $ip, ( FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) );
	}

	/**
	 * @param string $ip
	 * @return bool
	 */
	public function isValidIpRange( $ip ) {
		return $this->isValidIp4Range( $ip ) || $this->isValidIp6Range( $ip );
	}

	/**
	 * @param bool $bForceRefresh
	 * @return string[]
	 */
	public function getServerPublicIPs( $bForceRefresh = false ) {
		if ( $bForceRefresh || empty( $this->aMyIps ) ) {

			$aIPs = Utilities\Options\Transient::Get( 'my_server_ips' );
			if ( empty( $aIPs ) || !is_array( $aIPs ) || empty( $aIPs[ 'check_at' ] ) ) {
				$aIPs = [
					'check_at' => 0,
					'hash'     => '',
					'ips'      => []
				];
			}

			$nAge = Services::Request()->ts() - $aIPs[ 'check_at' ];
			$bExpired = ( $nAge > HOUR_IN_SECONDS )
						&& ( Services::Data()->getServerHash() != $aIPs[ 'hash' ] || $nAge > WEEK_IN_SECONDS );
			if ( $bForceRefresh || $bExpired ) {
				$aIPs = [
					'check_at' => Services::Request()->ts(),
					'hash'     => Services::Data()->getServerHash(),
					'ips'      => array_filter(
						( new Ipify\Api() )->getMyIps(),
						function ( $ip ) {
							return $this->isValidIp_PublicRemote( $ip );
						}
					)
				];
				Utilities\Options\Transient::Set( 'my_server_ips', $aIPs, MONTH_IN_SECONDS );
			}

			$this->aMyIps = $aIPs[ 'ips' ];
		}
		return $this->aMyIps;
	}

	/**
	 * @param $ip
	 * @return string|null
	 */
	public function determineSourceFromIp( $ip ) {
		return ( new FindSourceFromIp() )->run( $ip );
	}

	/**
	 * @param Net\VisitorIpDetection $detector
	 * @return $this
	 */
	public function setIpDetector( Utilities\Net\VisitorIpDetection $detector ) {
		$this->oIpDetector = $detector;
		return $this;
	}

	/**
	 * Override the Detector with this IP.
	 * @param string $ip
	 * @return $this
	 */
	public function setRequestIpAddress( $ip ) {
		$this->sIp = $ip;
		return $this;
	}
}