const login=async()=>{
    const user_id=document.getElementById("user_id").value;
    const user_pw=document.getElementById("user_pw").value;
    if(user_id&&user_pw){
        try{
            const account = await axios.post("../php/account/login.php",{
                user_id:user_id,
                user_pw:user_pw
            });
            if(account.data){
                 console.log(account.data);
            }else{ console.log("입력 실패");
        }
           
        }catch(error){
            console.log(error);
        }
    }
}
