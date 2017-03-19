Vue.http.options.root = "/api";

var customActions = {
    totals: {
        method: 'GET',
        url: 'bills/total'
    },
    payables: {
        method: 'GET',
        url: 'bills/payables'
    },
    receivables: {
        method: 'GET',
        url: 'bills/receivables'
    },
    pay: {
        method: 'PUT',
        url: 'bills/{id}/pay'
    },
    unpay: {
        method: 'PUT',
        url: 'bills/{id}/unpay'
    }
};

window.BillStore = Vue.resource("bills{/id}", {}, customActions);