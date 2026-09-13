let inputCode, outputCode;
window.addEventListener("DOMContentLoaded", function() {
    inputCode = ace.edit("inputCode");
    outputCode = ace.edit("outputCode");
    [inputCode, outputCode].forEach(editor => {
        editor.setTheme("ace/theme/monokai");
        editor.setOptions({
            fontSize: "14px",
            wrap: true,
            showPrintMargin: false
        });
    });
}); 


/* BEAUTIFY */
function beautifyCode() {
//     let code = inputCode.getValue();
//     // let lang = document.getElementById("language").value;
//     let lang = detectLanguage(code); // 👈 AUTO DETECT

// document.getElementById("language").value = lang; // 👈 UI sync
//     if (code.trim() === "") {
//         showToast("⚠️ Enter code first", "error");
//         return;
//     }
let code = inputCode.getValue();

    const languageEl = document.getElementById("language");

    let selected = languageEl ? languageEl.value : "";

    let lang = selected || detectLanguage(code);

    if(languageEl){
        languageEl.value = lang;
    }

    if(code.trim() === ""){
        showToast("⚠️ Enter code first", "error");
        return;
    }

    try {
       let formatted = "";

if(lang === "json"){
formatted = JSON.stringify(JSON.parse(code),null,4);
}

else if(lang === "javascript"){
formatted = js_beautify(code);
}

else if(lang === "html"){
formatted = html_beautify(code);
}

else if(lang === "css"){
formatted = css_beautify(code); // 👈 manual select only
}

else if(lang === "sql"){
formatted = sqlFormatter.format(code);
}
        outputCode.setValue(formatted, -1);
        showToast("✅ Beautified", "success");
    } catch (e) {
        showToast("❌ Invalid Code", "error");
    }
} /* MINIFY */
function minifyCode() {
    let code = inputCode.getValue();
    let lang = document.getElementById("language").value;
    if (code.trim() === "") {
        showToast("⚠️ Enter code first", "error");
        return;
    }
    try {
        let minified = "";
        if (lang === "json") {
            minified = JSON.stringify(JSON.parse(code));
        } else {
            minified = code.replace(/\s+/g, ' ');
        }
        outputCode.setValue(minified, -1);
        showToast("⚡ Minified", "success");
    } catch (e) {
        showToast("❌ Error", "error");
    }
} /* COPY */
function copyCode() {
    let text = outputCode.getValue();
    if (!text) {
        showToast("⚠️ Nothing to copy", "error");
        return;
    }
    navigator.clipboard.writeText(text);
    showToast("📋 Copied", "success");
} /* CLEAR */
function clearCode() {
    inputCode.setValue("");
    outputCode.setValue("");
    showToast("🧹 Cleared", "info");
}

function detectLanguage(code){

code = code.trim();

/* JSON (STRICT) */
try{
JSON.parse(code);
return "json";
}catch(e){}

/* HTML */
if(/^<\/?[a-z][\s\S]*>/i.test(code)){
return "html";
}

/* SQL */
if(/\b(select|insert|update|delete|create|drop|alter)\b/i.test(code)){
return "sql";
}

/* JAVASCRIPT (DEFAULT STRONG) */
if(
/\b(function|let|var|const|=>|console\.|document\.|window\.)\b/.test(code)
){
return "javascript";
}

/* ❌ CSS AUTO DETECT REMOVE (STABILITY KE LIYE) */

/* DEFAULT */
return "javascript";

}
// let selected = document.getElementById("language").value;

// /* USER SELECT > AUTO DETECT */
// let lang = selected || detectLanguage(code);