let inputEditor, outputEditor;

// window.onload = function(){
document.addEventListener("DOMContentLoaded", function(){

// INIT ACE
inputEditor = ace.edit("inputEditor");
outputEditor = ace.edit("outputEditor");

// SETTINGS
[inputEditor, outputEditor].forEach(editor => {
editor.setTheme("ace/theme/monokai");
editor.session.setMode("ace/mode/json");
editor.setOptions({
fontSize:"14px",
showPrintMargin:false,
wrap:true
});
});

});

// FORMAT
function formatJSON(){

let val = inputEditor.getValue();

if(val.trim()===""){
showToast("⚠️ Please enter JSON");
return;
}

try{
let obj = JSON.parse(val);
outputEditor.setValue(JSON.stringify(obj,null,4),-1);
showToast("✅ Formatted");
}catch(e){
showToast("❌ Invalid JSON");
}

}

// MINIFY
function minifyJSON(){

let val = inputEditor.getValue();

if(val.trim()===""){
showToast("⚠️ No JSON found");
return;
}

try{
let obj = JSON.parse(val);
outputEditor.setValue(JSON.stringify(obj),-1);
showToast("✅ Minified");
}catch(e){
showToast("❌ Invalid JSON");
}

}

// VALIDATE
function validateJSON(){

let val = inputEditor.getValue();

try{
JSON.parse(val);
showToast("✅ Valid JSON");
}catch(e){
showToast("❌ Invalid JSON");
}

}

// COPY
function copyJSON(){

let text = outputEditor.getValue();

if(!text){
showToast("⚠️ Nothing to copy");
return;
}

navigator.clipboard.writeText(text);
showToast("📋 Copied");
}

// DOWNLOAD
function downloadJSON(){

let text = outputEditor.getValue();

if(!text){
showToast("⚠️ Nothing to download");
return;
}

let blob = new Blob([text],{type:"application/json"});
let url = URL.createObjectURL(blob);

let a = document.createElement("a");
a.href = url;
a.download = "data.json";
a.click();

URL.revokeObjectURL(url);

showToast("⬇️ Downloaded");
}

// TOAST
// function showToast(msg){

// let t = document.getElementById("toast");

// t.innerText = msg;
// t.style.display = "block";

// setTimeout(()=>{
// t.style.display="none";
// },2000);

// }