//import _ from '_';
import ko from 'ko';

class BlockAccountAdminStore {
	constructor() {
		this.blockAccounts = ko.observableArray([]);
		this.blockAccounts.loading = ko.observable(false).extend({ 'throttle': 100 });
	}
}

export default new BlockAccountAdminStore();
