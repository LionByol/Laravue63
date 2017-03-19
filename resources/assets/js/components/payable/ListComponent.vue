<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h1>{{ title }}</h1>
                <small class="text-{{ status | formatStatus bills }}">{{ status }}</small><br v-if="totalToPay == 0">
                <small class="text-info" v-if="totalToPay == 0">Total pago: {{ total | currency 'R$ ' }}</small><br v-if="overdue > 0">
                <small class="text-danger" v-if="overdue > 0"><strong>Vencidas: {{ overdue | currency 'R$ ' }}</strong></small><br v-if="totalToPay > 0">
                <small v-if="totalToPay > 0"><strong>Total a pagar: {{ totalToPay | currency 'R$ ' }}</strong></small>

                <p v-if="!bills.length && !loaded">
                    <small><i class="fa fa-spinner fa-spin"></i> Carregando...</small>
                </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="#" class="btn btn-primary btn-sm" @click.prevent="newBill"> Nova Conta</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Vencimento</th>
                    <th>Nome</th>
                    <th>Paga?</th>
                    <th>Ações</th>
                    <th class="text-right">Valor</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(idx, bill) in bills" track-by="$index" :class="{'bg-danger': isOverDue(bill)}"
                    v-if="!bill.receivable">
                    <td>{{ bill.id }}</td>
                    <td>{{ bill.due_date | formatDate 'L' 'pt-br' }}</td>
                    <td>{{ bill.name }}</td>
                    <td :class="{'text-success':bill.done, 'text-danger':!bill.done}">{{ bill.done | doneLabel }}</td>
                    <td>
                        <a v-link="{ name: 'contas.pagar.editar', params: {id: bill.id} }"
                           class="btn btn-info btn-xs"><i class="fa fa-edit fa-fw"></i> Editar</a>
                        <a href="#" @click.prevent="payBill(idx)" class="btn btn-success btn-xs"
                           v-show="bill.done == 0"><i class="fa fa-dollar fa-fw"></i> Pagar</a>
                        <a href="#" @click.prevent="unpayBill(idx)" class="btn btn-warning btn-xs"
                           v-show="bill.done == 1"><i class="fa fa-warning fa-fw"></i> Estornar</a>
                        <a href="#" @click.prevent="deleteBill(bill)" class="btn btn-danger btn-xs"><i
                                class="fa fa-trash fa-fw"></i> Apagar Conta</a>
                        <confirm-modal :id="'Bill' + bill.id" :item="bill">Tem certeza que deseja apagar a conta
                            <br><strong>{{ bill.name }}</strong>?
                        </confirm-modal>
                    </td>
                    <td class="text-right">{{ bill.value | currency 'R$ ' }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>


<script>
    import ConfirmModal from "../layout/ConfirmModal.vue";

    export default{

        data(){
            return {
                title: 'Contas a Pagar',
                bills: this.$parent.bills,
                loaded: false
            }
        },
        components: {
            ConfirmModal
        },
        computed: {
            overdue() {
                if (!this.bills.length) {
                    return 0;
                }

                var totalOverdue = 0;
                for(var i in this.bills) {
                    if (this.isOverDue(this.bills[i])) {
                        totalOverdue += this.bills[i].value;
                    }
                }

                return totalOverdue;
            },
            total() {
                if (!this.bills.length) {
                    return 0;
                }

                var total = 0;
                for(var i in this.bills) {
                    total += this.bills[i].value;
                }

                return total;
            },
            totalToPay() {
                if (!this.bills.length) {
                    return 0;
                }

                var total = 0;
                for(var i in this.bills) {
                    if (!this.bills[i].done) {
                        total += this.bills[i].value;
                    }
                }

                return total;
            },
            status() {
                if (!this.bills.length) {
                    return "Nenhuma conta cadastrada!";
                }

                var count = 0;

                for (var i in this.bills) {
                    if (!this.bills[i].done) {
                        count++;
                    }
                }

                switch (count) {
                    case 0:
                        return "Nenhuma conta a pagar";
                    case 1:
                        return "Há uma conta a ser paga";
                    default:
                        return "Há " + count + " contas a serem pagas";
                }
            },
        },
        methods: {
            payBill(idx) {
                var bill = this.bills[idx],
                    vm = this;
                vm.$dispatch('show-please-wait');
                BillStore.pay({ id: bill.id }, bill).then((response) => {
                    vm.$dispatch('hide-please-wait');
                    bill.done = 1;
                }).catch((response, status, request) => {
                    vm.$dispatch('hide-please-wait');
                    alert(response.data);
                    bill.done = 0;
                });
            },
            unpayBill(idx) {
                var bill = this.bills[idx],
                    vm = this;
                vm.$dispatch('show-please-wait');
                BillStore.unpay({ id: bill.id }, bill).then(response => {
                    vm.$dispatch('hide-please-wait');
                    bill.done = 0;
                }).catch(response => {
                    vm.$dispatch('hide-please-wait');
                    alert(response.data);
                    bill.done = 1;
                });
            },
            deleteBill(bill) {
                $("#Bill" + bill.id).modal('show');
            },
            removeBill(bill, modalId) {
                var vm = this;
                BillStore.delete({ id: bill.id }).then(response => {
                    vm.bills.$remove(bill);
                    $("#" + modalId).modal('hide');
                }).catch(response => {
                    alert(response.data);
                });

            },
            isOverDue(bill) {
                var today = moment.now(),
                        due_date = moment(bill.due_date);

                return (due_date < today) && (!bill.done);
            },
            newBill() {
                this.$router.go({name: 'contas.pagar.nova'});
            }
        },
        events: {
            'return-modal-confirm': function (confirm, modalId, bill) {
                if (confirm) {
                    this.removeBill(bill, modalId);
                }
            },
            'load-parent-bills': function () {
                this.bills = this.$parent.bills;
            }
        },
        ready() {
            var vm = this;
            setTimeout(function () {
                vm.loaded = true;
            }, 5000);
        }
    }
</script>
