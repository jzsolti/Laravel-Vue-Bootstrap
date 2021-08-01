<template>
    <div class="container">
    
        <div class="card">
            <div class="card-header bg-primary"> My Products </div>
    
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"> Count products: 0 </li>
                            <li class="list-group-item"> Count categories: 0 </li>
    
                        </ul>
                    </div>
                    <div class="col-md-4">
    
                    </div>
                    <div class="col-md-2 d-flex flex-column">
                        <button type="button" class="btn btn-primary mb-2" @click="openProductFormModal">Product  <font-awesome-icon icon="plus" /> </button>
                        <button type="button" class="btn btn-primary" @click="openCategoryFormModal"> Category  <font-awesome-icon icon="plus" /></button>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-8">
                products
            </div>
            <div class="col-md-4">
                <ul class="list-group">
                    <li v-for="category in categories" :key="category.id" class="list-group-item d-flex justify-content-between">
                        <div>{{category.name}}</div>
                        <div>
                            <button type="button" @click="editCatetory( category)" class="btn btn-sm btn-success">
                                    <font-awesome-icon icon="edit" /></button>
                            <button type="button" @click="deleteCatetory( category.id)" class="btn btn-sm btn-danger">
                                    <font-awesome-icon icon="trash" /></button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="categoryFormModal" tabindex="-1" aria-labelledby="categoryFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <CategoryForm @category-added="categoryAdded" @category-updated="categoryUpdated" :category="category" />
            </div>
        </div>
    </div>
    <div class="modal fade" id="productFormModal" tabindex="-1" aria-labelledby="productFormModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <ProductForm />
            </div>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue'
import { Modal } from 'bootstrap';
import ProductForm from './Form.vue';
import CategoryForm from '../categories/Form.vue';
export default {
    components: { ProductForm, CategoryForm },
    mounted() {
        axios.get('categories').then((response) => {
            this.categories = response.data.data;
        }).catch((error) => {
            console.error(error);
        });

        this.categoryFormModal = new Modal(document.getElementById('categoryFormModal'), { keyboard: false });
        this.productFormModal = new Modal(document.getElementById('productFormModal'), { keyboard: false });
    },
    data() {
        return {
            categories: [],
            categoryFormModal: null,
            productFormModal: null,
            category: null // edit this category
        }
    },
    methods: {
        openCategoryFormModal() {
            this.categoryFormModal.show();
        },
        openProductFormModal() {
            this.productFormModal.show();
        },
        categoryAdded() {
            this.categoryFormModal.hide();
        },
        categoryUpdated(formData) {
            let foundIndex = this.categories.findIndex(category => category.id == this.category.id);
            this.categories[foundIndex].name = formData.name;

            this.categoryFormModal.hide();
        },
        editCatetory(category) {
            this.category = category;
            this.categoryFormModal.show();
        },
        deleteCatetory(category) {

        }
    }

}
</script>