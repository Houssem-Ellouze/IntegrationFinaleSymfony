import '../bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import '../css/style.css';

const login = document.querySelector(".login");
const SignUp = document.querySelector(".signup");
const form = document.querySelector("#form");
const switchs = document.querySelectorAll(".switch");

let current = 1;
function tab2(){
    form.style.marginLeft = "0";
    login.style.background = "linear-gradient(45deg,#00d5fc,#faf8f8);"
    SignUp.style.background = "none"
    switchs[current-1].classList.remove("active");
}
function tab1(){
    form.style.marginLeft = "-100%";
    SignUp.style.background = "linear-gradient(45deg,#00d5fc,#faf8f8);"
    login.style.background = "none"
    switchs[current-1].classList.add("active");
}
