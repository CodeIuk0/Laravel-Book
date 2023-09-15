<template>
    <div class="book-user-container" v-loading="isLoaded">
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
        <el-button type="primary" @click="openDialog">Add new book</el-button>

        <el-dialog v-model="dialogTableVisible" width="450" :before-close="handleBeforeClose">
            <template #title>
                <div>
                    Add book
                </div>
            </template>
            <div  v-loading="isLoadedNewBook">
            <el-form
            ref="formRegister"
            :model="registerBookForm"
            :rules="registerRules"
            label-width="120px"
            label-position="top"
            class="register-form gap-20"
            status-icon

            >
            <el-form-item label="Title" prop="title" >
                <el-input v-model="registerBookForm.title" input-style="text-align: center" />
            </el-form-item>

            <el-form-item label="Number of pages" prop="pages">
                <el-input-number v-model="registerBookForm.pages" :min="1" :max="10" />
            </el-form-item>

            <el-form-item label="Summary" prop="summary">
                <el-input v-model="registerBookForm.summary" maxlength="545" show-word-limit type="textarea" />
            </el-form-item>

            <el-form-item label="Tag" prop="tag">
                <el-input v-model="registerBookForm.tags" input-style="text-align: center"  />
            </el-form-item>

            </el-form>

            <el-button type="primary" class="w-100 mp-4" @click="createNewBook">Create</el-button>
        </div>
        </el-dialog>
    </div>


</template>

<script>

import AppBookView from '../_partialsBookTab/BookView.vue';
import FETCHING from "../../constant/fetch";
import { Plus } from '@element-plus/icons-vue';
import { ElMessageBox } from 'element-plus'

import store from "../../sotre/sotre"

import { mapActions,mapGetters } from 'vuex';
    export default {
        name: "AppLibraryTab",
        data() {
            return {
                bookRate: [],
                colorsRate: ['#99A9BF', '#F7BA2A', '#FF9900'],
                dialogTableVisible: false,
                registerBookForm: {
                    title: '',
                    pages: '',
                    summary: '',
                    tags: '',
            },
            }
        },
        components: {
            AppBookView,
            Plus
        },
        computed: {
            ...mapGetters(['User','isBooksStateFetched','isNewBooksStateFetched','Books']),
            isLoaded() {
            return (this.isBooksStateFetched==FETCHING.STARTED_FETCH || this.isBooksStateFetched==FETCHING.NO_FETCH);
        },
        isLoadedNewBook() {
            return this.isNewBooksStateFetched==FETCHING.STARTED_FETCH;
        }
        },
        methods: {
            ...mapActions(['removeBookInUserLibrary','setUserBookRate','addLibraryBook']),
            onTabEntry() {
            for(const bookComment of this.User.comments) {
               this.bookRate[bookComment.book_id] = bookComment.note
            }
        },

        openDialog() {
               this.dialogTableVisible = true;
        },

        async createNewBook() {
            const Book = {
                "id": this.Books.length+1,
            "title": this.registerBookForm.title,
            "pages": this.registerBookForm.pages,
            "summary": this.registerBookForm.summary,
            "tags": this.registerBookForm.tags,
            "editors": this.User.name,
            }
            store.commit("pushBook",Book);

            await this.addLibraryBook(Book);

             this.dialogTableVisible = false;
            this.clearForm();
        },

        clearForm() {
            this.registerBookForm.title = ""
            this.registerBookForm.pages = 1
            this.registerBookForm.summary = ""
            this.registerBookForm.tags = ""
        },

        handleBeforeClose(done = () => {}) {
            ElMessageBox.confirm('Are you sure to close this dialog?')
                .then(() => {
                this.clearForm();
                done()
                })
            },
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
        }
    }
</script>

<style lang="scss" scoped>

.book-user-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;

    div {
        width:100%
    }

    button {
        width: 225px;
    }
}

</style>
