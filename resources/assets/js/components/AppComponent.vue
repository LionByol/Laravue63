<template>
    <div class="page-header">
        <h3>Total</h3>
        <small v-if="!loaded"><i class="fa fa-spinner fa-spin"></i> Carregando...</small>
    </div>
    <p>Total a Pagar: {{ totalToPay | currency 'R$ ' }}</p>
    <p>Total a Receber: {{ totalToReceive | currency 'R$ ' }}</p>
    <p class="text-{{ totalTextColor }}">Total Final: {{ final | currency 'R$ ' }}</p>
    <router-view></router-view>

    <please-wait-modal :please-wait="'Aguarde!'"></please-wait-modal>
</template>

<script>

    export default{
        data() {
            return {
                totalToPay: 0,
                totalToReceive: 0,
                loaded:false
            };
        },
        computed: {
            final() {
                return this.totalToReceive - this.totalToPay;
            },
            totalTextColor() {
                var final = this.totalToReceive - this.totalToPay;

                if (!final) {
                    return 'muted';
                }

                if (final > 0) {
                    return 'success';
                }

                return 'danger';
            }
        },
        events: {
            'show-please-wait': function () {
                this.$broadcast('please-wait-show');
            },
            'hide-please-wait': function () {
                this.$broadcast('please-wait-hide');
            }
        },
        created() {
            var vm = this;
            BillStore.totals({}).then(function (response) {
                vm.totalToPay = response.data.totalToPay;
                vm.totalToReceive = response.data.totalToReceive;
                vm.loaded = true;
            }).catch(function (response) {
                console.log(response);
            });
        }
    }
</script>
