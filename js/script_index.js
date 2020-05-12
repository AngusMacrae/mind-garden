document.getElementById("logout-btn").addEventListener("click", function () {

    // send post request to self with name="logout" value="logout"

    let formData = new FormData();
    formData.append('name', 'logout');

    fetch("/", {
        body: formData,
        method: "post"
    });

});