function sessionCheck(listener) {
  const con = new XMLHttpRequest();

  con.onreadystatechange = () => {
    if (con.status === 200 && con.readyState === XMLHttpRequest.DONE) {
        const response = JSON.parse(con.responseText);
        if(listener !== null)
            listener(response);
    }
  };

  con.open("POST", "api/checkSession.php");
  con.send();
}
