cancel = (e) ->
  e.preventDefault()
  e.stopPropagation()
  false

newElement = (type, content, attr) ->
  element = document.createElement type

  if attr?
    for key, value of attr
      element.setAttribute key, value

  if content?
    if Array.isArray content
      for child in content
        if child instanceof Node
          element.appendChild child
        else
          element.appendChild document.createTextNode child
    else if content instanceof Node
      element.appendChild content
    else
      element.appendChild document.createTextNode content

  element

if window.matchMedia
  if window.matchMedia('(prefers-color-scheme:dark)').matches
    localStorage.setItem 'color-scheme', 'dark'
  else
    localStorage.setItem 'color-scheme', 'light'

updateColorScheme = () ->
  if localStorage.getItem('color-scheme') is 'dark'
    document.documentElement.classList.add 'dark'
  else
    document.documentElement.classList.remove 'dark'
updateColorScheme()

### if 'serviceWorker' of navigator
  window.addEventListener 'load', ->
    navigator.serviceWorker.register '/sw.js'
    .then (reg) ->
      console.log 'ServiceWorker registration successful with scope: ', reg.scope
    , (err) ->
      console.error 'ServiceWorker registration failed: ', err
else
  console.warn 'ServiceWorker is not supported in this browser. Progressive Web App features may not be available.' ###
