

import axios from 'axios';

const axiosConfig = {
  timeout: 30000,
  baseURL:"/api",
  headers: {
    common:
    {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': null
    }
    }
}

let token = document.head.querySelector('meta[name="csrf-token"]');



if (token) {
    axiosConfig.headers.common['X-CSRF-TOKEN'] = token.content;
}

const Axios = axios.create(axiosConfig)


export default Axios;
