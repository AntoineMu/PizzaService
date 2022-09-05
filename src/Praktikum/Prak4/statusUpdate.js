let request = new XMLHttpRequest();

function requestData(){
    request.open("GET", "kundenStatus.php");
    request.onreadystatechange = processData;
    request.send(null);
}

function processData(){
    if(request.readyState === 4){       //übertragung = DONE
        if(request.status === 200){         //http-status = OK
            if(request.responseText != null){
                process(request.responseText);  //daten verarbeiten
            } else{ console.error("Dokument ist leer"); }
        } else{ console.error( "Übertragung fehlgeschlagen"); }
    } else;
}

function process(intext){
    let intextParsed = JSON.parse(intext);
    let statusList = ['bestellt', 'im Ofen', 'fertig', 'unterwegs', 'geliefert'];
    console.log("Hallo");
    for(let i=0; i<intextParsed.length; ++i){
        let element = document.getElementById("kundeStatusListe");
        let status = intextParsed[i]["status"];
        let statusName = statusList[status];
        let name = intextParsed[i]["name"];
        element.innerText += name + ": " + statusName + "\n";
    }
}

function Initialisieren(){
    window.setInterval(requestData, 2000);
}
//window.setInterval(...) vielleicht um wiederholt zu updaten 