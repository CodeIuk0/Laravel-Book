<template>
    <div class="register-container">
        <el-form
        ref="formRegister"
        :model="registerForm"
        :rules="registerRules"
        label-width="120px"
        label-position="top"
        class="register-form gap-20"
        status-icon
        v-loading="isLoaded"
        >

        <div class="w-100">

        <el-text class="w-100 mb-4" tag="b" size="large" >Enregistrez-vous</el-text>
        <el-form-item label="Name" prop="name" >
            <el-input v-model="registerForm.name" />
        </el-form-item>
        <el-form-item label="Email" prop="email">
        <el-input v-model="registerForm.email" />
        </el-form-item>
        <el-form-item label="Password" prop="password" >
        <el-input v-model="registerForm.password" type="password" show-password />
        </el-form-item>
        <el-form-item label="Confrom password" prop="confirmPassword" >
            <el-input v-model="registerForm.confirmPassword" type="password" show-password />
        </el-form-item>
    </div>
    <div class="d-flex justify-content-between flex-column w-100">

        <div class="d-flex justify-content-between ">
            <el-checkbox v-model="registerForm.acceptCondition" label="Accept all conditions" />

            <RouterLink class="go-login-link" to="/login" >Login !</RouterLink>
                </div>



        <el-button type="primary" style="border-radius:5px" @click="submitForm('formRegister')">Register</el-button>
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
    name:"AppRegister",
    data() {
        return {
            registerForm: {
            name: '',
            email: '',
            password: '',
            confirmPassword: '',
            acceptCondition: false
            },
            registerRules: {
                name: [
            { required: true, message: 'Please enter your name', trigger: 'blur' },
            { min: 6, message: 'Length should be 6+', trigger: 'blur' },
        ],
            email: [
            { required: true, message: 'Please enter your email', trigger: 'blur' },
            { validator: this.validateEmail, trigger: 'blur' }
        ],
            password: [
                { required: true, message: 'The password is required', trigger: 'blur' },
                    { min: 6, message: 'Length should be 6+', trigger: 'blur' },
            ],
            confirmPassword: [
                {required: true, validator: this.confirmSamePassword, trigger: 'blur' }
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
        ...mapActions(['register']),

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

    confirmSamePassword(rule, value, callback)
    {
        if(this.registerForm.confirmPassword != this.registerForm.password)
        {
            return callback(new Error('The password must be the same.'))
        }

        if(this.registerForm.confirmPassword == '')
        {
            return callback(new Error('Please enter the password.'))
        }

        return callback();
    },

    async submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
           this.register(this.registerForm).then(userResponse=>{
            console.log("userResponse = ",userResponse)
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

/*

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



*/
}
</script>


<style lang="scss" scoped>
@import "../../sass/app.scss";

.register-container
{
    width:380px;
    box-shadow: 0 0 10px 2px rgba(0,0,0,0.5);
    padding: 20px;
    height: fit-content;
    border-radius: 10px;

.register-form {
    display: flex;
    flex-direction: column;
    align-items: end;
}

.go-login-link {
    text-decoration: none;
    text-underline-offset: 4px;
    &:hover {
        text-decoration: underline;
    }
}
}
</style>

