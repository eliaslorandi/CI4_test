/**
 * Constantes Globais da Aplicação
 * public/js/shared/constants.js
 */

const Constants = {
    // HTTP Status Codes
    HTTP_STATUS: {
        OK: 200,
        CREATED: 201,
        BAD_REQUEST: 400,
        UNAUTHORIZED: 401,
        FORBIDDEN: 403,
        NOT_FOUND: 404,
        CONFLICT: 409,
        UNPROCESSABLE_ENTITY: 422,
        SERVER_ERROR: 500
    },

    // Message Types
    MESSAGE_TYPE: {
        ERROR: 'error',
        SUCCESS: 'success',
        WARNING: 'warning',
        INFO: 'info'
    },

    // Task Status
    TASK_STATUS: {
        PENDING: 'pending',
        COMPLETED: 'completed',
        ARCHIVED: 'archived'
    },

    // Priority Levels
    PRIORITY: {
        LOW: 'low',
        MEDIUM: 'medium',
        HIGH: 'high'
    },

    // User Roles
    USER_ROLE: {
        ADMIN: 'admin',
        USER: 'user',
        GUEST: 'guest'
    },

    // Validation Types
    VALIDATION_TYPE: {
        EMAIL: 'email',
        REQUIRED: 'required',
        MIN_LENGTH: 'min_length',
        MAX_LENGTH: 'max_length',
        PATTERN: 'pattern',
        MATCH: 'match'
    },

    // Custom Events
    EVENTS: {
        USER_LOGGED_IN: 'user:login',
        USER_LOGGED_OUT: 'user:logout',
        TASK_CREATED: 'task:created',
        TASK_UPDATED: 'task:updated',
        TASK_DELETED: 'task:deleted',
        TASK_COMPLETED: 'task:completed'
    },

    // Animation Durations (ms)
    DURATION: {
        FAST: 200,
        NORMAL: 300,
        SLOW: 500,
        CACHE_TTL: 3600000 // 1 hour
    },

    // Regex Patterns
    PATTERNS: {
        EMAIL: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        PHONE: /^[\d\s\-\(\)]{10,}$/,
        URL: /^https?:\/\/.+\..+/,
        SLUG: /^[a-z0-9]+(?:-[a-z0-9]+)*$/,
        STRONG_PASSWORD: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
    },

    // LocalStorage Keys
    STORAGE_KEYS: {
        AUTH_TOKEN: 'auth_token',
        USER_DATA: 'user_data',
        PREFERENCES: 'user_preferences'
    },

    // Event Emitter Methods
    _listeners: {},

    emit(eventName, detail = {}) {
        if (!this._listeners[eventName]) return;
        this._listeners[eventName].forEach(callback => callback(detail));
    },

    on(eventName, callback) {
        if (!this._listeners[eventName]) {
            this._listeners[eventName] = [];
        }
        this._listeners[eventName].push(callback);
    },

    off(eventName, callback) {
        if (!this._listeners[eventName]) return;
        this._listeners[eventName] = this._listeners[eventName].filter(cb => cb !== callback);
    }
};
