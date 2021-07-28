<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>My Articles</span>
                        <span><router-link to="/user/articles/create" class="">New article</router-link></span>
                    </div>
                    <div class="card-body">
                        <Datatable :columns="columns" url="/user/articles" sortedColumn="created_at" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FormHelper from '../FormHelper.js';
import Swal from 'sweetalert2';
import Datatable from '../components/Datatable.vue';

export default {
    components: { Datatable },
    mounted() {

    },
    data() {
        return {
            loaded: false,
            columns: [
                { name: "edit", label: "Edit", orderable: false, link: true },
                { name: "title", label: "Title" },
                { name: "lead", label: "Lead" },
                { name: "created_at", label: "Created" },
            ]
        }
    },
    methods: {
        onSubmit() {
            // this.disabled = true;
        }
    }
}
</script>