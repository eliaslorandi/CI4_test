const AppConfig = {
    // API URLs
    api: {
        auth: {
            register: '/auth/register',
            login: '/auth/login',
            logout: '/auth/logout'
        },
        tasks: {
            list: '/tasks',
            create: '/tasks',
            update: '/tasks/:id',
            delete: '/tasks/:id',
            complete: '/tasks/:id/complete',
            pending: '/tasks/:id/pending'
        },
        categories: {
            list: '/categories',
            create: '/categories',
            update: '/categories/:id',
            delete: '/categories/:id'
        },
        user: {
            profile: '/user/profile',
            edit: '/user/edit',
            update: '/user/update',
            delete: '/user/delete'
        }
    },

    // URLs de redirecionamento
    redirects: {
        login: '/auth/login',
        register: '/auth/register',
        dashboard: '/dashboard',
        logout: '/'
    },

    // Timeouts
    timeouts: {
        redirect: 1500,
        alertDisplay: 2000,
        formSubmit: 30000
    },

    // Validações
    validation: {
        name: {
            min: 3,
            max: 100
        },
        email: {
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        },
        password: {
            min: 8,
            max: 255
        }
    },

    // Mensagens
    messages: {
        errors: {
            general: 'Ocorreu um erro. Tente novamente.',
            network: 'Erro de conexão. Verifique sua internet.',
            timeout: 'A requisição demorou muito. Tente novamente.'
        },
        success: {
            register: 'Conta criada com sucesso! Redirecionando...',
            login: 'Login realizado com sucesso!',
            logout: 'Você foi desconectado.'
        }
    },

    // Classes CSS
    css: {
        invalid: 'is-invalid',
        valid: 'is-valid',
        hidden: 'd-none'
    },

    // Seletores DOM
    selectors: {
        form: '#registerForm, #loginForm',
        submitBtn: '#submitBtn',
        errorAlert: '#alertError',
        successAlert: '#alertSuccess',
        errorList: '#errorList',
        successMessage: '#successMessage'
    },

    // Debug
    debug: {
        enabled: false
    },

    // Helper para construir URLs
    buildUrl(path, params = {}) {
        let url = path;
        if (Object.keys(params).length > 0) {
            const queryString = Object.entries(params)
                .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
                .join('&');
            url += '?' + queryString;
        }
        return url;
    }
};
