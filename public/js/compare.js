/* ============================================================================
   Text Compare tool

   Wrapped in an IIFE and exposed under `tc*` names on purpose. Every script in
   js/ is loaded globally by layouts/app.blade.php, and this file used to leak
   generic globals (clearText, escapeHtml, next, prev, first, last, changes,
   current). Later files redefined several of them - base64.js#clearText and
   hash-generator.js#clearCompare in particular - which silently killed this
   tool's Clear button on every page load. Keeping the internals private means
   load order can no longer break the compare tool.
   ========================================================================= */
(function () {
"use strict";

let changes = [];
let current = -1;

function tcCompare(){

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

/* ---------------------------------------------------------------------------
   LINE ALIGNMENT ENGINE

   The previous engine walked both files with a single-line lookahead:
   it only recognised a removal if lines1[i+1] === l2, and an insertion if
   l1 === lines2[j+1]. That works for a one-line edit and breaks on anything
   larger - inserting three lines into a file made every following line show
   as changed, because the two sides never re-synchronised.

   This uses diff-match-patch's line mode, which is already loaded for the
   word-level diff. diff_linesToChars_ maps each distinct line to a single
   character, so diff_main computes a real longest-common-subsequence over
   whole lines; diff_charsToLines_ maps the result back. Runs of deleted and
   inserted lines that sit next to each other are then paired up row by row
   and given the existing character-level highlight, which is what makes a
   modified line show its changed words.
   --------------------------------------------------------------------------- */

const lineDmp = new diff_match_patch();

/* diff_linesToChars_ keeps each line's trailing newline as part of the line,
   so a final line without one ("A") is a different token from the same line
   with one ("A\n"). Normalising both sides to end with a newline keeps every
   token uniform - otherwise a diff chunk can start mid-line and produce a
   spurious blank row. */
const nl1 = text1.endsWith("\n") ? text1 : text1 + "\n";
const nl2 = text2.endsWith("\n") ? text2 : text2 + "\n";

const mapped = lineDmp.diff_linesToChars_(nl1, nl2);
const lineDiffs = lineDmp.diff_main(mapped.chars1, mapped.chars2, false);
lineDmp.diff_charsToLines_(lineDiffs, mapped.lineArray);

/* diff_main can leave adjacent runs of the same type; merge them so a
   delete-run is always immediately followed by its insert-run. */
lineDmp.diff_cleanupSemantic(lineDiffs);

function splitLines(chunk) {
    const out = chunk.split("\n");
    /* A chunk ends with \n when it is a whole number of lines; drop the
       empty tail that split() produces so we do not invent a blank row. */
    if (out.length && out[out.length - 1] === "") out.pop();
    return out;
}

/* Flatten the diff into [type, line] pairs. */
const ops = [];
lineDiffs.forEach(function (part) {
    const type = part[0];
    splitLines(part[1]).forEach(function (line) { ops.push([type, line]); });
});

changes = [];
current = -1;

const leftRows = [];
const rightRows = [];
let lineNumber = 0;

function pushRow(leftHtml, rightHtml, isChange) {
    leftRows.push(
        '<div class="line" id="l' + lineNumber + '">' +
        '<span class="number">' + (lineNumber + 1) + '</span>' +
        '<span class="code">' + leftHtml + '</span></div>'
    );
    rightRows.push(
        '<div class="line" id="r' + lineNumber + '">' +
        '<span class="number">' + (lineNumber + 1) + '</span>' +
        '<span class="code">' + rightHtml + '</span></div>'
    );
    if (isChange) changes.push(lineNumber);
    lineNumber++;
}

/* Character-level highlight for a pair of lines that replaced each other. */
function modifiedPair(oldLine, newLine) {
    const charDiff = lineDmp.diff_main(oldLine, newLine);
    lineDmp.diff_cleanupSemantic(charDiff);

    let leftHtml = "";
    let rightHtml = "";

    charDiff.forEach(function (part) {
        const kind = part[0];
        const text = escapeHtml(part[1]);
        if (kind === 0) { leftHtml += text; rightHtml += text; }
        else if (kind === -1) { leftHtml += "<span class='remove'>" + text + "</span>"; }
        else { rightHtml += "<span class='add'>" + text + "</span>"; }
    });

    return [leftHtml, rightHtml];
}

let k = 0;
while (k < ops.length) {
    const type = ops[k][0];

    if (type === 0) {
        const text = escapeHtml(ops[k][1]);
        pushRow(text, text, false);
        k++;
        continue;
    }

    /* Collect the run of deletions, then the run of insertions that follows. */
    const removed = [];
    while (k < ops.length && ops[k][0] === -1) { removed.push(ops[k][1]); k++; }

    const added = [];
    while (k < ops.length && ops[k][0] === 1) { added.push(ops[k][1]); k++; }

    const rows = Math.max(removed.length, added.length);

    for (let r = 0; r < rows; r++) {
        const hasOld = r < removed.length;
        const hasNew = r < added.length;

        if (hasOld && hasNew) {
            const pair = modifiedPair(removed[r], added[r]);
            pushRow(pair[0], pair[1], true);
        } else if (hasOld) {
            pushRow(
                "<span class='remove'>" + escapeHtml(removed[r]) + "</span>",
                "<span class='add'>&nbsp;</span>",
                true
            );
        } else {
            pushRow(
                "<span class='remove'>&nbsp;</span>",
                "<span class='add'>" + escapeHtml(added[r]) + "</span>",
                true
            );
        }
    }
}

/* One write each, instead of += per line inside the loop. */
leftBox.innerHTML = leftRows.join("");
rightBox.innerHTML = rightRows.join("");

document.getElementById("result").style.display="block";

}

function escapeHtml(text){

return text
.replace(/&/g,"&amp;")
.replace(/</g,"&lt;")
.replace(/>/g,"&gt;");

}

function tcNext(){

if(changes.length===0) return;

if(current < changes.length-1){

current++;

scrollToChange();

}

}

function tcPrev(){

if(changes.length===0) return;

if(current>0){

current--;

scrollToChange();

}

}

function tcFirst(){

if(changes.length===0) return;

current=0;

scrollToChange();

}

function tcLast(){

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

function tcSwitchText(){

let t1=document.getElementById("text1");
let t2=document.getElementById("text2");

let temp=t1.value;

t1.value=t2.value;
t2.value=temp;

}

function tcClearAll(){

document.getElementById("text1").value="";
document.getElementById("text2").value="";
document.getElementById("result").style.display="none";

}

/* Public entry points used by resources/views/compare.blade.php */
window.tcCompare    = tcCompare;
window.tcSwitchText = tcSwitchText;
window.tcClearAll   = tcClearAll;
window.tcFirst      = tcFirst;
window.tcPrev       = tcPrev;
window.tcNext       = tcNext;
window.tcLast       = tcLast;

})();
