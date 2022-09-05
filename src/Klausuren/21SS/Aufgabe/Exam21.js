let request = new XMLHttpRequest();

function requestData() { // fordert die Daten asynchron an
    "use strict";
    let gameId = document.getElementById("teamId").value;
    request.open("GET", "Exam21API.php?gameId="+gameId);
    request.onreadystatechange = processData;
    request.send(null);
}

function processData() {
    "use strict";
    if (request.readyState === 4) { // Uebertragung = DONE
        if (request.status === 200) { // HTTP-Status = OK
            if (request.responseText != null)
                process(request.responseText);
            else console.error("Dokument ist leer");
        } else console.error("Uebertragung fehlgeschlagen");
    } // else; // Uebertragung laeuft noch
}

function pollData() {
    "use strict";
    requestData();
    window.setInterval(requestData, 5000);
}

function process(intext){
    "use strict";

    let intextParsed = JSON.parse(intext);
    let amount = intextParsed[0]["playing"]
    let element = document.getElementById("players");
    element.innerText = amount;
}

