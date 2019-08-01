import _ from '_';
import ko from 'ko';

import { StorageResultType, Notification } from 'Common/Enums';
import { bMobileDevice } from 'Common/Globals';
import { i18n } from 'Common/Translator';

import DomainStore from 'Stores/Admin/Domain';
import BlockAccountStore from 'Stores/Admin/BlockAccount';

import Remote from 'Remote/Admin/Ajax';

import { getApp } from 'Helper/Apps/Admin';

import { popup, command } from 'Knoin/Knoin';
import { AbstractViewNext } from 'Knoin/AbstractViewNext';

@popup({
	name: 'View/Popup/Block',
	templateID: 'PopupsBlock'
})
class BlockPopupView extends AbstractViewNext {
	constructor() {
		super();

		this.saving = ko.observable(false);
		this.savingError = ko.observable('');

		this.name = ko.observable('');
		this.name.focused = ko.observable(false);

		this.domain = ko.observable('');

		this.blockAccounts = BlockAccountStore.blockAccounts;
		this.domains = DomainStore.domainsWithoutAliases;

		this.domainsOptions = ko.computed(() =>
			_.map(this.domains(), (item) => ({ optValue: item.name, optText: item.name }))
		);

		this.canBeSaved = ko.computed(() => !this.saving() && '' !== this.name() && '' !== this.domain());

		this.onBlockAccountCreateOrSaveResponse = _.bind(this.onBlockAccountCreateOrSaveResponse, this);
	}

	@command((self) => self.canBeSaved())
	createCommand() {
		this.saving(true);
		Remote.blockAccount(this.onBlockAccountCreateOrSaveResponse, this.name(), this.domain());
	}

	onBlockAccountCreateOrSaveResponse(result, data) {
		this.saving(false);
		if (StorageResultType.Success === result && data) {
			if (data.Result) {
				getApp().reloadBlockAccountList();
				this.closeCommand();
			} else if (Notification.AccountAlreadyBlocked === data.ErrorCode) {
				this.savingError(i18n('ERRORS/ACCOUNT_ALREADY_BLOCKED'));
			}
		} else {
			this.savingError(i18n('ERRORS/UNKNOWN_ERROR'));
		}
	}

	onShow() {
		this.clearForm();
	}

	onShowWithDelay() {
		if ('' === this.name() && !bMobileDevice) {
			this.name.focused(true);
		}
	}

	clearForm() {
		this.saving(false);
		this.savingError('');

		this.name('');
		this.name.focused(false);

		this.domain('');
	}
}

export { BlockPopupView, BlockPopupView as default };
