<template>
    <div class="container">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Laravel8-Vue3 <span class="badge bg-secondary">Bootstrap5</span></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <router-link to="/" class="nav-item nav-link">Home</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/articles" class="nav-item nav-link">Articles</router-link>
                            </li>
                            <li class="nav-item" v-if="!this.$store.state.isAuthenticated">
                                <router-link to="/login" class="nav-item nav-link">Login</router-link>
                            </li>
                            <li class="nav-item" v-if="!this.$store.state.isAuthenticated">
                                <router-link to="/register" class="nav-item nav-link">Register</router-link>
                            </li>
    
                            <li class="nav-item dropdown" v-if="this.$store.state.isAuthenticated">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <font-awesome-icon icon="user" />
                  </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li>
    
                                        <router-link to="/user/account" class="dropdown-item">Account</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/user/articles" class="dropdown-item">My Articles</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/user/products" class="dropdown-item">My Products</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/logout" class="dropdown-item">Logout</router-link>
                                    </li>
                                </ul>
                            </li>
    
    
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <router-view> </router-view>
    </div>
</template>

<script>
export default {
    beforeCreate(){
        
/* http://127.0.0.1:8000/user/articles
        axios.get(`user/status`)
            .then((response) => {
                if('logged_in' in response.data){
 localStorage.setItem('isAuthenticated', 1)
                        this.$store.commit('login')
                }else{
                    localStorage.removeItem('isAuthenticated')
                this.$store.commit('logout')
                }
            }) 
            .catch((error) => {
                console.error(error);
            });*/
        
    }
}
</script>