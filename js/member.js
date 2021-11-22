const session=async()=>{
    const account = await axios.get("../php/account/checkSession.php");
    console.log(account.data[0].real_id);
    if(account.data[0].real_id){
    $('.menu' ).append('<a class="menu_a" href="./mypage.html">마이페이지</a>/<a class="menu_a" href="../php/account/logout.php">로그아웃</a>' );}
    else{
        $('.menu' ).append('<a class="menu_a" href="./login.html">로그인</a>/<a class="menu_a" href="./member.html">회원가입</a>');
    }
};

session();
