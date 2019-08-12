<?php

namespace RainLoop\Providers\BlockedAccount;

class PdoBlockedAccount
	extends \RainLoop\Common\PdoAbstract
	implements \RainLoop\Providers\BlockedAccount\BlockedAccountInterface
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
	 * @return array
	 */
	protected function getPdoAccessData()
	{
		return array($this->sDsnType, $this->sDsn, $this->sUser, $this->sPassword);
	}

	/**
	 * @return bool
	 */
	public function Includes($sName)
	{
		$this->touchDatabase();
		$sSql = 'SELECT id_account_str FROM rainloop_ab_blocked_accounts WHERE id_account_str = :name';
		$aParams = array(':name' => array($sName, \PDO::PARAM_STR));
		$oStmt = $this->prepareAndExecute($sSql, $aParams);
		if ($oStmt)
		{
			$aFetch = $oStmt->fetchAll(\PDO::FETCH_ASSOC);
			return (0 < \count($aFetch));
		}
		return false;
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
	public function GetList()
	{
		$this->touchDatabase();
		$aResult = array();

		$sSql = 'SELECT id_account_str FROM rainloop_ab_blocked_accounts';

		$oStmt = $this->prepareAndExecute($sSql);
		if ($oStmt)
		{
			$aFetch = $oStmt->fetchAll(\PDO::FETCH_ASSOC);
			if (\is_array($aFetch))
			{
				foreach ($aFetch as $aItem) {
					$aResult[] = array('Name' => $aItem['id_account_str']);
				}
			}
		}

		return $aResult;
	}

	/**
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function AddAccount($sName)
	{
		$this->touchDatabase();
		$sSql = 'INSERT INTO rainloop_ab_blocked_accounts VALUE (null, :id_account_str)';
		$this->prepareAndExecute($sSql, array('id_account_str' => array($sName, \PDO::PARAM_STR)));
		return true;
	}

	/**
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function DeleteAccount($sName)
	{
		$this->touchDatabase();
		$sSql = 'DELETE FROM rainloop_ab_blocked_accounts WHERE id_account_str = :id_account_str';
		$this->prepareAndExecute($sSql, array('id_account_str' => array($sName, \PDO::PARAM_STR)));
		return true;
	}

	private function touchDatabase()
	{
		$sInitial = <<<MYSQLINITIAL

CREATE TABLE IF NOT EXISTS rainloop_ab_blocked_accounts (

id_account		bigint UNSIGNED		NOT NULL AUTO_INCREMENT,
id_account_str	varchar(128)		NOT NULL DEFAULT '',

PRIMARY KEY(id_account)

)/*!40000 ENGINE=INNODB *//*!40101 CHARACTER SET utf8 COLLATE utf8_general_ci */;

MYSQLINITIAL;

		return $this->prepareAndExecute($sInitial);
	}

}
