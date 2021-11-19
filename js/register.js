//let flagID = false;
//let flagNickname = false;

function register() {
    /*
    if (!flagID) {
        alert("ID 중복 확인을 먼저 해주세요!");
        return;
    }
    if (!flagNickname) {
        alert("닉네임 중복 확인을 먼저 해주세요!");
        return;
    } */

    const regForm = document.forms["reg_form"];
    let userGender;
    console.log(regForm.elements["gender"].value);
    if(regForm.elements["gender"].value === "f") {
        userGender = 1;
    } else {
        userGender = 0;
    }

    const bodyDict = {
        user_id: regForm.elements["id"].value,
        user_pw: regForm.elements["password"].value,
        nickname: regForm.elements["nickname"].value,
        addr: regForm.elements["address"].value,
        mail: regForm.elements["email"].value,
        phone: regForm.elements["phone"].value,
        gender: userGender,
        age: regForm.elements["age"].value,
        birth: regForm.elements["birth"].value
    };

    ompw = regForm.elements["oneMorePassword"].value; 
    if(bodyDict.user_pw !== ompw) {
        alert("패스워드 확인이 일치하지 않습니다.");
        return;
    }

    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if (con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if (res.isSuccess) {
                alert("가입 완료");
                location.href = "../index.html";
            } else {
                alert("ID 중복");
            }
        }
    };

    con.open("POST", "api/account/signup.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(bodyDict));
}

function checkID() {
    desireID = document.forms["reg_form"].elements["id"].value;

    if (desireID === "" || !desireID) {
        alert("아이디를 입력해주세요");
        return;
    }

    const con = new XMLHttpRequest();

    con.onreadystatechange = () => {
        const resultDiv = document.querySelector("#checkIDResultDiv");
        if (con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if (res.isSuccess) {
                resultDiv.style.display = "inline";
                if (res.canUseID) {
                    flagID = true;
                    resultDiv.innerHTML = "사용 가능한 아이디입니다.";
                    resultDiv.style.color = "green";
                } else {
                    resultDiv.innerHTML = "사용 중인 아이디입니다.";
                    resultDiv.style.color = "red";
                }
            } else {
                alert(res.reason);
            }
        }
    };

    reqBody = {
        id: document.forms["reg_form"].elements["id"].value,
    };

    con.open("POST", "api/checkID.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqBody));
}

function checkNickname() {
    desireNick = document.forms["reg_form"].elements["nickname"].value;

    if (desireNick === "" || !desireNick) {
        alert("닉네임을 입력해주세요");
        return;
    }

    const con = new XMLHttpRequest();

    con.onreadystatechange = () => {
        const resultDiv = document.querySelector("#checkNicknameResultDiv");
        if (con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if (res.isSuccess) {
                resultDiv.style.display = "inline";
                if (res.canUseNick) {
                    flagID = true;
                    resultDiv.innerHTML = "사용 가능한 닉네임입니다.";
                    resultDiv.style.color = "green";
                } else {
                    resultDiv.innerHTML = "사용 중인 닉네임입니다.";
                    resultDiv.style.color = "red";
                }
            } else {
                alert(res.reason);
            }
        }
    };

    reqBody = {
        nickname: document.forms["reg_form"].elements["nickname"].value,
    };

    con.open("POST", "api/checkNick.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqBody));
}

function changedID() {
    if (flagID) {
        const resultDiv = document.querySelector("#checkIDResultDiv");
        flagID = false;
        resultDiv.style.display = "none";
    }
}

function changedNickname() {
    if (flagNickname) {
        const resultDiv = document.querySelector("#checkNicknameResultDiv");
        flagNickname = false;
        resultDiv.style.display = "none";
    }
}
