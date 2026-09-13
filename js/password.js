function generatePassword(){

let length = document.getElementById("length").value;

let upper = document.getElementById("uppercase").checked;
let lower = document.getElementById("lowercase").checked;
let number = document.getElementById("numbers").checked;
let symbol = document.getElementById("symbols").checked;

let chars = "";

if(upper) chars += "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
if(lower) chars += "abcdefghijklmnopqrstuvwxyz";
if(number) chars += "0123456789";
if(symbol) chars += "!@#$%^&*()_+{}[]<>?/";

if(chars === ""){
showToast("⚠️ Select at least one option","warning");
return;
}

let password = "";

for(let i=0;i<length;i++){
password += chars.charAt(Math.floor(Math.random() * chars.length));
}

document.getElementById("password").value = password;

}

function copyPassword(){

let pass = document.getElementById("password");

if(pass.value === ""){
showToast("⚠️ Generate password first","warning");
return;
}

pass.select();
document.execCommand("copy");

showToast("✅ Password Copied!","success");
}

// length display
// document.addEventListener("DOMContentLoaded", function(){

// let slider = document.getElementById("length");
// let output = document.getElementById("lenVal");

// output.innerText = slider.value;

// slider.oninput = function(){
// output.innerText = this.value;
// };

// });

document.addEventListener("DOMContentLoaded", function(){

    let slider = document.getElementById("length");
    let output = document.getElementById("lenVal");

    if(!slider || !output){
        return;
    }

    output.innerText = slider.value;

    slider.oninput = function(){
        output.innerText = this.value;
    };

});