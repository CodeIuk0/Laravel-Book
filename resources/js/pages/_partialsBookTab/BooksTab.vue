<template>
    <div class="books-container" v-loading="isLoaded">
        <el-scrollbar>
            <template v-if="Books.length > 0">
            <template v-for="(book_, index) in User.books">
                <AppBookView :book="book_" >
                    <template #button-action>
                        <el-popconfirm title="Are you sure ?" @confirm="()=>removeBookInUserLibrary_(book_)">
                            <template #reference>
                                <el-button type="danger"> <el-icon><Plus /></el-icon>Remove</el-button>
                            </template>
                          </el-popconfirm>
                    </template>
                    <template #book-rate>
                    <el-rate v-model="bookRate[book_.id]" :colors="colorsRate" @change="(rate)=>setBookRate(book_.id,rate)" />
                </template>
                </AppBookView>
            </template>
            <template v-if=" User.books.length > 0">
            <el-divider />
        </template>
        <template v-for="(book_, index) in Books">
            <AppBookView :book="book_" >
                <template #button-action>
                    <el-button type="success" @click="addBookInUserLibrary_(book_)"> <el-icon><Plus /></el-icon>Add</el-button>
                </template>
            </AppBookView>
        </template>
    </template>
    <template v-else>
        <el-empty description="No books" :image-size="400" image="http://localhost:8000/NoFound.svg">
            <el-button type="primary"  @click="refreshBooks" >Refresh</el-button>
        </el-empty>
      </template>
        </el-scrollbar>
    </div>
</template>

<script>
import { mapActions,mapGetters } from 'vuex';
import FETCHING from "../../constant/fetch";
import AppBookView from './BookView.vue';

import store from "../../sotre/sotre"

import { Plus } from '@element-plus/icons-vue';

    export default {
        name:"AppBooksTab",
        components: {
            Plus,
            AppBookView
        },

        data() {
             return {
                colorsRate: ['#99A9BF', '#F7BA2A', '#FF9900'],
                bookRate: {},
                collapseDescription: [],
            }
        },

        computed: {
        ...mapGetters(['User','isBooksStateFetched','Books']),

        isLoaded() {
            return (this.isBooksStateFetched==FETCHING.STARTED_FETCH || this.isBooksStateFetched==FETCHING.NO_FETCH);
        }
    },

    methods: {
        onTabEntry() {
            for(const bookComment of this.User.comments) {
               this.bookRate[bookComment.book_id] = bookComment.note
            }
        },
        ...mapActions(['addBookInUserLibrary','removeBookInUserLibrary','loadBooks','setUserBookRate']),

        async setBookRate(bookId,rate) {
            store.commit("setUserBookRate",{bookId,rate});
            await this.setUserBookRate({bookId,rate});
        },

        async removeBookInUserLibrary_(Book) {
            let bookIndex = this.User.books.map(function(book,index){
                return (book.id===Book.id) ? index:null
            }).filter(v=>v!=null)[0];

            store.commit("removeItemBookInUser",bookIndex);
            store.commit("pushBook",Book);

            await this.removeBookInUserLibrary(Book.id)
        },
        async addBookInUserLibrary_(Book) {
            let bookIndex = this.Books.map(function(book,index){
                return (book.id===Book.id) ? index:null
            }).filter(v=>v!=null)[0];

            store.commit("removeItemBook",bookIndex);
            store.commit("pushBookInUser",Book);

            console.log("go add book: ",Book);

           await this.addBookInUserLibrary(Book.id)
        },

        refreshBooks() {
            this.loadBooks();
        }
    }
    }
</script>

<style lang="scss" scoped>

.books-container {
    height:inherit;
    gap: 15px;
    display: flex;
    flex-direction: column;

    .el-scrollbar
    {
    :deep(.el-scrollbar__view)
    {
        display: flex;
    flex-direction: column;
    padding-right: 25px;
    gap: 15px;
    }
}
}

</style>
