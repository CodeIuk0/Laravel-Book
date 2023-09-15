<template>
    <el-card shadow="never"><div class="header-container"> {{  $props.book.title }}
        <div class="rate-container">
            <slot name="book-rate"></slot>

         <slot name="button-action"></slot>

    </div>
</div>
    <el-collapse v-model="collapseDescription[book.id]" @change="handleChange">
        <el-collapse-item title="Description" :name="$props.book.title">
            <div>
                {{ $props.book.summary }}
              </div>

        </el-collapse-item>
    </el-collapse>
    <el-text tag="p">{{ $props.book.editors }} {{ isSameUserAuhtors($props.book.editors) }}</el-text>
    </el-card>
</template>

<script>
import { mapActions,mapGetters } from 'vuex';
    export default {
         name: "AppBookView",
         data() {
                 return {
                    bookRate: [],
                    collapseDescription: []
                 }
         },
         props: {
           book: {
            type:Object
           },
           isAdd: {
            type:Boolean

           }
         },
         computed: {
            ...mapGetters(['User']),
         },
         methods: {
            isSameUserAuhtors(authorName) {
                return this.User.name === authorName ? " ( You ) ": null;
            }
         }
    }
</script>

<style lang="scss" scoped>

.el-card {
    border-radius: 8px;
    min-height: 60px;
    color: var(--el-color-primary);
    font-size: 1.4em;
    padding: 0;
    display: flex;
    align-items: center;
    :deep(.el-card__body)
    {
        display: flex;
        flex-direction: column;
        gap: 15px;
        width: 100%;

        .rate-container {
            display: flex;
            gap: 10px;

            button
            {
                span {
                    display: flex;
                    gap: 10px;
                }
            }
        }
    }

    .header-container {
        display: flex;
        justify-content: space-between;
    }
}

</style>
