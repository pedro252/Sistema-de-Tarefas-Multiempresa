axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

axios.interceptors.request.use(function (config) {
    const token = localStorage.getItem('jwt_token');
    if (token) {
        config.headers.Authorization = 'Bearer ' + token;
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response && error.response.status === 401) {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('user');
        window.location.href = '/';
    }
    return Promise.reject(error);
});

function getErrorMessage(error) {
    if (error.response && error.response.data) {
        if (typeof error.response.data === 'string') {
            return error.response.data;
        }
        if (error.response.data.error) {
            return error.response.data.error;
        }
        if (error.response.data.message) {
            return error.response.data.message;
        }
        if (error.response.data.errors) {
            return Object.values(error.response.data.errors).flat().join(', ');
        }
    }
    return error.message || 'Erro desconhecido';
}

function showSuccess(message) {
    alert(message);
}

function showError(message) {
    alert('Erro: ' + message);
}

function setAuthData(data) {
    if (data && data.access_token && data.user && data.user.name) {
        localStorage.setItem('jwt_token', data.access_token);
        localStorage.setItem('user', JSON.stringify(data.user));
        return true;
    } else {
        showError('Dados de autenticação inválidos');
        return false;
    }
}

function clearAuthData() {
    localStorage.removeItem('jwt_token');
    localStorage.removeItem('user');
}

function checkAuth() {
    const token = localStorage.getItem('jwt_token');
    const user = localStorage.getItem('user');
    
    if (token && user) {
        try {
            const parsedUser = JSON.parse(user);
            if (parsedUser && parsedUser.name) {
                return parsedUser;
            } else {
                clearAuthData();
                return null;
            }
        } catch (error) {
            console.error('Erro ao parsear user do localStorage:', error);
            clearAuthData();
            return null;
        }
    }
    return null;
}
