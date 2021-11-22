function chageLangSelect(){ 
    let select_walklist = document.getElementById("select_walklist"); // select element에서 선택된 option의 value가 저장된다. 
    let selectValue = select_walklist.options[select_walklist.selectedIndex].value; // select element에서 선택된 option의 text가 저장된다. 
    console.log(selectValue);
    if(selectValue==="apply"){
        $('ul *').remove();
        console.log(selectValue);
        getApplyWalkList();
    }else if(selectValue==="host"){
        $('ul *').remove();
        console.log(selectValue);
        getHostWalkList();
    }else{
        $('ul *').remove();
        console.log(selectValue);
        getJoinWalkList();
    }
}
const getDetailURL ='http://localhost/html/detail.html?';
//최근 등록된   confirmApplyWalk.php
//내가 신청한 게시글
const getApplyWalkList=async()=>{
    const list = await axios.get("../php/walk/getApplyWalkList.php",{
    });
    if(list.data.walksCount){
                for(var i=0;i<list.data.walksCount;i++){
                    $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 :  '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>');};
                }
}
const getHostWalkList=async()=>{

    const list = await axios.get("../php/walk/getHostWalkList.php",{
    });

    if(list.data.walksCount){
                const member_apply=()=>{
                    const member_list = await axios.get("../php/walk/getHostWalkList.php",{
                    });
                };
                for(var i=0;i<list.data.walksCount;i++){
                    $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 : '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p><div class=".reco"><h5>신청한 사람</h5></div></li>');                        
                    for(var i=0;i<length;i++){    
                    $('.reco' ).append('<div><h6>-happy</h6> <a href="">승인하기</a></div>');
                }
                }
                
    }
}
const getJoinWalkList=async()=>{
    const list = await axios.get("../php/walk/getJoinWalkList.php",{
    });
    if(list.data.walksCount){
                for(var i=0;i<list.data.walksCount;i++){
                    $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 :  '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>');
                    
            };
                }
}
getJoinWalkList();



