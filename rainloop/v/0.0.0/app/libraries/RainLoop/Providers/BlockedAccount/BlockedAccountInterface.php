<?php

namespace RainLoop\Providers\BlockedAccount;

interface BlockedAccountInterface
{
	/**
	 * @return bool
	 */
	public function IsSupported();
}
