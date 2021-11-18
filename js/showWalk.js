let hostID = "";
let currentWalkKey;

window.onload = () => {
    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const detailDiv = document.getElementById("detailDiv");
            const res = JSON.parse(con.responseText);
            if(res.isSuccess) {
                const walkBody = res.body;
                detailDiv.innerHTML = `제목: ${walkBody.title} | 주최 : ${walkBody.hostNickname} <br>
                장소: ${walkBody.depLocation} | 출발시간: ${walkBody.depTime} <br>
                인원: ${walkBody.nowMemberCount} / ${walkBody.maxMemberCount} <br>
                조건: ${walkBody.requireList.require} <br>
                설명: ${walkBody.description} <br>`;

                hostID = walkBody.hostID;
                
                if(res.isHost) {
                    const listDiv = document.querySelector("#listDiv");
                    const memberDiv = document.querySelector("#memberListDiv");
                    const applyDiv = document.querySelector("#applyListDiv");

                    listDiv.style.display = "block";
                    memberDiv.style.display = "block";
                    applyDiv.style.display = "block";

                    for(const i in res.memberList) {
                        memberDiv.innerHTML += `<h3>아이디: ${res.memberList[i].memberID} | 닉네임: ${res.memberList[i].nickname}</h3>`
                    }

                    for(const i in res.applyList) {
                        applyDiv.innerHTML += `<h3 style="display: inline;">아이디: ${res.applyList[i].memberID} | 닉네임: ${res.applyList[i].nickname}</h3> <button onclick="confirmWalk(${res.applyList[i].memberKey}, true)">승인</button> <button onclick="confirmWalk(${res.applyList[i].memberKey}, false)">거절</button><br>`
                    }
                }
            } else {
                detailDiv.innerHTML = "없는 글입니다.";
            }
        }
    }

    const url = new URL(window.location.href);
    const reqBody = {
        walkKey: url.searchParams.get("walkKey")
    };

    currentWalkKey = reqBody.walkKey;

    if(!reqBody.walkKey) {
        alert("키 오류");
        return;
    }
    
    con.open("POST", "api/getWalkDetail.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqBody));

    checkMyHost();
};


function checkMyHost() {
    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if(hostID !== res.id) {
                const applyBtn = document.querySelector("#applyBtn");
                applyBtn.style.display = "inline";
            }
        }
    }

    con.open("POST", "api/checkSession.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send();
}

function applyWalk() {
    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if(res.isSuccess) {
                alert("신청 성공");
            } else {
                alert(res.reason);
            }
        }
    }

    const reqBody = {
        walkKey: currentWalkKey
    }
    
    con.open("POST", "api/applyWalk.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqBody));
}

function confirmWalk(userKey, isAccept) {
    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            if(res.isSuccess) {
                alert("처리 완료");
                window.location.href = window.location.href;
            } else {
                alert(`code : ${res.code} | message : ${res.errorDetail}`);
            }
        }
    }

    const reqBody = {
        walkKey: currentWalkKey,
        confirmData: {userKey: userKey, isAccept: isAccept}
    };
    con.open("POST", "api/confirmApply.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqBody));
}
