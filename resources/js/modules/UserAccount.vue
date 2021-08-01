<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>User Account</span> <span class="badge bg-info text-dark">Created: {{created}}</span>
                    </div>
    
                    <div class="card-body">
    
                        <form method="POST" @submit.prevent="onSubmit">
    
                            <div class="form-group  mb-3">
                                <label for="name">Name</label>
                                <input v-model.trim="form.name" type="text" class="form-control" :class="inputClass('name')" />
                                <div class="invalid-feedback" v-if="errors.name">
                                    {{ errors.name[0] }}
                                </div>
                            </div>
    
                            <div class="form-group  mb-3">
                                <label for="email">E-Mail</label>
                                <input v-model.trim="form.email" type="text" class="form-control" :class="inputClass('email')" />
                                <div class="invalid-feedback" v-if="errors.email">
                                    {{ errors.email[0] }}
                                </div>
                            </div>
    
                            <div class="form-group  mb-3">
                                <label for="password">Password</label>
                                <input v-model.trim="form.password" type="password" class="form-control" :class="inputClass('password')" />
                                <div class="invalid-feedback" v-if="errors.password">
                                    {{ errors.password[0] }}
                                </div>
                            </div>
    
                            <div class="form-group  mb-3">
                                <label for="password">password_confirmation</label>
                                <input v-model.trim="form.password_confirmation" type="password" class="form-control" />
                            </div>
    
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" v-if="!disabled">Save </button>
    
                                <button class="btn btn-primary" type="button" disabled="disabled" v-if="disabled">
                                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Sending...</span>
                                                                                    </button>
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

import Swal from 'sweetalert2';
export default {
    mounted() {
        axios.get('user-account/get-user')
            .then((response) => {
                this.form.name = response.data.name;
                this.form.email = response.data.email;
                this.created = response.data.created;
            })
            .catch((error) => {
                console.error(error);
            });
    },
    data() {
        return {
            disabled: false,
            created: '',
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            errors: {}
        }
    },
    methods: {
        onSubmit() {
            this.disabled = true;

            axios.post('user-account/update', this.form)
                .then((response) => {
                    if ('success' in response.data) {
                        this.errors = {};
                        this.disabled = false;

                        Swal.fire({ icon: 'success', title: 'Updated', timer: 1000 });
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
            this.status = true;
            this.disabled = false;
            this.errors = {};
            this.form = {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
        }
        
    }
}
</script>