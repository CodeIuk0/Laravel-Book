<template>
    <div class="dashboard-common-layout">
        <el-tabs tab-position="left" v-model="defaultActivePad" class="demo-tabs" :before-leave="tabBeforeLeave">
            <el-tab-pane label="Dashboard" ref="tab-dashboard" name="tab-dashboard">
                <template #label>
        <span class="custom-tabs-label d-flex">
          <el-icon><House /> </el-icon>
          <span>Dashboard</span>
        </span>
      </template>
      <span>Dashboard</span>
    </el-tab-pane>

    <el-tab-pane label="Library" name="tab-librarys">
        <template #label>
<span class="custom-tabs-label d-flex">
  <el-icon><Collection /> </el-icon>
  <span>Library</span>
</span>
</template>
<AppLibraryTab ref="tab-librarys" />
</el-tab-pane>

<el-tab-pane label="Porfile" ref="tab-profile"  name="tab-profile">
    <template #label>
<span class="custom-tabs-label d-flex">
<el-icon><Avatar /> </el-icon>
<span>Porfile</span>
</span>
</template>
<span>Porfile</span>
</el-tab-pane>

<el-tab-pane label="Books" name="tab-books" >
    <template #label>
<span class="custom-tabs-label d-flex">
<el-icon><Notebook /> </el-icon>
<span>Books</span>
</span>
</template>
<AppBooksTab  ref="tab-books"  />
</el-tab-pane>

<el-tab-pane label="Settings" ref="tab-settings" name="tab-settings">
    <template #label>
<span class="custom-tabs-label d-flex">
<el-icon><Setting /> </el-icon>
<span>Settings</span>
</span>
</template>
<span>Settings</span>
</el-tab-pane>
<hr />
<el-tab-pane label="Settings" name="logout-tab-pane" disabled>
    <template #label>
<el-button type="primary" @click="Logout"><el-icon><ArrowLeftBold /></el-icon> Logout</el-button>
</template>
</el-tab-pane>
</el-tabs>

      </div>
</template>


<script>

import { mapActions,mapGetters } from 'vuex';
import { House,Notebook,Avatar,Collection,Setting,ArrowLeftBold } from '@element-plus/icons-vue'
import FETCHING from "../constant/fetch"

import { ElMessageBox } from 'element-plus'

import router from "../router";

import AppBooksTab from "./_partialsBookTab/BooksTab.vue"
import AppLibraryTab from "./_partialsLibrary/LibraryTab.vue"

export default {
    name: "AppDashboardPage",
    components: {
        House,
        Notebook,
        Avatar,
        Collection,
        Setting,
        ArrowLeftBold,
        AppBooksTab,
        AppLibraryTab
    },

    data() {
         return {
            defaultActivePad: "tab-dashboard"
         }
    },

    computed: {
        ...mapGetters(["User","isUserStateFetched",'isBooksStateFetched']),
    },

    methods: {
        tabBeforeLeave(activeName, oldActiveName){
            if(this.$refs[activeName].onTabEntry)
                this.$refs[activeName].onTabEntry()
        },
            ...mapActions(['login','logout']),

            Logout() {
                ElMessageBox.confirm('Are you sure to logout ?', 'Logout', {
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            }) .then(async () => {
                await this.logout();
                router.push({name:this.$route.meta.ifNoAuthRedirectTo, query: { from:  this.$route.name }})
            });
            }
        },
}
</script>


<style lang="scss" scoped>

@import "../../sass/app.scss";

.dashboard-common-layout {
    height: inherit;

    .el-tabs {
        height: inherit;



        :deep(.el-tabs__header) {
            width:200px;
            height: inherit;

            .el-tabs__nav {
                width: 100%;
                height: 100%;
                padding: 15px;
                gap: 15px;

                #tab-logout-tab-pane {
                    border-radius: 8px;
                    padding:0px;
                    display: flex;
                    cursor: initial;
                    flex-wrap: wrap;
                    button {
                        border-radius: 8px;
                        z-index: 1001;
                        width:100%;
                        height:100%;
                        span {
                            justify-content: space-between;
                            width: 100%;
                        }
                    }

                    &::before {
                        content: "";
                        border-top: 1px solid gray;
                        height: 24px;
                        margin-top: 10px;
                        width: 100%;
                    }
                }
                .el-tabs__item {
                    display: flex;
                    justify-content: center;
                    border-radius: 6px;

                    span {
                        width: 100%;
                        justify-content: space-between;

                        span {
                            width: 60%;
                            text-align: initial;
                        }
                    }

                    &.is-active {
                        background: var(--el-color-primary);
                        color:white;
                    }
                }

                .custom-tabs-label {
                    gap: 10px;
                    display: flex;
                    align-items: center;
                    i {
                        font-size: 1.5em;
                    }
                }
            }
        }

        :deep(.el-tabs__content)
        {
            height: inherit;
            padding: 15px;

            .el-tab-pane {
                background: rgba($el-color-info-light-1,0.1);
                box-shadow: 0 0 15px -5px var(--el-color-primary);
                border-radius: 10px;
                padding:15px;
                height: inherit;
            }
        }
    }
}

</style>
