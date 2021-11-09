function register() {
    const con = new XMLHttpRequest();
    const regForm = document.forms["reg_form"];
    const bodyDict = {
        id: regForm.elements["id"].value,
        password: regForm.elements["password"].value,
        nickname: regForm.elements["nickname"].value,
        address: regForm.elements["address"].value,
        email: regForm.elements["email"].value,
        gender: regForm.elements["gender"].value,
        age: regForm.elements["age"].value
    }

    console.log(JSON.stringify(bodyDict));
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if(res.isSuccess) {
                alert("가입 완료");
                location.href = "../index.html";
            } else {
                alert("ID 중복");
            }
        }
    }

    con.open("POST" ,"api/register.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(bodyDict));
}
