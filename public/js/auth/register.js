class RegisterForm {
    constructor() {
        this.form = document.getElementById('registerForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.alertError = document.getElementById('alertError');
        this.alertSuccess = document.getElementById('alertSuccess');
        this.errorList = document.getElementById('errorList');
        this.successMessage = document.getElementById('successMessage');
        
        this.init();
    }

    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        const inputs = ['name', 'email', 'password', 'passwordConfirm'];
        inputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', () => {
                    FormValidator.clearFieldError(inputId);
                    this.validateFieldInReal(input);
                });
                input.addEventListener('blur', () => {
                    this.validateFieldInReal(input);
                });
            }
        });
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        FormValidator.clearFormErrors(this.form);
        AlertManager.hideAlert(this.alertError);
        
        const formData = new FormData(this.form);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirm: formData.get('passwordConfirm')
        };
        
        const errors = this.validateForm(data);
        if (Object.keys(errors).length > 0) {
            Object.entries(errors).forEach(([field, message]) => {
                const fieldId = field === 'password_confirm' ? 'passwordConfirm' : field;
                FormValidator.showFieldError(fieldId, message);
            });
            AlertManager.showError(this.alertError, this.errorList, errors);
            return;
        }
        
        ButtonManager.setLoading(this.submitBtn, true);
        const result = await FetchHelper.post(AppConfig.api.auth.register, data);
        if (result.success) {
            AlertManager.showSuccess(this.alertSuccess, this.successMessage, result.message);
            Constants.emit(Constants.EVENTS.USER_LOGGED_IN, { timestamp: Date.now() });
            setTimeout(() => {
                window.location.href = AppConfig.redirects.login;
            }, AppConfig.timeouts.redirect);
        } else {
            AlertManager.showError(this.alertError, this.errorList, result.errors || { general: AppConfig.messages.errors.general });
            if (result.errors) {
                Object.entries(result.errors).forEach(([field, message]) => {
                    const fieldId = field === 'password_confirm' ? 'passwordConfirm' : field;
                    FormValidator.showFieldError(fieldId, message);
                });
            }
        }
        
        ButtonManager.setLoading(this.submitBtn, false);
    }

    validateForm(data) {
        const errors = {};
        const config = AppConfig.validation;

        const name = data.name?.trim();
        if (!name) {
            errors.name = 'Nome é obrigatório';
        } else if (name.length < config.name.min) {
            errors.name = `Mínimo ${config.name.min} caracteres`;
        } else if (name.length > config.name.max) {
            errors.name = `Máximo ${config.name.max} caracteres`;
        }

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
            errors.password = `Mínimo ${config.password.min} caracteres`;
        }

        const passwordConfirm = data.password_confirm;
        if (!passwordConfirm) {
            errors.password_confirm = 'Confirmação de senha é obrigatória';
        } else if (password !== passwordConfirm) {
            errors.password_confirm = 'As senhas não conferem';
        }

        return errors;
    }

    validateFieldInReal(input) {
        const name = input.id;
        const value = input.value.trim();
        const config = AppConfig.validation;
        
        if (!value) return;

        let error = '';

        if (name === 'name') {
            if (value.length < config.name.min) {
                error = `Mínimo ${config.name.min} caracteres`;
            } else if (value.length > config.name.max) {
                error = `Máximo ${config.name.max} caracteres`;
            }
        } else if (name === 'email') {
            if (!FormValidator.isValidEmail(value)) {
                error = 'Email inválido';
            }
        } else if (name === 'password') {
            if (value.length < config.password.min) {
                error = `Mínimo ${config.password.min} caracteres`;
            }
        } 
        // else if (name === 'passwordConfirm') {
        //     const password = document.getElementById('password').value;
        //     if (value !== password) {
        //         error = 'As senhas não conferem';
        //     }
        // }

        if (error) {
            FormValidator.showFieldError(name, error);
        } else {
            FormValidator.clearFieldError(name);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new RegisterForm();
});
