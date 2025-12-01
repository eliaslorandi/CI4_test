class LoginForm {
    constructor() {
        this.form = document.getElementById('loginForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.alertError = document.getElementById('alertError');
        this.alertSuccess = document.getElementById('alertSuccess');
        this.errorList = document.getElementById('errorList');
        this.successMessage = document.getElementById('successMessage');
        
        this.init();
    }

    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.form.querySelectorAll('input[type="email"], input[type="password"]').forEach(input => {
            input.addEventListener('input', () => {
                FormValidator.clearFieldError(input.id);
                this.validateFieldInReal(input);
            });
        });

        this.checkUrlSuccess();
    }

    checkUrlSuccess() {
        if (UrlHelper.hasParam('success')) {
            const message = UrlHelper.getParam('success');
            AlertManager.showSuccess(this.alertSuccess, this.successMessage, message);
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        FormValidator.clearFormErrors(this.form);
        AlertManager.hideAlert(this.alertError);
        
        const formData = new FormData(this.form);
        const data = {
            email: formData.get('email'),
            password: formData.get('password'),
            remember: formData.get('remember') ? 1 : 0
        };
        
        const errors = this.validateForm(data);
        if (Object.keys(errors).length > 0) {
            AlertManager.showError(this.alertError, this.errorList, errors);
            return;
        }
        
        ButtonManager.setLoading(this.submitBtn, true);
        
        const result = await FetchHelper.post(AppConfig.api.auth.login, data);
        
        if (result.success) {
            AlertManager.showSuccess(this.alertSuccess, this.successMessage, result.message);
            Constants.emit(Constants.EVENTS.USER_LOGGED_IN, { timestamp: Date.now() });
            setTimeout(() => {
                window.location.href = result.redirect || AppConfig.redirects.dashboard;
            }, AppConfig.timeouts.redirect);
        } else {
            AlertManager.showError(this.alertError, this.errorList, result.errors || { general: AppConfig.messages.errors.general });
        }
        
        ButtonManager.setLoading(this.submitBtn, false);
    }

    validateForm(data) {
        const errors = {};
        const config = AppConfig.validation;

        const email = data.email?.trim();
        if (!email) {
            errors.email = 'Email é obrigatório';
        } else if (!FormValidator.isValidEmail(email)) {
            errors.email = 'Email inválido';
        }

        const password = data.password;
        if (!password) {
            errors.password = 'Senha é obrigatória';
        } else if (password.length < config.password.min) {
            errors.password = 'Senha inválida';
        }

        return errors;
    }

    validateFieldInReal(input) {
        const name = input.id;
        const value = input.value.trim();
        const config = AppConfig.validation;
        
        if (!value) return;

        let error = '';

        if (name === 'email') {
            if (!FormValidator.isValidEmail(value)) error = 'Email inválido';
        } else if (name === 'password') {
            if (value.length < config.password.min) error = `Mínimo ${config.password.min} caracteres`;
        }

        if (error) {
            FormValidator.showFieldError(name, error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new LoginForm();
});
