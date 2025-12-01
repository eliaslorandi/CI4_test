class FormValidator {
    static isValidEmail(email) {
        return AppConfig.validation.email.pattern.test(email);
    }

    static showFieldError(fieldId, message) {
        const errorElement = document.getElementById(fieldId + 'Error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
            const input = document.getElementById(fieldId);
            if (input) {
                input.classList.add(AppConfig.css.invalid);
            }
        }
    }

    static clearFieldError(fieldId) {
        const errorElement = document.getElementById(fieldId + 'Error');
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.classList.remove('show');
        }
        const input = document.getElementById(fieldId);
        if (input) {
            input.classList.remove(AppConfig.css.invalid);
            input.classList.remove(AppConfig.css.valid);
        }
    }

    static clearFormErrors(form) {
        form.querySelectorAll('[id$="Error"]').forEach(el => {
            el.textContent = '';
            el.classList.remove('show');
        });
        form.querySelectorAll('input').forEach(input => {
            input.classList.remove(AppConfig.css.invalid);
            input.classList.remove(AppConfig.css.valid);
        });
    }
}

class AlertManager {
    static showError(alertElement, listElement, errors) {
        if (Array.isArray(errors)) {
            listElement.innerHTML = errors
                .map(error => `<li>${error}</li>`)
                .join('');
        } else if (typeof errors === 'object') {
            listElement.innerHTML = Object.values(errors)
                .map(error => `<li>${error}</li>`)
                .join('');
        }
        alertElement.style.display = 'block';
        alertElement.classList.add('show');
    }

    static showSuccess(alertElement, messageElement, message) {
        messageElement.textContent = message;
        alertElement.style.display = 'block';
        alertElement.classList.add('show');
    }

    static hideAlert(alertElement) {
        alertElement.style.display = 'none';
        alertElement.classList.remove('show');
    }
}

class ButtonManager {
    static setLoading(button, isLoading) {
        const textSpan = button.querySelector('#btnText');
        const spinner = button.querySelector('#btnSpinner');
        
        if (isLoading) {
            button.disabled = true;
            if (spinner) spinner.style.display = 'inline-block';
        } else {
            button.disabled = false;
            if (spinner) spinner.style.display = 'none';
        }
    }
}

class UrlHelper {
    static getParam(name) {
        const params = new URLSearchParams(window.location.search);
        return params.get(name);
    }

    static hasParam(name) {
        return new URLSearchParams(window.location.search).has(name);
    }

    static removeParam(name) {
        const params = new URLSearchParams(window.location.search);
        params.delete(name);
        return params.toString();
    }
}

class FetchHelper {
    static async post(url, data = {}) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(data)
            });

            const text = await response.text();
            let json = {};
            
            try {
                json = JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON response:', e);
                console.error('Response text:', text);
                json = { success: false };
            }
            
            if (!response.ok) {
                return {
                    success: false,
                    errors: json.errors || { general: AppConfig.messages.errors.general },
                    message: json.message || AppConfig.messages.errors.general
                };
            }

            return {
                success: true,
                ...json
            };
        } catch (error) {
            console.error('Fetch Error:', error);
            return {
                success: false,
                errors: { general: AppConfig.messages.errors.network }
            };
        }
    }

    static async get(url) {
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            let json = {};
            try {
                json = await response.json();
            } catch (e) {
                console.error('Failed to parse JSON response:', e);
                json = { success: false };
            }

            if (!response.ok) {
                return {
                    success: false,
                    errors: json.errors || { general: AppConfig.messages.errors.general }
                };
            }

            return {
                success: true,
                ...json
            };
        } catch (error) {
            console.error('Fetch Error:', error);
            return {
                success: false,
                errors: { general: AppConfig.messages.errors.network }
            };
        }
    }
}
