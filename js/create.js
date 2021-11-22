const createWriting = async () => {
    const title = document.querySelector(".title").value;
    const maxMemberCount = document.querySelector(".maxMemberCount").value;
    const description = document.querySelector(".description").value;
    const depTime = document.querySelector(".depTime").value;
    var depLocation = getDataFromDrawingMap();
    var depLatitude = depLocation.marker[0].y;
    var depLongitude = depLocation.marker[0].x;
    alert(JSON.stringify(depLocation));
    if (title && depTime && maxMemberCount && depLocation) {
        console.log(title);
        console.log(maxMemberCount);
        console.log(description);
        console.log(depTime);
        console.log(depLocation);
        try {
            const response = await axios.post("../php/walk/writeWalk.php", {
                title: title,
                depLatitude: depLatitude,
                depLongitude: depLongitude,
                maxMemberCount: maxMemberCount,
                description: description,
                depTime: depTime,
            });
        }
        catch (error) {
            console.log(error);
        }
    }
}