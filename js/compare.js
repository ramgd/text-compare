let changes = [];
let current = -1;

function compare(){

let text1 = document.getElementById("text1").value;
let text2 = document.getElementById("text2").value;

let leftBox = document.getElementById("left");
let rightBox = document.getElementById("right");

leftBox.innerHTML="";
rightBox.innerHTML="";

/* MESSAGE WHEN BOTH TEXT EMPTY */

if(text1 === "" && text2 === ""){

document.getElementById("result").style.display="block";

leftBox.innerHTML = `
<div class="empty-message">
😎 No text to compare — makes my life easy! Cheers ;-)
</div>
`;

rightBox.innerHTML = `
<div class="empty-message">
✨ Please enter text in both boxes to start comparing.
</div>
`;

return;
}

/* IDENTICAL TEXT */

if(text1 === text2){

document.getElementById("result").style.display="block";

leftBox.innerHTML = `
<div class="empty-message success">
✅ The two texts are identical!
</div>
`;

rightBox.innerHTML = `
<div class="empty-message success">
🎉 No differences found between the texts.
</div>
`;

return;
}

/* FIX START : LINE ALIGNMENT ENGINE */

let lines1 = text1.split("\n");
let lines2 = text2.split("\n");

const dmp = new diff_match_patch();

changes=[];
current=-1;

/* FIX: instead of simple for loop we use smart pointer system */

let i = 0;
let j = 0;
let lineNumber = 0;

while(i < lines1.length || j < lines2.length){

let l1 = lines1[i] ?? "";
let l2 = lines2[j] ?? "";

let leftLine="";
let rightLine="";

/* SAME LINE */

if(l1 === l2){

leftLine = escapeHtml(l1);
rightLine = escapeHtml(l2);

i++;
j++;

}

/* LINE REMOVED */

else if(lines1[i+1] === l2){

// leftLine = "<span class='remove'>"+escapeHtml(l1)+"</span>";
// rightLine = "<span class='add'></span>";
leftLine = "<span class='remove'>"+escapeHtml(l1)+"</span>";
rightLine = "<span class='add'>&nbsp;</span>";   /* FIX: blank highlight */
changes.push(lineNumber);

i++;

}

/* LINE ADDED */

else if(l1 === lines2[j+1]){

// leftLine = "<span class='remove'></span>";
// rightLine = "<span class='add'>"+escapeHtml(l2)+"</span>";
leftLine = "<span class='remove'>&nbsp;</span>";  /* FIX: blank highlight */
rightLine = "<span class='add'>"+escapeHtml(l2)+"</span>";
changes.push(lineNumber);

j++;

}

/* LINE MODIFIED */

else{

let diff = dmp.diff_main(l1,l2);
dmp.diff_cleanupSemantic(diff);

diff.forEach(function(part){

let type = part[0];
let text = escapeHtml(part[1]);

if(type === 0){

leftLine += text;
rightLine += text;

}

if(type === -1){

leftLine += "<span class='remove'>"+text+"</span>";

}

if(type === 1){

rightLine += "<span class='add'>"+text+"</span>";

}

});

changes.push(lineNumber);

i++;
j++;

}

/* PRINT LINE */

leftBox.innerHTML += `
<div class="line" id="l${lineNumber}">
<span class="number">${lineNumber+1}</span>
<span class="code">${leftLine}</span>
</div>
`;

rightBox.innerHTML += `
<div class="line" id="r${lineNumber}">
<span class="number">${lineNumber+1}</span>
<span class="code">${rightLine}</span>
</div>
`;

lineNumber++;

}

/* FIX END */

document.getElementById("result").style.display="block";

}

function escapeHtml(text){

return text
.replace(/&/g,"&amp;")
.replace(/</g,"&lt;")
.replace(/>/g,"&gt;");

}

function next(){

if(changes.length===0) return;

if(current < changes.length-1){

current++;

scrollToChange();

}

}

function prev(){

if(changes.length===0) return;

if(current>0){

current--;

scrollToChange();

}

}

function first(){

if(changes.length===0) return;

current=0;

scrollToChange();

}

function last(){

if(changes.length===0) return;

current=changes.length-1;

scrollToChange();

}

function scrollToChange(){

let line=changes[current];

document.getElementById("l"+line).scrollIntoView({
behavior:"smooth",
block:"center"
});

document.getElementById("r"+line).scrollIntoView({
behavior:"smooth",
block:"center"
});

}

function switchText(){

let t1=document.getElementById("text1");
let t2=document.getElementById("text2");

let temp=t1.value;

t1.value=t2.value;
t2.value=temp;

}

function clearText(){

document.getElementById("text1").value="";
document.getElementById("text2").value="";
document.getElementById("result").style.display="none";

}