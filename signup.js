function jsonCheckUsername(json) {
    // Controllo il campo exists ritornato dal JSON
    if (formStatus.username = !json.exists) {
        document.querySelector('.username').classList.remove('errorj');
        document.querySelector('#username_span').classList.remove('errorj');
        document.querySelector('#username_span').textContent = "";
    } else {
        document.querySelector('#username_span').textContent = "Nome utente già utilizzato";
        document.querySelector('.username').classList.add('errorj');
        document.querySelector('#username_span').classList.add('errorj');
    }
    checkForm();
}

function jsonCheckEmail(json) {
    // Controllo il campo exists ritornato dal JSON
    if (formStatus.email = !json.exists) {
        document.querySelector('.email').classList.remove('errorj');
        document.querySelector('#email_span').textContent = "";
        document.querySelector('#email_span').classList.remove('errorj');
    } else {
        document.querySelector('#email_span').textContent = "Email già utilizzata";
        document.querySelector('.email').classList.add('errorj');
        document.querySelector('#email_span').classList.add('errorj');
    }
    checkForm();
}

function fetchResponse(response) {
    if (!response.ok) return null;
    return response.json();
}

function checkUsername(event) {
    const input = document.querySelector('.username input');

    if(!/^[a-zA-Z0-9_]{1,15}$/.test(input.value)) {
        document.querySelector('#username_span').textContent = "Sono ammesse lettere, numeri e underscore. Max. 15";
        input.parentNode.classList.add('errorj');
        document.querySelector('#username_span').classList.add('errorj');
        formStatus.username = false;
        checkForm();
    } else {
        document.querySelector('#username_span').textContent = "";
        input.parentNode.classList.remove('errorj');
        fetch("check-username.php?q="+encodeURIComponent(input.value)).then(fetchResponse).then(jsonCheckUsername);
    }    
}

function checkEmail(event) {
    const emailInput = document.querySelector('.email input');
    if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(emailInput.value).toLowerCase())) {
        document.querySelector('#email_span').textContent = "Email non valida";
        document.querySelector('.email').classList.add('errorj');
        document.querySelector('#email_span').classList.add('errorj');
        formStatus.email = false;
        checkForm();
    } else {
        fetch("check-email.php?q="+encodeURIComponent(String(emailInput.value).toLowerCase())).then(fetchResponse).then(jsonCheckEmail);
    }
}

function checkPassword(event) {
    const passwordInput = document.querySelector('.password input');
    if (formStatus.password = passwordInput.value.length >= 8) {
        document.querySelector('.password').classList.remove('errorj');
        document.querySelector('#password_span').textContent = "";
        document.querySelector('#password_span').classList.remove('errorj');
    } else {
        document.querySelector('.password').classList.add('errorj');
        document.querySelector('#password_span').textContent = "La password deve avere almeno 8 caratteri";
        document.querySelector('#password_span').classList.add('errorj');
    }
    checkForm();
}

function checkConfirmPassword(event) {
    const confirmPasswordInput = document.querySelector('.confirm_pwd input');
    if (formStatus.confirm_pwd = confirmPasswordInput.value === document.querySelector('.password input').value) {
        document.querySelector('.confirm_pwd').classList.remove('errorj');
        document.querySelector('#confirm_span').textContent = "";
        document.querySelector('#confirm_span').classList.remove('errorj');
    } else {
        document.querySelector('.confirm_pwd').classList.add('errorj');
        document.querySelector('#confirm_span').textContent = "Le due passsword devono coincidere";
        document.querySelector('#confirm_span').classList.add('errorj');
    }
    checkForm();
}

function checkForm() {
    console.log(formStatus);
    if(formStatus['username'] && formStatus['email'] && formStatus['password'] && formStatus['confirm_pwd']) {
        document.querySelector('#submit').disabled = false;
    } else {
        document.querySelector('#submit').disabled = true;
    }
}

const formStatus = {};
document.querySelector('.username input').addEventListener('blur', checkUsername);
document.querySelector('.email input').addEventListener('blur', checkEmail);
document.querySelector('.password input').addEventListener('blur', checkPassword);
document.querySelector('.confirm_pwd input').addEventListener('blur', checkConfirmPassword);

if (document.querySelector('.errorj') !== null) {
    checkUsername(); 
    checkPassword(); 
    checkConfirmPassword(); 
    checkEmail();
    document.querySelector('.name input').dispatchEvent(new Event('blur'));
    document.querySelector('.surname input').dispatchEvent(new Event('blur'));
}