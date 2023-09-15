<template>
    <div class="register-layout">
    <AppRegister/>
      </div>
</template>

<script>
import AppRegister from "../components/Register.vue"

export default {
    name:"AppLoginPage",
    components: {
        AppRegister
    },
}
</script>


<style>
@import "../../sass/app.scss";
</style>

<style lang="scss" scoped >
@import "../../sass/app.scss";
.register-layout  {
    height: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
