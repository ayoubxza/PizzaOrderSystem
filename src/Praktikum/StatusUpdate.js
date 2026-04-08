document.getElementsByTagName("body")[0].onload = init;


function init() {
    requestData();
    window.setInterval(requestData, 2000);
}

// request als globale Variable
var request = new XMLHttpRequest();

function requestData() { // fordert die Daten asynchron an
    request.open("GET", "KundenStatus.php"); // URL für HTTP-GET
    request.onreadystatechange = processData; //Callback-Handler zuordnen
    request.send(null); // Request abschicken
}

function processData() {
    if(request.readyState == 4) { // Uebertragung = DONE
        if (request.status == 200) {   // HTTP-Status = OK
            if(request.responseText != null)
                process(request.responseText);// Daten verarbeiten
            else console.error ("Dokument ist leer");
        }
        else console.error ("Uebertragung fehlgeschlagen");
    } else ;          // Uebertragung laeuft noch
}


function process(json_data ){
    let data = JSON.parse(json_data);

    for (let i= 0; i < data.length; i++) {
        let status = data[i]["status"];
        let ordered_article_id = data[i]["ordered_article_id"];
        document.getElementById(ordered_article_id).textContent = statusToString(status);
    }
}

function statusToString(status){
    if(status === 0)
        return "Bestellt💳";
    else if (status === 1)
        return "Im Ofen👨‍🍳";
    else if (status === 2)
        return "fertig👨‍🍳";
    else if (status === 3)
        return "unterwegs🏃";
    else if (status === 4)
        return "geliefert📦";
    else
        return "ungültig❌";
}