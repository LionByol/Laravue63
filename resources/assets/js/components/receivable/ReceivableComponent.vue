<template>
    <router-view></router-view>
    <please-wait-modal :please-wait="'Aguarde!'"></please-wait-modal>
</template>

<script>


    export default{
        data() {
            return {
                bills: []
            }
        },
        methods: {
            addBill(bill) {
                this.bills.unshift(bill);
            },
            updateBillList(bills) {
                this.$set('bills', bills);
            }
        },
        events: {
            'add-bill-to-bill-list': function(bill) {
                this.addBill(bill);
            },
            'clear-form': function () {
                this.$broadcast('clear-form');
            },
            'show-please-wait': function() {
                this.$broadcast('please-wait-show');
            },
            'hide-please-wait': function() {
                this.$broadcast('please-wait-hide');
            }
        },
        init() {
            var vm = this;
            BillStore.receivables({}).then(function(response) {
                var results = response.data;
                vm.updateBillList(results);
                vm.$broadcast('load-parent-bills');
            });
        }
    }
</script>
