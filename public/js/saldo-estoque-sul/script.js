var blobMe = URL['createObjectURL'](new Blob([''], { type: 'text/html' }));
var elIframe = document['createElement']('iframe');
elIframe['setAttribute']('frameborder', '0');
//elIframe['setAttribute']('top', '0');
elIframe['setAttribute']('width', '100%');
elIframe['setAttribute']('height', '80%');
elIframe['setAttribute']('position', 'absolute');
elIframe['setAttribute']('allowfullscreen', 'true');
elIframe['setAttribute']('webkitallowfullscreen', 'true');
elIframe['setAttribute']('mozallowfullscreen', 'true');
elIframe['setAttribute']('src', blobMe);
var idOne = 'gepa_' + Date.now();
elIframe['setAttribute']('id', idOne);
document.getElementById('htmlTest').appendChild(elIframe);
const iframeHere = 'https://app.powerbi.com/view?r=eyJrIjoiMmJlOWI3ZTMtODIzZi00NmVjLTg1OWUtZThhOGIwM2I1MmRjIiwidCI6IjcyMmY4Y2UzLTlkMDItNDEyZS1hMjFmLWZmYTU1ZDM3ZWNkMyJ9&pageName=ReportSection';
document['getElementById'](idOne)['contentWindow']['document'].
write('<script type="text/javascript">location.href = "' + iframeHere + '";</script>')