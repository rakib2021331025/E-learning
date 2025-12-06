// Google Sign-In Configuration
// Note: Replace YOUR_GOOGLE_CLIENT_ID with your actual Google OAuth Client ID
const GOOGLE_CLIENT_ID = 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com';

// Load Google Sign-In API
function loadGoogleSignIn() {
    if (typeof gapi === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://accounts.google.com/gsi/client';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
        
        script.onload = function() {
            initializeGoogleSignIn();
        };
    } else {
        initializeGoogleSignIn();
    }
}

function initializeGoogleSignIn() {
    if (typeof gapi !== 'undefined') {
        gapi.load('auth2', function() {
            gapi.auth2.init({
                client_id: GOOGLE_CLIENT_ID
            });
        });
    }
}

// Handle Google Sign-In for Login
function handleGoogleLogin() {
    if (typeof google === 'undefined' || !google.accounts) {
        alert('Google Sign-In is loading. Please wait a moment and try again.');
        return;
    }

    google.accounts.id.initialize({
        client_id: GOOGLE_CLIENT_ID,
        callback: handleGoogleResponse
    });

    google.accounts.id.prompt((notification) => {
        if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
            // Use popup flow
            google.accounts.oauth2.initTokenClient({
                client_id: GOOGLE_CLIENT_ID,
                scope: 'email profile',
                callback: function(tokenResponse) {
                    fetchUserInfo(tokenResponse.access_token, 'login');
                }
            }).requestAccessToken();
        }
    });
}

// Handle Google Sign-In for Signup
function handleGoogleSignup() {
    if (typeof google === 'undefined' || !google.accounts) {
        alert('Google Sign-In is loading. Please wait a moment and try again.');
        return;
    }

    google.accounts.id.initialize({
        client_id: GOOGLE_CLIENT_ID,
        callback: handleGoogleResponse
    });

    google.accounts.id.prompt((notification) => {
        if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
            // Use popup flow
            google.accounts.oauth2.initTokenClient({
                client_id: GOOGLE_CLIENT_ID,
                scope: 'email profile',
                callback: function(tokenResponse) {
                    fetchUserInfo(tokenResponse.access_token, 'signup');
                }
            }).requestAccessToken();
        }
    });
}

function handleGoogleResponse(response) {
    // Decode JWT token
    const base64Url = response.credential.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    const userData = JSON.parse(jsonPayload);
    
    // Send to server
    sendGoogleAuthToServer(userData.email, userData.name, response.credential);
}

function fetchUserInfo(accessToken, type) {
    fetch('https://www.googleapis.com/oauth2/v2/userinfo', {
        headers: {
            'Authorization': 'Bearer ' + accessToken
        }
    })
    .then(response => response.json())
    .then(data => {
        sendGoogleAuthToServer(data.email, data.name, accessToken);
    })
    .catch(error => {
        console.error('Error fetching user info:', error);
        alert('Failed to sign in with Google. Please try again.');
    });
}

function sendGoogleAuthToServer(email, name, token) {
    $.ajax({
        url: 'student/google_auth.php',
        method: 'POST',
        dataType: 'json',
        data: {
            google_token: token,
            google_email: email,
            google_name: name
        },
        success: function(response) {
            if (response.status === 'login_success' || response.status === 'register_success') {
                $('#showmsg, #logshowmsg').html('<div class="alert alert-success">Login successful! Redirecting...</div>');
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1000);
            } else {
                $('#showmsg, #logshowmsg').html('<div class="alert alert-danger">' + (response.message || 'Login failed') + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            $('#showmsg, #logshowmsg').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
        }
    });
}

// Simpler implementation using Google Sign-In Button (Recommended)
$(document).ready(function() {
    // Load Google Sign-In library
    if (typeof google === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://accounts.google.com/gsi/client';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Google Login Button Handler
    $('#google-login, #google-login-index').click(function() {
        handleGoogleLogin();
    });

    // Google Signup Button Handler
    $('#google-signup').click(function() {
        handleGoogleSignup();
    });
});

// Alternative: Simple Google OAuth implementation
function simpleGoogleSignIn() {
    // Redirect to Google OAuth
    const clientId = GOOGLE_CLIENT_ID;
    const redirectUri = window.location.origin + '/student/google_callback.php';
    const scope = 'email profile';
    const responseType = 'code';
    
    const authUrl = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${clientId}&redirect_uri=${encodeURIComponent(redirectUri)}&response_type=${responseType}&scope=${encodeURIComponent(scope)}`;
    
    window.location.href = authUrl;
}

// For now, let's use a simpler approach - just show an alert
// User needs to configure their Google OAuth Client ID
$(document).ready(function() {
    $('#google-login, #google-login-index, #google-signup').click(function(e) {
        e.preventDefault();
        alert('Google Sign-In is being configured. Please contact the administrator to enable this feature.\n\nFor now, you can use email and password to login.');
        // Uncomment below when Google OAuth is configured:
        // simpleGoogleSignIn();
    });
});

