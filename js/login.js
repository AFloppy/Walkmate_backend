function login() {
    const loginForm = document.forms["login_form"];
    const bodyDict = {
        id: loginForm.elements["id"].value,
        password: loginForm.elements["password"].value
    }

    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const response = JSON.parse(con.responseText);
            if(response.isSuccess) {
                location.href = "index.html";
            } else {
                alert(response.reason);
            }
        }
    }

    con.open("POST", "api/login.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(bodyDict));
}