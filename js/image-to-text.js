let selectedFile = null;

/* PAGE LOAD */

document.addEventListener(
"DOMContentLoaded",
function(){

const dropZone =
document.getElementById("dropZone");

const fileInput =
document.getElementById("imageFile");

if(!dropZone || !fileInput){
return;
}

/* CLICK */

dropZone.addEventListener(
"click",
function(){

fileInput.click();

}
);

/* FILE SELECT */

fileInput.addEventListener(
"change",
function(){

if(this.files.length){

selectedFile =
this.files[0];

loadImage(
selectedFile
);

}

}
);

/* DRAG OVER */

dropZone.addEventListener(
"dragover",
function(e){

e.preventDefault();

dropZone.classList.add(
"dragover"
);

}
);

/* DRAG LEAVE */

dropZone.addEventListener(
"dragleave",
function(){

dropZone.classList.remove(
"dragover"
);

}
);

/* DROP */

dropZone.addEventListener(
"drop",
function(e){

e.preventDefault();

dropZone.classList.remove(
"dragover"
);

if(
e.dataTransfer.files.length
){

selectedFile =
e.dataTransfer.files[0];

loadImage(
selectedFile
);

}

}
);

});

/* PREVIEW */

function loadImage(file){

if(!file) return;

const reader =
new FileReader();

reader.onload =
function(e){

const img =
document.getElementById(
"preview"
);

img.src =
e.target.result;

img.style.display =
"block";

};

reader.readAsDataURL(file);

}

/* OCR */

async function extractText(){

if(!selectedFile){

showToast(
"Select image first",
"error"
);

return;
}

showToast(
"Reading image...",
"info"
);

const result =
await Tesseract.recognize(
selectedFile,
"eng"
);

document.getElementById(
"outputText"
).value =
result.data.text;

showToast(
"Text extracted successfully",
"success"
);

}

/* COPY */

function copyText(){

let text =
document.getElementById(
"outputText"
).value;

if(!text){

showToast(
"No text found",
"error"
);

return;
}

navigator.clipboard.writeText(
text
);

showToast(
"Copied",
"success"
);

}

/* DOWNLOAD */

function downloadText(){

let text =
document.getElementById(
"outputText"
).value;

if(!text){

showToast(
"No text found",
"error"
);

return;
}

let blob =
new Blob(
[text],
{
type:"text/plain"
}
);

let a =
document.createElement("a");

a.href =
URL.createObjectURL(blob);

a.download =
"extracted_text.txt";

a.click();

showToast(
"Downloaded",
"success"
);

}