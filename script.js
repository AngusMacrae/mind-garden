const main = document.querySelector("main");
const newNoteSection = document.getElementById("newNoteSection");
const previousNotesSection = document.getElementById("previousNotesSection");
const noteInputFields = document.querySelectorAll(".noteInputField");
const archiveNoteBtn = document.querySelector(".archiveNoteBtn");

let APIurl = "/notes.php"
// userID is also available as a variable

archiveNoteBtn.addEventListener("click", function (e) {


    // fetch request to create new note
    // if successful, reload the page

    const headers = {
        "Content-Type": "application/json",
        "Access-Control-Origin": "*"
    }

    const payload = {
        "userID": userID,
        "operation": "new",
    }

    console.log(payload);

    fetch(APIurl, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(payload)
        })
        .then(response => {
            console.log(response.status);
            if (response.status == 200) {
                return response.json();
            } else {
                throw 'Server error';
            }
        })
        .then(jsonresponse => {
            if (jsonresponse.status = "ok") {
                location.reload();
            } else {
                throw 'Server error';
            }
        })
        .catch(error => {
            console.log(error);
            // display alert
        });

});

for (let i = 0; i < noteInputFields.length; i++) {

    let timer = 0;

    noteInputFields[i].addEventListener("input", function (e) {

        let noteIDToUpdate = this.closest("article").dataset.noteid;

        showAlert("saving", noteIDToUpdate);

        clearTimeout(timer);
        console.log("cleared")
        timer = setTimeout(() => {
            console.log("calling update function");
            updateNote(noteIDToUpdate);
            timer = null;
        }, 2000);

    });

};

main.addEventListener("click", function (e) {

    if (e.target.classList.contains("deleteNoteBtn")) {

        e.target.setAttribute("disabled", "true");

        let noteToDelete = e.target.closest("article");
        let noteIDToDelete = noteToDelete.dataset.noteid;

        e.target.classList.toggle("visible");
        showAlert("deleting", noteIDToDelete);

        const headers = {
            "Content-Type": "application/json",
            "Access-Control-Origin": "*"
        }

        const payload = {
            "userID": userID,
            "operation": "delete",
            "targetNote": noteIDToDelete
        }

        console.log(payload);

        fetch(APIurl, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(jsonresponse => {
                console.log(jsonresponse);
                if (jsonresponse.status == "ok") {
                    showAlert("deleted", noteIDToDelete);
                    noteToDelete.classList.add("deleted");
                    setTimeout(() => noteToDelete.remove(), 1500);
                } else {
                    throw "Serverside error - note was not deleted";
                }
            })
            .catch((error) => {
                console.log(error);
                e.target.classList.toggle("visible");
                showAlert("delete-failed", noteIDToDelete);
                e.target.removeAttribute("disabled");
                e.target.focus();
            });

    }

});

function updateNote(noteIDToUpdate) {

    // sends request to server to update this note

    let note = main.querySelector('[data-noteID="' + noteIDToUpdate + '"]');
    let noteContent = note.querySelector(".noteInputField").textContent;
    let lastUpdated = new Date();

    const headers = {
        "Content-Type": "application/json",
        "Access-Control-Origin": "*"
    }

    const payload = {
        "userID": userID,
        "operation": "update",
        "targetNote": noteIDToUpdate,
        "noteContent": noteContent,
        "lastUpdated": lastUpdated
    }

    console.log(payload);

    fetch(APIurl, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(payload)
        })
        .then(response => {
            console.log(response.status);
            if (response.status == 200) {
                return response.json();
            } else {
                throw 'Server error';
            }
        })
        .then(jsonresponse => {
            console.log(jsonresponse);
            if (jsonresponse.status == "ok") {
                showAlert("saved", noteIDToUpdate);
            } else {
                throw "Serverside error - note was not deleted";
            }
        })
        .catch(error => {
            console.log(error);
            showAlert("save-failed", noteIDToUpdate);
        });

}

function showAlert(alertType, noteID) {

    // alertType can be one of saving, saved, save-failed, deleting, deleted, delete-failed
    let note = main.querySelector('[data-noteID="' + noteID + '"]');
    let alerts = note.querySelectorAll(".badge");
    alerts.forEach(elem => elem.classList.remove("visible"));
    let alertToShow = note.querySelector("." + alertType);
    alertToShow.classList.add("visible");

}