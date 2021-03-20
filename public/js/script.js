const main = document.querySelector('main');
const noteInputFields = document.querySelectorAll('.note textarea');
const archiveNoteBtn = document.querySelector('.archive-note-btn');

const APIurl = '/api/';

archiveNoteBtn.addEventListener('click', function (e) {
  const noteToArchive = this.closest('.note');
  const noteIDToArchive = noteToArchive.dataset.noteid;

  showAlert('archiving', noteIDToArchive);

  const headers = {
    'Access-Control-Origin': '*',
  };

  fetch(APIurl, {
    method: 'POST',
    headers: headers,
  })
    .then(response => {
      if (response.status == 201) {
        showAlert('archived', noteIDToArchive);
        setTimeout(() => location.reload(), 2000);
      } else {
        throw `Failed - ${response.status} server response`;
      }
    })
    .catch(error => {
      console.log(error);
      showAlert('archive-failed', noteIDToArchive);
    });
});

noteInputFields.forEach(inputField => {
  let timer = 0;

  inputField.addEventListener('input', function () {
    const note = this.closest('.note');
    const lastUpdated = moment().format('YYYY-MM-DD [at] HH:mm');
    note.querySelector('.last-changed-field').textContent = lastUpdated;
    note.querySelector('.last-changed').classList.add('d-block');

    const noteIDToUpdate = note.dataset.noteid;
    showAlert('saving', noteIDToUpdate);

    clearTimeout(timer);
    timer = setTimeout(() => {
      updateNote(noteIDToUpdate, lastUpdated);
      timer = null;
    }, 2000);
  });
});

main.addEventListener('click', function (e) {
  if (e.target.classList.contains('delete-note-btn')) {
    e.target.setAttribute('disabled', 'true');

    const noteToDelete = e.target.closest('.note');
    const noteIDToDelete = noteToDelete.dataset.noteid;

    e.target.classList.toggle('d-none');
    showAlert('deleting', noteIDToDelete);

    fetch(`${APIurl}?noteID=${noteIDToDelete}`, {
      method: 'DELETE',
    })
      .then(response => {
        if (response.status == 204) {
          showAlert('deleted', noteIDToDelete);
          noteToDelete.classList.add('deleted');
          setTimeout(() => noteToDelete.remove(), 1500);
        } else {
          throw `Failed - ${response.status} server response`;
        }
      })
      .catch(error => {
        console.log(error);
        e.target.classList.toggle('d-none');
        showAlert('delete-failed', noteIDToDelete);
        e.target.removeAttribute('disabled');
        e.target.focus();
      });
  }
});

function updateNote(noteIDToUpdate, lastUpdated) {
  const note = main.querySelector(`[data-noteID="${noteIDToUpdate}"]`);
  const noteContent = note.querySelector('textarea').value;

  const headers = {
    'Content-Type': 'application/json',
    'Access-Control-Origin': '*',
  };

  const payload = {
    noteContent: noteContent,
    lastUpdated: lastUpdated,
  };

  fetch(`${APIurl}?noteID=${noteIDToUpdate}`, {
    method: 'PUT',
    headers: headers,
    body: JSON.stringify(payload),
  })
    .then(response => {
      if (response.status == 200) {
        showAlert('saved', noteIDToUpdate);
        return response.json();
      } else {
        throw `Failed - ${response.status} server response`;
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
  const alertToShow = note.querySelector(`.${alertType}`);
  alertToShow.classList.add('visible');
  if (alertType == 'saved') {
    setTimeout(() => {
      alertToShow.classList.remove('visible');
    }, 4000);
  }
}
