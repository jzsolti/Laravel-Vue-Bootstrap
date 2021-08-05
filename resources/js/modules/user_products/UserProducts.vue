<template>
    <div class="">
    
        <div class="card">
            <div class="card-header bg-primary"> My Products </div>
    
            <div class="card-body">
    
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-primary me-3" @click="openProductFormModal">Product  <font-awesome-icon icon="plus" /> </button>
                    <button type="button" class="btn btn-primary" @click="openCategoryFormModal"> Category  <font-awesome-icon icon="plus" /></button>
                </div>
    
            </div>
        </div>
    
        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info"> Products </div>
                    <Datatable @edit-product="editProduct" @destroy-product="destroyProduct" ref="productsTable" />
                </div>
    
            </div>
            <div class="col-md-4">
    
                <div class="card">
                    <div class="card-header bg-secondary text-white"> Categories {{categories.length}} </div>
                    <ul class="list-group">
                        <li v-for="category in categories" :key="category.id" class="list-group-item d-flex justify-content-between">
                            <div>{{category.name}}</div>
                            <div>
                                <button type="button" @click="editCatetory( category)" class="btn btn-sm btn-success ">
                                                                            <font-awesome-icon icon="edit" /></button>
                                <button type="button" @click="deleteCatetory( category)" class="btn btn-sm btn-danger ms-2">
                                                                            <font-awesome-icon icon="trash" /></button>
                            </div>
                        </li>
                    </ul>
                </div>
    
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
                <ProductForm :product="product" :categories="categories" @products-change="productsChange" />
            </div>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue'
import { Modal } from 'bootstrap';
import ProductForm from './Form.vue';
import CategoryForm from '../categories/Form.vue';
import Datatable from './Datatable.vue';
import ArrayHelper from '../../ArrayHelper.js';
import Swal from 'sweetalert2';
export default {
    components: { ProductForm, CategoryForm, Datatable },
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
            category: null, // edit this category
            product: null,
        }
    },
    methods: {
        openCategoryFormModal() {
            this.category = null;
            this.categoryFormModal.show();
        },
        openProductFormModal() {
            this.product = null;
            this.productFormModal.show();
        },
        categoryAdded(newCategory) {
            this.categories.push(newCategory);
            this.categories = ArrayHelper.sortByString(this.categories, 'name');
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
            Swal.fire({
                title: '<strong class="text-danger">Are you sure?</strong>',
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`categories/${category.id}`)
                        .then((response) => {
                            if ('deleted' in response.data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: `Category deleted`,
                                    timer: 1000
                                });


                                this.categories = this.categories.filter((categoryItem) => { return categoryItem.id != category.id })
                            }
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                }
            });

        },
        productsChange() {
            this.$refs.productsTable.changePage(1);
            this.productFormModal.hide();
        },
        editProduct(product) {
            this.product = product;
            this.productFormModal.show();
        },
        destroyProduct(product) {


            Swal.fire({
                title: '<strong class="text-danger">Are you sure?</strong>',
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`user/products/${product.id}`).then((response) => {

                        if ('success' in response.data) {
                            Swal.fire({
                                icon: 'success',
                                title: `Product deleted`,
                                timer: 1000
                            });

                            this.$refs.productsTable.changePage(1);
                        }

                    }).catch((error) => {
                        console.error(error);
                    });
                }
            });

        }
    }

}
</script>