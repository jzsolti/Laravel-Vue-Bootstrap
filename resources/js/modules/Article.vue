<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Article</div>

          <div class="card-body">body</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from '../components/Loading.vue';
export default {
    componnets: {
        Loading
    },
    mounted() {
console.log( this.$route.params.id );

    },
    data() {
        return {
            loaded: false,
            article: null
        }
    },
}
</script>