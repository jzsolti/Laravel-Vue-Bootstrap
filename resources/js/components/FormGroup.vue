<template>
    <div class="form-group">
        <label :for="name">{{ label }}</label>
    
        <input v-model.trim="form[name]" type="text" class="form-control" />
        <div class="invalid-feedback" v-if="errors[name]">
            {{ errors[name][0] }}
        </div>
    </div>
</template>

<script>
export default {
    //name: 'FormGroup',
    props: ['name', 'label'],
    inject: ['errors', 'form'],
    created() {
        console.log(`Injected property: ${form[name]}`) // > Injected property: John Doe
    }
}
</script>
