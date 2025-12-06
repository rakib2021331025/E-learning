// Firebase Authentication Configuration
// Replace with your Firebase config
const firebaseConfig = {
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
    projectId: "YOUR_PROJECT_ID",
    storageBucket: "YOUR_PROJECT_ID.appspot.com",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID"
};

// Initialize Firebase
if (typeof firebase !== 'undefined') {
    firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();
    const provider = new firebase.auth.GoogleAuthProvider();
} else {
    // Load Firebase SDK
    const firebaseScript = document.createElement('script');
    firebaseScript.src = 'https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js';
    firebaseScript.onload = function() {
        const authScript = document.createElement('script');
        authScript.src = 'https://www.gstatic.com/firebasejs/9.23.0/firebase-auth.js';
        authScript.onload = function() {
            firebase.initializeApp(firebaseConfig);
            window.auth = firebase.auth();
            window.provider = new firebase.auth.GoogleAuthProvider();
        };
        document.head.appendChild(authScript);
    };
    document.head.appendChild(firebaseScript);
}

// Firebase Google Sign-In Function
function signInWithGoogle() {
    if (typeof firebase === 'undefined' || !firebase.auth) {
        alert('Firebase is loading. Please wait a moment and try again.');
        return;
    }
    
    const auth = firebase.auth();
    const provider = new firebase.auth.GoogleAuthProvider();
    
    // Add additional scopes if needed
    provider.addScope('email');
    provider.addScope('profile');
    
    auth.signInWithPopup(provider)
        .then((result) => {
            // This gives you a Google Access Token
            const credential = result.credential;
            const token = credential.accessToken;
            const user = result.user;
            
            // Send user data to server
            sendFirebaseAuthToServer(user.email, user.displayName, user.photoURL, user.uid);
        })
        .catch((error) => {
            console.error('Firebase Auth Error:', error);
            alert('Failed to sign in with Google. Please try again.');
        });
}

// Send Firebase auth data to server
function sendFirebaseAuthToServer(email, name, photoUrl, uid) {
    $.ajax({
        url: 'student/firebase_auth.php',
        method: 'POST',
        dataType: 'json',
        data: {
            firebase_email: email,
            firebase_name: name,
            firebase_photo: photoUrl,
            firebase_uid: uid
        },
        success: function(response) {
            if (response.status === 'success') {
                $('#showmsg, #logshowmsg').html('<div class="alert alert-success">Login successful! Redirecting...</div>');
                setTimeout(() => {
                    window.location.href = response.redirect || 'index.php';
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

// Alternative: Sign in with Redirect (for mobile)
function signInWithGoogleRedirect() {
    if (typeof firebase === 'undefined' || !firebase.auth) {
        alert('Firebase is loading. Please wait a moment and try again.');
        return;
    }
    
    const auth = firebase.auth();
    const provider = new firebase.auth.GoogleAuthProvider();
    
    auth.signInWithRedirect(provider)
        .then(() => {
            // Redirect will happen automatically
        })
        .catch((error) => {
            console.error('Firebase Auth Error:', error);
            alert('Failed to sign in with Google. Please try again.');
        });
}

// Check if user is already signed in
firebase.auth().onAuthStateChanged((user) => {
    if (user) {
        // User is signed in
        console.log('User signed in:', user.email);
    } else {
        // User is signed out
        console.log('User signed out');
    }
});

// Handle redirect result
firebase.auth().getRedirectResult()
    .then((result) => {
        if (result.credential) {
            const user = result.user;
            sendFirebaseAuthToServer(user.email, user.displayName, user.photoURL, user.uid);
        }
    })
    .catch((error) => {
        console.error('Redirect error:', error);
    });

// Initialize on page load
$(document).ready(function() {
    // Use popup for desktop, redirect for mobile
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Handle all Firebase Google login buttons
    $('#firebase-google-login, #firebase-google-signup, #firebase-google-login-index, #firebase-google-signup-index').click(function(e) {
        e.preventDefault();
        if (isMobile) {
            signInWithGoogleRedirect();
        } else {
            signInWithGoogle();
        }
    });
    
    // Wait for Firebase to load
    setTimeout(function() {
        if (typeof firebase !== 'undefined' && firebase.auth) {
            window.auth = firebase.auth();
            window.provider = new firebase.auth.GoogleAuthProvider();
        }
    }, 1000);
});

