function formulari(){
    let x = document.forms["incidencia"]["descripcio"].value;
    let y = document.forms["incidencia"]["id_dept"].value;
    if (x == "" || y == "" ) {
        alert("No pots deixar els camps buits");
        return false;
    }
}

function valModi(){
    let x = document.forms["modificar"]["prioridad"].value;
    let y = document.forms["modificar"]["id_tecnic"].value;
    let z = document.forms["modificar"]["id_tipo"].value;
    if (x == "" || y == "" || z == "") {
        alert("No pots deixar els camps buits");
        return false;
    }
}

function valActua(){
    let x = document.forms["actuacion"]["descripcio"].value;
    let y = document.forms["actuacion"]["duracio"].value;
    let z = document.forms["actuacion"]["visible"].value;
    if (x == "" || y == "" || z == "") {
        alert("No pots deixar els camps buits");
        return false;
    }
}

function valLog(){
    let x = document.forms["loging"]["email"].value;
    let y = document.forms["loging"]["password"].value;
    if (x == "" || y == "") {
        alert("No pots deixar els camps buits");
        return false;
    }
}   


