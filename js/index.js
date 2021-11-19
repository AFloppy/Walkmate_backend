let pageWalkListCount = 0;
let pageFirstWalkKey = -1;

window.onload = () => {
    sessionCheck(initMain);
    initMain();
};

function initMain(response) {
    //if(response.isLogin) {
        const statusDiv = document.querySelector("#statusDiv");
        const loginRegDiv = document.querySelector("#loginRegDiv");
        const userDiv = document.querySelector("#userDiv");

        //statusDiv.innerHTML = "ID : " + response.id + " | " + "닉네임 : " + response.nickname;
        loginRegDiv.style.display = "none";
        userDiv.style.display = "block";

        getWalkList();
    //}
}

function getWalkList() {
    const con = new XMLHttpRequest();
    const reqBody = {
        requireCount: 10,
        firstWalkKey: pageFirstWalkKey,
        walkListCount: pageWalkListCount
    };
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            const res = JSON.parse(con.responseText);
            const listDiv = document.getElementById("walkList");

            if(res.isSuccess) {
                if(res.walksCount > 0) {
                    for(i = 0;i < res.walksCount;i++){
                        a = res.walks[i];
                        listDiv.innerHTML += makeListElement(a.title, a.depLocation, a.depTime, a.description, a.nowMemberCount,
                                                a.maxMemberCount, a.requireList.require, a.hostNickname, a.walkKey);
                    }
                    if(pageFirstWalkKey === -1) {
                        pageFirstWalkKey = res.walks[0].walkKey;
                    }
                    pageWalkListCount += res.walksCount;
                } else {
                    listDiv.innerHTML = "산책이 없습니다.";
                }
            } else {
                listDiv.innerHTML = "DB 오류";
            }
        }
    }

    con.open("POST", "api/walk/getWalkList.php");
    con.setRequestHeader("Content-Type", "application/json");
    con.send(JSON.stringify(reqBody));
}

function makeListElement(title, location, time, desc, nowMember, maxMember, require, hostNick, walkKey) {
    return `<div class="listElement" onclick="location.href='showWalk.html?walkKey=${walkKey}'">
        <strong>${title}</strong> ${nowMember} / ${maxMember}<br>
        ${hostNick} | ${location} | ${require} | ${time}<br>
        ${desc}
        <div> <br>`;
}

function logout() {
    const con = new XMLHttpRequest();
    con.onreadystatechange = () => {
        if(con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
            if(con.responseText) {
                window.location.reload();
            }
        }
    }

    con.open("POST", "api/logout.php");
    con.send(); 
}