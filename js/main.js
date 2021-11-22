const getRecWalk=async()=>{
    const list = await axios.post("../php/walk/getWalkList.php",{
        requireCount: 10,
        walkListCount: 20,
        requestTime: "2021-11-23 10:00:00"
    });
    console.log(list);


};
getRecWalk();
