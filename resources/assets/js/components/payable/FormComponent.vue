<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h2>{{ formTitle }}</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <form @submit.prevent="submitBill" class="form form-horizontal" role="form">
                <div class="form-group" :class="{'has-error' : formErrors['name']}">
                    <label class="control-label col-sm-3">Nome</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" v-model="bill.name" name="name">
                        <span v-if="formErrors['name']" class="text-danger">{{ formErrors['name'] }}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error' : formErrors['value']}">
                    <label class="control-label col-sm-3">Valor</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.01" class="form-control" v-model="bill.value" name="value">
                        <span v-if="formErrors['value']" class="text-danger">{{ formErrors['value'] }}</span>
                    </div>
                </div>
                <div class="form-group" :class="{'has-error' : formErrors['due_date']}">
                    <label class="control-label col-sm-3">Vencimento</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" v-model="bill.due_date" name="due_date" placeholder="dd/mm/aaaa">
                        <span v-if="formErrors['due_date']" class="text-danger">{{ formErrors['due_date'] }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                        <button type="button" class="btn btn-danger btn-sm" @click="back">Voltar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>


<script>

    export default{
        data() {
            return {
                bill: {
                    name: '',
                    due_date: '',
                    value: '',
                    done: 0,
                    receivable: 0
                },
                formType: 'insert',
                formErrors: {},
            };
        },
        computed: {
            formTitle() {
                return this.formType == 'insert' ? 'Nova Conta' : 'Editar Conta';
            }
        },
        methods: {
            submitBill() {
                this.$dispatch('show-please-wait');
                this.bill.due_date = moment(this.bill.due_date, ["DD/MM/YYYY","YYYY-MM-DD"]).format("YYYY-MM-DD");
                switch (this.formType) {
                    case 'update':
                        this.updateBill();
                        break;
                    default:
                        this.saveBill();
                }

            },
            saveBill() {
                var vm = this;
                BillStore.save({}, vm.bill).then(response => {

                    vm.bill = response.data;

                    vm.$dispatch('add-bill-to-bill-list', vm.bill);
                    vm.$router.go({name: 'contas.pagar'});
                    vm.$dispatch('hide-please-wait');

                }).catch((response, status, request) => {
                    this.$dispatch('hide-please-wait');
                    this.formErrors = response.data;
                });
            },
            updateBill() {
                var vm = this;
                BillStore.update({ id: vm.bill.id }, vm.bill).then(response => {

                    vm.bill = response.data;

                    vm.$router.go({name: 'contas.pagar'});
                    vm.$dispatch('hide-please-wait');

                }).catch((response, status, request) => {

                    vm.$dispatch('hide-please-wait');
                    vm.formErrors = response.data;
                });
            },
            back() {
                this.$router.go({name: 'contas.pagar'});
            }
        },
        events: {
            'set-form-type': function (type) {
                this.formType = type;
            },
            'clear-form': function () {
                console.log('clear-form');
                this.formType = "insert";
                this.formErrors = {};
                this.bill = {
                    name: '',
                    due_date: '',
                    value: '',
                    done: 0,
                    receivable: 0
                };
            }
        },
        watch: {
            '$route': function() {
                if (this.$route.name == 'contas.pagar.nova') {
                    this.bill = {
                        name: '',
                        due_date: '',
                        value: '',
                        done: 0,
                        receivable: 0
                    };
                }

                if (this.$route.name == 'contas.pagar.editar' && this.bill.name == '') {
                    var id = this.$router.params.id,
                        vm = this;
                    BillStore.get({ id: id }).then(response => {
                        vm.bill = response.data;
                    }).catch(response => {
                        alert(response.data);
                    })
                }
            }
        },
        created() {

            if (this.$route.name == "contas.pagar.editar") {
                var id = this.$route.params.id,
                    vm = this;
                vm.formType = "update";

                $.each(vm.$parent.bills, function (index, item) {
                    if (item.id == id) {
                        vm.bill = item;
                    }
                });

            }
        }
    }
</script>
