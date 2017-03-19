<template>
    <div id="{{ id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalWindow">
        <div class="modal-dialog modal-sm" role="document">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title text-danger" id="gridSystemModalLabel"><i class="fa fa-warning"></i> Atenção!</h3>
                </div>

                <div class="modal-body"><slot></slot></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" @click="confirm(false)">Não</button>
                    <button type="button" class="btn btn-primary" @click.prevent="confirm(true)">Sim</button>
                </div>

            </div>


        </div>
    </div>
</template>
<script>

    export default{
        props: ['id','item'],
        methods: {
            confirm(trueOrFalse) {
                if (trueOrFalse) {
                    $("#" + this.id)
                            .find('.btn-primary')
                            .addClass('disabled').text('')
                            .append('<i class="fa fa-spinner fa-spin"></i> Aguarde!');
                }
                this.$dispatch('return-modal-confirm', trueOrFalse, this.id, this.item);
            }
        }
    }
</script>
