import _ from '_';
import ko from 'ko';

import { StorageResultType } from 'Common/Enums';
import { showScreenPopup } from 'Knoin/Knoin';

import BlockedAccountStore from 'Stores/Admin/BlockedAccount';
//import DomainStore from 'Stores/Admin/Domain';
import Remote from 'Remote/Admin/Ajax';

import { getApp } from 'Helper/Apps/Admin';

class BlockAdminSettings {
	constructor() {
		//this.blockedAccounts = DomainStore.domains;
		this.blockedAccounts = BlockedAccountStore.blockedAccounts;

		this.visibility = ko.computed(() => (this.blockedAccounts.loading() ? 'visible' : 'hidden'));

		//this.domainForDeletion = ko.observable(null).deleteAccessHelper();
		this.accountForUnblock = ko.observable(null).deleteAccessHelper();

		this.onBlockedAccountListChangeRequest = _.bind(this.onBlockedAccountListChangeRequest, this);
		this.onBlockedAccountLoadRequest = _.bind(this.onBlockedAccountLoadRequest, this);
	}

	blockAccount() {
		showScreenPopup(require('View/Popup/Block'));
	}

	/*
	unblockAccount(account) {
		this.blockedAccounts.remove(account);
		//Remote.
	}

	deleteDomain(domain) {
		this.domains.remove(domain);
		Remote.domainDelete(this.onDomainListChangeRequest, domain.name);
	}

	disableDomain(domain) {
		domain.disabled(!domain.disabled());
		Remote.domainDisable(this.onDomainListChangeRequest, domain.name, domain.disabled());
	}

	*/
	onBuild(oDom) {
		const self = this;
		oDom.on('click', '.b-admin-blocked-account-list-table .e-item .e-action', function() {
			// eslint-disable-line prefer-arrow-callback
			const blockedAccountItem = ko.dataFor(this); // eslint-disable-line no-invalid-this
			if (blockedAccountItem) {
				Remote.blockedAccount(self.onBlockedAccountLoadRequest, blockedAccountItem.name);
			}
		});

		getApp().reloadBlockedAccountList();
	}

	onBlockedAccountLoadRequest(sResult, oData) {
		if (StorageResultType.Success === sResult && oData && oData.Result) {
			showScreenPopup(require('View/Popup/Block'), [oData.Result]);
		}
	}

	onBlockedAccountListChangeRequest() {
		getApp().reloadBlockedAccountList();
	}
}

export { BlockAdminSettings, BlockAdminSettings as default };
