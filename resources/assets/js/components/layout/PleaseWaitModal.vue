<template>
    <div id="#PleaseWait" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalWindow">
        <div class="modal-dialog modal-sm" role="document">

            <div class="modal-content">
                <div class="modal-body bg-primary text-center">
                    <p><i class="fa fa-spinner fa-spin"></i>&nbsp;{{ pleaseWait }}</p>
                </div>
            </div>

        </div>
    </div>
</template>
<script>

    export default{
        props: ['pleaseWait'],
        data() {
            return {
                id: "#PleaseWait"
            };
        },
        methods: {
            show() {
                $(document.getElementById("#PleaseWait")).modal('show');
            },
            hide() {
                $(document.getElementById("#PleaseWait")).modal('hide');
            }
        },
        events: {
            'please-wait-show': function () {
                this.show();
            },
            'please-wait-hide': function () {
                this.hide();
            }
        },
        created() {
            if (!this.pleaseWait) {
                this.pleaseWait = "Please Wait!";
            }
        }
    }
</script>
