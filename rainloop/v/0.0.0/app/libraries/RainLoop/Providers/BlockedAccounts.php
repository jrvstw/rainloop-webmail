<?php

namespace RainLoop\Providers;

class PdoBlockedAccounts
	extends \RainLoop\Common\PdoAbstract
{
	/**
	 * @var string
	 */
	private $sDsn;

	/**
	 * @var string
	 */
	private $sDsnType;

	/**
	 * @var string
	 */
	private $sUser;

	/**
	 * @var string
	 */
	private $sPassword;

	public function __construct($sDsn, $sUser = '', $sPassword = '', $sDsnType = 'mysql')
	{
		$this->sDsn = $sDsn;
		$this->sUser = $sUser;
		$this->sPassword = $sPassword;
		$this->sDsnType = $sDsnType;

		$this->bExplain = false; // debug
	}

	/**
	 * @return bool
	 */
	public function IsSupported()
	{
		$aDrivers = \class_exists('PDO') ? \PDO::getAvailableDrivers() : array();
		return \is_array($aDrivers) ? \in_array($this->sDsnType, $aDrivers) : false;
	}

	/**
	 * @param int $iUserID
	 * @param int $iIdContact
	 * @return array
	 */
	private function getList($iUserID, $iIdContact)
	{
		$aResult = array();

		/*
		$sSql = 'SELECT prop_value, prop_frec FROM rainloop_ab_properties WHERE id_user = :id_user AND id_contact = :id_contact AND prop_type = :type';
		$aParams = array(
			':id_user' => array($iUserID, \PDO::PARAM_INT),
			':id_contact' => array($iIdContact, \PDO::PARAM_INT),
			':type' => array(PropertyType::EMAIl, \PDO::PARAM_INT)
		);
		 */
		$sSql = 'SELECT id_account_str FROM rainloop_ab_blocked_accounts';

		$oStmt = $this->prepareAndExecute($sSql);
		if ($oStmt)
		{
			$aFetch = $oStmt->fetchAll(\PDO::FETCH_ASSOC);
			if (\is_array($aFetch))
			{
				foreach ($aFetch as $aItem)
				{
					if ($aItem && !empty($aItem['prop_value']) && !empty($aItem['prop_frec']))
					{
						$aResult[$aItem['prop_value']] = (int) $aItem['prop_frec'];
					}
				}
			}
		}

		return $aResult;
	}

}
