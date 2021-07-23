<template>
    <div class="form-group">
        <label for="name">{{ label }}</label>
        <input  type="text" class="form-control" />
       
    </div>
</template>

<script>
export default {
    props: ['name','label'],
    //inject: ['errors','form'],
    created() {
        //console.log(`Injected property: ${name}`) // > Injected property: John Doe
    }
}
</script>
