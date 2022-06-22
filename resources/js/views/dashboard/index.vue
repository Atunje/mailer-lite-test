<template>

    <div>

        <b-row>
            <b-col><h3>Subscribers</h3></b-col>
            <b-col align="right"><b-button variant="dark" @click='selectSubscriber({})' v-b-modal.edit-modal>Create Subscriber</b-button></b-col>
        </b-row>

        <hr />
        
        <b-row>
            <b-col>
                <b-form-group class="d-inline" label="Search">
                    <b-form-input class="d-inline" v-model="search_filter.search" placeholder="Search..."  />
                </b-form-group>
                <b-form-group class="d-inline" label="Per Page">
                    <b-form-select class="d-inline" v-model="search_filter.per_page" :options="[25,50,100]"></b-form-select>
                </b-form-group>
                <b-form-group class="d-inline" label="State">
                    <b-form-select class="d-inline" v-model="search_filter.state" :options="['', 'active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed']"></b-form-select>
                </b-form-group>
                <b-button class="d-inline" variant="primary" @click='getSubscribers'>Filter</b-button>
            </b-col>

             <b-col align="right">
                <b-form-group class="d-inline" label="Bulk Action">
                    <b-form-select v-model="bulk_action" :options="['', 'Delete', 'Change State']"></b-form-select>
                </b-form-group>
                <b-form-group class="d-inline" label="State" v-if="bulk_action=='Change State'">
                    <b-form-select v-model="new_state" :options="['', 'action', 'unsubscribed', 'junk', 'bounced', 'unconfirmed']"></b-form-select>
                </b-form-group>
                <b-button class="d-inline" variant="primary" @click='bulkAction'>Bulk Update</b-button>
            </b-col>
        </b-row>
        <small>Click on row to select</small>
        <b-table 
            small 
            responsive 
             hover
            :fields="fields" 
            :items="subscribers" 
            select-mode="multi"
            ref="selectableTable"
            selectable
            @row-selected="onRowSelected"
            class="mt-0 table-fit">

            <template #cell(selected)="{ rowSelected }">
                <template v-if="rowSelected">
                    <span aria-hidden="true">&check;</span>
                    <span class="sr-only">Selected</span>
                </template>
                <template v-else>
                    <span aria-hidden="true">&nbsp;</span>
                    <span class="sr-only">Not selected</span>
                </template>
            </template>

                <template #cell()>
                    <b-check></b-check>
                </template>

                <template #cell(name)="data">
                    {{ data.item.name }}
                </template>

                <template #cell(email)="data">
                    {{ data.item.email }}
                </template>

                <template #cell(state)="data">
                    <span class="font-weight-bold">
                        <span v-if="data.item.state=='unsubscribed'" class="text-dark"> Unsubscribed</span>
                        <span v-if="data.item.state=='active'" class="text-success"><b-icon icon="check2-circle" class="icon" /> Active</span>
                        <span v-if="data.item.state=='junk'" class="text-warning"> Junk</span>
                        <span v-if="data.item.state=='bounced'" class="text-secondary"> Bounced</span>
                        <span v-if="data.item.state=='unconfirmed'" class="text-info"> Unconfirmed</span>
                    </span>
                </template>

                <template #cell(action)="data">
                    <b-button variant="info" @click='selectSubscriber(data.item)' v-b-modal.edit-modal>View</b-button>
                    <b-button variant="warning" @click='deleteSubscriber(data.item)'>Delete</b-button>
                </template>

        </b-table>

        <button class="btn btn-outline-dark left" @click='getSubscribers(parseInt(pagination.current_page)-1)'>Previous</button>
        <button class="btn btn-outline-dark right" @click='getSubscribers(parseInt(pagination.current_page)+1)'>Next</button>

        <edit-modal :subscriber="selected_subscriber" @newSubscriber="getSubscribers" />
        
    </div>
    
</template>

<script>

    import { SubscriberService } from '../../services';
    import EditModal from "./components/editModal.vue"
    
    export default {
        name:'dashboard',
        components: {
            EditModal
        },
        data() {
            return {
                subscribers: [],
                pagination:{},
                fields: ['', 'name', 'email', 'state', 'action'],
                selected_subscriber:{},
                search_filter:{},
                selected_subs: [],
                bulk_action:"",
                new_state:"",
            }
        },
        methods: {
            async getSubscribers(page=1) {
                console.log(page)
                this.search_filter.page = page;
                console.log(this.search_filter)
                let { data } = await SubscriberService.getSubscribers(this.search_filter);
                this.subscribers = data.subscribers;
                this.pagination = data.pagination;
            },
            selectSubscriber(subscriber) {
                this.selected_subscriber = subscriber;
            },
            async deleteSubscriber(subscriber) {
                let { data } = await SubscriberService.deleteSubscriber(subscriber.id);
                this.getSubscribers();
            },
            async bulkAction() {
                const ids = this.selected_subs.map(subscriber => subscriber.id)

                if(this.bulk_action == 'Delete') {
                    this.bulkDelete(ids);
                } else {
                    this.updateState(ids);
                }

                this.getSubscribers();
                
            },
            async bulkDelete(ids) {
                let { data } = await SubscriberService.deleteSubscribers(ids);
            },
            async updateState(ids) {
                let { data } = await SubscriberService.changeState(ids, this.new_state);
            },
            onRowSelected(items) {
                this.selected_subs = items
            },
            selectAllRows() {
                this.$refs.selectableTable.selectAllRows()
            },
            clearSelected() {
                this.$refs.selectableTable.clearSelected()
            },
        },
        created() {
            this.getSubscribers();
        }
    };
    
</script>

<style scoped>

        .col-form-label{
            text-align: left !important;
        }

</style>