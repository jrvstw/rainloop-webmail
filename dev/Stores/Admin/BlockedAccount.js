//import _ from '_';
import ko from 'ko';

class BlockedAccountAdminStore {
	constructor() {
		this.blockedAccounts = ko.observableArray([]);
		this.blockedAccounts.loading = ko.observable(false).extend({ 'throttle': 100 });
	}
}

export default new BlockedAccountAdminStore();
