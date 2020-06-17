const main = document.querySelector('main');
const noteInputFields = document.querySelectorAll('.noteInputField');
const archiveNoteBtn = document.querySelector('.archiveNoteBtn');

const APIurl = '/notes.php';
// userID is also available as a variable

archiveNoteBtn.addEventListener('click', function (e) {
  const noteToArchive = this.closest('article');
  const noteIDToArchive = noteToArchive.dataset.noteid;

  showAlert('archiving', noteIDToArchive);

  const headers = {
    'Access-Control-Origin': '*',
  };

  fetch(`${APIurl}?noteID=${noteIDToArchive}&userID=${userID}`, {
    method: 'POST',
    headers: headers,
  })
    .then(response => {
      if (response.status == 200) {
        showAlert('archived', noteIDToArchive);
        setTimeout(() => location.reload(), 2000);
      } else {
        throw `Failed - server responsed with ${response.status}`;
      }
    })
    .catch(error => {
      console.log(error);
      showAlert('archive-failed', noteIDToArchive);
    });
});

for (let i = 0; i < noteInputFields.length; i++) {
  let timer = 0;

  noteInputFields[i].addEventListener('input', function (e) {
    const note = this.closest('article');
    const lastUpdated = moment().format('YYYY-MM-DD [at] HH:mm');
    note.querySelector('.last-changed-field').textContent = lastUpdated;
    note.querySelector('.last-changed').classList.add('visible');

    const noteIDToUpdate = note.dataset.noteid;
    showAlert('saving', noteIDToUpdate);

    clearTimeout(timer);
    timer = setTimeout(() => {
      updateNote(noteIDToUpdate, lastUpdated);
      timer = null;
    }, 2000);
  });
}

main.addEventListener('click', function (e) {
  if (e.target.classList.contains('deleteNoteBtn')) {
    e.target.setAttribute('disabled', 'true');

    const noteToDelete = e.target.closest('article');
    const noteIDToDelete = noteToDelete.dataset.noteid;

    e.target.classList.toggle('visible');
    showAlert('deleting', noteIDToDelete);

    fetch(`${APIurl}?noteID=${noteIDToDelete}&userID=${userID}`, {
      method: 'DELETE',
    })
      .then(response => {
        if (response.status == 204) {
          showAlert('deleted', noteIDToDelete);
          noteToDelete.classList.add('deleted');
          setTimeout(() => noteToDelete.remove(), 1500);
        } else {
          throw `Failed - server responsed with ${response.status}`;
        }
      })
      .catch(error => {
        console.log(error);
        e.target.classList.toggle('visible');
        showAlert('delete-failed', noteIDToDelete);
        e.target.removeAttribute('disabled');
        e.target.focus();
      });
  }
});

function updateNote(noteIDToUpdate, lastUpdated) {
  const note = main.querySelector('[data-noteID="' + noteIDToUpdate + '"]');
  let noteContent = note.querySelector('.noteInputField').value;
  // const patt2 = new RegExp("<div>", "g");
  // const patt3 = new RegExp("</div>", "g");
  // const patt4 = new RegExp("<br>", "g");
  // noteContent = noteContent.replace(patt2, "\n").replace(patt3, "").replace(patt4, "");

  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Origin': '*',
  };

  const payload = {
    noteContent: noteContent,
    lastUpdated: lastUpdated,
  };

  fetch(`${APIurl}?noteID=${noteIDToUpdate}&userID=${userID}`, {
    method: 'PUT',
    headers: headers,
    body: JSON.stringify(payload),
  })
    .then(response => {
      if (response.status == 200) {
        showAlert('saved', noteIDToUpdate);
        return response.json();
      } else {
        throw `Failed - server responsed with ${response.status}`;
      }
    })
    .then(responseData => console.log(responseData))
    .catch(error => {
      showAlert('save-failed', noteIDToUpdate);
      console.log(error);
    });
}

function showAlert(alertType, noteID) {
  // alertType can be one of saving, saved, save-failed, deleting, deleted, delete-failed, archiving, archived, archive-failed
  const note = main.querySelector('[data-noteID="' + noteID + '"]');
  const alerts = note.querySelectorAll('.badge');
  alerts.forEach(elem => elem.classList.remove('visible'));
  const alertToShow = note.querySelector('.' + alertType);
  alertToShow.classList.add('visible');
  if (alertType == 'saved') {
    setTimeout(() => {
      alertToShow.classList.remove('visible');
    }, 4000);
  }
}
