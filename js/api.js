// ADD PARAM FIELD
function addParam() {
    let div = document.createElement("div");
    div.innerHTML = `<input placeholder="Key"> <input placeholder="Value">`;
    document.getElementById("params").appendChild(div);
}

// ADD HEADER FIELD
function addHeader() {
    let div = document.createElement("div");
    div.innerHTML = `<input placeholder="Key"> <input placeholder="Value">`;
    document.getElementById("headers").appendChild(div);
}

// CLEAR ALL
function clearAll() {
    document.getElementById("url").value = "";
    document.getElementById("body").value = "";
    document.getElementById("response").innerHTML = "";
    document.getElementById("params").innerHTML = "";
    document.getElementById("headers").innerHTML = "";
    showToast("🧹 Cleared", "info");
}

// COPY RESPONSE
function copyResponse() {
    let text = document.getElementById("response").innerText;

    if (!text) {
        showToast("⚠️ Nothing to copy", "error");
        return;
    }

    navigator.clipboard.writeText(text);
    showToast("📋 Copied", "success");
}

// BUILD QUERY PARAMS
function buildQuery() {
    let params = document.querySelectorAll("#params div");
    let query = [];

    params.forEach(p => {
        let key = p.children[0].value;
        let val = p.children[1].value;

        if (key) {
            query.push(`${encodeURIComponent(key)}=${encodeURIComponent(val)}`);
        }
    });

    return query.length ? "?" + query.join("&") : "";
}

// BUILD HEADERS
function buildHeaders() {
    let headers = {};
    let list = document.querySelectorAll("#headers div");

    list.forEach(h => {
        let key = h.children[0].value;
        let val = h.children[1].value;

        if (key) {
            headers[key] = val;
        }
    });

    return headers;
}

// SEND REQUEST
function sendRequest() {

    let method = document.getElementById("method").value;
    let baseUrl = document.getElementById("url").value;
    let body = document.getElementById("body").value;

    if (!baseUrl) {
        showToast("⚠️ Enter API URL", "error");
        return;
    }

    // ADD QUERY PARAMS
    let url = baseUrl + buildQuery();

    let headers = buildHeaders();

    let options = {
        method: method,
        headers: headers
    };

    // BODY
    if (method !== "GET" && body) {
        try {
            JSON.parse(body); // validate
            options.body = body;
        } catch {
            showToast("❌ Invalid JSON Body", "error");
            return;
        }
    }

    let start = new Date().getTime();

    fetch(url, options)
        .then(async res => {

            let end = new Date().getTime();

            document.getElementById("status").innerText = "Status: " + res.status;
            document.getElementById("time").innerText = "Time: " + (end - start) + " ms";

            let data;

            try {
                data = await res.json();
                showJSON(data);
            } catch {
                data = await res.text();
                document.getElementById("response").innerText = data;
            }

            showToast("✅ Success", "success");

        })
        .catch(err => {
            document.getElementById("response").innerText = err;
            showToast("❌ Failed", "error");
        });

}

// JSON PRETTY VIEW
function showJSON(obj) {
    let formatted = JSON.stringify(obj, null, 4);
    document.getElementById("response").innerText = formatted;
}
