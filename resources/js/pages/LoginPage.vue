<template>
    <div class="login-layout">
    <AppLogin/>
      </div>
</template>

<script>
import AppLogin from "../components/Login.vue"

export default {
    name:"AppLoginPage",
    components: {
        AppLogin
    },

}
</script>


<style>
@import "../../sass/app.scss";
</style>

<style lang="scss" scoped >
@import "../../sass/app.scss";
.login-layout  {
    height: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
