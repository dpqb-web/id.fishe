document.addEventListener 'DOMContentLoaded', ->
  ### URLastPath = (url) ->
    new URL url
    .pathname
    .split '/'
    .filter Boolean
    .pop() ###

  menu =
    aktif: document.querySelector "nav.mobile a[href='" + window.location.pathname + "']"
  menu.aktif.classList.add 'aktif' if menu.aktif?

  pesan =
    induk: document.querySelector '.pesan2'
    tutup: document.querySelectorAll '.pesan2 .tutup button'
  if pesan.induk?
    pesan.tutup.forEach (tombol) ->
      tombol.addEventListener 'click', (e) ->
        e.target.closest('section').remove()
        pesan.induk.remove() if pesan.induk.children.length is 0

  dialog =
    induk: document.querySelectorAll '.modal'
    tutup: document.querySelectorAll '.modal button[data-aksi="tutup"]'
  if dialog.induk?
    dialog.induk.forEach (modal) ->
      modal.addEventListener 'click', (e) ->
        modal.hidden = true if e.target is modal
    dialog.tutup.forEach (tombol) ->
      tombol.addEventListener 'click', (e) ->
        e.target.closest('.modal').hidden = true
