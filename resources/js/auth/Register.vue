<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Registration</div>
    
                    <div class="card-body">
    
                        <div className="alert alert-success" v-if="status">
                            A fresh verification link has been sent to your email address. Check your email for a verification link.
                        </div>
    
                        <form method="POST" @submit.prevent="onSubmit">
    
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model.trim="form.name" type="text" class="form-control" :class="inputClass('name')" name="name" />
                                <div class="invalid-feedback" v-if="errors.name">
                                    {{ errors.name[0] }}
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label for="email">E-Mail</label>
                                <input v-model.trim="form.email" type="text" class="form-control" :class="inputClass('email')" name="email" />
                                <div class="invalid-feedback" v-if="errors.email">
                                    {{ errors.email[0] }}
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input v-model.trim="form.password" type="password" class="form-control" :class="inputClass('password')" name="password" />
                                <div class="invalid-feedback" v-if="errors.password">
                                    {{ errors.password[0] }}
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label for="password">password confirmation</label>
                                <input v-model.trim="form.password_confirmation" type="password" class="form-control" name="password_confirmation" />
                            </div>
    
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" v-if="!disabled">Save</button>
    
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
export default {

    data() {
        return {
            disabled: false,
            status: false,
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
            axios.post('register', this.form)
                .then((response) => {
                    if ('success' in response.data) {
                        this.resetForm()
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
        inputClass(inputName) {
            if (Object.keys(this.errors).length === 0) {
                return '';
            } else if (inputName in this.errors) {
                return 'is-invalid';
            } else {
                return 'is-valid';
            }
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