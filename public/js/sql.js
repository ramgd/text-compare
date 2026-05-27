function minifySQL(){

let input = document.getElementById("sqlInput").value;

if(input.trim()===""){
showToast("⚠️ Please enter SQL query","error");
return;
}

/* REMOVE COMMENTS + EXTRA SPACES */
let output = input
.replace(/--.*$/gm,"")              // remove single line comments
.replace(/\/\*[\s\S]*?\*\//g,"")   // remove block comments
.replace(/\s+/g," ")               // multiple spaces to one
.trim();

document.getElementById("sqlOutput").value = output;

showToast("✅ SQL Minified","success");
}

function formatSQL(){

let input = document.getElementById("sqlInput").value;

if(input.trim()===""){
showToast("⚠️ Please enter SQL query","error");
return;
}

/* BASIC FORMAT */
let output = input
.replace(/\s+/g," ")
.replace(/SELECT/gi,"\nSELECT")
.replace(/FROM/gi,"\nFROM")
.replace(/WHERE/gi,"\nWHERE")
.replace(/AND/gi,"\n  AND")
.replace(/OR/gi,"\n  OR")
.replace(/GROUP BY/gi,"\nGROUP BY")
.replace(/ORDER BY/gi,"\nORDER BY")
.replace(/LIMIT/gi,"\nLIMIT");

document.getElementById("sqlOutput").value = output.trim();

showToast("✨ SQL Formatted","success");
}

function copySQL(){

let output = document.getElementById("sqlOutput").value;

if(!output){
showToast("⚠️ Nothing to copy","error");
return;
}

navigator.clipboard.writeText(output);
showToast("📋 Copied","success");
}

function clearSQL(){

document.getElementById("sqlInput").value="";
document.getElementById("sqlOutput").value="";

showToast("🧹 Cleared","info");
}