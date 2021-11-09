window.onload = () => {
  sessionCheck(checkListener);
};

function checkListener(res) {
  if (!res.isLogin) {
    alert("먼저 로그인 해주세요.");
    location.href = "index.html";
  }
}

function makewalk() {
    const infoForm = document.forms["walkInfoForm"];
    const reqBody = {
        title: infoForm.elements["title"].value,
        location: infoForm.elements["location"].value,
        maxMember: infoForm.elements["maxmember"].value,
        require: infoForm.elements["require"].value,
        description: infoForm.elements["desc"].value
    }

    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if(res.isSuccess) {
                alert("성공");
                location.href = "index.html";
            } else {
                alert(res.reason);
            }
        }
    }

    con.open("POST", "api/makeWalk.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqBody));
}
