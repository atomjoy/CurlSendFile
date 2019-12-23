/**
 * HttpPost - http post request
 * @param string   url  Http request url
 * @param array   arr  Post data array: var data = []; data['id'] = 1; data['cmd'] = 'get-user';
 * @param function cb   Callback function with response parametr
 * @param form obj   form Form object $('#form')[0];
 * 
 * hr.setRequestHeader("Content-Type", "application/json"); 
 * data:
 * var json = {"email": "hey@mail.xx", "password": "101010"}
 * var data = JSON.stringify(json)
 * 
 * hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 * data: 
 * "fname=Henry&lname=Max"
 */
function HttpPost(url, arr, cb, form){
    if (form === undefined) { var data = new FormData(); }else{ var data = new FormData(form); }
    if (arr !== undefined) {
        for (const index in arr) {    
            data.append(index, arr[index]);
        }
    }
    var hr = new XMLHttpRequest();
    hr.onreadystatechange=function(){
        if (hr.readyState==4 && hr.status==200){
            if( typeof cb === 'function' ){ cb(hr.responseText); }
        }
    }
    hr.upload.onprogress = function(e) {
        var done = e.position || e.loaded, total = e.totalSize || e.total;
        console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (Math.floor(done/total*1000)/10) + '%');
    };
    hr.withCredentials = true;
    hr.crossDomain = true;
    hr.open("POST",url,true);
    hr.send(data);
}

// HttpPost callback
function cb(res){
	console.log(res);
	var json = JSON.parse(res);
	console.log(json.error + ' ' + json.list);
	// loop
	for (var obj in json.list){		
        console.log(json.list[obj].id + ' ' + json.list[obj].name);
	}
}
