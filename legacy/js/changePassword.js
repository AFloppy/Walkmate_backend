function changePassword() {
    const chForm = document.forms["changePasswordForm"];
    const reqDict = {
        originalPassword: chForm.elements["originalPassword"].value,
        desirePassword: chForm.elements["desirePassword"].value
    };

    if(reqDict.desirePassword !== chForm.elements["oneMorePassword"].value) {
        alert("비밀번호 확인이 일치하지 않습니다.");
        return;
    }

    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if(res.isSuccess)
            {
                alert("비밀번호가 변경되었습니다.");
                window.close();
            } else{
                alert(res.reason);
            }
        }
    }

    con.open("POST", "api/modifyPassword.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqDict));
}