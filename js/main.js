
//최근 등록된 
const getRecWalk=async()=>{
    const list = await axios.post("../php/walk/getWalkList.php",{
        requireCount: 10, //한번에 몇개씩
        walkListCount: 0, //지금까지 몇개를 불러왔는지
        requestTime: "2021-11-23 10:00:00"
    });
    console.log(list.data.walksCount);
    if(list.data.walksCount){
        for(var i=0;i<list.data.walksCount;i++){
            $('.rec' ).append('<a href="./detail.html"><li>'+list.data.walks[i].title+'</li></a>' );}
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
            $('.near' ).append('<a href="./detail.html"><li>'+list.data.walks[i].title+'</li></a>' );}
        }
    }

getRecWalk();


getNearWalk();

