import { BehaviorSubject } from 'rxjs';

import {methods} from '../components/methods';

const config = JSON.stringify({
    apiUrl: 'http://localhost:8000/api'
})
const userSubject = new BehaviorSubject(null);
const baseUrl = `${config.apiUrl}/`;

export const security = {
    login,
    // logout,
    // refreshToken,
    register,
    verifyEmail,
    forgotPassword,
    validateResetToken,
    resetPassword,
    getAll,
    getById,
    create,
    update,
    delete: _delete,
    user: userSubject.asObservable(),
    get userValue () { return userSubject.value }
};

function login(username, password) {
    return methods.post(`${baseUrl}/login`, { username, password })
        .then(user => {
            // publish user to subscribers and start timer to refresh token
            userSubject.next(user);
            startRefreshTokenTimer();
            return user;
        });
}


function register(params) {
    return methods.post(`${baseUrl}/register`, params);
}

function verifyEmail(token) {
    return methods.post(`${baseUrl}/verify-email`, { token });
}

function forgotPassword(email) {
    return methods.post(`${baseUrl}/forgot-password`, { email });
}

function validateResetToken(token) {
    return methods.post(`${baseUrl}/validate-reset-token`, { token });
}

function resetPassword({ token, password, confirmPassword }) {
    return methods.post(`${baseUrl}/reset-password`, { token, password, confirmPassword });
}

function getAll() {
    return methods.get(baseUrl);
}

function getById(id) {
    return methods.get(`${baseUrl}/${id}`);
}

function create(params) {
    return methods.post(baseUrl, params);
}

function update(id, params) {
    return methods.put(`${baseUrl}/${id}`, params)
        .then(user => {
            // update stored user if the logged in user updated their own record
            if (user.id === userSubject.value.id) {
                // publish updated user to subscribers
                user = { ...userSubject.value, ...user };
                userSubject.next(user);
            }
            return user;
        });
}

// prefixed with underscore because 'delete' is a reserved word in javascript
function _delete(id) {
    return methods.delete(`${baseUrl}/${id}`)
        .then(x => {
            // auto logout if the logged in user deleted their own record
            if (id === userSubject.value.id) {
                logout();
            }
            return x;
        });
}

// helper functions

let refreshTokenTimeout;

function startRefreshTokenTimer() {
    // parse json object from base64 encoded jwt token
    const jwtToken = JSON.parse(atob(userSubject.value.jwtToken.split('.')[1]));

    // set a timeout to refresh the token a minute before it expires
    const expires = new Date(jwtToken.exp * 1000);
    const timeout = expires.getTime() - Date.now() - (60 * 1000);
    refreshTokenTimeout = setTimeout(refreshToken, timeout);
}

function stopRefreshTokenTimer() {
    clearTimeout(refreshTokenTimeout);
}