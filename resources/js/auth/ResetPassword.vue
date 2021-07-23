<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Reset password</div>
    
                    <div class="card-body">
    
                        <div class="alert alert-success" v-if="status">
                            {{ this.status }}
                        </div>
    
                        <form method="POST" @submit.prevent="submitResetPassword">
    
                            <div class="form-group">
                                <label for="email">E-Mail</label>
                                <input v-model.trim="form.email" type="text" class="form-control" :class="inputClass('email')" />
                                <div class="invalid-feedback" v-if="errors.email">
                                    {{ errors.email[0] }}
                                </div>
                            </div>
    
                            <div class="form-group">
    
                                <button type="submit" class="btn btn-primary" v-if="!disabled">Send Password Reset Link</button>
    
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
import { useRoute } from 'vue-router';
export default {

    data() {
        return {
            disabled: false,
            status: false,

            form: {
                email: '',
            },
            errors: {}
        }
    },

    methods: {
        submitResetPassword() {
            this.disabled = true;

            axios.post('password/send-resetlink-email', this.form).then((response) => {
                if ('status' in response.data) {
                    this.status = response.data.status;
                }
                this.disabled = false;
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
            if (Object.keys(this.errors).length === 0) {
                return '';
            } else if (inputName in this.errors) {
                return 'is-invalid';
            } else {
                return 'is-valid';
            }
        }
    }
}
</script>