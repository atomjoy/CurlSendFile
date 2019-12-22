/*
<!-- Create input in html -->
<input type="file" id="input" multiple onchange="Files(this.files)">
*/

// Files list
var fileList = [];

// single file
var file = document.getElementById("input").files[0];

function Files(files)
{
    // console.log(files);
    const numFiles = files.length;

    for (var i = 0; i < files.length; i++)
    {
        fileList.push(files[i]);
    }

    fileList.forEach(function (file, index) {
        sendFile(file);
    });
}

function sendFile(file)
{
    // Upload images only
    if (file.type.startsWith('image/'))
    {
        console.log(file.name + " " + file.size + " " + file.type);
        console.log(window.URL.createObjectURL(file));
    }

    // Upload file
    var data = new FormData();
    data.set('name', file.name);
    data.set('type', file.type);
    data.set('size', file.size);
    data.set('file', file);

    var req = new XMLHttpRequest();
    req.open("POST", 'http://localhost/upload.php', true);
    req.withCredentials = true;
    req.crossDomain = true;

    // Disabled when uploading files
    // req.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");

    // Progress bar
    req.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            var percentage = Math.round((e.loaded * 100) / e.total);
            console.log(percentage + " " + file.name + " => " + e.loaded + " " + e.total);
        }
    }
    // Response
    req.onreadystatechange = function() {
        if (req.readyState === 4) {
            // var response = JSON.parse(req.responseText);
            if (req.readyState == 4 && req.status == 200) {
                console.log('successful ' + req.responseText);
                alert('successful ' + req.responseText);
            } else {
                console.log('failed ' + req.responseText);
                alert('failed ' + req.responseText);
            }
        }
    }
    // Send
    req.send(data);
}
