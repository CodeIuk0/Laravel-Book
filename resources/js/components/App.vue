<template>
    <div class="common-layout">
        <router-view></router-view>
      </div>
</template>

<script>
    export default {
        name:"App"
}
</script>

<style>
@import "../../sass/app.scss";
</style>

<style lang="scss" scoped >
@import "../../sass/app.scss";
#app  {

    .common-layout
    {
        height:100%;
        width:100%;
    }
}
</style>
