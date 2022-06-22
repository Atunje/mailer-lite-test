<template>
    
    <b-modal 
        ref="edit-modal" 
        id="edit-modal" 
        @show="reset"
        @hidden="reset"
        hide-footer>
        <template #modal-title>
            New Field
        </template>
        <div class="d-block">
            <b-alert :variant="status_variant" :show="status_msg!=''">{{ status_msg }}</b-alert>
            <b-row>
                <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-2">
                     <b-form-group label="Title">
                        <b-form-input v-model="form.title" required />
                        <small v-if="status_data != undefined" class="text-warning">{{ status_data.title!=undefined ? status_data.title[0] : '' }}</small>
                    </b-form-group>
                </b-col>
                <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-2">
                    <b-form-group label="Type">
                        <b-form-select v-model="form.type" :options="['', 'date', 'number', 'string', 'boolean']" required></b-form-select>
                        <small v-if="status_data != undefined" class="text-warning">{{ status_data.type!=undefined ? status_data.type[0] : '' }}</small>
                    </b-form-group>
                </b-col>
            </b-row>
        </div>
        <div class="mt-3">
            <StatefulButton
                :loading="submitting"
                loadingText="Processing..."
                text="Save"
                elementClass="rounded-lg"
                @click="save()"
            />
            <b-button @click="$bvModal.hide('edit-modal')">Close</b-button>
        </div>
    </b-modal>

</template>

<script>

    import StatefulButton from "../../../components/actions/StatefulButton.vue";
    import { FieldService } from "../../../services"

    export default {
        name: "Edit-Modal",
        components: {
            StatefulButton
        },
        data() {
            return { 
                submitting: false,
                form:{},
                status_msg:'',
                status_variant:'',
                status_data: {}
            }
        },
        methods: {
            reset() {
                this.status_msg = '';
                this.status_data = {}
            },
            async save() {
                this.submitting = true;
                FieldService.createField(this.form).then((response) => {
                    this.submitting = false;
                    this.status_msg = response.message;
                    this.status_variant = 'success';
                    this.status_data = {};
                    this.form = {};
                    this.$emit('newField');
                }, (e) => {
                    let response = e.response.data;
                    this.status_data = response.data;
                    this.submitting = false;
                });
            },
        },
    }

</script>

<style>

</style>