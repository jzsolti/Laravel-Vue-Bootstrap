<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{cardHeaderText}} </div>
    
                    <div class="card-body">
    
                        <Loading size="3" v-if="!loaded" />
    
                        <form method="POST" @submit.prevent="onSubmit" v-else>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for='title'>Title</label>
                                        <input v-model.trim="form.title" type="text" class="form-control" :class="inputClass('title')" name="title" />
                                        <div class="invalid-feedback" v-if="errors.title">
                                            {{ errors.title[0] }}
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <label for='lead'>Lead in</label>
                                        <textarea v-model="form.lead" class="form-control" :class="inputClass('lead')" name="lead"></textarea>
                                        <div class="invalid-feedback" v-if="errors.lead">
                                            {{ errors.lead[0] }}
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <label for='content'>Content</label>
                                        <textarea v-model="form.content" class="form-control" :class="inputClass('content')" name="content"></textarea>
                                        <div class="invalid-feedback" v-if="errors.content">
                                            {{ errors.content[0] }}
                                        </div>
                                    </div>
    
                                    <label for='image'>Image</label>
                                    <div class="custom-file">
                                        <label class="custom-file-label">Choose file</label>
                                        <input @change="onFileChange" name="image" type="file" :class="inputClass('image')" class="custom-file-input" />
                                        <div class="invalid-feedback" v-if="errors.image">
                                            {{ errors.image[0] }}
                                        </div>
                                    </div>
    
                                    <img v-if="imagePreView" class="img-thumbnail mt-2" :src="imagePreView" />
    
                                </div>
                                <div class="col-md-6">
    
                                    <ul class="list-group labels">
                                        <li v-for="label in labels" class="list-group-item">
                                            <input type="checkbox" :id="'label-'+label.id" v-model="form.labels" :value="label.id" />
                                            <label :for="'label-'+label.id" class="ps-2"> {{ label.name }}</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
    
                            <div class="d-flex justify-content-between  mt-3">
                                <button v-if="articleId != null" type="button" class="btn btn-danger" @click.prevent="delete">Delete </button>
                                <div>
                                    <button type="submit" class="btn btn-primary" v-if="!disabled">Save</button>
    
                                    <button class="btn btn-primary" type="button" disabled="disabled" v-if="disabled">
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Sending...</span>
                                                    </button>
                                </div>
                            </div>
    
                        </form>
    
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FormHelper from '../FormHelper.js';
import Loading from '../components/Loading.vue';
import Swal from 'sweetalert2';
export default {
    components: {
        Loading
    },
    mounted() {
        if (this.$route.params.id) {
            this.articleId = this.$route.params.id;
        } 

        axios.get(`labels`)
            .then((response) => {
                this.labels = response.data.data;
            })
            .catch((error) => {
                console.error(error);
            }).then(() => {
                if (this.articleId == null) {
                    return null;
                }

                return axios.get(`user/articles/${this.articleId}`);
            }).then((response) => {
                if (response) {
                    let responseData = response.data.data;
                    this.form.title = responseData.title;
                    this.form.lead = responseData.lead;
                    this.form.content = responseData.content;
                    this.imagePreView = responseData.image_src;
                    this.form.labels = responseData.labels;
                }
            }).then(() => {
                this.loaded = true;
            })
            .catch((error) => {
                if (error.response && error.response.status === 404) {
                    this.$router.push('/_404')
                } else {
                    console.error(error);
                }
            });

    },
    data() {
        return {
            disabled: false,
            articleId: null,
            imagePreView: null,
            labels: [],
            loaded: false,
            form: {
                title: '',
                lead: '',
                content: '',
                image: null,
                labels: []
            },
            errors: {}
        }
    },
    computed: {
        cardHeaderText() {
            return (this.articleId != null) ? 'Edit article' : 'New article'
        },
        action(){
             return (this.articleId != null) ? `user/articles/${this.articleId}` : `user/articles/create`
        }
    },
    methods: {
        onSubmit() {
            this.disabled = true;

            let formData = new FormData();
            for (const [key, value] of Object.entries(this.form)) {
                if (key != 'labels' && value != 'undefined' && value != null) {
                    formData.append(key, value);
                }
            }
            for (const [key, value] of Object.entries(this.form.labels)) {
                formData.append('labels[]', value);
            }

            if (this.articleId != null) {
                formData.append('_method', 'put');
            }


            axios.post(this.action, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then((response) => {
                    this.disabled = false;
                    this.errors = {};
                    if ('data' in response.data && this.articleId  == null) {
                        Swal.fire({
                            icon: 'success',
                            title: `New article created`,
                            timer: 1000
                        });
                        this.articleId = response.data.data.id;
                       
                        this.$router.push(`/user/articles/edit/${response.data.data.id}`)
                    }else {
                       
                        Swal.fire({
                            icon: 'success',
                            title: `Article updated`,
                            timer: 1000
                        });
                    }
                })
                .catch((error) => {
                    this.disabled = false;
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error(error);
                    }
                });
        },
        delete() {
            Swal.fire({
                title: '<strong class="text-danger">Are you sure?</strong>',
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`user/articles/${this.articleId}`)
                        .then((response) => {
                            if ('deleted' in response.data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: `Article deleted`,
                                    timer: 1000
                                });
                                this.$router.push(`/user/articles/`)
                            }
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                }
            });
        },
        onFileChange(event) {
            let file = event.target.files[0];
            this.imagePreView = URL.createObjectURL(file);
            this.form.image = file;
        },
        inputClass(inputName) {
            return FormHelper.inputClass(inputName, this.errors)
        }
    }
}
</script>