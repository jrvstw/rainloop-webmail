<?php

namespace RainLoop\Providers;

class BlockedAccount extends \RainLoop\Providers\AbstractProvider
{
	/**
	 * @var \RainLoop\Providers\BlockedAccount\BlockedAccountInterface
	 */
	private $oDriver;

	/**
	 * @param \RainLoop\Providers\BlockedAccount\Interface $oDriver
	 *
	 * @return void
	 */
	public function __construct($oDriver)
	{
		$this->oDriver = null;
		if ($oDriver instanceof \RainLoop\Providers\BlockedAccount\BlockedAccountInterface)
		{
			$this->oDriver = $oDriver;
		}
	}

	/**
	 * @return bool
	 */
	public function IsActive()
	{
		return $this->oDriver instanceof \RainLoop\Providers\BlockedAccount\BlockedAccountInterface &&
			$this->oDriver->IsSupported();
	}

	/**
	 * @return array
	 */
	public function GetList()
	{
		return $this->IsActive() ? $this->oDriver->GetList() : array();
	}

	/**
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function AddAccount($sName)
	{
		return $this->IsActive() ? $this->oDriver->AddAccount($sName) : false;
	}

	/**
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function DeleteAccount($sName)
	{
		return $this->IsActive() ? $this->oDriver->DeleteAccount($sName) : false;
	}

}

