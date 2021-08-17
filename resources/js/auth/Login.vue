<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>
    
                    <div class="card-body">
    
                        <div class="alert alert-success" v-if="emailVerification">
                            After logging in, your email address will be confirmed.
                        </div>
    
                        <div className="alert alert-warning" v-if="needVefification">
                            Verify Your Email AddressBefore login, please check your email for a verification link.
                        </div>
    
                        <form method="POST" @submit.prevent="submitLogin">
    
                            <div class="form-group">
                                <label for="email">E-Mail</label>
                                <input v-model.trim="form.email" type="text" class="form-control" :class="inputClass('email')" name="email" />
                                <div class="invalid-feedback" v-if="errors.email">
                                    {{ errors.email[0] }}
                                </div>
                            </div>
    
                            <div class="form-group ">
                                <label for="password">Password</label>
                                <input v-model.trim="form.password" type="password" class="form-control" :class="inputClass('password')" name="password"/>
                                <div class="invalid-feedback" v-if="errors.password">
                                    {{ errors.password[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="remember" v-model="form.remember" class="form-check-input" />
                                    <label for="remember" class="form-check-label"> Remember me</label>
                                </div>
                            </div>
    
                            <div class="form-group">
    
                                <button type="submit" class="btn btn-primary" v-if="!disabled">Login</button>
    
                                <button class="btn btn-primary" type="button" disabled="disabled" v-if="disabled">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Sending...</span>
                                    </button>
                            </div>
    
                            <div class="form-group">
                                <router-link to="/password/reset-password" class="nav-item nav-link">Forgot Your Password?</router-link>
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
    mounted() {
        this.$nextTick(function() {

            if (this.$route.params.token) {

                axios.post('verify/email', { token: this.$route.params.token })
                    .then((response) => {
                        if ('success' in response.data) {
                            this.emailVerification = true;
                        }
                    }).catch((error) => {
                        console.error(error);
                    });
            }
        })
    },
    data() {
        return {
            disabled: false,
            emailVerification: false,
            needVefification: false,
            form: {
                email: '',
                password: '',
                remember: false
            },
            errors: {}
        }
    },

    methods: {
        submitLogin() {
            this.disabled = true;
            
            axios.get('/sanctum/csrf-cookie')
                .then(() => {
                    return axios.post('login', this.form);
                }).then((response) => {

                    if ('logged_in' in response.data) {
                        localStorage.setItem('isAuthenticated', 1)
                        this.$store.commit('login')

                        //redirect 
                        this.$router.push('/user/account')
                    } else if ('need_vefification' in response.data) {
                        this.needVefification = true;
                        this.form.password = '';
                    }
                    this.disabled = false;
                }).catch((error) => {
                    this.disabled = false;
                    this.form.password = '';
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