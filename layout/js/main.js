let loginTab = document.querySelector('.login-page .selected');
let loginForm = document.querySelector('.login-page .login');
let signupForm = document.querySelector('.login-page .signup');
document.querySelectorAll('.login-page h1 span').forEach(function(el){
    el.onclick = function(){
        document.querySelectorAll('.login-page h1 span').forEach(function(s){
            s.classList.remove('selected');
        });
        el.classList.add('selected');
        if(el.dataset.class === "login"){
            loginForm.classList.add('active');
            signupForm.classList.remove('active');
        } else {
            signupForm.classList.add('active');
            loginForm.classList.remove('active');
        }
    };
});
if(loginForm) loginForm.classList.add('active');
document.querySelectorAll('.live').forEach(function(input){
    input.oninput = function(){
        let target = document.querySelector(input.dataset.class);
        if(target){
            target.textContent = input.value;
        }
    };
});
