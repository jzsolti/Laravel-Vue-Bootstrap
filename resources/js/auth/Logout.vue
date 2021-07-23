<script>
export default {
    mounted() {
        this.$nextTick(function() {
            axios.post('logout').then((response) => {
                localStorage.removeItem('isAuthenticated')
                this.$store.commit('logout')

                this.$router.push('/')
            }).catch((error) => {
                console.error(error);
            });
        })
    },
}
</script>