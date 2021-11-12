let flagNickname = true;

function openChangePasswordPopup() {
    window.open("changePassword.html", "ChangePassword", 
    "width = 400, height = 300, left = 100, top = 100");
}

function modifyConfirm() {
    const con = new XMLHttpRequest();

    const modifyForm = document.forms["modify_form"];
    const reqBody = {
        nickname: modifyForm.elements["nickname"].value,
        address: modifyForm.elements["address"].value,
        gender: modifyForm.elements["gender"].value,
        age: modifyForm.elements["age"].value
    }

    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if(res.isSuccess) {
                alert("변경 성공");
            } else {
                alert("변경 실패");
                window.location.reload();
            }
        }
    }
    con.open("POST", "api/modifyUserInfo.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqBody));
}

function getInformation() {
    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);

            if(res.isSuccess) {
                const modifyForm = document.forms["modify_form"];
                modifyForm.elements["id"].value = res.userInfo.ID;
                modifyForm.elements["nickname"].value = res.userInfo.nickname;
                modifyForm.elements["address"].value = res.userInfo.address;
                modifyForm.elements["gender"].value = res.userInfo.gender;
                modifyForm.elements["age"].value = res.userInfo.age; 
            }
        }
    }

    con.open("POST", "api/getUserInfo.php");
    con.setRequestHeader("Content-Type", "application/json");

    con.send();
}

window.onload = getInformation;