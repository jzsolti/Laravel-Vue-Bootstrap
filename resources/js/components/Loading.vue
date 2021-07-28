<template>
    <div class="d-flex justify-content-center">
        <div class="spinner-border"   role="status" v-bind:style="spinerStyle">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Loading',
    props: {
        size: {
            type: Number,
            defaul: 1
        }
    },
    computed: {
      spinerStyle () {
        return { width: `${this.size}rem`, height: `${this.size}rem` }
      }
    }
}
</script>