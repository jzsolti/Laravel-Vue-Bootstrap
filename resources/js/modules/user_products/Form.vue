<template>
    <form @submit.prevent="onSubmit">
        <div class="modal-header">
            <h5 class="modal-title" id="cproductFormModalLabel">Product form</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group  mb-3">
                <label for="name">Name</label>
                <input v-model.trim="form.name" type="text" class="form-control" :class="inputClass('name')" />
                <div class="invalid-feedback" v-if="errors.name">
                    {{ errors.name[0] }}
                </div>
            </div>
            <div class="form-group  mb-3">
                <label for="name">Status</label>
                <select v-model="form.status" class="form-select" :class="inputClass('status')">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
      </select>

                <div class="invalid-feedback" v-if="errors.status">
                    {{ errors.status[0] }}
                </div>
            </div>
            <div class="form-group  mb-3">
                <label for="name">Categories</label>
    
                <ul class="list-group">
                    <li v-for="category in categories.value" :key="category.id" class="list-group-item d-flex justify-content-between">
                        <input type="checkbox" :id="'category-'+category.id" v-model="form.categories" :value="category.id" />
                        <label :for="'category-'+category.id" class="ml-2"> {{ category.name }}</label>
                    </li>
                </ul>
                <div class="text-danger" v-if="errors.categories">
                    {{ errors.categories[0] }}
                </div>
            </div>
    
        </div>
        <div class="modal-footer">
    
            <button type="submit" class="btn btn-primary" v-if="!disabled">Save </button>
    
            <button class="btn btn-primary" type="button" disabled="disabled" v-if="disabled">
                                                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Sending...</span>
                                                                                            </button>
    
        </div>
    </form>
</template>

<script>
import FormHelper from '../../FormHelper.js';
export default {
    inject: ['categories'],
    props: ['product'],
    mounted() {

    },
    data() {
        return {
            disabled: false,
            form: {
                name: '',
                categories: []
            },
            errors: {}
        }
    },
    watch: {
        // This would be called anytime the value of value changes
        product(newValue, oldValue) {
            this.resetForm();
            if (newValue != null) {
                this.form.name = newValue.name;
                this.form.categories = newValue.categories;
            }
        }
    },
    methods: {
        onSubmit() {

            this.disabled = true;

            axios.post('user/products/create', this.form)
                .then((response) => {
                    if ('success' in response.data) {
                        this.errors = {};
                        this.disabled = false;

                        //Swal.fire({ icon: 'success', title: 'Updated', timer: 1000 });
                    }
                }).catch((error) => {
                    this.disabled = false;
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error(error);
                    }
                });

        },
        inputClass(inputName) {
            return FormHelper.inputClass(inputName, this.errors)
        },
        resetForm() {
            this.form.name = '';
            this.form.categories = [],
                this.errors = {};
        }
    }
}
</script>
