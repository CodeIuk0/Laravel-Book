<template>
    <div class="login-container">
        <el-form
        ref="formLogin"
        :model="loginForm"
        :rules="loginRules"
        label-width="120px"
        label-position="top"
        class="login-form gap-20"
        status-icon
        v-loading="isLoaded"
        >

        <div class="w-100">

            <template v-if="disabledFromLogged">
                <el-text class="w-100 mb-4" tag="b" size="large" >Connectez-vous</el-text>
        <el-form-item label="Email" prop="email">
        <el-input v-model="loginForm.email" />
        </el-form-item>
        <el-form-item label="Password" prop="password" >
        <el-input v-model="loginForm.password" type="password" show-password />
        </el-form-item>
        </template>
        <template v-else>
            <el-text class="w-100 mb-4" tag="b" size="large">Deconnectez-vous</el-text>
        </template>
    </div>
    <div class="d-flex justify-content-between flex-column w-100">
        <template v-if="disabledFromLogged">
            <div class="d-flex justify-content-between ">
        <el-checkbox v-model="loginForm.remember" label="Remember" />

        <RouterLink class="go-register-link" to="/register" >Register now !</RouterLink>
            </div>
        <el-button type="primary" style="border-radius:5px" @click="submitForm('formLogin')">Login</el-button>
    </template>
    <template v-else>
        <el-button type="primary" style="border-radius:5px" @click="logoutUser()">Logout</el-button>
    </template>

    </div>
        </el-form>
      </div>
</template>

<script>
import { mapActions,mapGetters } from 'vuex';
import { ElMessage } from 'element-plus'
import FETCHING from "../constant/fetch"

import router from "../router";

export default {
    name:"AppLogin",
    data() {
        return {
            loginForm: {
            email: '',
            password: '',
            remember: false,
            },
            loginRules: {
            email: [
            { required: true, message: 'Please enter your email', trigger: 'blur' },
            { validator: this.validateEmail, trigger: 'blur' }
        ],
            password: [
                { required: true, message: 'The password is required', trigger: 'blur' },
                    { min: 6, message: 'Length should be 6+', trigger: 'blur' },
            ]}
        }
    },
    computed: {
        ...mapGetters(["User","isUserStateFetched"]),
        FETCHING: () => FETCHING,

        isLoaded() {
            return (this.isUserStateFetched==FETCHING.STARTED_FETCH || this.isUserStateFetched==FETCHING.NO_FETCH);
        },
        disabledFromLogged()
        {
            return !this.User;
        }
    },
    methods: {
        ...mapActions(['login','logout']),

        validateEmail(rule, value, callback) {
          //
    if (value === '') {
    callback(new Error('Please enter your email'));
    } else {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        callback(new Error('Invalid email format'));
    } else {
        callback();
    }
    }
    },

    logoutUser()
    {
       this.logout();
    },

    async submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
           this.login(this.loginForm).then(userResponse=>{

            if(!userResponse.success)
            {
                ElMessage.error(userResponse.data)
                return false;
            }else if(userResponse.success)
            {
                ElMessage({
                    message: `Bienvenu à toi ${this.User.name}.`,
                    type: 'success',
                    duration:2000
                })
                router.push({ name: 'dashboard' });
                return true;
            }
            return false;
           });
          } else
            return false;
        });
      },

}
}
</script>


<style lang="scss" scoped>
@import "../../sass/app.scss";

.login-container
{
    width:380px;
    box-shadow: 0 0 10px 2px rgba(0,0,0,0.5);
    padding: 20px;
    height: fit-content;
    border-radius: 10px;

.login-form {
    display: flex;
    flex-direction: column;
    align-items: end;
}

.go-register-link {
    text-decoration: none;
    text-underline-offset: 4px;
    &:hover {
        text-decoration: underline;
    }
}
}
</style>

