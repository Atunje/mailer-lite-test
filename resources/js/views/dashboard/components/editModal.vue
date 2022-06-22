<template>
    
    <b-modal 
        ref="edit-modal" 
        id="edit-modal" 
        @show="reset"
        @hidden="reset"
        hide-footer>
        <template #modal-title>
            Subscriber
        </template>
        <div class="d-block">
            <b-alert :variant="status_variant" :show="status_msg!=''">{{ status_msg }}</b-alert>
            <b-row v-if="subscriber.id!=undefined">
                <b-col cols="12" sm="12" md="12" lg="12" xl="12" class="mb-2" v-bind:key="index" v-for="(value, field, index) in subscriber">

                     <b-form-group :label="titleCase(field)" :label-for="field" v-if="field!='id' && field!='created_at' && field!='state'">
                        <b-form-input v-model="subscriber[field]"  />
                        <small v-if="status_data != undefined" class="text-warning">{{ status_data[field]!=undefined ? status_data[field][0] : "" }}</small>
                    </b-form-group>

                    <b-form-group label="State" label-for="state" v-if="field=='state'">
                        <b-form-select v-model="subscriber.state" :options="['', 'active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed']"></b-form-select>
                        <small v-if="status_data != undefined" class="text-warning">{{ status_data.state!=undefined ? status_data.state[0] : '' }}</small>
                    </b-form-group>

                </b-col>
            </b-row>

            <b-row v-else>

                <b-form-group label="Name" label-for="name">
                    <b-form-input v-model="subscriber.name" required  />
                    <small v-if="status_data != undefined" class="text-warning">{{ status_data.name!=undefined ? status_data.name[0] : '' }}</small>
                </b-form-group>

                <b-form-group label="Email" label-for="email">
                    <b-form-input v-model="subscriber.email" required  />
                    <small v-if="status_data != undefined" class="text-warning">{{ status_data.email!=undefined ? status_data.email[0] : '' }}</small>
                </b-form-group>

                <b-form-group label="State" label-for="state">
                    <b-form-select v-model="subscriber.state" :options="['', 'active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed']" required></b-form-select>
                    <small v-if="status_data != undefined" class="text-warning">{{ status_data.state!=undefined ? status_data.state[0] : '' }}</small>
                </b-form-group>

                <b-form-group :label="titleCase(field.title)" :label-for="field.title" v-bind:key="index" v-for="(field, index) in fields">
                    <b-form-input v-model="subscriber[field.title]"  />
                    <small v-if="status_data != undefined" class="text-warning">{{ status_data[field.title]!=undefined ? status_data[field.title][0] : '' }}</small>
                </b-form-group>

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
    import { titleCase } from "../../../utils/helpers"
    import { SubscriberService, FieldService } from "../../../services"

    export default {
        name: "Edit-Modal",
        props: ['subscriber'],
        components: {
            StatefulButton
        },
        data() {
            return { 
                submitting: false,
                fields:[],
                status_msg:'',
                status_variant:'warning',
                status_data: {}
            }
        },
        methods: {
            titleCase,
            reset() {
                this.status_msg = '';
                this.status_data = {}
            },
            async save() {
                if(this.subscriber.id == undefined) {
                    this.create();
                } else {
                    this.update();
                }
            },
            async update() {
                this.submitting = true;
                SubscriberService.updateSubscriber(this.subscriber.id, this.subscriber).then((response) => {
                    this.submitting = false;
                    this.status_msg = response.message;
                    this.status_variant = 'success';
                    this.status_data = {};
                }, (e) => {
                    let response = e.response.data;
                    this.status_data = response.data;
                    this.submitting = false;
                });
            },
            async create() {
                this.submitting = true;
                SubscriberService.createSubscriber(this.subscriber).then((response) => {
                    this.submitting = false;
                    this.status_msg = response.message;
                    this.status_variant = 'success';
                    this.status_data = {};
                    this.subscriber = {}
                    this.$emit('newSubscriber');
                }, (e) => {
                    let response = e.response.data;
                    this.status_data = response.data;
                    this.submitting = false;
                });
            },
            async getFields() {
                let { data } = await FieldService.getFields();
                this.fields = data.fields;
            }
        },
        created() {
            this.getFields();
        }
    }

</script>

<style>

</style>