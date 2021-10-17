import axios from 'axios';
import router from '../router/index';

const API_URL = process.env.VUE_APP_API_URL;

class AuthService {
  login = (userInfo: any) => {
    const axiosResponse = axios.post(`${API_URL}/login`, {
      email: userInfo.email,
      password: userInfo.password,
    })
      .then((response: any) => {
        localStorage.setItem('userToken', JSON.stringify(response.data.token));
        router.push('/');

        return true;
      })
      .catch((error) => {
        if (error.response.status === 404) {
          return 'Usuário não encontrado';
        }

        if (error.response.status === 401) {
          return 'Email ou senha incorretos';
        }

        console.log(error);
        return 'Houve algum erro. Favor entrar em contato com o administrador';
      });

    return axiosResponse;
  }

  logout = async () => {
    const response: any = await axios.post(`${API_URL}/logout`);

    if (response.status === 204) {
      localStorage.removeItem('user');

      return true;
    }

    return response;
  }

  register = async (firstName: string, lastName: string, email: string, password: string) => {
    const response: any = await axios.post(`${API_URL}/sign-up`, {
      firstName,
      lastName,
      email,
      password,
    });

    if (response.status === 200) {
      localStorage.setItem('user', JSON.stringify(response.data.token));

      return true;
    }

    return response;
  }
}

export default new AuthService();
