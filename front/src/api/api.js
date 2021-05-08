import axios from 'axios'
import * as env from 'app/conf'

export default {
    ax(url, objects){
      debugger;
      return this.callApi(url, objects)
    },

    callApi (url, objects) {
      debugger;
      const [method, path] = url.split(':');
      const config = {
        method,
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        },
        url: `${env.API_URL}${path}`,
        data: objects,
      };
      return axios(config);
    },
}
