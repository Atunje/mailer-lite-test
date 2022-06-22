<template>

    <div>

        <b-row>
            <b-col><h3>Fields</h3></b-col>
            <b-col align="right"><b-button variant="dark" v-b-modal.edit-modal>Create Field</b-button></b-col>
        </b-row>

        <b-table 
            small 
            responsive 
            :fields="field_names" 
            :items="fields"
            class="mt-4 table-fit">
        </b-table>

        <edit-modal @newField="getFields" />

    </div>

</template>

<script>

    import { FieldService } from "../../services"
    import editModal from "./components/editModal.vue"

    export default {
        name:'fields',
        components: {
            editModal
        },
        data() {
            return {
                fields: [],
                field_names: ['title', 'type']
            }
        },
        methods: {
            async getFields() {
                let { data } = await FieldService.getFields();
                this.fields = data.fields;
            },
        },
        created() {
            this.getFields();
        }
    }

</script>

<style scoped>

</style>