<template>
    <div class="container">
        <header>
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <router-link to="/" class="nav-item nav-link">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/articles" class="nav-item nav-link">Articles</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/login" class="nav-item nav-link" v-if="!this.$store.state.isAuthenticated">Login</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/register" class="nav-item nav-link" v-if="!this.$store.state.isAuthenticated">Register</router-link>
                </li> 
                <li class="nav-item">
                    <router-link to="/user/account" class="nav-item nav-link" v-if="this.$store.state.isAuthenticated">Account</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/user/articles" class="nav-item nav-link" v-if="this.$store.state.isAuthenticated">My Articles</router-link>
                </li>
                 <li class="nav-item">
                    <router-link to="/logout" class="nav-item nav-link" v-if="this.$store.state.isAuthenticated">Logout</router-link>
                </li>
            </ul>
        </header>
        <router-view> </router-view>
    </div>
</template>
 
<script>
export default {}
</script>