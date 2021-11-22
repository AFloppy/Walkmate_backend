function chageLangSelect(){ 
    let select_walklist = document.getElementById("select_walklist"); // select element에서 선택된 option의 value가 저장된다. 
    let selectValue = select_walklist.options[select_walklist.selectedIndex].value; // select element에서 선택된 option의 text가 저장된다. 
    console.log(selectValue);
    if(selectValue==="near"){
        $('ul *').remove();
        console.log(selectValue);
        getNearWalk();
    }else{
        $('ul *').remove();
        console.log(selectValue);
        getRecWalk();
    }
}




//최근 등록된 


const getDetailURL ='http://localhost/html/detail.html?';

const getRecWalk=async()=>{
    const list = await axios.post("../php/walk/getWalkList.php",{
        requireCount: 10, //한번에 몇개씩
        walkListCount: 0, //지금까지 몇개를 불러왔는지
        requestTime: "2021-11-23 10:00:00"
    });
    console.log(list.data.walksCount);
    if(list.data.walksCount){
        
        for(var i=0;i<list.data.walksCount;i++){
            
            $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 : '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>' );};
        }
}
//가까운 거리
const getNearWalk=async()=>{
    const list = await axios.post("../php/walk/getRecWalkList.php",{
        requireCount: 10, //한번에 몇개씩
        walkListCount: 0, //지금까지 몇개를 불러왔는지
        requestTime: "2021-11-23 10:00:00",
        limitDistance: 1.0
    });
    console.log(list);
    if(list.data.walksCount){
        for(var i=0;i<list.data.walksCount;i++){
            console.log(list.data.walks[i].title);
            $('ul' ).append('<li><a href="'+'http://localhost/html/detail.html?'+'walkKey='+list.data.walks[i].walkKey+'"><p class="li_h">'+list.data.walks[i].title+'</p></a><p style="color: gray;">인원 : '+list.data.walks[i].maxMemberCount+'명 날짜 : '+list.data.walks[i].depTime+'</p></li>' );};
        }
    }



    

