function notifyParent() {
    parent.frameLoaded();
}

function frameLoaded() {
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = 'http://www.undercover-gaming.nl/forum';
    document.body.appendChild(iframe);
    setTimeout('location.reload(true);', 500);
}